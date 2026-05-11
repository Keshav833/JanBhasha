<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    @php
        $quotaPercent = $stats['quota_percent'] ?? 0;
        $quotaColor = $quotaPercent > 85 ? '#ef4444' : ($quotaPercent > 60 ? '#f59e0b' : '#3b82f6');
    @endphp

    {{-- Stats row --}}
    @if(!empty($stats))
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8 fade-in">
        <div class="stat-card p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="text-2xl">📝</div>
                <span class="badge badge-success">All time</span>
            </div>
            <div class="text-3xl font-extrabold text-white">{{ number_format($stats['total_translations']) }}</div>
            <div class="text-sm text-slate-400 mt-1">Total Translations</div>
        </div>

        <div class="stat-card p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="text-2xl">✅</div>
                <span class="badge badge-success">Completed</span>
            </div>
            <div class="text-3xl font-extrabold text-white">{{ number_format($stats['completed']) }}</div>
            <div class="text-sm text-slate-400 mt-1">Successful</div>
        </div>

        <div class="stat-card p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="text-2xl">⚡</div>
                <span class="badge badge-warning">Cached</span>
            </div>
            <div class="text-3xl font-extrabold text-white">{{ number_format($stats['cached_translations']) }}</div>
            <div class="text-sm text-slate-400 mt-1">From Cache</div>
        </div>

        <div class="stat-card p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="text-2xl">❌</div>
                <span class="badge badge-error">Failed</span>
            </div>
            <div class="text-3xl font-extrabold text-white">{{ number_format($stats['failed']) }}</div>
            <div class="text-sm text-slate-400 mt-1">Failed</div>
        </div>
    </div>

    {{-- Quota bar --}}
    <div class="card p-6 mb-8 fade-in" style="animation-delay:.05s">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="font-semibold text-white">Monthly Character Quota</h3>
                <p class="text-sm text-slate-400 mt-0.5">{{ number_format($stats['this_month_chars']) }} / {{ number_format($stats['monthly_quota']) }} characters used this month</p>
            </div>
            <div class="text-2xl font-bold" style="color: {{ $quotaColor }}">{{ $stats['quota_percent'] }}%</div>
        </div>
        <div class="quota-bar">
            <div class="quota-fill" style="width: {{ min($stats['quota_percent'], 100) }}%; background: linear-gradient(90deg, {{ $quotaColor }}cc, {{ $quotaColor }});"></div>
        </div>
    </div>
    @endif

    {{-- Quick translate CTA --}}
    <div class="card mb-8 overflow-hidden fade-in" style="animation-delay:.1s">
        <div class="flex items-center" style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
            <div class="p-8 flex-1 text-white">
                <h3 class="text-xl font-bold mb-1">Translate Government Content</h3>
                <p class="text-blue-200 text-sm mb-4">Convert official English documents, notices, and policies into Hindi instantly.</p>
                <a href="{{ route('translations.create') }}" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition-colors">
                    ✍️ Start Translating →
                </a>
            </div>
            <div class="pr-8 text-7xl opacity-20 hidden lg:block">🇮🇳</div>
        </div>
    </div>

    {{-- Recent activity --}}
    <div class="card fade-in" style="animation-delay:.15s">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Recent Translations</h3>
            <a href="{{ route('translations.index') }}" class="text-sm text-blue-600 hover:underline">View all →</a>
        </div>
        @if($recentActivity->isEmpty())
        <div class="px-6 py-12 text-center">
            <div class="text-4xl mb-3">📭</div>
            <p class="text-gray-500 text-sm">No translations yet.</p>
            <a href="{{ route('translations.create') }}" class="mt-3 inline-block text-sm text-blue-600 hover:underline">Create your first translation →</a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/5">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Source Text</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Characters</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($recentActivity as $t)
                    <tr class="table-row">
                        <td class="px-6 py-3">
                            <a href="{{ route('translations.show', $t) }}" class="text-slate-200 hover:text-blue-500 transition-colors">
                                {{ Str::limit($t->source_text, 60) }}
                            </a>
                        </td>
                        <td class="px-6 py-3 text-slate-400">{{ number_format($t->characters) }}</td>
                        <td class="px-6 py-3">
                            <span class="badge badge-{{ $t->status === 'completed' ? 'success' : ($t->status === 'failed' ? 'error' : 'warning') }}">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-slate-500">{{ $t->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <a href="{{ route('translations.show', $t) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                View →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>
