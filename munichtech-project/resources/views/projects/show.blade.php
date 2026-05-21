@extends('layouts.app')

@section('title', $project->title)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header card-header-dark d-flex justify-content-between align-items-center">
                <span>{{ $project->title }}</span>
                <span class="badge bg-light">{{ ucfirst($project->status) }}</span>
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
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span class="fw-semibold">Milestones and Tasks</span>
                @if($canManage)
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addMilestoneModal">
                            <i class="bi bi-flag me-1"></i>Add Milestone
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            <i class="bi bi-plus-lg me-1"></i>Add Task
                        </button>
                    </div>
                @endif
            </div>
            <div class="card-body">
                @forelse($project->milestones as $milestone)
                    <div class="mb-4 border-bottom pb-3">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <h5 class="mb-1">{{ $milestone->title }}</h5>
                            <div class="d-flex align-items-center gap-2">
                                @if($canManage)
                                    <form method="POST" action="{{ route('milestones.updateStatus', $milestone) }}" class="status-auto-submit mb-0">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm status-select-milestone @if($milestone->status == 'completed') bg-success text-white @elseif($milestone->status == 'in_progress') bg-primary text-white @else bg-light text-secondary @endif" aria-label="Update milestone status">
                                            <option value="pending" @selected($milestone->status === 'pending')>⏳ Pending</option>
                                            <option value="in_progress" @selected($milestone->status === 'in_progress')>⚡ In progress</option>
                                            <option value="completed" @selected($milestone->status === 'completed')>✅ Completed</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="badge bg-{{ $milestone->status === 'completed' ? 'success' : ($milestone->status === 'in_progress' ? 'primary' : 'secondary') }}">
                                        {{ str_replace('_', ' ', ucfirst($milestone->status)) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($milestone->description)
                            <p class="small text-muted mb-1">{{ $milestone->description }}</p>
                        @endif
                        <p class="small mb-2">
                            <strong>Target date:</strong> {{ $milestone->target_date?->format('M j, Y') ?? 'Not set' }}
                        </p>
                        @if($milestone->tasks->isNotEmpty())
                            <ul class="list-group list-group-flush">
                                @foreach($milestone->tasks as $task)
                                    <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-2 px-0">
                                        <div class="flex-grow-1">
                                            <strong>{{ $task->title }}</strong>
                                            @if($task->description)
                                                <div class="small text-muted">{{ Str::limit($task->description, 100) }}</div>
                                            @endif
                                            @if($task->assignee)
                                                <div class="small text-muted">
                                                    <i class="bi bi-person me-1"></i>{{ $task->assignee->name }}
                                                </div>
                                            @endif
                                            <div class="small mt-1">
                                                <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning text-dark' : 'light text-dark border') }}">
                                                    {{ ucfirst($task->priority) }} priority
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($canManage)
                                                <form method="POST" action="{{ route('tasks.updateStatus', $task) }}" class="status-auto-submit mb-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm status-select-task @if($task->status == 'done') bg-success text-white @elseif($task->status == 'review') bg-warning text-dark @elseif($task->status == 'in_progress') bg-primary text-white @else bg-light text-secondary @endif" aria-label="Update task status">
                                                        <option value="todo" @selected($task->status === 'todo')>⏳ To do</option>
                                                        <option value="in_progress" @selected($task->status === 'in_progress')>⚡ In progress</option>
                                                        <option value="review" @selected($task->status === 'review')>👀 Review</option>
                                                        <option value="done" @selected($task->status === 'done')>✅ Done</option>
                                                    </select>
                                                </form>
                                                @if($task->status !== 'done')
                                                    <form method="POST" action="{{ route('tasks.updateStatus', $task) }}" class="status-quick-complete mb-0">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="done">
                                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Mark as done">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-success" title="Completed"><i class="bi bi-check-circle-fill"></i></span>
                                                @endif
                                            @else
                                                <span class="badge bg-{{ $task->status === 'done' ? 'success' : ($task->status === 'in_progress' ? 'primary' : ($task->status === 'review' ? 'warning text-dark' : 'secondary')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="small text-muted mb-0">No tasks in this milestone yet.</p>
                        @endif
                        @if($canManage)
                            <button type="button"
                                class="btn btn-sm btn-link px-0 mt-2"
                                data-bs-toggle="modal"
                                data-bs-target="#addTaskModal"
                                data-milestone-id="{{ $milestone->id }}">
                                <i class="bi bi-plus-circle me-1"></i>Add task to this milestone
                            </button>
                        @endif
                    </div>
                @empty
                    @if($standaloneTasks->isEmpty())
                        <div class="text-center py-4 py-lg-5">
                            <div class="display-6 text-muted mb-3">
                                <i class="bi bi-kanban"></i>
                            </div>
                            <h5 class="text-muted">No milestones or tasks yet</h5>
                            <p class="text-muted small mb-4 mx-auto" style="max-width: 420px;">
                                Start building your project workflow by adding the first milestone or task. Your team will see progress here as you go.
                            </p>
                            @if($canManage)
                                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addMilestoneModal">
                                        <i class="bi bi-plus-lg me-2"></i>Add First Milestone / Task
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                        <i class="bi bi-check2-square me-2"></i>Add Standalone Task
                                    </button>
                                </div>
                            @else
                                <p class="text-muted small mb-0">Ask the project owner to add milestones and tasks.</p>
                            @endif
                        </div>
                    @endif
                @endforelse

                @if($standaloneTasks->isNotEmpty())
                    <div class="{{ $project->milestones->isNotEmpty() ? 'pt-3 border-top' : '' }}">
                        <h6 class="text-muted text-uppercase small mb-3">Standalone Tasks</h6>
                        <ul class="list-group list-group-flush">
                            @foreach($standaloneTasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-2 px-0">
                                    <div class="flex-grow-1">
                                        <strong>{{ $task->title }}</strong>
                                        @if($task->description)
                                            <div class="small text-muted">{{ Str::limit($task->description, 100) }}</div>
                                        @endif
                                        @if($task->assignee)
                                            <div class="small text-muted">
                                                <i class="bi bi-person me-1"></i>{{ $task->assignee->name }}
                                            </div>
                                        @endif
                                        <div class="small mt-1">
                                            <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning text-dark' : 'light text-dark border') }}">
                                                {{ ucfirst($task->priority) }} priority
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($canManage)
                                            <form method="POST" action="{{ route('tasks.updateStatus', $task) }}" class="status-auto-submit mb-0">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm status-select-task @if($task->status == 'done') bg-success text-white @elseif($task->status == 'review') bg-warning text-dark @elseif($task->status == 'in_progress') bg-primary text-white @else bg-light text-secondary @endif" aria-label="Update task status">
                                                    <option value="todo" @selected($task->status === 'todo')>⏳ To do</option>
                                                    <option value="in_progress" @selected($task->status === 'in_progress')>⚡ In progress</option>
                                                    <option value="review" @selected($task->status === 'review')>👀 Review</option>
                                                    <option value="done" @selected($task->status === 'done')>✅ Done</option>
                                                </select>
                                            </form>
                                            @if($task->status !== 'done')
                                                <form method="POST" action="{{ route('tasks.updateStatus', $task) }}" class="status-quick-complete mb-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="done">
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Mark as done">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-success" title="Completed"><i class="bi bi-check-circle-fill"></i></span>
                                            @endif
                                        @else
                                            <span class="badge bg-{{ $task->status === 'done' ? 'success' : ($task->status === 'in_progress' ? 'primary' : ($task->status === 'review' ? 'warning text-dark' : 'secondary')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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

@if($canManage)
    <div class="modal fade" id="addMilestoneModal" tabindex="-1" aria-labelledby="addMilestoneModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('projects.milestones.store', $project) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMilestoneModalLabel">
                            <i class="bi bi-flag me-2"></i>Add Milestone
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="milestone_title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="milestone_title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" required placeholder="e.g. MVP Launch">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="milestone_description" class="form-label">Description</label>
                            <textarea name="description" id="milestone_description" rows="3" class="form-control @error('description') is-invalid @enderror"
                                placeholder="What does this milestone deliver?">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="milestone_target_date" class="form-label">Target date</label>
                                <input type="date" name="target_date" id="milestone_target_date"
                                    class="form-control @error('target_date') is-invalid @enderror"
                                    value="{{ old('target_date') }}">
                                @error('target_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="milestone_status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="milestone_status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="pending" @selected(old('status', 'pending') === 'pending')>Pending</option>
                                    <option value="in_progress" @selected(old('status') === 'in_progress')>In progress</option>
                                    <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Save Milestone
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('projects.tasks.store', $project) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTaskModalLabel">
                            <i class="bi bi-check2-square me-2"></i>Add Task
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="task_milestone_id" class="form-label">Milestone (optional)</label>
                            <select name="milestone_id" id="task_milestone_id" class="form-select @error('milestone_id') is-invalid @enderror">
                                <option value="">— No milestone (standalone task) —</option>
                                @foreach($project->milestones as $milestone)
                                    <option value="{{ $milestone->id }}" @selected(old('milestone_id') == $milestone->id)>
                                        {{ $milestone->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('milestone_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="task_title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="task_title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" required placeholder="e.g. Design API schema">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="task_description" class="form-label">Description</label>
                            <textarea name="description" id="task_description" rows="3" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Acceptance criteria, links, notes...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="task_assigned_to" class="form-label">Assign to</label>
                                <select name="assigned_to" id="task_assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                                    <option value="">— Unassigned —</option>
                                    @foreach($assignableUsers as $user)
                                        <option value="{{ $user->id }}" @selected(old('assigned_to') == $user->id)>
                                            {{ $user->name }}{{ $user->id === $project->owner_id ? ' (Owner)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="task_due_date" class="form-label">Due date</label>
                                <input type="date" name="due_date" id="task_due_date"
                                    class="form-control @error('due_date') is-invalid @enderror"
                                    value="{{ old('due_date') }}">
                                @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="task_status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="task_status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="todo" @selected(old('status', 'todo') === 'todo')>To do</option>
                                    <option value="in_progress" @selected(old('status') === 'in_progress')>In progress</option>
                                    <option value="review" @selected(old('status') === 'review')>Review</option>
                                    <option value="done" @selected(old('status') === 'done')>Done</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="task_priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                <select name="priority" id="task_priority" class="form-select @error('priority') is-invalid @enderror" required>
                                    <option value="low" @selected(old('priority', 'medium') === 'low')>Low</option>
                                    <option value="medium" @selected(old('priority', 'medium') === 'medium')>Medium</option>
                                    <option value="high" @selected(old('priority') === 'high')>High</option>
                                </select>
                                @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Save Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
@if($canManage)
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.status-auto-submit select[name="status"]').forEach(function (select) {
        select.addEventListener('change', function () {
            select.closest('form').submit();
        });
    });

    var milestoneModal = document.getElementById('addMilestoneModal');
    var taskModal = document.getElementById('addTaskModal');

    @if($errors->has('priority') || $errors->has('assigned_to') || $errors->has('milestone_id') || (old('status') && in_array(old('status'), ['todo', 'in_progress', 'review', 'done'], true)))
        if (taskModal) {
            new bootstrap.Modal(taskModal).show();
        }
    @elseif($errors->has('target_date') || ($errors->any() && ! $errors->has('priority')))
        if (milestoneModal) {
            new bootstrap.Modal(milestoneModal).show();
        }
    @endif

    document.querySelectorAll('[data-bs-target="#addTaskModal"][data-milestone-id]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var select = document.getElementById('task_milestone_id');
            if (select) {
                select.value = btn.getAttribute('data-milestone-id') || '';
            }
        });
    });
});
</script>
@endif
@endpush
