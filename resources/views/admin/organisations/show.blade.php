<x-app-layout>
    <x-slot name="header">Admin — {{ $organisation->name }}</x-slot>

    <div class="max-w-5xl space-y-6 fade-in">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.organisations.index') }}" class="text-sm text-blue-600 hover:underline inline-flex items-center gap-1">
                ← Back to Organisations
            </a>
            <div class="flex gap-2">
                @if(!$organisation->trashed())
                <a href="{{ route('admin.organisations.edit', $organisation) }}" class="btn-secondary text-sm"><i class="fas fa-edit"></i> Edit</a>
                <form method="POST" action="{{ route('admin.organisations.regenerate-key', $organisation) }}">
                    @csrf
                    <button type="submit" onclick="return confirm('Regenerate API key? The old key will stop working immediately.')"
                            class="btn-secondary text-sm text-amber-600 hover:border-amber-400">
                        <i class="fas fa-key"></i> Regenerate Key
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.organisations.destroy', $organisation) }}" onsubmit="return confirm('Deactivate this organisation?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-secondary text-sm text-red-500 hover:border-red-400"><i class="fas fa-trash"></i> Deactivate</button>
                </form>
                @endif
            </div>
        </div>

        {{-- Header card --}}
        <div class="card overflow-hidden">
            <div class="p-6" style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
                <div class="flex items-start gap-5">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-bold text-white flex-shrink-0 bg-white/20">
                        {{ strtoupper(substr($organisation->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 text-white">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-2xl font-bold">{{ $organisation->name }}</h2>
                            @if($organisation->trashed())
                                <span class="badge badge-error">Deactivated</span>
                            @elseif($organisation->is_active)
                                <span class="badge" style="background:rgba(255,255,255,.2);color:white;">Active</span>
                            @else
                                <span class="badge badge-warning">Inactive</span>
                            @endif
                        </div>
                        @if($organisation->department)
                        <p class="text-blue-200 mt-1">{{ $organisation->department }}</p>
                        @endif
                        <div class="flex flex-wrap gap-4 mt-3 text-sm text-blue-200">
                            @if($organisation->email)
                            <span>📧 {{ $organisation->email }}</span>
                            @endif
                            @if($organisation->website)
                            <a href="{{ $organisation->website }}" target="_blank" class="hover:text-white transition-colors">🌐 {{ $organisation->website }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 divide-x divide-gray-100 border-t border-gray-100">
                <div class="p-5 text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($organisation->users_count) }}</div>
                    <div class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Users</div>
                </div>
                <div class="p-5 text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($organisation->translations_count) }}</div>
                    <div class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Translations</div>
                </div>
                <div class="p-5 text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($organisation->glossaries_count) }}</div>
                    <div class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Glossary Terms</div>
                </div>
            </div>
        </div>

        {{-- API Key --}}
        <div class="card p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-key"></i> API Key</h3>
            <div class="flex items-center gap-3">
                <code id="api-key-display" class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono text-gray-700 truncate">
                    {{ $organisation->api_key }}
                </code>
                <button onclick="copyApiKey()" class="btn-secondary text-sm flex-shrink-0"><i class="fas fa-copy"></i> Copy</button>
            </div>
            <p class="text-xs text-gray-400 mt-3">Send this key as the <code class="bg-gray-100 px-1 rounded">X-API-Key</code> header in API requests. Keep it confidential.</p>
        </div>

        {{-- Quota --}}
        <div class="card p-6">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-chart-pie"></i> Monthly Quota</h3>
            @php
                $used = $organisation->monthlyCharactersUsed();
                $limit = $organisation->monthly_char_limit;
                $pct = $limit > 0 ? round(($used / $limit) * 100, 1) : 0;
                $color = $pct > 85 ? '#ef4444' : ($pct > 60 ? '#f59e0b' : '#3b82f6');
            @endphp
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600">{{ number_format($used) }} / {{ number_format($limit) }} characters this month</span>
                <span class="text-lg font-bold" style="color:{{ $color }}">{{ $pct }}%</span>
            </div>
            <div class="quota-bar">
                <div class="quota-fill" style="width:{{ min($pct,100) }}%;background:linear-gradient(90deg,{{ $color }}cc,{{ $color }});"></div>
            </div>
        </div>

        {{-- Recent Translations --}}
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Recent Translations</h3>
                <span class="text-xs text-gray-400">Last 10</span>
            </div>
            @if($recentTranslations->isEmpty())
            <div class="px-6 py-10 text-center text-gray-400">No translations yet.</div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Source</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">User</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Characters</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentTranslations as $t)
                        <tr class="table-row">
                            <td class="px-6 py-3 text-gray-700">{{ Str::limit($t->source_text, 55) }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $t->user?->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ number_format($t->characters) }}</td>
                            <td class="px-6 py-3">
                                <span class="badge badge-{{ $t->status === 'completed' ? 'success' : ($t->status === 'failed' ? 'error' : 'warning') }}">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-400">{{ $t->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Users --}}
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Team Members</h3>
                <a href="{{ route('admin.users.index') }}?organisation={{ $organisation->id }}" class="text-sm text-blue-600 hover:underline">Manage Users →</a>
            </div>
            @if($organisation->users->isEmpty())
            <div class="px-6 py-10 text-center text-gray-400">No users in this organisation.</div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($organisation->users as $user)
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-gray-800">{{ $user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $user->email }}</div>
                    </div>
                    <span class="badge {{ $user->isSuperAdmin() ? 'badge-error' : ($user->isAdmin() ? 'badge-warning' : 'badge-success') }}">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <script>
        function copyApiKey() {
            const key = document.getElementById('api-key-display').innerText.trim();
            navigator.clipboard.writeText(key).then(() => {
                const btn = document.querySelector('[onclick="copyApiKey()"]');
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => btn.innerHTML = '<i class="fas fa-copy"></i> Copy', 2000);
            });
        }
    </script>
</x-app-layout>
