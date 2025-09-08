<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PgtAiResult;
use App\Models\Disease;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use PDF;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     */
    public function index(): View
    {
        // --- User Statistics ---

        // Total Users
        $totalUsers = User::count();
        $totalUsersLastMonth = User::where('created_at', '<', Carbon::now()->subMonth())->count();
        $totalUsersPercentageChange = $totalUsersLastMonth > 0
            ? (($totalUsers - $totalUsersLastMonth) / $totalUsersLastMonth) * 100
            : ($totalUsers > 0 ? 100 : 0);

        // New Users (today)
        $newUsersToday = User::whereDate('created_at', Carbon::today())->count();
        $newUsersYesterday = User::whereDate('created_at', Carbon::yesterday())->count();
        $newUsersPercentageChange = $newUsersYesterday > 0
            ? (($newUsersToday - $newUsersYesterday) / $newUsersYesterday) * 100
            : ($newUsersToday > 0 ? 100 : 0);

        // Active Users (this week)
        $activeUsersThisWeek = Schema::hasColumn('users', 'last_active_at')
            ? User::where('last_active_at', '>=', Carbon::now()->subWeek())->count()
            : 0;
        $activeUsersLastWeek = Schema::hasColumn('users', 'last_active_at')
            ? User::whereBetween('last_active_at', [Carbon::now()->subWeeks(2), Carbon::now()->subWeek()])->count()
            : 0;
        $activeUsersPercentageChange = $activeUsersLastWeek > 0
            ? (($activeUsersThisWeek - $activeUsersLastWeek) / $activeUsersLastWeek) * 100
            : ($activeUsersThisWeek > 0 ? 100 : 0);

        // --- Scan Statistics ---
        $totalScansSubmitted = PgtAiResult::count();

        return view('dashboard', [
            'totalUsers' => $totalUsers,
            'totalUsersPercentageChange' => round($totalUsersPercentageChange),
            'newUsers' => $newUsersToday,
            'newUsersPercentageChange' => round($newUsersPercentageChange),
            'activeUsers' => $activeUsersThisWeek,
            'activeUsersPercentageChange' => round($activeUsersPercentageChange),
            'totalScansSubmitted' => $totalScansSubmitted
        ]);
    }

    /**
     * Display all data from pgt_ai_results table.
     */
    public function showPgtAiResults(): View
    {
        $pgtAiResults = PgtAiResult::all();
        return view('reports.pgt_ai_results', compact('pgtAiResults'));
    }

    /**
     * Export pgt_ai_results to PDF.
     */
    public function exportPgtAiResultsPdf()
    {
        $pgtAiResults = \App\Models\PgtAiResult::with('user')->get();
        $pdf = PDF::loadView('reports.pgt_ai_results_pdf', compact('pgtAiResults'));
        return $pdf->download('pgt_ai_results.pdf');
    }

    /**
     * Export pgt_ai_results to Excel.
     */
    public function exportPgtAiResultsExcel()
    {
        // Implement Excel export logic here
        // You will likely need a library like Maatwebsite\Excel
        // Example: return Excel::download(new PgtAiResultsExport, 'pgt_ai_results.xlsx');
        return response()->json(['message' => 'Excel export functionality to be implemented.']);
    }

    /**
     * Test endpoint to verify the controller is working
     */
    public function test()
    {
        try {
            \Log::info('Dashboard test method called');
            
            $userCount = User::count();
            $scanCount = PgtAiResult::count();
            
            \Log::info('Test method completed successfully', [
                'user_count' => $userCount,
                'scan_count' => $scanCount
            ]);
            
            return response()->json([
                'message' => 'Dashboard controller is working',
                'timestamp' => now(),
                'user_count' => $userCount,
                'scan_count' => $scanCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in test method: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'error' => 'Test method failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simple ping endpoint to test controller accessibility
     */
    public function ping()
    {
        try {
            \Log::info('Dashboard ping method called');
            
            return response()->json([
                'message' => 'pong',
                'controller' => 'DashboardController',
                'timestamp' => now()->toISOString(),
                'url' => request()->url(),
                'method' => request()->method()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in ping method: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Ping method failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getChartData()
    {
        try {
            \Log::info('getChartData method called');
            
            // Check if models exist and are accessible
            if (!class_exists('App\Models\PgtAiResult')) {
                throw new \Exception('PgtAiResult model not found');
            }
            
            if (!class_exists('App\Models\User')) {
                throw new \Exception('User model not found');
            }
            
            // Get the last 6 months for the x-axis
            $months = collect(range(5, 0))->map(function($i) {
                return Carbon::now()->subMonths($i)->format('M Y');
            })->toArray();

            \Log::info('Months array created:', $months);

            // Disease Detection Trends (Line Chart) - Database agnostic approach
            try {
                $diseaseTrends = PgtAiResult::where('created_at', '>=', Carbon::now()->subMonths(6))
                    ->get()
                    ->groupBy(function($item) {
                        return $item->created_at->format('M Y');
                    })
                    ->map(function($group) {
                        return $group->count();
                    });
                
                \Log::info('Disease trends data retrieved:', ['count' => $diseaseTrends->count()]);
            } catch (\Exception $e) {
                \Log::error('Error getting disease trends:', ['error' => $e->getMessage()]);
                $diseaseTrends = collect();
            }
            
            $diseaseTrendData = [];
            foreach ($months as $month) {
                $diseaseTrendData[] = $diseaseTrends->get($month, 0);
            }

            // Top Detected Diseases (Bar Chart)
            try {
                $topDiseases = PgtAiResult::select('disease_name', DB::raw('count(*) as count'))
                    ->whereNotNull('disease_name')
                    ->where('disease_name', '!=', '')
                    ->groupBy('disease_name')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->get();
                
                \Log::info('Top diseases data retrieved:', ['count' => $topDiseases->count()]);
            } catch (\Exception $e) {
                \Log::error('Error getting top diseases:', ['error' => $e->getMessage()]);
                $topDiseases = collect();
            }

            // User Growth Over Time (Line Chart) - Database agnostic approach
            try {
                $userGrowth = User::where('created_at', '>=', Carbon::now()->subMonths(6))
                    ->get()
                    ->groupBy(function($item) {
                        return $item->created_at->format('M Y');
                    })
                    ->map(function($group) {
                        return $group->count();
                    });
                
                \Log::info('User growth data retrieved:', ['count' => $userGrowth->count()]);
            } catch (\Exception $e) {
                \Log::error('Error getting user growth:', ['error' => $e->getMessage()]);
                $userGrowth = collect();
            }
            
            $userGrowthData = [];
            foreach ($months as $month) {
                $userGrowthData[] = $userGrowth->get($month, 0);
            }

            $responseData = [
                'diseaseTrends' => [
                    'labels' => $months,
                    'data' => $diseaseTrendData
                ],
                'topDiseases' => [
                    'labels' => $topDiseases->pluck('disease_name')->toArray(),
                    'data' => $topDiseases->pluck('count')->toArray()
                ],
                'userGrowth' => [
                    'labels' => $months,
                    'data' => $userGrowthData
                ]
            ];

            \Log::info('Chart data response prepared successfully');
            return response()->json($responseData);
            
        } catch (\Exception $e) {
            \Log::error('Error in getChartData: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to fetch chart data',
                'message' => $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }
} 