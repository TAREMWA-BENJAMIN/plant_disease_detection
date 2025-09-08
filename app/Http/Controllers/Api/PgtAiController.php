<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PgtAiResultRequest;
use App\Models\PgtAiResult;
use App\Models\User;
use App\Services\PgtAiService;
use App\Services\AuditTrailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PgtAiController extends Controller
{
    protected PgtAiService $pgtAiService;
    protected $auditTrailService;

    public function __construct(PgtAiService $pgtAiService, AuditTrailService $auditTrailService)
    {
        $this->pgtAiService = $pgtAiService;
        $this->auditTrailService = $auditTrailService;
    }

    /**
     * Get all results for the authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        // For now, return all results without user filtering
        $results = PgtAiResult::with('user')
            ->orderBy('created_at', 'desc')
            ->when($request->boolean('paginate', false), function($query) use ($request) {
                return $query->paginate($request->integer('per_page', 10));
            }, function($query) {
                return $query->get();
            });

        return response()->json($results);
    }

    /**
     * Get results for a specific user
     */
    public function userResults(Request $request, User $user): JsonResponse
    {
        $results = $this->pgtAiService->getUserResults(
            $user,
            $request->boolean('paginate', false),
            $request->integer('per_page', 10)
        );

        return response()->json($results);
    }

    /**
     * Get all shared results
     */
    public function shared(Request $request): JsonResponse
    {
        $results = $this->pgtAiService->getSharedResults(
            $request->boolean('paginate', false),
            $request->integer('per_page', 10)
        );

        return response()->json($results);
    }

    /**
     * Store a new result
     */
    public function store(PgtAiResultRequest $request): JsonResponse
    {
        \Log::info('Authenticated user in scan store', [
            'user' => auth()->user(),
            'user_id' => auth()->id(),
            'request_user' => $request->user(),
        ]);
        $data = $request->validated();
        $result = $this->pgtAiService->createResult($data);

        // Log scan action with explicit user
        $user = auth()->user();
        if ($user) {
            $this->auditTrailService->log('scan', $result, 'User performed a scan (API)');
        } else {
            \Log::warning('No authenticated user found for scan audit trail', [
                'result_id' => $result->id,
                'request_user' => $request->user(),
                'auth_user' => auth()->user(),
            ]);
        }

        return response()->json($result, 201);
    }

    /**
     * Get a specific result
     */
    public function show(PgtAiResult $result): JsonResponse
    {
        return response()->json($result);
    }

    /**
     * Update a result
     */
    public function update(PgtAiResultRequest $request, PgtAiResult $result): JsonResponse
    {
        // Remove authorization check
        $updated = $this->pgtAiService->updateResult($result, $request->validated());
        return response()->json(['success' => $updated]);
    }

    /**
     * Delete a result
     */
    public function destroy(PgtAiResult $result): JsonResponse
    {
        // Remove authorization check
        $deleted = $this->pgtAiService->deleteResult($result);
        return response()->json(['success' => $deleted]);
    }

    /**
     * Get all scan results for the authenticated user
     */
    public function myScans(Request $request): JsonResponse
    {
        $user = $request->user();
        $results = PgtAiResult::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json(['data' => $results]);
    }
} 