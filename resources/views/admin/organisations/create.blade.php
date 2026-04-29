<x-app-layout>
    <x-slot name="header">Admin — Create Organisation</x-slot>

    <div class="max-w-2xl fade-in">
        <div class="mb-6">
            <a href="{{ route('admin.organisations.index') }}" class="text-sm text-blue-600 hover:underline inline-flex items-center gap-1">
                ← Back to Organisations
            </a>
        </div>

        <div class="card overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100" style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
                <h2 class="text-lg font-bold text-white">New Organisation</h2>
                <p class="text-blue-200 text-sm mt-0.5">Register a government department or ministry on JanBhasha.</p>
            </div>

            <form method="POST" action="{{ route('admin.organisations.store') }}" class="p-6 space-y-5">
                @csrf

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Organisation Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="e.g. Ministry of Finance"
                               class="input-field @error('name') border-red-400 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Department</label>
                        <input type="text" name="department" value="{{ old('department') }}"
                               placeholder="e.g. Department of Economic Affairs"
                               class="input-field @error('department') border-red-400 @enderror">
                        @error('department')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Official Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="digital@ministry.gov.in"
                               class="input-field @error('email') border-red-400 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Website URL</label>
                        <input type="url" name="website" value="{{ old('website') }}"
                               placeholder="https://ministry.gov.in"
                               class="input-field @error('website') border-red-400 @enderror">
                        @error('website')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Monthly Character Limit <span class="text-red-500">*</span></label>
                    <input type="number" name="monthly_char_limit" value="{{ old('monthly_char_limit', 1000000) }}" required
                           min="10000" max="100000000" step="10000"
                           class="input-field @error('monthly_char_limit') border-red-400 @enderror">
                    <p class="text-xs text-gray-400 mt-1">Maximum characters this organisation can translate per month. (default: 1,000,000)</p>
                    @error('monthly_char_limit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', '1') ? 'checked' : '' }}
                           class="w-4 h-4 rounded text-blue-600">
                    <label for="is_active" class="text-sm font-medium text-gray-700">
                        Active — Allow this organisation to use the JanBhasha API
                    </label>
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="btn-primary text-sm inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Create Organisation
                    </button>
                    <a href="{{ route('admin.organisations.index') }}" class="btn-secondary text-sm">Cancel</a>
                </div>
            </form>
        </div>

        <div class="mt-4 p-4 rounded-xl bg-blue-50 border border-blue-100">
            <div class="flex items-start gap-2">
                <span class="text-blue-500">ℹ️</span>
                <p class="text-sm text-blue-700">An API key will be automatically generated for the new organisation. Share it securely with the organisation's technical team.</p>
            </div>
        </div>
    </div>
</x-app-layout>
