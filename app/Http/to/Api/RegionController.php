<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeographicalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    protected GeographicalService $geographicalService;

    public function __construct(GeographicalService $geographicalService)
    {
        $this->geographicalService = $geographicalService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $regions = $this->geographicalService->getRegions();
        return response()->json($regions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
} 