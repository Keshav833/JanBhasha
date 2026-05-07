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
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($translation->source_label)
                    <span class="text-sm font-semibold text-gray-700">{{ $translation->source_label }}</span>
                    <span class="text-gray-300">·</span>
                    @endif
                    <span class="badge badge-{{ $translation->status === 'completed' ? 'success' : ($translation->status === 'failed' ? 'error' : 'warning') }}">
                        {{ ucfirst($translation->status) }}
                    </span>
                    @if($translation->is_cached)
                    <span class="badge" style="background:#e0e7ff;color:#4338ca;"><i class="fas fa-bolt"></i> Cached</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">{{ $translation->created_at->format('d M Y, H:i') }}</span>
                    <form method="POST" action="{{ route('translations.destroy', $translation) }}" onsubmit="return confirm('Delete this record?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors ml-2" title="Delete">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="p-6 grid md:grid-cols-2 gap-6">
                {{-- Source --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-lg text-blue-600"><i class="fas fa-language"></i></span>
                        <h3 class="text-sm font-semibold text-gray-700">English Source</h3>
                        <span class="ml-auto text-xs text-gray-400">{{ number_format($translation->characters) }} chars</span>
                    </div>
                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 text-sm text-gray-700 leading-relaxed min-h-[140px]">
                        {{ $translation->source_text }}
                    </div>
                </div>

                {{-- Translation --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-lg text-orange-600"><i class="fas fa-globe"></i></span>
                        <h3 class="text-sm font-semibold text-gray-700">Hindi Translation</h3>
                        <button onclick="copyHindi()" class="ml-auto text-xs text-blue-600 hover:underline"><i class="fas fa-copy"></i> Copy</button>
                    </div>
                    <div id="hindi-output" class="translation-box min-h-[140px]">
                        @if($translation->translated_text)
                            {{ $translation->translated_text }}
                        @else
                            <span class="text-gray-400 italic">Translation not available.</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer meta --}}
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex items-center gap-4 text-xs text-gray-400">
                <span>Provider: <strong class="text-gray-600">{{ ucfirst($translation->provider) }}</strong></span>
                <span>·</span>
                <span>Source: <strong class="text-gray-600">{{ strtoupper($translation->source_lang) }}</strong></span>
                <span>→</span>
                <span>Target: <strong class="text-gray-600">{{ strtoupper($translation->target_lang) }}</strong></span>
                @if($translation->user)
                <span>·</span>
                <span>By: <strong class="text-gray-600">{{ $translation->user->name }}</strong></span>
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
        function copyHindi() {
            const text = document.getElementById('hindi-output').innerText.trim();
            navigator.clipboard.writeText(text).then(() => {
                const btn = document.querySelector('[onclick="copyHindi()"]');
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => btn.innerHTML = '<i class="fas fa-copy"></i> Copy', 2000);
            });
        }
    </script>
</x-app-layout>
