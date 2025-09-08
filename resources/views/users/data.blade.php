@extends('layouts.app')

@section('content')
<div class="page-content">

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">User List</h6>
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i> Add User
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table id="usersDataTable" class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Photo</th>
                                    <th>Role</th>
                                    <th>District</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone_number }}</td>
                                    <td>
                                        @php
                                            $photo = $user->photo;
                                            $imgSrc = $photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $photo]) : route('images.show', ['folder' => 'default-avatar', 'filename' => 'default-avatar.png']);
                                        @endphp
                                        <img src="{{ $imgSrc }}" alt="{{ $user->first_name ?? $user->name }}" class="rounded-circle" width="40" height="40">
                                    </td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>{{ $user->district->name ?? 'Not specified' }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm view-user-btn" data-bs-toggle="modal" 
                                        data-bs-target="#viewUserModal" data-user-id="{{ $user->id }}">View</button>

                                        <a href="{{ route('users.edit', $user->id) }}" 
                                            class="btn btn-primary btn-sm">Edit</a>

                                        <form action="{{ route('users.destroy', $user->id) }}" 
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="userDetailsContent">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(function() {
        'use strict';
        // Initialize DataTables with proper error handling
        function initUsersDataTable() {
            if (typeof $.fn.DataTable !== 'undefined') {
                try {
                    // Destroy existing DataTable if it exists
                    if ($.fn.DataTable.isDataTable('#usersDataTable')) {
                        $('#usersDataTable').DataTable().destroy();
                    }
                    
                    $('#usersDataTable').DataTable({
                        "lengthMenu": [
                            [10, 30, 50, -1],
                            [10, 30, 50, "All"]
                        ],
                        "pageLength": 10,
                        "language": {
                            search: ""
                        },
                        "responsive": true,
                        "autoWidth": false
                    });
                    
                    // Customize search input
                    setTimeout(function() {
                        $('#usersDataTable_filter input').attr('placeholder', 'Search users...');
                        $('#usersDataTable_filter input').removeClass('form-control-sm');
                        $('#usersDataTable_length select').removeClass('form-control-sm');
                    }, 100);
                    
                    console.log('Users DataTable initialized successfully');
                } catch (error) {
                    console.error('Error initializing users DataTable:', error);
                }
            } else {
                console.log('DataTables not available, retrying in 200ms...');
                setTimeout(initUsersDataTable, 200);
            }
        }
        
        // Initialize when ready
        initUsersDataTable();

        $('#viewUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var userId = button.data('user-id'); // Extract info from data-* attributes

            var modal = $(this);
            modal.find('.modal-title').text('User Details');
            modal.find('#userDetailsContent').html('<p>Loading user details...</p>');

            $.ajax({
                url: '{{ url("/users") }}/' + userId,
                method: 'GET',
                success: function(data) {
                    var user = data;
                    var imgSrc = user.photo_url;
                    var detailsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center mb-3">
                                <img src="${imgSrc}" alt="${user.name}" class="" width="100%" height="400" onerror="this.onerror=null;this.src='{{ asset("images/default-avatar/default-avatar.png") }}';">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Name:</strong> ${user.name}</p>
                            <p><strong>Email:</strong> ${user.email}</p>
                            <p><strong>Phone Number:</strong> ${user.phone_number}</p>
                            <p><strong>Role:</strong> ${user.role}</p>
                            <p><strong>District:</strong> ${user.district && user.district.name ? user.district.name : 'Not specified'}</p>
                            <p><strong>Region:</strong> ${user.region ? user.region : 'Not specified'}</p>
                            <p><strong>Created At:</strong> ${user.created_at}</p>
                            ${user.role === 'admin' ? `<p><strong>Title:</strong> ${user.title || ''}</p><p><strong>Specialization:</strong> ${user.specialization || ''}</p><p><strong>Organization:</strong> ${user.organization || ''}</p>` : ''}
                        </div>
                    </div>
                    `;
                    modal.find('#userDetailsContent').html(detailsHtml);
                },
                error: function(error) {
                    let msg = 'Error loading user details.';
                    if (error.status === 401 || error.status === 403) {
                        msg = 'You are not authorized. Please log in again.';
                    } else if (error.responseText && error.responseText.indexOf('<!DOCTYPE html>') !== -1) {
                        msg = 'Session expired or not authorized. Please log in.';
                    }
                    modal.find('#userDetailsContent').html('<p>' + msg + '</p>');
                    console.error('Error fetching user details:', error);
                }
            });
        });
    });
</script>
@endpush