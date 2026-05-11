<x-app-layout>
    <x-slot name="header">Glossary</x-slot>

    <div class="fade-in">
        {{-- Header row --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-500">Custom term overrides applied before every translation for your organisation.</p>
            <a href="{{ route('glossary.create') }}" class="btn-primary text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Term
            </a>
        </div>

        <div class="card overflow-hidden">
            @if($glossaries->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="text-5xl mb-3">📖</div>
                <p class="text-gray-500 text-sm">No glossary terms yet.</p>
                <p class="text-gray-400 text-xs mt-1">Add terms like "Ministry → मंत्रालय" to preserve them during translation.</p>
                <a href="{{ route('glossary.create') }}" class="mt-4 inline-block btn-primary text-sm">Add First Term →</a>
            </div>
            @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/5 bg-white/2">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">English Term</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Hindi Override</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Case Sensitive</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Notes</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($glossaries as $term)
                    <tr class="table-row">
                        <td class="px-6 py-4 font-semibold text-slate-100">{{ $term->source_term }}</td>
                        <td class="px-6 py-4 font-bold text-blue-400" style="font-family:'Noto Sans Devanagari',sans-serif;font-size:1.05rem;">{{ $term->target_term }}</td>
                        <td class="px-6 py-4">
                            <span class="badge {{ $term->case_sensitive ? 'badge-warning' : 'badge-success' }}">
                                {{ $term->case_sensitive ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs">{{ $term->notes ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('glossary.edit', $term) }}" class="text-blue-400 hover:text-blue-300 font-medium text-xs">Edit</a>
                                <form method="POST" action="{{ route('glossary.destroy', $term) }}" onsubmit="return confirm('Delete this term?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($glossaries->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $glossaries->links() }}</div>
            @endif
            @endif
        </div>
    </div>
</x-app-layout>
