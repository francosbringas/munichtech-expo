@extends('layouts.app')

@section('title', $project->title)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header card-header-dark d-flex justify-content-between align-items-center">
                <span>{{ $project->title }}</span>
                <span class="badge bg-light text-dark">{{ ucfirst($project->status) }}</span>
            </div>
            <div class="card-body">
                <p>{{ $project->description }}</p>
                @if($project->tags)
                    <div class="mb-3">
                        @foreach($project->getTags() as $tag)
                            <span class="badge bg-secondary me-1">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
                <p class="mb-2"><strong>Owner:</strong> {{ $project->owner->name }}</p>
                <div class="progress mb-0" style="height: 10px;">
                    <div class="progress-bar bg-success" style="width: {{ $project->progress }}%;">{{ $project->progress }}%</div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header">Milestones and Tasks</div>
            <div class="card-body">
                @forelse($project->milestones as $milestone)
                    <div class="mb-4 border-bottom pb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="mb-1">{{ $milestone->title }}</h5>
                            <span class="badge bg-{{ $milestone->status === 'completed' ? 'success' : ($milestone->status === 'in_progress' ? 'primary' : 'secondary') }}">
                                {{ str_replace('_', ' ', ucfirst($milestone->status)) }}
                            </span>
                        </div>
                        <p class="small text-muted mb-1">{{ $milestone->description }}</p>
                        <p class="small mb-2"><strong>Target date:</strong> {{ $milestone->target_date?->format('d/m/Y') ?? 'No date' }}</p>
                        @if($milestone->tasks->isNotEmpty())
                            <ul class="list-group list-group-flush">
                                @foreach($milestone->tasks as $task)
                                    <li class="list-group-item d-flex justify-content-between align-items-start px-0">
                                        <div>
                                            <strong>{{ $task->title }}</strong>
                                            <div class="small text-muted">{{ Str::limit($task->description, 100) }}</div>
                                            @if($task->assignee)
                                                <div class="small">Assigned to: {{ $task->assignee->name }}</div>
                                            @endif
                                        </div>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @empty
                    <div class="alert alert-info mb-0">No milestones have been defined for this project yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header card-header-dark">Team / Collaborators</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                    <div class="avatar-circle">{{ strtoupper(substr($project->owner->name, 0, 1)) }}</div>
                    <div>
                        <strong>{{ $project->owner->name }}</strong>
                        <div class="small text-muted">Owner</div>
                    </div>
                </div>
                @foreach($project->members as $member)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar-circle">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                        <div class="flex-grow-1">
                            <strong>{{ $member->name }}</strong>
                            <div class="small text-muted">{{ $member->company_name ?? $member->role }}</div>
                        </div>
                        <span class="badge bg-primary">{{ ucfirst($member->pivot->role) }}</span>
                    </div>
                @endforeach
                @if($project->members->isEmpty())
                    <p class="text-muted small mb-0">No additional collaborators assigned.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
