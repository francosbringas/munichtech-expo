@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-header">Search</div>
            <div class="card-body">
                <form method="GET" action="{{ route('search.index') }}" class="row gy-3">
                    <div class="col-md-4">
                        <input type="text" name="query" value="{{ $filters['query'] ?? '' }}" class="form-control" placeholder="Search by name, email or project title">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">Rol</option>
                            @foreach(\App\Models\User::ROLES as $role)
                                <option value="{{ $role }}" @selected(($filters['role'] ?? '') === $role)>{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="company_name" value="{{ $filters['company_name'] ?? '' }}" class="form-control" placeholder="Company">
                    </div>
                    <div class="col-md-2">
                        <select name="project_status" class="form-select">
                            <option value="">Status</option>
                            <option value="planning" @selected(($filters['project_status'] ?? '') === 'planning')>Planning</option>
                            <option value="active" @selected(($filters['project_status'] ?? '') === 'active')>Active</option>
                            <option value="paused" @selected(($filters['project_status'] ?? '') === 'paused')>Paused</option>
                            <option value="completed" @selected(($filters['project_status'] ?? '') === 'completed')>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-12 text-end">
                        <button class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row gy-4">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header">Users</div>
            <div class="card-body">
                @forelse($users as $user)
                    <div class="mb-3 border-bottom pb-3">
                        <h5>{{ $user->name }}</h5>
                        <p class="mb-1">{{ $user->role }} @if($user->company_name) | {{ $user->company_name }} @endif</p>
                        <p class="small text-muted">{{ $user->email }}</p>
                    </div>
                @empty
                    <p class="text-muted">No users found.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header">Projects</div>
            <div class="card-body">
                @forelse($projects as $project)
                    <div class="mb-3 border-bottom pb-3">
                        <h5>{{ $project->title }}</h5>
                        <p class="mb-1">{{ Str::limit($project->description, 120) }}</p>
                        <p class="small text-muted">Owner: {{ $project->owner->name }} | Status: {{ ucfirst($project->status) }}</p>
                    </div>
                @empty
                    <p class="text-muted">No projects found.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
