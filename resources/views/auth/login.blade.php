<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Plant Disease Detector</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, rgba(240, 247, 240, 0.05) 0%, rgba(195, 230, 195, 0.05) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image: url('{{ url("files/images/farmer.PNG") }}');
            background-size: cover;
            background-position: center;
            background-blend-mode: normal;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            display: flex;
            backdrop-filter: blur(1px);
        }

        .login-image {
            flex: 1;
            background: linear-gradient(45deg, rgba(46, 125, 50, 0.6), rgba(27, 94, 32, 0.6));
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(46, 125, 50, 0.2), rgba(27, 94, 32, 0.2));
            opacity: 0.3;
        }

        .login-form {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1.5rem;
        }

        .form-subtitle {
            color: #718096;
            margin-bottom: 2rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 20px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .input-group-text {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            background: transparent;
        }

        .btn-login {
            background: linear-gradient(45deg, rgba(46, 125, 50, 0.9), rgba(27, 94, 32, 0.9));
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.4);
            background: linear-gradient(45deg, rgba(46, 125, 50, 1), rgba(27, 94, 32, 1));
        }

        .register-link {
            color: #2e7d32;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .register-link:hover {
            color: #1b5e20;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .login-image {
                display: none;
            }
            .login-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-image">
            <h2 class="mb-4">Welcome to Plant Disease Detector</h2>
            <p class="mb-4">Your AI-powered solution for plant health management</p>
            
            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <span>Advanced Plant Disease Detection</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <span>AI-Powered Analysis</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <span>Real-time Monitoring</span>
                </div>
            </div>
        </div>
        
        <div class="login-form" style="margin:auto;max-width:370px;width:100%;background:rgba(255,255,255,0.2);border-radius:1.25rem;box-shadow:0 8px 32px 0 rgba(31,38,135,0.15);padding:2.5rem 2rem;text-align:center;">
            <a href="/" class="login-logo" style="font-size:1.5rem;font-weight:bold;color:#2563eb;text-decoration:none;margin-bottom:1.5rem;display:inline-block;">Plant<span style='color:#2d7a36;font-weight:bold;'>D</span></a>
            <h2 style="font-size:1.2rem;margin-bottom:2rem;color:#000000;font-weight:bold;">Welcome back! Log in to your account.</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group mb-3" style="margin-bottom:1.2rem;text-align:left;">
                    <label for="email" style="display:block;margin-bottom:0.4rem;color:#444;font-weight:500;">Email Address</label>
                    <div style="display:flex;align-items:center;background:#f3f6fa;border-radius:0.5rem;padding:0.5rem 0.8rem;border:1px solid #e0e0e0;">
                        <span style="margin-right:0.7rem;color:#888;font-size:1.1rem;"><i class="fas fa-envelope"></i></span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus style="border:none;background:transparent;outline:none;width:100%;font-size:1rem;color:#222;">
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert" style="display:block;color:#e3342f;font-size:0.95rem;margin-top:0.3rem;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="input-group mb-3" style="margin-bottom:1.2rem;text-align:left;">
                    <label for="password" style="display:block;margin-bottom:0.4rem;color:#444;font-weight:500;">Password</label>
                    <div style="display:flex;align-items:center;background:#f3f6fa;border-radius:0.5rem;padding:0.5rem 0.8rem;border:1px solid #e0e0e0;position:relative;">
                        <span style="margin-right:0.7rem;color:#888;font-size:1.1rem;"><i class="fas fa-lock"></i></span>
                        <input id="password" type="password" name="password" required style="border:none;background:transparent;outline:none;width:100%;font-size:1rem;color:#222;">
                        <span id="togglePassword" style="cursor:pointer;margin-left:0.7rem;color:#2563eb;font-size:1.2rem;position:absolute;right:1rem;">
                            <i class="fas fa-eye-slash"></i>
                        </span>
                    </div>
                    <a href="{{ route('password.request') }}" style="display:block;margin-top:0.5rem;color:#ff0000;font-weight:bold;text-align:right;text-decoration:none;font-size:0.98rem;">Forgot Password?</a>
                    @error('password')
                        <span class="invalid-feedback" role="alert" style="display:block;color:#e3342f;font-size:0.95rem;margin-top:0.3rem;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="remember-me" style="display:flex;align-items:center;margin-bottom:1.5rem;font-size:0.95rem;">
                    <input type="checkbox" name="remember" id="remember" style="margin-right:0.5rem;">
                    <label for="remember" style="margin-bottom:0;">Remember Me</label>
                </div>
                <button type="submit" class="btn-login" style="width:100%;padding:0.7rem 0;background:#2d7a36;color:#fff;border:none;border-radius:0.5rem;font-size:1.1rem;font-weight:600;cursor:pointer;transition:background 0.2s;">Sign In</button>
            </form>
            <div class="register-link" style="margin-top:1.5rem;font-size:1rem;">
                <span style="color: #000000;">Don't have an account?</span>
                <a href="{{ route('register') }}" style="color:#ff0000;font-weight:bold;text-decoration:none;margin-left:0.3rem;">Create Account</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            const icon = togglePassword.querySelector('i');
            icon.classList.toggle('fa-eye', isPassword);
            icon.classList.toggle('fa-eye-slash', !isPassword);
        });
    </script>
</body>
</html> 