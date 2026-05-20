@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">Registro a MunichTech EXPO</div>
            <div class="card-body">
                <form method="POST" action="{{ route('events.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Categoría de ticket</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Selecciona una categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }} - ${{ number_format($category->price, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de ticket</label>
                        <input type="text" name="ticket_type" value="{{ old('ticket_type') }}" class="form-control" placeholder="Ej. VIP, Standard" required>
                    </div>
                    <button class="btn btn-primary">Confirmar registro</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
