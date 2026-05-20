@extends('layouts.app')

@section('content')
<div class="row gy-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-start border-4 border-primary">
            <div class="card-body">
                <h5>Total users</h5>
                <p class="display-6 mb-0">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-start border-4 border-success">
            <div class="card-body">
                <h5>Projects</h5>
                <p class="display-6 mb-0">{{ $totalProjects }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-start border-4 border-warning">
            <div class="card-body">
                <h5>Active Collaborations</h5>
                <p class="display-6 mb-0">{{ $activeCollaborations }}</p>
            </div>
        </div>
    </div>
</div>
<div class="row gy-4 mt-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">System Health / Audit Logs</div>
            <div class="card-body">
                <p class="text-muted">Basic monitoring of security events, accesses and system integrity.</p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>IP</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLogs as $log)
                                <tr>
                                    <td>{{ $log->user?->name ?? 'Sistema' }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header">Environment Status</div>
            <div class="card-body">
                <p class="mb-2">Database: SQLite</p>
                <p class="mb-2">Framework: Laravel 11</p>
                <p class="mb-2">Security: CSRF active and input validation</p>
                <p class="mb-2">Protection: Secure Eloquent queries</p>
            </div>
        </div>
    </div>
</div>
@endsection
