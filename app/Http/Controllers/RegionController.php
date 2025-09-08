<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Services\GeographicalService;
use Illuminate\Http\JsonResponse;

class RegionController extends Controller
{
    protected GeographicalService $geographicalService;

    public function __construct(GeographicalService $geographicalService)
    {
        $this->geographicalService = $geographicalService;
    }

    /**
     * Get all regions
     */
    public function index(): JsonResponse
    {
        $regions = $this->geographicalService->getRegions();
        return response()->json($regions);
    }

    /**
     * Get countries for a specific region
     */
    public function countries(Region $region): JsonResponse
    {
        $countries = $this->geographicalService->getCountriesByRegion($region);
        return response()->json($countries);
    }
} 