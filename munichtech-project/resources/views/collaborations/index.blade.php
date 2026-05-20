@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 d-flex justify-content-between align-items-center mb-4">
        <h2>Colaboraciones</h2>
        <a href="{{ route('collaborations.create') }}" class="btn btn-primary">Solicitar colaboración</a>
    </div>
</div>
<div class="row gy-4">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header">Solicitudes enviadas</div>
            <div class="card-body">
                @forelse($sent as $item)
                    <div class="mb-3 border-bottom pb-3">
                        <h5>{{ $item->receiver->name }}</h5>
                        <p class="mb-1"><strong>Empresa:</strong> {{ $item->receiver->company_name ?? 'Sin empresa' }}</p>
                        <p>{{ Str::limit($item->message, 140) }}</p>
                        <span class="badge bg-primary">{{ ucfirst($item->status) }}</span>
                    </div>
                @empty
                    <p class="text-muted">Aún no has enviado solicitudes.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header">Solicitudes recibidas</div>
            <div class="card-body">
                @forelse($received as $item)
                    <div class="mb-3 border-bottom pb-3">
                        <h5>{{ $item->sender->name }}</h5>
                        <p class="mb-1"><strong>Rol:</strong> {{ $item->sender->role }}</p>
                        <p>{{ Str::limit($item->message, 140) }}</p>
                        <span class="badge bg-{{ $item->status === 'accepted' ? 'success' : ($item->status === 'rejected' ? 'danger' : 'secondary') }}">{{ ucfirst($item->status) }}</span>
                        @if($item->status === 'pending')
                            <form action="{{ route('collaborations.respond', $item) }}" method="POST" class="mt-2 d-flex gap-2">
                                @csrf
                                <button name="action" value="accepted" class="btn btn-sm btn-success">Aceptar</button>
                                <button name="action" value="rejected" class="btn btn-sm btn-danger">Rechazar</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-muted">No hay solicitudes para responder.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
