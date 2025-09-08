@extends('layouts.app')

@section('title', 'Generated PGT-AI Reports')

@push('styles')
    <link rel="stylesheet" href="{{ asset('files/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}">
@endpush

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">PGT-AI Reports</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">LIST OF ALL PLANT SCAN RESULTS FROM THE GPT-AI SYSTEM</h6>
                <a href="{{ route('reports.pgt-ai-results.pdf') }}" class="btn btn-danger btn-sm" target="_blank">
                    <i class="fas fa-file-pdf me-1"></i> Print PDF
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Plant Name</th>
                                <th>Plant Image</th>
                                <th>Disease Name</th>
                                <th>Disease Details</th>
                                <th>Suggested Solution</th>
                                <th>Prevention Tip</th>
                                <th>Detected On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $result)
                                <tr>
                                    <td>{{ $result->id }}</td>
                                    <td>{{ $result->user->name ?? 'N/A' }}</td>
                                    <td>{{ $result->plant_name }}</td>
                                    <td>
                                        <img src="{{ route('images.show', ['folder' => 'plant_images', 'filename' => $result->plant_image]) }}" alt="{{ $result->plant_name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    </td>
                                    <td>{{ $result->disease_name ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($result->disease_details ?? 'N/A', 50) }}</td>
                                    <td>{{ Str::limit($result->suggested_solution ?? 'N/A', 50) }}</td>
                                    <td>{{ Str::limit($result->prevention_tips ?? 'N/A', 50) }}</td>
                                    <td>{{ $result->created_at->format('d M, Y H:i A') }}</td>
                                    <td>
                                        <a href="{{ route('pgt-ai.results.show', $result->id) }}" class="btn btn-info btn-sm">View</a>
                                        <form action="{{ route('pgt-ai.results.destroy', $result->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this result?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $results->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('files/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('files/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('files/js/data-table.js') }}"></script>
@endpush 