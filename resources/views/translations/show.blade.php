<x-app-layout>
    <x-slot name="header">Translation Result</x-slot>

    <div class="max-w-4xl space-y-6 fade-in">

        {{-- Status banner --}}
        @if($translation->status === 'failed')
        <div class="flash-error flex items-center gap-3">
            <span class="text-xl text-red-600"><i class="fas fa-times-circle"></i></span>
            <div>
                <div class="font-semibold">Translation Failed</div>
                <div class="text-sm mt-0.5">{{ $translation->error_message }}</div>
            </div>
        </div>
        @endif

        {{-- Main result card --}}
        <div class="card overflow-hidden">
            {{-- Header strip --}}
            <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($translation->source_label)
                    <span class="text-sm font-semibold text-slate-200">{{ $translation->source_label }}</span>
                    <span class="text-slate-700">·</span>
                    @endif
                    <span class="badge badge-{{ $translation->status === 'completed' ? 'success' : ($translation->status === 'failed' ? 'error' : 'warning') }}">
                        {{ ucfirst($translation->status) }}
                    </span>
                    @if($translation->is_cached)
<<<<<<< HEAD
                    <span class="badge" style="background:#e0e7ff;color:#4338ca;"><i class="fas fa-bolt"></i> Cached</span>
=======
                    <span class="badge" style="background:rgba(99,102,241,0.15);color:#818cf8;border:1px solid rgba(99,102,241,0.25);">⚡ Cached</span>
>>>>>>> upstream/master
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500">{{ $translation->created_at->format('d M Y, H:i') }}</span>
                    <form method="POST" action="{{ route('translations.destroy', $translation) }}" onsubmit="return confirm('Delete this record?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-slate-600 hover:text-red-500 transition-colors ml-2" title="Delete">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="p-6 grid md:grid-cols-2 gap-6">
                {{-- Source --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
<<<<<<< HEAD
                        <span class="text-lg text-blue-600"><i class="fas fa-language"></i></span>
                        <h3 class="text-sm font-semibold text-gray-700">English Source</h3>
                        <span class="ml-auto text-xs text-gray-400">{{ number_format($translation->characters) }} chars</span>
=======
                        <span class="text-sm font-semibold text-slate-200">Source ({{ \App\Services\TranslationService::getLanguageName($translation->source_lang) }})</span>
                        <span class="ml-auto text-xs text-slate-500">{{ number_format($translation->characters) }} chars</span>
>>>>>>> upstream/master
                    </div>
                    <div class="border border-white/10 rounded-xl p-4 bg-white/5 text-sm text-slate-300 leading-relaxed min-h-[140px]">
                        {{ $translation->source_text }}
                    </div>
                </div>

                {{-- Translation --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
<<<<<<< HEAD
                        <span class="text-lg text-orange-600"><i class="fas fa-globe"></i></span>
                        <h3 class="text-sm font-semibold text-gray-700">Hindi Translation</h3>
                        <button onclick="copyHindi()" class="ml-auto text-xs text-blue-600 hover:underline"><i class="fas fa-copy"></i> Copy</button>
=======
                        <h3 class="text-sm font-semibold text-slate-200">Translation ({{ \App\Services\TranslationService::getLanguageName($translation->target_lang) }})</h3>
                        <button onclick="copyOutput()" class="ml-auto text-xs text-blue-400 hover:underline">📋 Copy</button>
>>>>>>> upstream/master
                    </div>
                    <div id="output-text" class="translation-box min-h-[140px]">
                        @if($translation->translated_text)
                            {{ $translation->translated_text }}
                        @else
                            <span class="text-gray-400 italic">Translation not available.</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer meta --}}
            <div class="px-6 py-3 border-t border-white/10 bg-white/2 flex items-center gap-4 text-xs text-slate-500">
                <span>Provider: <strong class="text-slate-400">
                    @if($translation->provider === 'mock')
                        Google AI (Free)
                    @elseif($translation->provider === 'google')
                        Google Cloud Premium
                    @elseif($translation->provider === 'libre')
                        LibreTranslate
                    @else
                        {{ ucfirst($translation->provider) }}
                    @endif
                </strong></span>
                <span>·</span>
                <span>Source: <strong class="text-slate-400">{{ \App\Services\TranslationService::getLanguageName($translation->source_lang) }}</strong></span>
                <span>→</span>
                <span>Target: <strong class="text-slate-400">{{ \App\Services\TranslationService::getLanguageName($translation->target_lang) }}</strong></span>
                @if($translation->user)
                <span>·</span>
                <span>By: <strong class="text-slate-400">{{ $translation->user->name }}</strong></span>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <a href="{{ route('translations.create') }}" class="btn-primary text-sm inline-flex items-center gap-2">
                <i class="fas fa-pen"></i> New Translation
            </a>
            <a href="{{ route('translations.index') }}" class="btn-secondary text-sm">
                ← View History
            </a>
        </div>
    </div>

    <script>
        function copyOutput() {
            const text = document.getElementById('output-text').innerText.trim();
            navigator.clipboard.writeText(text).then(() => {
<<<<<<< HEAD
                const btn = document.querySelector('[onclick="copyHindi()"]');
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => btn.innerHTML = '<i class="fas fa-copy"></i> Copy', 2000);
=======
                const btn = document.querySelector('[onclick="copyOutput()"]');
                btn.textContent = '✅ Copied!';
                setTimeout(() => btn.textContent = '📋 Copy', 2000);
>>>>>>> upstream/master
            });
        }
    </script>
</x-app-layout>
