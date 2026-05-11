<x-app-layout>
    <x-slot name="header">Translation History</x-slot>

    <div class="fade-in">
        {{-- Filter bar --}}
        <form method="GET" action="{{ route('translations.index') }}" class="card px-5 py-4 mb-6 flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search source text…"
                   class="input-field flex-1 min-w-[200px]" style="max-width:320px;">
            <select name="status" class="input-field" style="max-width:160px;">
                <option value="">All Statuses</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>❌ Failed</option>
                <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>⏳ Pending</option>
            </select>
            <button type="submit" class="btn-primary text-sm">Filter</button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('translations.index') }}" class="btn-secondary text-sm">Clear</a>
            @endif
            <div class="ml-auto text-sm text-slate-300">{{ $translations->total() }} record{{ $translations->total() !== 1 ? 's' : '' }}</div>
        </form>

        <div class="card overflow-hidden">
            @if($translations->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="text-5xl mb-3">📭</div>
                <p class="text-slate-300">No translations found.</p>
                <a href="{{ route('translations.create') }}" class="mt-4 inline-block btn-primary text-sm">Start translating →</a>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/5 bg-white/5">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Label / Source</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Languages</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Preview</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Chars</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Date</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($translations as $t)
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                @if($t->source_label)
                                <div class="text-xs font-bold text-blue-600 mb-0.5">{{ $t->source_label }}</div>
                                @endif
                                <a href="{{ route('translations.show', $t) }}" class="text-white font-semibold hover:text-blue-500 transition-colors">
                                    {{ Str::limit($t->source_text, 55) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col text-xs">
                                    <span class="text-slate-500">From: {{ \App\Services\TranslationService::getLanguageName($t->source_lang) }}</span>
                                    <span class="text-blue-500 font-medium">To: {{ \App\Services\TranslationService::getLanguageName($t->target_lang) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-300 font-medium" style="font-family:'Noto Sans Devanagari',sans-serif;">
                                {{ $t->translated_text ? Str::limit($t->translated_text, 45) : '—' }}
                            </td>
                            <td class="px-6 py-4 text-slate-400 font-medium">{{ number_format($t->characters) }}</td>
                            <td class="px-6 py-4">
                                <span class="badge badge-{{ $t->status === 'completed' ? 'success' : ($t->status === 'failed' ? 'error' : 'warning') }}">
                                    {{ ucfirst($t->status) }}
                                </span>
                                @if($t->is_cached)
                                <span class="badge ml-1" style="background:#e0e7ff;color:#4338ca;">⚡</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 whitespace-nowrap">{{ $t->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('translations.show', $t) }}" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center whitespace-nowrap">
                                    View <span class="ml-1">→</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($translations->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $translations->links('vendor.pagination.simple') }}
            </div>
            @endif
            @endif
        </div>
    </div>
</x-app-layout>
