@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h2 class="mb-4">Edit Profile</h2>
                    <a href="{{ route('settings.show') }}" class="btn btn-link mb-3 p-0"><i class="fas fa-arrow-left me-1"></i> Back to Profile</a>
                    <form id="profileForm" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="text-center mb-4">
                            <img id="preview-image" src="{{ $user->photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $user->photo]) : route('images.show', ['folder' => 'default-avatar', 'filename' => 'default-avatar.png']) }}" alt="{{ $user->first_name }}" class="rounded-circle mb-2" width="100" height="100">
                            <div class="mt-2">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="photo" id="photo" accept="image/*" class="form-control" onchange="previewImage(this)">
                                @error('photo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-control">
                                @error('first_name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control">
                                @error('last_name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control">
                            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="form-control">
                            @error('phone_number') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="region_id" class="form-label">Region</label>
                                <select name="region_id" id="region_id" class="form-select" required>
                                    <option value="">Select Region</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ old('region_id', optional($user->district)->region_id) == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="district_id" class="form-label">District</label>
                                <select name="district_id" id="district_id" class="form-select">
                                    <option value="">Select District</option>
                                    @foreach($districts as $district)
                                        @if(old('region_id', optional($user->district)->region_id) == $district->region_id)
                                            <option value="{{ $district->id }}" {{ old('district_id', $user->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('district_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                                <input type="password" name="password" id="password" class="form-control">
                                @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');
    regionSelect.addEventListener('change', function() {
        const regionId = this.value;
        districtSelect.innerHTML = '<option value="">Select District</option>';
        if (regionId) {
            // Get the current base URL dynamically
            const baseUrl = window.location.pathname.includes('/profile/edit') 
                ? window.location.pathname.replace('/profile/edit', '') 
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
});
</script>
@endpush
@endsection 