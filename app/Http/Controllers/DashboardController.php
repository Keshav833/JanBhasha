<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Super admins go to the dedicated admin dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $org  = $user->organisation;

        // If the user has no organisation, show a minimal dashboard
        if (!$org) {
            return view('dashboard', [
                'stats'          => [],
                'recentActivity' => collect(),
                'org'            => null,
            ]);
        }

        $baseQuery = Translation::forOrganisation($org->id);

        $stats = [
            'total_translations'  => (clone $baseQuery)->count(),
            'completed'           => (clone $baseQuery)->completed()->count(),
            'failed'              => (clone $baseQuery)->failed()->count(),
            'this_month_chars'    => $org->monthlyCharactersUsed(),
            'monthly_quota'       => $org->monthly_char_limit,
            'quota_percent'       => $org->monthly_char_limit > 0
                ? round(($org->monthlyCharactersUsed() / $org->monthly_char_limit) * 100, 1)
                : 0,
            'cached_translations' => (clone $baseQuery)->where('is_cached', true)->count(),
        ];

        $recentActivity = (clone $baseQuery)
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'recentActivity', 'org'));
    }
}
