@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-4">
    <h2 class="mb-1"><i class="bi bi-shield-lock text-warning"></i> Admin Operations Center</h2>
    <p class="text-muted mb-0">Full management of users, registrations, projects, and audit logs.</p>
</div>

<div class="row gy-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-start border-4 border-primary">
            <div class="card-body py-3">
                <h6 class="text-muted mb-1">Users</h6>
                <p class="display-6 mb-0">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-start border-4 border-success">
            <div class="card-body py-3">
                <h6 class="text-muted mb-1">Projects</h6>
                <p class="display-6 mb-0">{{ $totalProjects }}</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-start border-4 border-warning">
            <div class="card-body py-3">
                <h6 class="text-muted mb-1">Pending Requests</h6>
                <p class="display-6 mb-0 text-warning">{{ $pendingRequests }}</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-start border-4 border-info">
            <div class="card-body py-3">
                <h6 class="text-muted mb-1">Registrations</h6>
                <p class="display-6 mb-0">{{ $totalEventRegs }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header card-header-dark">Users by Role</div>
            <div class="card-body">
                @forelse($usersByRole as $role => $total)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $role }}</span>
                        <span class="badge bg-secondary">{{ $total }}</span>
                    </div>
                @empty
                    <p class="text-muted mb-0">No data available.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-header card-header-dark">Recent Audit Logs</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead>
                            <tr><th>User</th><th>Action</th><th>IP</th><th>Date</th></tr>
                        </thead>
                        <tbody>
                            @foreach($recentLogs as $log)
                                <tr>
                                    <td>{{ $log->user?->name ?? 'System' }}</td>
                                    <td class="small">{{ Str::limit($log->action, 40) }}</td>
                                    <td class="small">{{ $log->ip_address ?? 'N/A' }}</td>
                                    <td class="small">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="mb-5">
    <h4 class="mb-3"><i class="bi bi-people me-2"></i>User Management</h4>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Admin</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td class="small">{{ $user->email }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.update-user-role', $user) }}" class="d-flex gap-1">
                                        @csrf @method('PATCH')
                                        <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                            @foreach(\App\Models\User::ROLES as $role)
                                                <option value="{{ $role }}" @selected($user->role === $role)>{{ $role }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->is_admin)
                                        <span class="badge bg-warning text-dark">Admin</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <form method="POST" action="{{ route('admin.toggle-user-active', $user) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-{{ $user->is_active ? 'danger' : 'success' }}">
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.toggle-admin', $user) }}" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-warning">Toggle Admin</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<section class="mb-5">
    <h4 class="mb-3"><i class="bi bi-calendar-event me-2"></i>Event Registration Management</h4>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Ticket Category</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $reg)
                            <tr>
                                <td>{{ $reg->user->name }}</td>
                                <td><span class="badge bg-primary">{{ ucfirst($reg->ticket_category) }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.update-event-status', $reg) }}" class="d-flex gap-1">
                                        @csrf @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="pending" @selected($reg->status === 'pending')>Pending</option>
                                            <option value="confirmed" @selected($reg->status === 'confirmed')>Confirmed</option>
                                            <option value="cancelled" @selected($reg->status === 'cancelled')>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="small">{{ $reg->created_at->format('d/m/Y') }}</td>
                                <td class="small text-muted">{{ $reg->special_requirements ? Str::limit($reg->special_requirements, 30) : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<section class="mb-4">
    <h4 class="mb-3"><i class="bi bi-kanban me-2"></i>Project Management</h4>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Project</th>
                            <th>Owner</th>
                            <th>Workflow Status</th>
                            <th>Admin Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr>
                                <td>{{ $project->title }}</td>
                                <td class="small">{{ $project->owner->name }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($project->status) }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.update-project-admin-status', $project) }}" class="d-flex gap-1">
                                        @csrf @method('PATCH')
                                        <select name="admin_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="active" @selected($project->admin_status === 'active')>Active</option>
                                            <option value="inactive" @selected($project->admin_status === 'inactive')>Inactive</option>
                                            <option value="suspended" @selected($project->admin_status === 'suspended')>Suspended</option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $project->progress }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
