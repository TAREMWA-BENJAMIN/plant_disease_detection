<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PgtAiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\FarmingResourceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/regions', [RegionController::class, 'index']);
Route::get('/regions/{region}/districts', [RegionController::class, 'getDistricts']);
Route::apiResource('/community', CommunityController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/experts', [AuthController::class, 'experts']);
    Route::apiResource('/community', CommunityController::class)->except(['index', 'show']);
    Route::post('community/{chat}/reply', [CommunityController::class, 'reply']);
    
    // Protect scan/store endpoints

    Route::post('/v1/results', [PgtAiController::class, 'store']);
    Route::put('/v1/results/{result}', [PgtAiController::class, 'update']);
    Route::delete('/v1/results/{result}', [PgtAiController::class, 'destroy']);
    Route::post('pgt-ai/results', [PgtAiController::class, 'store']);
    Route::get('/v1/my-scans', [PgtAiController::class, 'myScans']);
    Route::post('/audit-trail', [\App\Http\Controllers\AuditTrailController::class, 'store']);
    Route::post('/pgt-ai/audit-trail', [\App\Http\Controllers\AuditTrailController::class, 'store']);
});

// Public endpoints

Route::get('/v1/results', [PgtAiController::class, 'index']);
Route::get('/v1/results/shared', [PgtAiController::class, 'shared']);
Route::get('/v1/results/{result}', [PgtAiController::class, 'show']);
Route::get('/market-prices', [\App\Http\Controllers\MarketPriceController::class, 'apiIndex']);
Route::get('/news', [\App\Http\Controllers\NewsController::class, 'apiIndex']);
Route::get('/news/{news}', [\App\Http\Controllers\NewsController::class, 'apiShow']);

// Farming Resources API Routes (Videos & PDFs)

Route::get('/farming-resources', [FarmingResourceController::class, 'index']);
Route::get('/farming-resources/featured', [FarmingResourceController::class, 'featured']);
Route::get('/farming-resources/categories', [FarmingResourceController::class, 'categories']);
Route::get('/farming-resources/category/{category}', [FarmingResourceController::class, 'byCategory']);
Route::get('/farming-resources/type/{type}', [FarmingResourceController::class, 'byType']);
Route::get('/farming-resources/search', [FarmingResourceController::class, 'search']);
Route::get('/farming-resources/{id}', [FarmingResourceController::class, 'show']);
Route::post('/farming-resources/{id}/download', [FarmingResourceController::class, 'download']);

// Legacy routes for backward compatibility
Route::get('/farming-videos', [FarmingResourceController::class, 'index']);
Route::get('/farming-videos/featured', [FarmingResourceController::class, 'featured']);
Route::get('/farming-videos/categories', [FarmingResourceController::class, 'categories']);
Route::get('/farming-videos/category/{category}', [FarmingResourceController::class, 'byCategory']);
Route::get('/farming-videos/search', [FarmingResourceController::class, 'search']);
Route::get('/farming-videos/{id}', [FarmingResourceController::class, 'show']);
Route::post('/farming-videos/{id}/download', [FarmingResourceController::class, 'download']);

// Test routes for debugging
Route::get('/', function() {
    return response()->json([
        'status' => 'success',
        'message' => 'Plant Disease Detector API'
    ]);
});

Route::get('/test', function() {
    return response()->json([
        'status' => 'success',
        'message' => 'API is working'
    ]);
});

// Debug route
Route::get('/debug', function(Request $request) {
    return response()->json([
        'status' => 'success',
        'request' => [
            'ip' => $request->ip(),
            'url' => $request->url(),
            'method' => $request->method(),
            'headers' => $request->headers->all()
        ]
    ]);
});

// Plant Disease Detection Routes - All Public
Route::prefix('v1')->group(function () {
    // Results endpoints
    Route::get('/results', [PgtAiController::class, 'index']);
    Route::get('/results/shared', [PgtAiController::class, 'shared']);
    Route::get('/results/{result}', [PgtAiController::class, 'show']);
    Route::put('/results/{result}', [PgtAiController::class, 'update']);
    Route::delete('/results/{result}', [PgtAiController::class, 'destroy']);
});

Route::prefix('pgt-ai')->group(function () {
    Route::post('results', [PgtAiController::class, 'store']);
}); 