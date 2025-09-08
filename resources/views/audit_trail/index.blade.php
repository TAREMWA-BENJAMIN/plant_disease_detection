@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Audit Trail</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>TimeStamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Platform & IP</th>
                    <th>User Agent</th>
                    
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td style="white-space: nowrap;">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $log->user ? ($log->user->first_name . ' ' . $log->user->last_name) : 'Guest' }}</td>
                        <td>{{ ucfirst($log->action) }}</td>
                        <td>{{ $log->description }} , ID: {{ $log->model_id }}</td>
                        <td>{{ $log->platform }}, {{ $log->ip_address }}</td>
                        <td title="{{ $log->user_agent }}">{{ \Illuminate\Support\Str::limit($log->user_agent, 20) }}</td>
                        
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">No audit logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection 