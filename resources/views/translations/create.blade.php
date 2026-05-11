<x-app-layout>
    <x-slot name="header">New Translation</x-slot>

    <div class="max-w-3xl fade-in">
        <div class="card p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-xl">✍️</div>
                <div>
                    <h2 class="font-bold text-white">Multi-Language Translation</h2>
                    <p class="text-sm text-slate-400">Official government content translation between Indian languages</p>
                </div>
            </div>

            <form method="POST" action="{{ route('translations.store') }}" class="space-y-5" id="translation-form">
                @csrf

                <div>
                    <label for="source_label" class="block text-sm font-medium text-white mb-1.5">
                        Document Label <span class="text-slate-500 font-normal">(optional)</span>
                    </label>
                    <input type="text" id="source_label" name="source_label" value="{{ old('source_label') }}"
                           class="input-field" placeholder="e.g. Gazette Notification #42, Budget Circular 2025">
                    @error('source_label')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="flex-1 w-full">
                        <label for="source_lang" class="block text-sm font-medium text-white mb-1.5">Translate From</label>
                        <select name="source_lang" id="source_lang" class="input-field">
                            @foreach(\App\Services\TranslationService::INDIAN_LANGUAGES as $code => $name)
                                <option value="{{ $code }}" {{ old('source_lang', 'en') == $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('source_lang')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="button" onclick="swapLanguages()" class="mt-6 p-2 rounded-full hover:bg-gray-100 text-slate-500 transition-colors" title="Swap Languages">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </button>

                    <div class="flex-1 w-full">
                        <label for="target_lang" class="block text-sm font-medium text-white mb-1.5">Translate To</label>
                        <select name="target_lang" id="target_lang" class="input-field">
                            @foreach(\App\Services\TranslationService::INDIAN_LANGUAGES as $code => $name)
                                <option value="{{ $code }}" {{ old('target_lang', 'hi') == $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('target_lang')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="source_text" class="block text-sm font-medium text-white mb-1.5">
                        Source Text <span class="text-red-500">*</span>
                    </label>
                    <textarea id="source_text" name="source_text" rows="8"
                              class="input-field" placeholder="Paste or type the source content here…"
                              maxlength="50000">{{ old('source_text') }}</textarea>
                    <div class="flex justify-between mt-1.5">
                        @error('source_text')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-slate-500 ml-auto" id="char-count">0 / 50,000 characters</p>
                    </div>
                </div>


                {{-- Org glossary note --}}
                @if($org && $org->glossaries()->count() > 0)
                <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-sm text-blue-700 flex items-start gap-2">
                    <span class="mt-0.5">📖</span>
                    <span>{{ $org->glossaries()->count() }} custom glossary terms will be applied before translation.</span>
                </div>
                @endif

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 3.938A9 9 0 0121 12M9 3a6 6 0 016 6M9 15l-6-6 6-6"/></svg>
                        Translate Now
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

        {{-- Tips card --}}
        <div class="mt-5 bg-amber-50 border border-amber-200 rounded-xl p-5">
            <h4 class="text-sm font-semibold text-amber-800 mb-2">💡 Tips for Better Translations</h4>
            <ul class="text-sm text-amber-700 space-y-1 list-disc list-inside">
                <li>Use formal, structured sentences for best results</li>
                <li>Custom glossary terms in your organisation will be preserved</li>
                <li>Repeated translations are served from cache instantly</li>
                <li>Max 50,000 characters per request</li>
            </ul>
        </div>
    </div>

    <script>
        const textarea = document.getElementById('source_text');
        const counter  = document.getElementById('char-count');
        function updateCount() {
            const n = textarea.value.length;
            counter.textContent = n.toLocaleString() + ' / 50,000 characters';
            counter.style.color = n > 45000 ? '#ef4444' : '#9ca3af';
        }
        textarea.addEventListener('input', updateCount);
        updateCount();

        function swapLanguages() {
            const sourceSelect = document.getElementById('source_lang');
            const targetSelect = document.getElementById('target_lang');
            const temp = sourceSelect.value;
            sourceSelect.value = targetSelect.value;
            targetSelect.value = temp;
        }
    </script>
</x-app-layout>
