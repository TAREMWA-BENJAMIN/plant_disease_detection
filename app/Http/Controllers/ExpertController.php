<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class ExpertController extends Controller
{
    /**
     * Display a listing of agricultural experts.
     */
    public function index(): View
    {
        $experts = User::where('user_type', 'admin')
                        ->with(['district'])
                        ->latest()
                        ->get();

        return view('experts.index', compact('experts'));
    }
} 