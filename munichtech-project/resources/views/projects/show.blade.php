@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header">{{ $project->title }}</div>
            <div class="card-body">
                <p>{{ $project->description }}</p>
                <p><strong>Estado:</strong> {{ ucfirst($project->status) }}</p>
                <p><strong>Empresa:</strong> {{ $project->company_name ?? 'Sin definir' }}</p>
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $project->progress }}%;" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $project->progress }}%</div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">Hitos y tareas</div>
            <div class="card-body">
                @if($project->milestones->isEmpty())
                    <div class="alert alert-info">No hay hitos definidos aún para este proyecto.</div>
                @endif
                @foreach($project->milestones as $milestone)
                    <div class="mb-4 border-bottom pb-3">
                        <h5>{{ $milestone->title }}</h5>
                        <p>{{ $milestone->description }}</p>
                        <p><strong>Fecha límite:</strong> {{ $milestone->due_date?->format('d/m/Y') ?? 'Sin fecha' }}</p>
                        <span class="badge bg-{{ $milestone->completed ? 'success' : 'secondary' }}">{{ $milestone->completed ? 'Completado' : 'Pendiente' }}</span>
                        @if($milestone->tasks->isNotEmpty())
                            <div class="mt-3">
                                <h6>Tareas</h6>
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
