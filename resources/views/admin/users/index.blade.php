<x-app-layout>
    <x-slot name="header">Admin — Users</x-slot>

    <div class="fade-in">
        {{-- Filter bar --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="card px-5 py-4 mb-6 flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email…"
                   class="input-field flex-1 min-w-[200px]" style="max-width:280px;">
            <select name="organisation" class="input-field" style="max-width:200px;">
                <option value="">All Organisations</option>
                @foreach($organisations as $org)
                <option value="{{ $org->id }}" {{ request('organisation') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                @endforeach
            </select>
            <select name="role" class="input-field" style="max-width:160px;">
                <option value="">All Roles</option>
                <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="admin"       {{ request('role') === 'admin'       ? 'selected' : '' }}>Admin</option>
                <option value="translator"  {{ request('role') === 'translator'  ? 'selected' : '' }}>Translator</option>
            </select>
            <button type="submit" class="btn-primary text-sm">Filter</button>
            @if(request()->hasAny(['search','organisation','role']))
            <a href="{{ route('admin.users.index') }}" class="btn-secondary text-sm">Clear</a>
            @endif
            <div class="ml-auto flex items-center gap-3">
                <span class="text-sm text-gray-500">{{ $users->total() }} user{{ $users->total() !== 1 ? 's' : '' }}</span>
                <a href="{{ route('admin.users.create') }}" class="btn-primary text-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    New User
                </a>
            </div>
        </form>

        <div class="card overflow-hidden">
            @if($users->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="text-5xl mb-3 text-gray-400"><i class="fas fa-users"></i></div>
                <p class="text-gray-500">No users found.</p>
                <a href="{{ route('admin.users.create') }}" class="mt-4 inline-block btn-primary text-sm">Create First User →</a>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">User</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Organisation</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Role</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Joined</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                                         style="background: linear-gradient(135deg,
                                         {{ $user->isSuperAdmin() ? '#7c3aed,#4f46e5' : ($user->isAdmin() ? '#ea580c,#f59e0b' : '#1d4ed8,#3b82f6') }});">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                @if($user->organisation)
                                    <a href="{{ route('admin.organisations.show', $user->organisation) }}" class="hover:text-blue-600 transition-colors">
                                        {{ $user->organisation->name }}
                                    </a>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="badge {{ $user->isSuperAdmin() ? 'badge-error' : ($user->isAdmin() ? 'badge-warning' : 'badge-success') }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400 whitespace-nowrap">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-amber-600 hover:text-amber-800 font-medium text-xs">Edit</a>
                                    @if($user->id !== auth()->id())
                                    <span class="text-gray-200">|</span>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                          onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 font-medium text-xs">Delete</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
            @endif
            @endif
        </div>
    </div>
</x-app-layout>
