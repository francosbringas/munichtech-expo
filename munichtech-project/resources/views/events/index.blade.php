@extends('layouts.app')

@section('title', 'My Registrations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My MunichTech EXPO Registrations</h2>
    <a href="{{ route('events.create') }}" class="btn btn-success">New Registration</a>
</div>
<div class="row">
    @forelse($registrations as $registration)
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="card-title">{{ ucfirst($registration->ticket_category) }} Pass</h5>
                        <span class="badge bg-{{ $registration->status === 'confirmed' ? 'success' : ($registration->status === 'pending' ? 'warning text-dark' : 'danger') }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                    </div>
                    @if($registration->special_requirements)
                        <p class="card-text small text-muted">{{ $registration->special_requirements }}</p>
                    @endif
                    <p class="text-muted small mb-0">
                        Submitted: {{ $registration->created_at->format('d/m/Y H:i') }}
                        @if($registration->confirmed_at)
                            · Confirmed: {{ $registration->confirmed_at->format('d/m/Y') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">You do not have any registrations yet. Register to get your event ticket.</div>
        </div>
    @endforelse
</div>
@if($registrations->hasPages())
    <div class="mt-3">{{ $registrations->links() }}</div>
@endif
@endsection
