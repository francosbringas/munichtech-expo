@extends('layouts.app')

@section('title', 'Collaborations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Collaborations</h2>
        <p class="text-muted mb-0">Manage your sent and received requests.</p>
    </div>
    <a href="{{ route('collaborations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Request Collaboration
    </a>
</div>

<div class="row gy-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header card-header-dark">Sent Requests</div>
            <div class="card-body">
                @forelse($sentRequests as $item)
                    <div class="mb-3 border-bottom pb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">{{ $item->receiver->name }}</h5>
                                <p class="mb-1 small text-muted">{{ $item->receiver->role }} · {{ $item->receiver->company_name ?? 'No company' }}</p>
                            </div>
                            <span class="badge bg-{{ $item->status === 'accepted' ? 'success' : ($item->status === 'rejected' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                        <p class="mb-2 small">{{ Str::limit($item->message, 140) }}</p>
                        @if($item->status === 'accepted')
                            <a href="{{ route('projects.create', ['collaboration_id' => $item->id]) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-folder-plus me-1"></i>Create Project
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="text-muted mb-0">You have not sent any requests yet.</p>
                @endforelse
                @if($sentRequests->hasPages())
                    <div class="mt-3">{{ $sentRequests->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header card-header-dark">Received Requests</div>
            <div class="card-body">
                @forelse($receivedRequests as $item)
                    <div class="mb-3 border-bottom pb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">{{ $item->sender->name }}</h5>
                                <p class="mb-1 small text-muted">{{ $item->sender->role }} · {{ $item->sender->company_name ?? 'No company' }}</p>
                            </div>
                            <span class="badge bg-{{ $item->status === 'accepted' ? 'success' : ($item->status === 'rejected' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                        <p class="mb-2 small">{{ Str::limit($item->message, 140) }}</p>
                        @if($item->status === 'pending')
                            <form action="{{ route('collaborations.respond', $item) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <button name="action" value="accept" class="btn btn-sm btn-success">Accept</button>
                                <button name="action" value="reject" class="btn btn-sm btn-outline-danger">Reject</button>
                            </form>
                        @endif
                        @if($item->status === 'accepted')
                            <a href="{{ route('projects.create', ['collaboration_id' => $item->id]) }}" class="btn btn-success btn-sm mt-2">
                                <i class="bi bi-folder-plus me-1"></i>Create Project
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="text-muted mb-0">You have not received any requests yet.</p>
                @endforelse
                @if($receivedRequests->hasPages())
                    <div class="mt-3">{{ $receivedRequests->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
