<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGlossaryRequest;
use App\Models\Glossary;
use Illuminate\Http\Request;

class GlossaryController extends Controller
{
    public function index(Request $request)
    {
        $org = $request->user()->organisation;
        abort_if(!$org, 403, 'You are not associated with any organisation.');

        $glossaries = Glossary::where('organisation_id', $org->id)
            ->orderBy('source_term')
            ->paginate(20);

        return view('glossary.index', compact('glossaries', 'org'));
    }

    public function create(Request $request)
    {
        $org = $request->user()->organisation;
        abort_if(!$org, 403, 'You are not associated with any organisation.');

        return view('glossary.create', compact('org'));
    }

    public function store(StoreGlossaryRequest $request)
    {
        $org = $request->user()->organisation;
        abort_if(!$org, 403, 'You are not associated with any organisation.');

        Glossary::create([
            ...$request->validated(),
            'organisation_id' => $org->id,
        ]);

        return redirect()
            ->route('glossary.index')
            ->with('success', 'Glossary term added.');
    }

    public function edit(Request $request, Glossary $glossary)
    {
        abort_if($glossary->organisation_id !== $request->user()->organisation?->id, 403, 'You are not associated with any organisation.');

        return view('glossary.edit', compact('glossary'));
    }

    public function update(StoreGlossaryRequest $request, Glossary $glossary)
    {
        abort_if($glossary->organisation_id !== $request->user()->organisation?->id, 403, 'You are not associated with any organisation.');

        $glossary->update($request->validated());

        return redirect()
            ->route('glossary.index')
            ->with('success', 'Glossary term updated.');
    }

    public function destroy(Request $request, Glossary $glossary)
    {
        abort_if($glossary->organisation_id !== $request->user()->organisation?->id, 403, 'You are not associated with any organisation.');

        $glossary->delete();

        return redirect()
            ->route('glossary.index')
            ->with('success', 'Glossary term deleted.');
    }
}
