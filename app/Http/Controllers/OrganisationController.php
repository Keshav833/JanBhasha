<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganisationRequest;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class OrganisationController extends Controller implements HasMiddleware
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

    public function index()
    {
        $organisations = Organisation::withTrashed()
            ->withCount('users', 'translations')
            ->latest()
            ->paginate(20);

        return view('admin.organisations.index', compact('organisations'));
    }

    public function create()
    {
        return view('admin.organisations.create');
    }

    public function store(StoreOrganisationRequest $request)
    {
        $org = Organisation::create($request->validated());

        return redirect()
            ->route('admin.organisations.show', $org)
            ->with('success', "Organisation '{$org->name}' created. API Key: {$org->api_key}");
    }

    public function show(Organisation $organisation)
    {
        $organisation->loadCount('users', 'translations', 'glossaries');
        $organisation->load('users');

        $recentTranslations = $organisation->translations()
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.organisations.show', compact('organisation', 'recentTranslations'));
    }

    public function edit(Organisation $organisation)
    {
        return view('admin.organisations.edit', compact('organisation'));
    }

    public function update(StoreOrganisationRequest $request, Organisation $organisation)
    {
        $organisation->update($request->validated());

        return redirect()
            ->route('admin.organisations.show', $organisation)
            ->with('success', 'Organisation updated.');
    }

    public function destroy(Organisation $organisation)
    {
        $organisation->delete();

        return redirect()
            ->route('admin.organisations.index')
            ->with('success', 'Organisation deactivated.');
    }

    /**
     * Regenerate the organisation's API key.
     */
    public function regenerateApiKey(Organisation $organisation)
    {
        $organisation->update(['api_key' => Organisation::generateApiKey()]);

        return back()->with('success', "New API key generated: {$organisation->fresh()->api_key}");
    }
}
