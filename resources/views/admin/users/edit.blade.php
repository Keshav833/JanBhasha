<x-app-layout>
    <x-slot name="header">Admin — Edit {{ $user->name }}</x-slot>

    <div class="max-w-2xl fade-in">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:underline inline-flex items-center gap-1">
                ← Back to Users
            </a>
        </div>

        <div class="card overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100" style="background: linear-gradient(135deg, #7c3aed, #4f46e5);">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">{{ $user->name }}</h2>
                        <p class="text-purple-200 text-sm">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6 space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="input-field @error('name') border-red-400 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="input-field @error('email') border-red-400 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                        <input type="password" name="password" placeholder="Leave blank to keep current"
                               class="input-field @error('password') border-red-400 @enderror">
                        <p class="text-xs text-gray-400 mt-1">Minimum 8 characters. Leave blank to keep existing password.</p>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                        <select name="role" required class="input-field @error('role') border-red-400 @enderror">
                            <option value="translator"  {{ old('role', $user->role) === 'translator'  ? 'selected' : '' }}>Translator</option>
                            <option value="admin"       {{ old('role', $user->role) === 'admin'       ? 'selected' : '' }}>Admin</option>
                            <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Organisation</label>
                    <select name="organisation_id" class="input-field @error('organisation_id') border-red-400 @enderror">
                        <option value="">None (Super Admin / No Org)</option>
                        @foreach($organisations as $org)
                        <option value="{{ $org->id }}" {{ old('organisation_id', $user->organisation_id) == $org->id ? 'selected' : '' }}>
                            {{ $org->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('organisation_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="btn-primary text-sm inline-flex items-center gap-2">
                        💾 Save Changes
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary text-sm">Cancel</a>
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')"
                          class="ml-auto">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-secondary text-sm text-red-500 hover:border-red-400">🗑️ Delete User</button>
                    </form>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
