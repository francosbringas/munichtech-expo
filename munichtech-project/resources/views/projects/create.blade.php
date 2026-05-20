@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">Crear proyecto</div>
            <div class="card-body">
                <form method="POST" action="{{ route('projects.store') }}">
                    @csrf
                    @if(isset($collaboration))
                        <input type="hidden" name="collaboration_request_id" value="{{ $collaboration->id }}">
                        <div class="alert alert-info">
                            Creando proyecto a partir de la colaboración con <strong>{{ $collaboration->sender->name ?? $collaboration->receiver->name }}</strong>.
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Título del proyecto</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" rows="5" class="form-control" required>{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Empresa asociada</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-control">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Progreso (%)</label>
                            <input type="number" name="progress" value="{{ old('progress', 0) }}" min="0" max="100" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="status" class="form-select" required>
                                <option value="planning" @selected(old('status') === 'planning')>Planificación</option>
                                <option value="active" @selected(old('status') === 'active')>Activa</option>
                                <option value="paused" @selected(old('status') === 'paused')>Pausada</option>
                                <option value="completed" @selected(old('status') === 'completed')>Completada</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6>Hito inicial (opcional)</h6>
                        <div class="mb-3">
                            <label class="form-label">Título del hito</label>
                            <input type="text" name="milestone_title" value="{{ old('milestone_title') }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción del hito</label>
                            <textarea name="milestone_description" rows="3" class="form-control">{{ old('milestone_description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de entrega</label>
                            <input type="date" name="milestone_due_date" value="{{ old('milestone_due_date') }}" class="form-control">
                        </div>
                    </div>
                    <button class="btn btn-primary">Guardar proyecto</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
