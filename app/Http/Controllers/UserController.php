<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\GeographicalService;
use App\Models\District;
use App\Models\Region;
use App\Services\AuditTrailService;

class UserController extends Controller
{
    protected UserService $userService;
    protected GeographicalService $geographicalService;
    protected $auditTrailService;

    public function __construct(UserService $userService, GeographicalService $geographicalService, AuditTrailService $auditTrailService)
    {
        $this->userService = $userService;
        $this->geographicalService = $geographicalService;
        $this->auditTrailService = $auditTrailService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = $this->userService->getAll();
        return view('users.data', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $regions = Region::orderBy('name')->get();
        $districts = \App\Models\District::orderBy('name')->get();
        return view('users.create', compact('regions', 'districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $destination = base_path('files/images/profile-photos');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $data['photo'] = $filename;
        }
        $user = $this->userService->create($data);

        $action = 'created User - '.$user->first_name.' '.$user->last_name;
        // Log user creation
        $this->auditTrailService->log('create', $user, $action);

        // Redirect based on registration source
        if ($request->has('public_registration')) {
            return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
        } else {
            return redirect()->route('users.index')->with('success', 'User created successfully!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        $user = $this->userService->getById($user->id);
        if ($user) {
            $user->load(['district.region']);
        }
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $data = [
            'id' => $user->id,
            'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'role' => $user->user_type ?? $user->role ?? 'N/A',
            'district' => $user->district,
            'region' => $user->district && $user->district->region ? $user->district->region->name : null,
            'status' => $user->status === true || $user->status === 'active' ? 'active' : 'inactive',
            'created_at' => $user->created_at ? $user->created_at->toDateString() : '',
            'photo_url' => $user->photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $user->photo]) : route('images.show', ['folder' => 'default-avatar', 'filename' => 'default-avatar.png']),
            'title' => $user->title,
            'specialization' => $user->specialization,
            'organization' => $user->organization,
        ];
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $user->load(['district.region']);
        $regions = Region::orderBy('name')->get();
        $districts = $this->geographicalService->getDistricts([]);
        return view('users.edit', compact('user', 'regions', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                $oldPath = base_path('files/images/profile-photos/' . $user->photo);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $file = $request->file('photo');
            $destination = base_path('files/images/profile-photos');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $data['photo'] = $filename;
        }
        $this->userService->update($user, $data);
        $action = 'updated User - ' . $user->first_name . ' ' . $user->last_name;
        $this->auditTrailService->log('update', $user, $action);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        $action = 'deleted User - ' . $user->first_name . ' ' . $user->last_name;
        $this->userService->delete($user);
        $this->auditTrailService->log('delete', $user, $action);
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $this->auditTrailService->log('login', $user, 'User logged in');
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        $regions = Region::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        return view('auth.register', compact('regions', 'districts'));
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $this->auditTrailService->log('logout', $user, 'User logged out');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Show the user's profile.
     */
    public function profile()
    {
        return view('profile', ['user' => Auth::user()]);
    }

    /**
     * Update the user's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $user->update($request->validated());
        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile')->with('success', 'Password updated successfully.');
    }

    /**
     * Search users by name or email for AJAX search bar.
     */
    public function search(Request $request): JsonResponse
    {
        $q = $request->input('q');
        $users = User::query()
            ->where(function($query) use ($q) {
                $query->where('first_name', 'like', "%$q%")
                      ->orWhere('last_name', 'like', "%$q%")
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%$q%"])
                      ->orWhere('email', 'like', "%$q%")
                ;
            })
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'email']);
        $results = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => trim($user->first_name . ' ' . $user->last_name),
                'email' => $user->email,
            ];
        });
        return response()->json($results);
    }

    // AJAX endpoint to get districts by region
    public function getDistrictsByRegion(Request $request)
    {
        $regionId = $request->input('region_id');
        \Log::info('AJAX getDistrictsByRegion called', ['region_id' => $regionId]);
        $districts = \App\Models\District::where('region_id', $regionId)->orderBy('name')->get();
        \Log::info('Districts found', ['count' => $districts->count(), 'districts' => $districts->pluck('name')]);
        return response()->json($districts);
    }
}
