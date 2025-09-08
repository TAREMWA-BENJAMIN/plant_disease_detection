<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    /**
     * Display a listing of the audit logs.
     */
    public function index(Request $request)
    {
        $logs = AuditTrail::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('audit_trail.index', compact('logs'));
    }

    /**
     * Store a new audit log entry (API).
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'action' => 'required|string',
            'model_type' => 'nullable|string',
            'model_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'platform' => 'nullable|string',
        ]);
        $log = AuditTrail::create([
            'user_id' => $user ? $user->id : null,
            'action' => $data['action'],
            'model_type' => $data['model_type'] ?? null,
            'model_id' => $data['model_id'] ?? null,
            'description' => $data['description'] ?? null,
            'platform' => $data['platform'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        return response()->json(['success' => true, 'log' => $log], 201);
    }
}
