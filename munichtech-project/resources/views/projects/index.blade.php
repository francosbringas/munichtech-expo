@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Projects Dashboard</h2>
        <p class="text-muted mb-0">Manage your initiatives and track progress in real time.</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i>New Project
    </a>
</div>

@if($matchmakingSuggestions->isNotEmpty())
<section class="mb-5">
    <div class="d-flex align-items-center gap-2 mb-3">
        <h4 class="mb-0"><i class="bi bi-stars text-warning"></i> AI-Recommended Connections</h4>
        <span class="badge bg-warning text-dark">Matchmaking</span>
    </div>
    <div id="matchmakingCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($matchmakingSuggestions->chunk(3) as $chunkIndex => $chunk)
                <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                    <div class="row g-3">
                        @foreach($chunk as $suggestion)
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            <div class="avatar-circle">{{ strtoupper(substr($suggestion->name, 0, 1)) }}</div>
                                            <div>
                                                <h6 class="mb-0">{{ $suggestion->name }}</h6>
                                                <small class="text-muted">{{ $suggestion->role }}</small>
                                            </div>
                                        </div>
                                        <p class="small text-muted mb-2">{{ $suggestion->company_name ?? '—' }}</p>
                                        @if($suggestion->interests)
                                            <div class="mb-3">
                                                @foreach(array_slice($suggestion->getInterestsArray(), 0, 3) as $interest)
                                                    <span class="badge bg-light text-dark border me-1">{{ $interest }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        <a href="{{ route('collaborations.create', ['receiver_id' => $suggestion->id]) }}" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="bi bi-person-plus me-1"></i>Connect
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        @if($matchmakingSuggestions->count() > 3)
            <button class="carousel-control-prev" type="button" data-bs-target="#matchmakingCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon rounded"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#matchmakingCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon rounded"></span>
            </button>
        @endif
    </div>
</section>
@endif

<div class="row gy-4">
    @forelse($projects as $project)
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">{{ $project->title }}</h5>
                        <span class="badge bg-{{ $project->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($project->status) }}</span>
                    </div>
                    <p class="mb-2 small">{{ Str::limit($project->description, 140) }}</p>
                    <p class="small text-muted mb-2">Owner: {{ $project->owner->name }}</p>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ $project->progress }}%;"></div>
                    </div>
                    <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">You do not have any projects yet. Create one from an accepted collaboration or directly.</div>
        </div>
    @endforelse
</div>

@if($projects->hasPages())
    <div class="mt-4">{{ $projects->links() }}</div>
@endif
@endsection
