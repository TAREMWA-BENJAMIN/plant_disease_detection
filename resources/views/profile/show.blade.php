@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">Admin Profile</h2>
                        <a href="{{ route('settings.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Profile
                        </a>
                    </div>
                    <div class="text-center mb-4">
                        <img src="{{ $user->photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $user->photo]) : route('images.show', ['folder' => 'default-avatar', 'filename' => 'default-avatar.png']) }}" alt="{{ $user->first_name }}" class="rounded-circle mb-2" width="120" height="120">
                        <h4 class="mb-0">{{ $user->first_name }} {{ $user->last_name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>
                        @if($user->is_verified)
                            <span class="badge bg-success">Verified User</span>
                        @endif
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Phone Number:</strong>
                            <div>{{ $user->phone_number }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>User Type:</strong>
                            <div>{{ ucfirst($user->user_type) }}</div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <strong>District:</strong>
                            <div>{{ $user->district->name ?? 'Not specified' }}</div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <strong>Last Login:</strong>
                            <div>{{ $user->last_login ? $user->last_login->diffForHumans() : 'Never' }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-success">
                            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt me-1"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 