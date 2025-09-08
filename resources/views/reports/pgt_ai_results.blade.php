@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">AI Scan Results Report</h5>
                    <div class="d-flex">
                        <a href="{{ route('reports.pgt-ai-results.pdf') }}" class="btn btn-danger btn-sm me-2">
                            <i class="fas fa-file-pdf me-1"></i> Export to PDF
                        </a>
                        <a href="{{ route('reports.pgt-ai-results.excel') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel me-1"></i> Export to Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>USER</th>
                                    <th>PLANT NAME</th>
                                    <th>PLANT IMAGE</th>
                                    <th>DISEASE NAME</th>
                                    <th>DISEASE DETAILS</th>
                                    <th>SUGGESTED SOLUTION</th>
                                    <th>PREVENTION TIP</th>
                                    <th>DETECTED ON</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pgtAiResults as $result)
                                <tr>
                                    <td>{{ $result->id }}</td>
                                    <td>{{ $result->user->name ?? 'N/A' }}</td>
                                    <td>{{ $result->plant_name }}</td>
                                    <td>
                                        @if($result->plant_image)
                                            <img src="{{ route('images.show', ['folder' => 'plant_images', 'filename' => $result->plant_image]) }}" width="60" />
                                        @endif
                                    </td>
                                    <td>{{ $result->disease_name }}</td>
                                    <td>{{ $result->disease_details }}</td>
                                    <td>{{ $result->suggested_solution }}</td>
                                    <td>{{ $result->prevention_tips }}</td>
                                    <td>{{ $result->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No AI scan results found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 