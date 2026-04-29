<x-app-layout>
    <x-slot name="header">Admin — Edit {{ $organisation->name }}</x-slot>

    <div class="max-w-2xl fade-in">
        <div class="mb-6">
            <a href="{{ route('admin.organisations.show', $organisation) }}" class="text-sm text-blue-600 hover:underline inline-flex items-center gap-1">
                ← Back to {{ $organisation->name }}
            </a>
        </div>

        <div class="card overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100" style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
                <h2 class="text-lg font-bold text-white">Edit Organisation</h2>
                <p class="text-blue-200 text-sm mt-0.5">Update details for {{ $organisation->name }}.</p>
            </div>

            <form method="POST" action="{{ route('admin.organisations.update', $organisation) }}" class="p-6 space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Organisation Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $organisation->name) }}" required
                               class="input-field @error('name') border-red-400 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Department</label>
                        <input type="text" name="department" value="{{ old('department', $organisation->department) }}"
                               class="input-field @error('department') border-red-400 @enderror">
                        @error('department')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Official Email</label>
                        <input type="email" name="email" value="{{ old('email', $organisation->email) }}"
                               class="input-field @error('email') border-red-400 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Website URL</label>
                        <input type="url" name="website" value="{{ old('website', $organisation->website) }}"
                               class="input-field @error('website') border-red-400 @enderror">
                        @error('website')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Monthly Character Limit <span class="text-red-500">*</span></label>
                    <input type="number" name="monthly_char_limit" value="{{ old('monthly_char_limit', $organisation->monthly_char_limit) }}"
                           required min="10000" max="100000000" step="10000"
                           class="input-field @error('monthly_char_limit') border-red-400 @enderror">
                    @error('monthly_char_limit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', $organisation->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 rounded text-blue-600">
                    <label for="is_active" class="text-sm font-medium text-gray-700">
                        Active — Allow this organisation to use the JanBhasha API
                    </label>
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="btn-primary text-sm inline-flex items-center gap-2">
                        💾 Save Changes
                    </button>
                    <a href="{{ route('admin.organisations.show', $organisation) }}" class="btn-secondary text-sm">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
