@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Projects</h2>
        <p class="text-muted">Management of your initiatives and real-time progress.</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn btn-success">Create New Project</a>
</div>
<div class="row gy-4">
    @forelse($projects as $project)
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $project->title }}</h5>
                    <p class="mb-2">{{ Str::limit($project->description, 140) }}</p>
                    <p class="mb-2"><strong>Status:</strong> {{ ucfirst($project->status) }}</p>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $project->progress }}%;" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">You don't have any projects yet. Create one from an accepted collaboration.</div>
        </div>
    @endforelse
</div>
@endsection
