@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">Register for MunichTech EXPO</div>
            <div class="card-body">
                <form method="POST" action="{{ route('events.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Ticket Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }} - ${{ number_format($category->price, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ticket Type</label>
                        <input type="text" name="ticket_type" value="{{ old('ticket_type') }}" class="form-control" placeholder="e.g., VIP, Standard" required>
                    </div>
                    <button class="btn btn-primary">Confirm Registration</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
