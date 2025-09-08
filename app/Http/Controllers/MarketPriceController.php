<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MarketPrice;

class MarketPriceController extends Controller
{
    public function index()
    {
        $marketPrices = MarketPrice::orderBy('commodity')->get();
        return view('market_prices.index', compact('marketPrices'));
    }

    public function apiIndex()
    {
        $marketPrices = MarketPrice::orderBy('commodity')->get();
        return response()->json($marketPrices);
    }
}
