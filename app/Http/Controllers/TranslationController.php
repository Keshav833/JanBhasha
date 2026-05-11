<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTranslationRequest;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function __construct(
        private readonly TranslationService $translationService,
    ) {}

    /**
     * List paginated translation history for the authenticated user's org.
     */
    public function index(Request $request)
    {
        $org = $request->user()->organisation;

        abort_if(!$org, 403, 'You are not associated with any organisation.');

        $translations = Translation::forOrganisation($org->id)
            ->with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), fn ($q) => $q->where('source_text', 'like', '%' . $request->search . '%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('translations.index', compact('translations', 'org'));
    }

    /**
     * Show the translation form.
     */
    public function create(Request $request)
    {
        $org = $request->user()->organisation;
        abort_if(!$org, 403, 'You are not associated with any organisation.');

        return view('translations.create', compact('org'));
    }

    /**
     * Submit and process a new translation.
     */
    public function store(StoreTranslationRequest $request)
    {
        $user = $request->user();
        $org  = $user->organisation;

        abort_if(!$org, 403, 'You are not associated with any organisation.');

        try {
            $translation = $this->translationService->translate(
                text:       $request->validated('source_text'),
                org:        $org,
                user:       $user,
                sourceLang: $request->validated('source_lang'),
                targetLang: $request->validated('target_lang'),
                label:      $request->validated('source_label'),
            );

            return redirect()
                ->route('translations.show', $translation)
                ->with('success', 'Translation completed successfully.');
        } catch (\RuntimeException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show a single translation result.
     */
    public function show(Request $request, Translation $translation)
    {
        $org = $request->user()->organisation;
        abort_if($translation->organisation_id !== $org?->id, 403, 'You are not associated with any organisation.');

        return view('translations.show', compact('translation'));
    }

    /**
     * Delete (soft-delete) a translation log entry.
     */
    public function destroy(Request $request, Translation $translation)
    {
        $org = $request->user()->organisation;
        abort_if($translation->organisation_id !== $org?->id, 403, 'You are not associated with any organisation.');

        $translation->delete();

        return redirect()
            ->route('translations.index')
            ->with('success', 'Translation record deleted.');
    }
}
