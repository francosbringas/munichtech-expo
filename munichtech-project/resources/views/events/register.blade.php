@extends('layouts.app')

@section('title', 'Register Ticket')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header card-header-dark">Get Ticket — MunichTech EXPO</div>
            <div class="card-body">
                <form method="POST" action="{{ route('events.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Ticket Category</label>
                        <select name="ticket_category" class="form-select @error('ticket_category') is-invalid @enderror" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $value => $label)
                                <option value="{{ $value }}" @selected(old('ticket_category') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('ticket_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Special requirements (optional)</label>
                        <textarea name="special_requirements" rows="3" class="form-control">{{ old('special_requirements') }}</textarea>
                    </div>
                    <button class="btn btn-success w-100">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
