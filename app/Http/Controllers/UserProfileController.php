<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\District;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load(['district']);
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user()->load(['district']);
        $regions = Region::orderBy('name')->get();
        $districts = \App\Models\District::orderBy('name')->get();
        return view('profile.edit', compact('user', 'regions', 'districts'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        if ($request->hasFile('photo')) {
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
            $validated['photo'] = $filename;
        }
        if ($request->filled('password')) {
            $validated['password'] = \Hash::make($request->password);
        } else {
            unset($validated['password']);
        }
        $user->update($validated);
        return redirect()->route('settings.edit')->with('success', 'Profile updated successfully');
    }

    public function destroy()
    {
        Auth::logout();
        return redirect('/');
    }
} 