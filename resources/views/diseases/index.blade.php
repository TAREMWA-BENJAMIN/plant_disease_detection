@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Disease Types</h4>
                </div>
                <div class="card-body">
                    @if($diseases->isEmpty())
                        <p>No disease types found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Disease Name</th>
                                        <th>Description</th>
                                        <th>Suggested Solution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($diseases as $disease)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $disease->disease_name }}</td>
                                            <td>{{ $disease->description }}</td>
                                            <td>{{ $disease->suggested_solution }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 