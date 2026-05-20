@extends('layouts.app')

@section('title', 'Search')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header card-header-dark">Global Search</div>
    <div class="card-body">
        <form method="GET" action="{{ route('search.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Name, email, company, tags...">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="all" @selected($filters['type'] === 'all')>All</option>
                    <option value="users" @selected($filters['type'] === 'users')>Users</option>
                    <option value="projects" @selected($filters['type'] === 'projects')>Projects</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">Role</option>
                    @foreach(\App\Models\User::ROLES as $role)
                        <option value="{{ $role }}" @selected($filters['role'] === $role)>{{ $role }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Search</button>
            </div>
        </form>
    </div>
</div>

<div class="row gy-4">
    @if($filters['type'] === 'all' || $filters['type'] === 'users')
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">Users</div>
                <div class="card-body">
                    @forelse($users as $user)
                        <div class="mb-3 border-bottom pb-3 d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $user->name }}</h6>
                                <p class="mb-1 small">{{ $user->role }} @if($user->company_name)| {{ $user->company_name }}@endif</p>
                                <p class="small text-muted mb-0">{{ $user->email }}</p>
                                @if($user->interests)
                                    <p class="small mt-1"><em>{{ $user->interests }}</em></p>
                                @endif
                            </div>
                            <a href="{{ route('collaborations.create', ['receiver_id' => $user->id]) }}" class="btn btn-sm btn-outline-primary">Connect</a>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No users found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
    @if($filters['type'] === 'all' || $filters['type'] === 'projects')
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">Projects</div>
                <div class="card-body">
                    @forelse($projects as $project)
                        <div class="mb-3 border-bottom pb-3">
                            <h6>{{ $project->title }}</h6>
                            <p class="small mb-1">{{ Str::limit($project->description, 120) }}</p>
                            <p class="small text-muted mb-0">Owner: {{ $project->owner->name }} | {{ ucfirst($project->status) }}</p>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No projects found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
