<?php

namespace App\Services;

use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditTrailService
{
    /**
     * Log an action to the audit trail.
     *
     * @param string $action
     * @param null|\Illuminate\Database\Eloquent\Model $model
     * @param null|string $description
     * @param null|string $platform
     * @return void
     */
    public function log($action, $model = null, $description = null, $platform = null)
    {
        $user = auth()->user() ?? request()->user();
        
        \Log::info('AuditTrailService called', [
            'action' => $action,
            'user' => $user ? $user->id : 'null',
            'user_name' => $user ? $user->name : 'null',
            'auth_user' => auth()->user() ? auth()->user()->id : 'null',
            'request_user' => request()->user() ? request()->user()->id : 'null',
            'model' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'description' => $description,
            'platform' => $platform,
        ]);
        $request = request();

        // Ensure we have a valid user or log as guest
        $userId = null;
        if ($user && $user->id) {
            $userId = $user->id;
        } else {
            \Log::warning('No valid user found for audit trail', [
                'action' => $action,
                'description' => $description,
                'auth_user' => auth()->user(),
                'request_user' => request()->user(),
            ]);
        }

        AuditTrail::create([
            'user_id' => $userId,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'description' => $description,
            'platform' => $platform ?? $this->detectPlatform($request),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Detect platform from request headers/user agent.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function detectPlatform($request)
    {
        $userAgent = $request->userAgent();
        if (stripos($userAgent, 'Android') !== false || stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'Mobile') !== false) {
            return 'Mobile App';
        }
        return 'Web App';
    }
} 