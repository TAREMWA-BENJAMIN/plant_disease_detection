@extends('layouts.app')

@section('content')
<div class="page-content pt-5 mt-4"> <!-- Add small top padding and margin -->
    <!-- Breadcrumb removed -->

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Edit User: {{ $user->name }}</h6>
                    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="text-center mb-4">
                            <img id="preview-image" src="{{ $user->photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $user->photo]) : route('images.show', ['folder' => 'default-avatar', 'filename' => 'default-avatar.png']) }}" alt="{{ $user->first_name }}" class="rounded-circle mb-2" width="100" height="100">
                            <div class="mt-2" style="max-width: 300px; margin: 0 auto;">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*" onchange="previewImage(this)">
                                @error('photo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                                @error('first_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                                @error('last_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                                @error('phone_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="user_type" class="form-label">Role</label>
                                <select class="form-control" id="user_type" name="user_type" required onchange="toggleAdminFields()">
                                    <option value="admin" {{ old('user_type', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ old('user_type', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                                @error('user_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="region_id" class="form-label">Region</label>
                                <select class="form-control" id="region_id" name="region_id" required>
                                    <option value="">Select Region</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ old('region_id', optional($user->district)->region_id) == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="district_id" class="form-label">District</label>
                                <select class="form-control" id="district_id" name="district_id">
                                    <option value="">Select District</option>
                                    @foreach($districts as $district)
                                        @if(old('region_id', optional($user->district)->region_id) == $district->region_id)
                                            <option value="{{ $district->id }}" {{ old('district_id', $user->district_id) == $district->id ? 'selected' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('district_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3 admin-only" style="display:none;">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $user->title) }}">
                            </div>
                            <div class="col-md-4 mb-3 admin-only" style="display:none;">
                                <label for="specialization" class="form-label">Specialization</label>
                                <input type="text" class="form-control" id="specialization" name="specialization" value="{{ old('specialization', $user->specialization) }}">
                            </div>
                        </div>
                        <div class="row admin-only" style="display:none;">
                            <div class="col-md-4 mb-3">
                                <label for="organization" class="form-label">Organization/Institution</label>
                                <input type="text" class="form-control" id="organization" name="organization" value="{{ old('organization', $user->organization) }}">
                            </div>
                        </div>
                       
                        <button type="submit" class="btn btn-primary me-2">Update User</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');
    regionSelect.addEventListener('change', function() {
        const regionId = this.value;
        districtSelect.innerHTML = '<option value="">Select District</option>';
        if (regionId) {
            // Get the current base URL dynamically
            const baseUrl = window.location.pathname.includes('/users/') 
                ? window.location.pathname.replace(/\/users\/\d+\/edit/, '') 
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
    const roleSelect = document.getElementById('user_type');
    const adminFields = document.querySelectorAll('.admin-only');
    function toggleAdminFields() {
        var role = document.getElementById('user_type').value;
        adminFields.forEach(function(field) {
            field.style.display = (role === 'admin') ? '' : 'none';
        });
    }
    roleSelect.addEventListener('change', toggleAdminFields);
    toggleAdminFields(); // Initial call
});

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush 