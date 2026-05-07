<x-app-layout>
    <x-slot name="header">Admin — Organisations</x-slot>

    <div class="fade-in">

        {{-- Page Actions --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-500">Manage all registered government organisations on JanBhasha.</p>
            </div>
            <a href="{{ route('admin.organisations.create') }}" class="btn-primary text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New Organisation
            </a>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="stat-card p-5">
                <div class="text-3xl font-extrabold text-gray-900">{{ $organisations->total() }}</div>
                <div class="text-sm text-gray-500 mt-1">Total Orgs</div>
            </div>
            <div class="stat-card p-5">
                <div class="text-3xl font-extrabold text-green-600">{{ $organisations->where('is_active', true)->count() }}</div>
                <div class="text-sm text-gray-500 mt-1">Active</div>
            </div>
            <div class="stat-card p-5">
                <div class="text-3xl font-extrabold text-red-500">{{ $organisations->whereNotNull('deleted_at')->count() }}</div>
                <div class="text-sm text-gray-500 mt-1">Deactivated</div>
            </div>
            <div class="stat-card p-5">
                <div class="text-3xl font-extrabold text-blue-600">{{ $organisations->sum('users_count') }}</div>
                <div class="text-sm text-gray-500 mt-1">Total Users</div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">All Organisations</h3>
                <span class="badge badge-warning">Super Admin Only</span>
            </div>
            @if($organisations->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="text-5xl mb-3 text-gray-400"><i class="fas fa-building"></i></div>
                <p class="text-gray-500">No organisations yet.</p>
                <a href="{{ route('admin.organisations.create') }}" class="mt-4 inline-block btn-primary text-sm">Create First Organisation →</a>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Organisation</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Department</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Users</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Translations</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Created</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($organisations as $org)
                        <tr class="table-row {{ $org->trashed() ? 'opacity-50' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                                         style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
                                        {{ strtoupper(substr($org->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.organisations.show', $org) }}" class="font-semibold text-gray-800 hover:text-blue-600 transition-colors">
                                            {{ $org->name }}
                                        </a>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $org->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $org->department ?? '—' }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-700">{{ number_format($org->users_count) }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-700">{{ number_format($org->translations_count) }}</td>
                            <td class="px-6 py-4">
                                @if($org->trashed())
                                    <span class="badge badge-error">Deactivated</span>
                                @elseif($org->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-warning">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-400 whitespace-nowrap">{{ $org->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.organisations.show', $org) }}" class="text-blue-600 hover:text-blue-800 font-medium text-xs">View</a>
                                    @if(!$org->trashed())
                                    <span class="text-gray-200">|</span>
                                    <a href="{{ route('admin.organisations.edit', $org) }}" class="text-amber-600 hover:text-amber-800 font-medium text-xs">Edit</a>
                                    <span class="text-gray-200">|</span>
                                    <form method="POST" action="{{ route('admin.organisations.destroy', $org) }}" onsubmit="return confirm('Deactivate this organisation?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 font-medium text-xs">Deactivate</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($organisations->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $organisations->links() }}
            </div>
            @endif
            @endif
        </div>
    </div>
</x-app-layout>
