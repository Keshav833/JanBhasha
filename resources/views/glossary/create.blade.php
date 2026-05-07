<x-app-layout>
    <x-slot name="header">Add Glossary Term</x-slot>

    <div class="max-w-xl fade-in">
        <div class="card p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-xl text-blue-600"><i class="fas fa-book"></i></div>
                <div>
                    <h2 class="font-bold text-gray-900">New Glossary Term</h2>
                    <p class="text-sm text-gray-500">This term will be preserved during all translations for your org</p>
                </div>
            </div>

            <form method="POST" action="{{ route('glossary.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="source_term" class="block text-sm font-medium text-gray-700 mb-1.5">English Term <span class="text-red-500">*</span></label>
                    <input type="text" id="source_term" name="source_term" value="{{ old('source_term') }}"
                           class="input-field" placeholder="e.g. Ministry" required>
                    @error('source_term')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="target_term" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Hindi Override <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="target_term" name="target_term" value="{{ old('target_term') }}"
                           class="input-field" placeholder="e.g. मंत्रालय" style="font-family:'Noto Sans Devanagari',sans-serif;font-size:1.05rem;" required>
                    @error('target_term')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="case_sensitive" name="case_sensitive" value="1" {{ old('case_sensitive') ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <label for="case_sensitive" class="text-sm text-gray-700">Case sensitive matching</label>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="text" id="notes" name="notes" value="{{ old('notes') }}"
                           class="input-field" placeholder="Context or usage notes…">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary text-sm">Add Term</button>
                    <a href="{{ route('glossary.index') }}" class="btn-secondary text-sm">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
