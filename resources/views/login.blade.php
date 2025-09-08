@extends('layouts.auth')

@section('content')
<div class="page-wrapper full-page">
    <div class="page-content d-flex align-items-center justify-content-center">
        <div class="row w-100 mx-0 auth-page">
            <div class="col-md-8 col-xl-6 mx-auto">
                <div class="card">
                    <div class="row">
                        <div class="col-md-4 pe-md-0">
                            <div class="auth-side-wrapper" style="background-image: url({{ url('files/images/farmer.PNG') }})">
                            </div>
                        </div>
                        <div class="col-md-8 ps-md-0">
                            <div class="auth-form-wrapper px-4 py-5">
                                <a href="{{ url('/') }}" class="noble-ui-logo d-block mb-2">Noble<span>UI</span></a>
                                <h5 class="text-muted fw-normal mb-4">Welcome back! Log in to your account.</h5>
                                <form class="forms-sample" method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                            id="email" name="email" value="{{ old('email') }}" 
                                            placeholder="Email" required autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                            id="password" name="password" 
                                            placeholder="Password" required autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="remember" 
                                            name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>

                                    <div>
                                        <button type="submit" class="btn btn-primary me-2 mb-2 mb-md-0">
                                            Login
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                                            <i class="btn-icon-prepend" data-feather="twitter"></i>
                                            Login with twitter
                                        </button>
                                    </div>

                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="d-block mt-3 text-muted">
                                            Forgot your password?
                                        </a>
                                    @endif

                                    <a href="{{ route('register') }}" class="d-block mt-3 text-muted">
                                        Not a user? Sign up
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection