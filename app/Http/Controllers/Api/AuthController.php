<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Services\AuditTrailService;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    protected $auditTrailService;

    public function __construct(AuditTrailService $auditTrailService)
    {
        $this->auditTrailService = $auditTrailService;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'user_type' => 'nullable|string|max:50',
            'region_id' => 'nullable|integer|exists:regions,id',
            'district_id' => 'nullable|integer|exists:districts,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $destination = base_path('files/images/profile-photos');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $data['photo'] = $filename;
        }

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'] ?? null,
            'user_type' => $data['user_type'] ?? null,
            'region_id' => $data['region_id'] ?? null,
            'district_id' => $data['district_id'] ?? null,
            'photo' => $data['photo'] ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Log registration
        $this->auditTrailService->log('register', $user, 'User registered');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Log failed login attempt
            $this->auditTrailService->log('login_failed', null, 'Failed login attempt for email: ' . $request->email);
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Log successful login
        $this->auditTrailService->log('login', $user, 'User logged in');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $request->user()->currentAccessToken()->delete();

        // Log logout
        $this->auditTrailService->log('logout', $user, 'User logged out');

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function experts(Request $request)
    {
        try {
            $experts = \App\Models\User::where('user_type', 'admin')
                ->leftJoin('districts', 'users.district_id', '=', 'districts.id')
                ->leftJoin('regions', 'districts.region_id', '=', 'regions.id')
                ->select(
                    'users.id',
                    'users.title',
                    'users.email',
                    'users.phone_number as phone',
                    'users.organization',
                    'users.specialization',
                    'users.photo',
                    'regions.name as region',
                    DB::raw("TRIM(CONCAT(COALESCE(users.title, ''), ' ', COALESCE(users.first_name, ''), ' ', COALESCE(users.last_name, ''))) as full_name")
                )
                ->get();

            $experts->transform(function ($expert) {
                $expert->photo_url = $expert->photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $expert->photo]) : null;
                unset($expert->photo);
                return $expert;
            });

            return response()->json([
                'status' => 'success',
                'data' => $experts
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch experts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Add this method for forgot password
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)], 200);
        } else {
            return response()->json(['message' => __($status)], 400);
        }
    }
}
