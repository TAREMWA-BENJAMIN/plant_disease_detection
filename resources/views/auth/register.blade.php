<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Plant Disease Detector</title>
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

        .register-container {
            background: rgba(255, 255, 255, 0.35);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            display: flex;
            backdrop-filter: blur(1px);
        }

        .register-image {
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

        .register-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(46, 125, 50, 0.2), rgba(27, 94, 32, 0.2));
            opacity: 0.3;
        }

        .register-form {
            flex: 1;
            padding: 40px 24px;
            display: block;
            height: 100vh;
            overflow-y: auto;
            box-sizing: border-box;
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
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .input-group-text {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            background: rgba(255, 255, 255, 0.8);
        }

        .btn-register {
            background: linear-gradient(45deg, rgba(46, 125, 50, 0.9), rgba(27, 94, 32, 0.9));
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.4);
            background: linear-gradient(45deg, rgba(46, 125, 50, 1), rgba(27, 94, 32, 1));
        }

        .login-link {
            color: #2e7d32;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-link:hover {
            color: #1b5e20;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .benefit-icon {
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
            .register-container {
                flex-direction: column;
            }
            .register-image {
                display: none;
            }
            .register-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-image">
            <h2 class="mb-4">Join Our Community</h2>
            <p class="mb-4">Start your journey in plant disease detection</p>
            
            <div class="benefits">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span>Secure & Private</span>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <span>Instant Analysis</span>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span>Expert Community</span>
                </div>
            </div>
        </div>
        
        <div class="register-form" style="max-height: 90vh; overflow-y: auto;">
            <h1 class="form-title" style="color: #000000; font-weight: bold;">Create Account</h1>
            <p class="form-subtitle" style="color: #000000; font-weight: bold;">Join our plant disease detection platform</p>

            @if (
                $errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="public_registration" value="1">
                <div class="mb-4">
                    <label for="first_name" class="form-label">First Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                    </div>
                    @error('first_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
                <div class="mb-4">
                    <label for="last_name" class="form-label">Last Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                    </div>
                    @error('last_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
                <div class="mb-4">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                    </div>
                    @error('phone_number')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        <span class="input-group-text" style="cursor:pointer" id="togglePassword"><i class="fas fa-eye-slash"></i></span>
                    </div>
                    @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        <span class="input-group-text" style="cursor:pointer" id="togglePasswordConfirm"><i class="fas fa-eye-slash"></i></span>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="region_id" class="form-label">Region</label>
                    <select class="form-control" id="region_id" name="region_id" required>
                        <option value="">Select Region</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="district_id" class="form-label">District</label>
                    <select class="form-control" id="district_id" name="district_id">
                        <option value="">Select District</option>
                        @isset($districts)
                            @foreach($districts as $district)
                                @if(old('region_id') == $district->region_id)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endif
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="mb-4">
                    <label for="user_type" class="form-label">User Type</label>
                    <select class="form-control" id="user_type" name="user_type" required>
                        <option value="user" {{ old('user_type') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div id="admin-fields" style="display: none;">
                    <div class="mb-4">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                    </div>
                    <div class="mb-4">
                        <label for="specialization" class="form-label">Specialization</label>
                        <input type="text" class="form-control" id="specialization" name="specialization" value="{{ old('specialization') }}">
                    </div>
                    <div class="mb-4">
                        <label for="organization" class="form-label">Organization/Institution</label>
                        <input type="text" class="form-control" id="organization" name="organization" value="{{ old('organization') }}">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary btn-register w-100 mb-4" style="color: #ffffff; font-weight: bold;">Create Account</button>
                <div class="text-center">
                    <p class="mb-0" style="color: #000000;">Already have an account? <a href="{{ route('login') }}" class="login-link" style="color: #ff0000; font-weight: bold;">Sign In</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const regionSelect = document.getElementById('region_id');
        const districtSelect = document.getElementById('district_id');
        regionSelect.addEventListener('change', function() {
            const regionId = this.value;
            districtSelect.innerHTML = '<option value="">Select District</option>';
            if (regionId) {
                // Get the current base URL dynamically
                const baseUrl = window.location.pathname.includes('/register') 
                    ? window.location.pathname.replace('/register', '') 
                    : '';
                
                fetch(`${baseUrl}/districts/by-region?region_id=${regionId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(function(district) {
                            const option = document.createElement('option');
                            option.value = district.id;
                            option.textContent = district.name;
                            districtSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching districts:', error);
                    });
            }
        });
        const userTypeSelect = document.getElementById('user_type');
        const adminFields = document.getElementById('admin-fields');
        function toggleAdminFields() {
            if (userTypeSelect.value === 'admin') {
                adminFields.style.display = '';
            } else {
                adminFields.style.display = 'none';
            }
        }
        userTypeSelect.addEventListener('change', toggleAdminFields);
        toggleAdminFields(); // Initial call

        // Password visibility toggle
        function setupPasswordToggle(inputId, toggleId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(toggleId);
            toggle.addEventListener('click', function() {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                const icon = toggle.querySelector('i');
                icon.classList.toggle('fa-eye', isPassword);
                icon.classList.toggle('fa-eye-slash', !isPassword);
            });
        }
        setupPasswordToggle('password', 'togglePassword');
        setupPasswordToggle('password_confirmation', 'togglePasswordConfirm');
    });
    </script>
</body>
</html> 