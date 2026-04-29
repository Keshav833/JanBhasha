<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                abort_if(!$request->user()?->isSuperAdmin(), 403, 'Super admin access required.');
                return $next($request);
            }),
        ];
    }

    // ── Dashboard ────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_orgs'         => Organisation::count(),
            'active_orgs'        => Organisation::where('is_active', true)->count(),
            'total_users'        => User::count(),
            'total_translations' => Translation::count(),
            'this_month'         => Translation::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)->count(),
            'completed'          => Translation::where('status', 'completed')->count(),
            'failed'             => Translation::where('status', 'failed')->count(),
        ];

        $recentOrgs = Organisation::withCount('users', 'translations')
            ->latest()->limit(5)->get();

        $recentTranslations = Translation::with(['user', 'organisation'])
            ->latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recentOrgs', 'recentTranslations'));
    }

    // ── Users ─────────────────────────────────────────
    public function users(Request $request)
    {
        $users = User::with('organisation')
            ->when($request->filled('organisation'), fn ($q) => $q->where('organisation_id', $request->organisation))
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q2) use ($request) {
                $q2->where('name', 'like', '%'.$request->search.'%')
                   ->orWhere('email', 'like', '%'.$request->search.'%');
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $organisations = Organisation::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'organisations'));
    }

    public function createUser()
    {
        $organisations = Organisation::where('is_active', true)->orderBy('name')->get();
        return view('admin.users.create', compact('organisations'));
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'unique:users,email'],
            'password'        => ['required', Password::defaults()],
            'role'            => ['required', 'in:super_admin,admin,translator'],
            'organisation_id' => ['nullable', 'exists:organisations,id'],
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$data['name']}' created successfully.");
    }

    public function editUser(User $user)
    {
        $organisations = Organisation::where('is_active', true)->orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'organisations'));
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'unique:users,email,'.$user->id],
            'role'            => ['required', 'in:super_admin,admin,translator'],
            'organisation_id' => ['nullable', 'exists:organisations,id'],
            'password'        => ['nullable', Password::defaults()],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'Cannot delete your own account.');
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted.');
    }
}
