@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">Nueva solicitud de colaboración</div>
            <div class="card-body">
                <form method="POST" action="{{ route('collaborations.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Selecciona un usuario</label>
                        <select name="receiver_id" class="form-select" required>
                            <option value="">Elegir colaborador</option>
                            @foreach($receivers as $receiver)
                                <option value="{{ $receiver->id }}">{{ $receiver->name }} – {{ $receiver->role }} @if($receiver->company_name)({{ $receiver->company_name }})@endif</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mensaje</label>
                        <textarea name="message" rows="6" class="form-control" required>{{ old('message') }}</textarea>
                        <small class="text-muted">Describe tu propuesta y los resultados esperados.</small>
                    </div>
                    <button class="btn btn-primary">Enviar solicitud</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
