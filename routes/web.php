<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PgtAiController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\AuditTrailController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Simple test route for chart data (public for testing)
Route::get('/test-chart-data', function() {
    return response()->json([
        'message' => 'Test chart data endpoint working',
        'timestamp' => now()->toISOString(),
        'diseaseTrends' => [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [10, 15, 8, 12, 20, 18]
        ],
        'topDiseases' => [
            'labels' => ['Disease A', 'Disease B', 'Disease C'],
            'data' => [25, 18, 12]
        ],
        'userGrowth' => [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [5, 8, 12, 15, 20, 25]
        ]
    ]);
})->name('test.chart-data');

Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'store']);
Route::get('districts/by-region', [App\Http\Controllers\UserController::class, 'getDistrictsByRegion'])->name('districts.by-region');
Route::get('/districts/by-region', [App\Http\Controllers\UserController::class, 'getDistrictsByRegion'])->name('districts.by-region');

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    // User list route (now protected)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/dashboard/test', [DashboardController::class, 'test'])->name('dashboard.test');
    Route::get('/dashboard/ping', [DashboardController::class, 'ping'])->name('dashboard.ping');
    
    // Debug route to check if routes are working
    Route::get('/debug/routes', function() {
        try {
            return response()->json([
                'message' => 'Routes are working',
                'current_url' => request()->url(),
                'base_path' => request()->getBasePath(),
                'user_authenticated' => auth()->check(),
                'routes' => [
                    'dashboard' => route('dashboard'),
                    'chart_data' => route('dashboard.chart-data'),
                    'test' => route('dashboard.test')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Debug route failed',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    });



    // Routes for AI Scan Results Report
    Route::get('/reports/pgt-ai-results', [DashboardController::class, 'showPgtAiResults'])->name('reports.pgt-ai-results');
    Route::get('/reports/pgt-ai-results/pdf', [DashboardController::class, 'exportPgtAiResultsPdf'])->name('reports.pgt-ai-results.pdf');
    Route::get('/reports/pgt-ai-results/excel', [DashboardController::class, 'exportPgtAiResultsExcel'])->name('reports.pgt-ai-results.excel');
    Route::get('/reports/generate', [PgtAiController::class, 'generateReport'])->name('reports.generate');

    // User management
    Route::resource('users', UserController::class);
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/districts/{countryId}', [UserProfileController::class, 'getDistricts'])->name('profile.districts');
    Route::put('password', [UserController::class, 'updatePassword'])->name('password.update');

    // Geographical data for user edit form
    Route::get('users/get-subregions', [UserController::class, 'getSubregions'])->name('users.get-subregions');
    Route::get('users/get-countries', [UserController::class, 'getCountries'])->name('users.get-countries');
    Route::get('users/get-districts', [UserController::class, 'getDistricts'])->name('users.get-districts');

    // Region management
    Route::resource('regions', RegionController::class);
    Route::get('regions/{region}/subregions', [RegionController::class, 'subregions'])->name('regions.subregions');
    Route::get('regions/{region}/countries', [RegionController::class, 'countries'])->name('regions.countries');

    // District management
    // Route::resource('districts', DistrictController::class);
    Route::get('districts/{district}/users', [DistrictController::class, 'users'])->name('districts.users');

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Plant Disease Detection Routes
    Route::prefix('pgt-ai')->name('pgt-ai.')->group(function () {
        // Dashboard/Overview
        Route::get('/dashboard', [PgtAiController::class, 'dashboard'])->name('dashboard');
        
        // Results Management
        Route::get('/results', [PgtAiController::class, 'index'])->name('results.index');
        Route::get('/results/{result}', [PgtAiController::class, 'show'])->name('results.show');
        Route::put('/results/{result}', [PgtAiController::class, 'update'])->name('results.update');
        Route::delete('/results/{result}', [PgtAiController::class, 'destroy'])->name('results.destroy');
        
        // Shared Results
        Route::get('/shared', [PgtAiController::class, 'shared'])->name('shared');
        
        // User Results
        Route::get('/users/{user}/results', [PgtAiController::class, 'userResults'])->name('users.results');
    });

    // Routes for Disease Management
    Route::resource('/diseases', DiseaseController::class);

    // Routes for Community Forum
    Route::resource('/community', CommunityController::class);
    Route::post('/community', [App\Http\Controllers\CommunityController::class, 'store'])->name('community.store');
    Route::post('/community/{chat}/reply', [App\Http\Controllers\CommunityController::class, 'reply'])->name('community.reply');
    Route::delete('/community/{chat}', [App\Http\Controllers\CommunityController::class, 'destroy'])->name('community.destroy');

    // Settings routes
    Route::get('/settings', [App\Http\Controllers\UserProfileController::class, 'show'])->name('settings.show');
    Route::get('/settings/edit', [App\Http\Controllers\UserProfileController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [App\Http\Controllers\UserProfileController::class, 'update'])->name('settings.update');

    // Market Prices
    Route::get('/market-prices', [\App\Http\Controllers\MarketPriceController::class, 'index'])->name('market-prices.index');

    // News
    Route::get('/news', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{news}', [\App\Http\Controllers\NewsController::class, 'show'])->name('news.show');
    Route::post('/api/fetch-news', [\App\Http\Controllers\NewsController::class, 'fetchNews'])->name('news.fetch');

    Route::get('/farming-resources', function () {
        $resources = \App\Models\FarmingResource::orderBy('created_at', 'desc')->get();
        return view('farming_resources', compact('resources'));
    })->name('farming-resources.index');

    Route::get('/farming-resources/upload', function () {
        return view('farming_resources_upload');
    })->name('farming-resources.upload');

    Route::post('/farming-resources/upload', [
        App\Http\Controllers\FarmingResourceWebController::class,
        'store',
    ])->name('farming-resources.store');

    Route::delete('/farming-resources/{id}', [App\Http\Controllers\FarmingResourceWebController::class, 'destroy'])->name('farming-resources.destroy');
});

// Password reset routes
Route::get('/forgot-password', [App\Http\Controllers\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\ForgotPasswordController::class, 'reset'])->name('password.update.reset');

// Disease Management (Temporarily moved outside auth middleware for testing)
Route::get('/diseases', [App\Http\Controllers\DiseaseController::class, 'index'])->name('diseases.index');

// Agricultural Experts
Route::get('/experts', [App\Http\Controllers\ExpertController::class, 'index'])->name('experts.index');

Route::get('/search/users', [App\Http\Controllers\UserController::class, 'search'])->name('users.search');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/audit-trail', [\App\Http\Controllers\AuditTrailController::class, 'index'])->name('audit_trail.index');
});

Route::get('/test-audit-log', function() {
    app(\App\Services\AuditTrailService::class)->log('test', null, 'Test audit log from route');
    return 'Audit log attempted. Check DB and logs.';
});

Route::get('/truncate-audit-tables', function() {
    \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    \DB::table('audit_trail')->truncate();
    \DB::table('chat_replies')->truncate();
    \DB::table('chat')->truncate();
    \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    return 'Truncated audit_trail, chat_replies, and chat tables.';
});

Route::get('images/{folder}/{filename}', [App\Http\Controllers\ImageController::class, 'show'])->name('images.show');

// Route to serve files from the files directory
Route::get('files/{path}', function($path) {
    $filePath = base_path('files/' . $path);
    
    if (!file_exists($filePath)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($filePath);
    return response()->file($filePath, ['Content-Type' => $mimeType]);
})->where('path', '.*')->name('files.serve');

