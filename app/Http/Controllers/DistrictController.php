<?php

namespace App\Http\Controllers;

use App\Services\GeographicalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    protected GeographicalService $geographicalService;

    public function __construct(GeographicalService $geographicalService)
    {
        $this->geographicalService = $geographicalService;
    }

    /**
     * Get all districts with optional country filter
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['country_id']);
        $districts = $this->geographicalService->getDistricts($filters);
        return response()->json($districts);
    }
} 