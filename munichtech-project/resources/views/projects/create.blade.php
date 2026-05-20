@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">Create Project</div>
            <div class="card-body">
                <form method="POST" action="{{ route('projects.store') }}">
                    @csrf
                    @if(isset($collaboration))
                        <input type="hidden" name="collaboration_request_id" value="{{ $collaboration->id }}">
                        <div class="alert alert-info">
                            Creating project from collaboration with <strong>{{ $collaboration->sender->name ?? $collaboration->receiver->name }}</strong>.
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Project Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="5" class="form-control" required>{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Associated Company</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-control">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Progress (%)</label>
                            <input type="number" name="progress" value="{{ old('progress', 0) }}" min="0" max="100" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="planning" @selected(old('status') === 'planning')>Planning</option>
                                <option value="active" @selected(old('status') === 'active')>Active</option>
                                <option value="paused" @selected(old('status') === 'paused')>Paused</option>
                                <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6>Initial Milestone (optional)</h6>
                        <div class="mb-3">
                            <label class="form-label">Milestone Title</label>
                            <input type="text" name="milestone_title" value="{{ old('milestone_title') }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Milestone Description</label>
                            <textarea name="milestone_description" rows="3" class="form-control">{{ old('milestone_description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="milestone_due_date" value="{{ old('milestone_due_date') }}" class="form-control">
                        </div>
                    </div>
                    <button class="btn btn-primary">Save Project</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
