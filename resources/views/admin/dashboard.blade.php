<x-app-layout>
    <x-slot name="header">Admin Dashboard</x-slot>

    <div class="fade-in space-y-6">

        {{-- Welcome Banner --}}
        <div class="card overflow-hidden">
            <div class="flex items-center" style="background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 100%);">
                <div class="p-8 flex-1 text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-2xl">⚡</span>
                        <h2 class="text-2xl font-bold">Super Admin Control Centre</h2>
                    </div>
                    <p class="text-blue-200 text-sm">Full system access — manage organisations, users, translations, and API keys.</p>
                    <div class="flex gap-3 mt-5">
                        <a href="{{ route('admin.organisations.create') }}" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-all">
                            🏛️ New Organisation
                        </a>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-all">
                            👤 New User
                        </a>
                    </div>
                </div>
                <div class="pr-8 text-8xl opacity-10 hidden lg:block">🏛️</div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="stat-card p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="text-2xl">🏛️</div>
                    <span class="badge badge-success">Active: {{ $stats['active_orgs'] }}</span>
                </div>
                <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_orgs']) }}</div>
                <div class="text-sm text-gray-500 mt-1">Organisations</div>
            </div>
            <div class="stat-card p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="text-2xl">👥</div>
                    <span class="badge" style="background:#e0e7ff;color:#4338ca;">All Roles</span>
                </div>
                <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_users']) }}</div>
                <div class="text-sm text-gray-500 mt-1">Total Users</div>
            </div>
            <div class="stat-card p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="text-2xl">📝</div>
                    <span class="badge badge-warning">This month: {{ number_format($stats['this_month']) }}</span>
                </div>
                <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_translations']) }}</div>
                <div class="text-sm text-gray-500 mt-1">Translations</div>
            </div>
            <div class="stat-card p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="text-2xl">✅</div>
                    <span class="badge badge-error">Failed: {{ number_format($stats['failed']) }}</span>
                </div>
                <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['completed']) }}</div>
                <div class="text-sm text-gray-500 mt-1">Completed</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            {{-- Recent Organisations --}}
            <div class="card overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Recent Organisations</h3>
                    <a href="{{ route('admin.organisations.index') }}" class="text-sm text-blue-600 hover:underline">View all →</a>
                </div>
                @if($recentOrgs->isEmpty())
                <div class="px-6 py-10 text-center text-gray-400">No organisations yet.</div>
                @else
                <div class="divide-y divide-gray-50">
                    @foreach($recentOrgs as $org)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                             style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
                            {{ strtoupper(substr($org->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('admin.organisations.show', $org) }}" class="font-medium text-gray-800 hover:text-blue-600 transition-colors block truncate">
                                {{ $org->name }}
                            </a>
                            <div class="text-xs text-gray-400">{{ $org->users_count }} users · {{ number_format($org->translations_count) }} translations</div>
                        </div>
                        @if($org->is_active)
                        <span class="w-2 h-2 rounded-full bg-green-400 flex-shrink-0"></span>
                        @else
                        <span class="w-2 h-2 rounded-full bg-red-400 flex-shrink-0"></span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Recent Translations --}}
            <div class="card overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Recent Translations (System-wide)</h3>
                </div>
                @if($recentTranslations->isEmpty())
                <div class="px-6 py-10 text-center text-gray-400">No translations yet.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Org</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Source</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recentTranslations as $t)
                            <tr class="table-row">
                                <td class="px-6 py-3 text-xs text-gray-500">{{ $t->organisation?->name ?? '—' }}</td>
                                <td class="px-6 py-3 text-gray-700">{{ Str::limit($t->source_text, 35) }}</td>
                                <td class="px-6 py-3">
                                    <span class="badge badge-{{ $t->status === 'completed' ? 'success' : ($t->status === 'failed' ? 'error' : 'warning') }}">
                                        {{ ucfirst($t->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.organisations.index') }}" class="card p-5 flex items-center gap-4 hover:shadow-lg transition-all group">
                <div class="text-3xl">🏛️</div>
                <div>
                    <div class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">Organisations</div>
                    <div class="text-xs text-gray-400">Manage all orgs</div>
                </div>
            </a>
            <a href="{{ route('admin.users.index') }}" class="card p-5 flex items-center gap-4 hover:shadow-lg transition-all group">
                <div class="text-3xl">👥</div>
                <div>
                    <div class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">Users</div>
                    <div class="text-xs text-gray-400">Manage all users</div>
                </div>
            </a>
            <a href="{{ route('translations.index') }}" class="card p-5 flex items-center gap-4 hover:shadow-lg transition-all group">
                <div class="text-3xl">📋</div>
                <div>
                    <div class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">Translations</div>
                    <div class="text-xs text-gray-400">Translation history</div>
                </div>
            </a>
            <a href="{{ route('glossary.index') }}" class="card p-5 flex items-center gap-4 hover:shadow-lg transition-all group">
                <div class="text-3xl">📖</div>
                <div>
                    <div class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">Glossary</div>
                    <div class="text-xs text-gray-400">Term management</div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
