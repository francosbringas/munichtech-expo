@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header">{{ $project->title }}</div>
            <div class="card-body">
                <p>{{ $project->description }}</p>
                <p><strong>Status:</strong> {{ ucfirst($project->status) }}</p>
                <p><strong>Company:</strong> {{ $project->company_name ?? 'Not defined' }}</p>
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $project->progress }}%;" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $project->progress }}%</div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">Milestones and Tasks</div>
            <div class="card-body">
                @if($project->milestones->isEmpty())
                    <div class="alert alert-info">There are no milestones defined yet for this project.</div>
                @endif
                @foreach($project->milestones as $milestone)
                    <div class="mb-4 border-bottom pb-3">
                        <h5>{{ $milestone->title }}</h5>
                        <p>{{ $milestone->description }}</p>
                        <p><strong>Due Date:</strong> {{ $milestone->due_date?->format('d/m/Y') ?? 'No date' }}</p>
                        <span class="badge bg-{{ $milestone->completed ? 'success' : 'secondary' }}">{{ $milestone->completed ? 'Completed' : 'Pending' }}</span>
                        @if($milestone->tasks->isNotEmpty())
                            <div class="mt-3">
                                <h6>Tasks</h6>
                                <ul class="list-group">
                                    @foreach($milestone->tasks as $task)
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>{{ $task->title }}</strong>
                                                <div class="small text-muted">{{ Str::limit($task->description, 120) }}</div>
                                            </div>
                                            <span class="badge bg-{{ $task->completed ? 'success' : 'warning' }}">{{ $task->completed ? 'Completada' : 'Activa' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
