@extends('layouts.app')

@section('title', 'Create Project')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header card-header-dark">Create Project</div>
            <div class="card-body">
                @if(isset($collaboration) && $partner)
                    <div class="alert alert-success">
                        <i class="bi bi-handshake me-2"></i>
                        Creating project from accepted collaboration with <strong>{{ $partner->name }}</strong> ({{ $partner->role }}).
                    </div>
                @endif
                <form method="POST" action="{{ route('projects.store') }}">
                    @csrf
                    @if(isset($collaboration))
                        <input type="hidden" name="collaboration_request_id" value="{{ $collaboration->id }}">
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Project Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags (comma-separated)</label>
                        <input type="text" name="tags" value="{{ old('tags') }}" class="form-control" placeholder="AI, IoT, Security">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Initial status</label>
                        <select name="status" class="form-select" required>
                            <option value="planning" @selected(old('status') === 'planning')>Planning</option>
                            <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                            <option value="paused" @selected(old('status') === 'paused')>Paused</option>
                            <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                        </select>
                    </div>
                    <button class="btn btn-primary w-100">Save Project</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
