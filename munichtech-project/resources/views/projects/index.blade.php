@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Proyectos</h2>
        <p class="text-muted">Gestión de tus iniciativas y progreso en tiempo real.</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn btn-success">Crear nuevo proyecto</a>
</div>
<div class="row gy-4">
    @forelse($projects as $project)
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $project->title }}</h5>
                    <p class="mb-2">{{ Str::limit($project->description, 140) }}</p>
                    <p class="mb-2"><strong>Estado:</strong> {{ ucfirst($project->status) }}</p>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $project->progress }}%;" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-primary btn-sm">Ver detalles</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">Aún no tienes proyectos. Crea uno a partir de una colaboración aceptada.</div>
        </div>
    @endforelse
</div>
@endsection
