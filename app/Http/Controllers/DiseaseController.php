<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Illuminate\View\View;

class DiseaseController extends Controller
{
    /**
     * Display a listing of diseases.
     */
    public function index(): View
    {
        $diseases = Disease::all(); // Fetch all diseases from the database
        return view('diseases.index', compact('diseases'));
    }
} 