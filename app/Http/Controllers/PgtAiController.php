<?php

namespace App\Http\Controllers;

use App\Models\PgtAiResult;
use App\Models\User;
use App\Services\PgtAiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PgtAiController extends Controller
{
    protected PgtAiService $pgtAiService;

    public function __construct(PgtAiService $pgtAiService)
    {
        $this->pgtAiService = $pgtAiService;
    }

    /**
     * Display a paginated list of all results for reporting.
     */
    public function generateReport(Request $request): View
    {
        $results = $this->pgtAiService->getAllResults(
            true,
            $request->integer('per_page', 15)
        );

        return view('pgt-ai.reports.index', compact('results'));
    }

    /**
     * Display the dashboard
     */
    public function dashboard(): View
    {
        $userResults = $this->pgtAiService->getUserResults(auth()->user(), true, 5);
        $sharedResults = $this->pgtAiService->getSharedResults(true, 5);

        return view('pgt-ai.dashboard', compact('userResults', 'sharedResults'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $results = $this->pgtAiService->getUserResults(
            $request->user(),
            true,
            $request->integer('per_page', 10)
        );

        return view('pgt-ai.results.index', compact('results'));
    }

    /**
     * Display the specified resource.
     */
    public function show(PgtAiResult $result): View
    {
        return view('pgt-ai.results.show', compact('result'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PgtAiResult $result)
    {
        $this->authorize('update', $result);

        $data = $request->validate([
            'plant_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:healthy,diseased'],
            'disease_name' => ['nullable', 'string', 'max:255'],
            'disease_details' => ['nullable', 'string'],
            'suggested_solution' => ['nullable', 'string'],
            'shared' => ['boolean']
        ]);

        $this->pgtAiService->updateResult($result, $data);

        return redirect()
            ->route('pgt-ai.results.show', $result)
            ->with('success', 'Plant disease detection result updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PgtAiResult $result)
    {
        $this->pgtAiService->deleteResult($result);

        return redirect()
            ->route('reports.generate')
            ->with('success', 'Plant disease detection result deleted successfully.');
    }

    /**
     * Display all shared results
     */
    public function shared(Request $request): View
    {
        $results = $this->pgtAiService->getSharedResults(
            true,
            $request->integer('per_page', 10)
        );

        return view('pgt-ai.results.shared', compact('results'));
    }

    /**
     * Display results for a specific user
     */
    public function userResults(Request $request, User $user): View
    {
        $results = $this->pgtAiService->getUserResults(
            $user,
            true,
            $request->integer('per_page', 10)
        );

        return view('pgt-ai.results.user', compact('results', 'user'));
    }
} 