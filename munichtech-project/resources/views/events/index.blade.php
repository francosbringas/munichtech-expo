@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Mis registros a MunichTech EXPO</h2>
    <a href="{{ route('events.create') }}" class="btn btn-success">Nuevo registro</a>
</div>
<div class="row">
    @foreach($registrations as $registration)
        <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $registration->category->name }}</h5>
                    <p class="card-text">Tipo de ticket: <strong>{{ $registration->ticket_type }}</strong></p>
                    <p class="card-text">Estatus: <span class="badge bg-{{ $registration->status === 'confirmed' ? 'success' : ($registration->status === 'pending' ? 'secondary' : 'danger') }}">{{ ucfirst($registration->status) }}</span></p>
                    <p class="text-muted">Registrado el {{ $registration->registered_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    @endforeach
    @if($registrations->isEmpty())
        <div class="col-12">
            <div class="alert alert-info">No tienes registros todavía. Comienza con un ticket para MunichTech EXPO.</div>
        </div>
    @endif
</div>
@endsection
