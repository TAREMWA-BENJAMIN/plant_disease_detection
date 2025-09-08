@extends('layouts.app')

@section('content')
<div class="page-content">

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Agricultural Experts List</h6>
                    <div class="table-responsive">
                        <table id="expertsDataTable" class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Photo</th>
                                    <th>District</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($experts as $expert)
                                <tr>
                                    <td>{{ $expert->id }}</td>
                                    <td>{{ $expert->name }}</td>
                                    <td>{{ $expert->email }}</td>
                                    <td>{{ $expert->phone_number }}</td>
                                    <td>
                                        <img src="{{ $expert->photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $expert->photo]) : route('images.show', ['folder' => 'default-avatar', 'filename' => 'default-avatar.png']) }}" alt="{{ $expert->name }}" class="rounded-circle" width="40" height="40">
                                    </td>
                                    <td>{{ $expert->district->name ?? 'N/A' }}</td>
                                    <td>{{ $expert->created_at ? $expert->created_at->format('Y-m-d') : '' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm btn-view-expert" data-id="{{ $expert->id }}">View</button>
                                        <a href="{{ route('users.edit', $expert->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('users.destroy', $expert->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this expert?')">Delete</button>
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

<!-- Expert Details Modal -->
<div class="modal fade" id="expertDetailsModal" tabindex="-1" aria-labelledby="expertDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="expertDetailsModalLabel">Expert Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="expert-details-content">
        <!-- Details will be loaded here -->
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
        function initExpertsDataTable() {
            if (typeof $.fn.DataTable !== 'undefined') {
                try {
                    // Destroy existing DataTable if it exists
                    if ($.fn.DataTable.isDataTable('#expertsDataTable')) {
                        $('#expertsDataTable').DataTable().destroy();
                    }
                    
                    $('#expertsDataTable').DataTable({
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
                        $('#expertsDataTable_filter input').attr('placeholder', 'Search experts...');
                        $('#expertsDataTable_filter input').removeClass('form-control-sm');
                        $('#expertsDataTable_length select').removeClass('form-control-sm');
                    }, 100);
                    
                    console.log('Experts DataTable initialized successfully');
                } catch (error) {
                    console.error('Error initializing experts DataTable:', error);
                }
            } else {
                console.log('DataTables not available, retrying in 200ms...');
                setTimeout(initExpertsDataTable, 200);
            }
        }
        
        // Initialize when ready
        initExpertsDataTable();

        // View Expert Details
        $('.btn-view-expert').on('click', function() {
            var expertId = $(this).data('id');
            $.get('{{ url("/users") }}/' + expertId, function(user) {
                var html = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center mb-3">
                                <img src="${user.photo_url}" alt="${user.name}" width="100%" height="250" onerror="this.onerror=null;this.src='{{ asset("images/default-avatar/default-avatar.png") }}';">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Name:</strong> ${user.name}</p>
                            <p><strong>Email:</strong> ${user.email}</p>
                            <p><strong>Phone Number:</strong> ${user.phone_number}</p>
                            <p><strong>Role:</strong> ${user.role}</p>
                            <p><strong>District:</strong> ${user.district && user.district.name ? user.district.name : 'Not specified'}</p>
                            <p><strong>Region:</strong> ${user.region ? user.region : 'Not specified'}</p>
                        </div>
                    </div>
                `;
                $('#expert-details-content').html(html);
                $('#expertDetailsModal').modal('show');
            });
        });
    });
</script>
@endpush 