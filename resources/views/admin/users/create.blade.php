<x-app-layout>
    <x-slot name="header">Admin — Create User</x-slot>

    <div class="max-w-2xl fade-in">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:underline inline-flex items-center gap-1">
                ← Back to Users
            </a>
        </div>

        <div class="card overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100" style="background: linear-gradient(135deg, #7c3aed, #4f46e5);">
                <h2 class="text-lg font-bold text-white">Create User Account</h2>
                <p class="text-purple-200 text-sm mt-0.5">Add a new user to the JanBhasha portal.</p>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-5">
                @csrf

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="e.g. Ravi Kumar"
                               class="input-field @error('name') border-red-400 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="user@ministry.gov.in"
                               class="input-field @error('email') border-red-400 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required
                               placeholder="Minimum 8 characters"
                               class="input-field @error('password') border-red-400 @enderror">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                        <select name="role" required class="input-field @error('role') border-red-400 @enderror">
                            <option value="">Select a role…</option>
                            <option value="translator"  {{ old('role') === 'translator'  ? 'selected' : '' }}>Translator</option>
                            <option value="admin"       {{ old('role') === 'admin'       ? 'selected' : '' }}>Admin</option>
                            <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Organisation</label>
                    <select name="organisation_id" class="input-field @error('organisation_id') border-red-400 @enderror">
                        <option value="">None (Super Admin / No Org)</option>
                        @foreach($organisations as $org)
                        <option value="{{ $org->id }}" {{ old('organisation_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Leave blank for Super Admin accounts or platform-level staff.</p>
                    @error('organisation_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Role descriptions --}}
                <div class="grid grid-cols-3 gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="text-center">
                        <div class="text-lg mb-1">🌐</div>
                        <div class="text-xs font-semibold text-gray-700">Translator</div>
                        <div class="text-xs text-gray-400 mt-0.5">Can translate & view history</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg mb-1">⚙️</div>
                        <div class="text-xs font-semibold text-gray-700">Admin</div>
                        <div class="text-xs text-gray-400 mt-0.5">Manage org users & glossary</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg mb-1">👑</div>
                        <div class="text-xs font-semibold text-gray-700">Super Admin</div>
                        <div class="text-xs text-gray-400 mt-0.5">Full system access</div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="btn-primary text-sm inline-flex items-center gap-2">
                        👤 Create User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary text-sm">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
