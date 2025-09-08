@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 400px; margin: 60px auto; background: #fff; border-radius: 1rem; box-shadow: 0 8px 32px 0 rgba(31,38,135,0.15); padding: 2.5rem 2rem;">
    <h2 style="font-size:1.3rem;margin-bottom:2rem;color:#333;font-weight:600;text-align:center;">Forgot Your Password?</h2>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label" style="color:#444;font-weight:500;">Email Address</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert" style="display:block;color:#e3342f;font-size:0.95rem;margin-top:0.3rem;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100" style="background:#2563eb;border:none;border-radius:0.5rem;font-size:1.1rem;font-weight:600;">Send Password Reset Link</button>
    </form>
    <div style="margin-top:1.5rem;text-align:center;">
        <a href="{{ route('login') }}" style="color:#2563eb;text-decoration:none;font-weight:500;">Back to Login</a>
    </div>
</div>
@endsection 