@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="py-3 px-4 mb-3" style="background: linear-gradient(90deg, #4e73df 0%, #3757c6 100%); color: #fff; border-radius: 6px;">
                <h3 class="mb-0">Adding a New User</h3>
            </div>
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white">
                    
                </div>
                <div class="card-body mb-5">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">User Type</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="user" selected>User</option>
                                    <option value="expert">Expert</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="district_id" class="form-label">District</label>
                                <select class="form-select" id="district_id" name="district_id">
                                    <option value="">Select District</option>
                                    @foreach($districts as $district)
                                        @if(old('region_id') == $district->region_id)
                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="region_id" class="form-label">Region</label>
                                <select class="form-select" id="region_id" name="region_id" required>
                                    <option value="">Select Region</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            </div>
                        </div>
                        <div id="admin-fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="specialization" class="form-label">Specialization</label>
                                    <input type="text" class="form-control" id="specialization" name="specialization">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="organization" class="form-label">Organization/Institution</label>
                                    <input type="text" class="form-control" id="organization" name="organization">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add User</button>
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
                const baseUrl = window.location.pathname.includes('/users/create') 
                    ? window.location.pathname.replace('/users/create', '') 
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
        const roleSelect = document.getElementById('role');
        const adminFields = document.getElementById('admin-fields');
        function toggleAdminFields() {
            if (roleSelect.value === 'admin') {
                adminFields.style.display = '';
            } else {
                adminFields.style.display = 'none';
            }
        }
        roleSelect.addEventListener('change', toggleAdminFields);
        toggleAdminFields(); // Initial call
    });
</script>
@endpush 