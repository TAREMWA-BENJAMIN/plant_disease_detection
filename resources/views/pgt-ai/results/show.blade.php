@extends('layouts.app')

@section('title', 'AI Scan Result Details')

@section('content')
<div class="container-fluid">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('reports.generate') }}">PGT-AI Reports</a></li>
            <li class="breadcrumb-item active" aria-current="page">Result #{{ $result->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Scan Result Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($result->plant_image)
                                <img src="{{ route('images.show', ['folder' => 'plant_images', 'filename' => $result->plant_image]) }}" alt="Plant Image" class="img-fluid rounded" style="max-height: 300px;">
                            @else
                                <p class="text-muted">No Image Available</p>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <dl class="row">
                                <dt class="col-sm-4">Result ID:</dt>
                                <dd class="col-sm-8">{{ $result->id }}</dd>

                                <dt class="col-sm-4">Detected On:</dt>
                                <dd class="col-sm-8">{{ $result->created_at->format('F d, Y \a\t h:i A') }}</dd>

                                <dt class="col-sm-4">Scanned by:</dt>
                                <dd class="col-sm-8">{{ $result->user->name ?? 'N/A' }}</dd>

                                <hr class="my-3">

                                <dt class="col-sm-4">Plant Name:</dt>
                                <dd class="col-sm-8">{{ $result->plant_name }}</dd>

                                <dt class="col-sm-4">Disease Name:</dt>
                                <dd class="col-sm-8">{{ $result->disease_name ?? 'N/A' }}</dd>

                                <dt class="col-sm-4">Disease Details:</dt>
                                <dd class="col-sm-8">{{ $result->disease_details ?? 'N/A' }}</dd>

                                <dt class="col-sm-4">Suggested Solution:</dt>
                                <dd class="col-sm-8">{{ $result->suggested_solution ?? 'N/A' }}</dd>
                                
                                <dt class="col-sm-4">Prevention Tips:</dt>
                                <dd class="col-sm-8">{{ $result->prevention_tips ?? 'N/A' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('reports.generate') }}" class="btn btn-secondary">Back to Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 