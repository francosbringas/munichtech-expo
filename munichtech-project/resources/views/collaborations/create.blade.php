@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">New collaboration request</div>
            <div class="card-body">
                <form method="POST" action="{{ route('collaborations.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select a user</label>
                        <select name="receiver_id" class="form-select" required>
                            <option value="">Select a collaborator</option>
                            @foreach($receivers as $receiver)
                                <option value="{{ $receiver->id }}">{{ $receiver->name }} – {{ $receiver->role }} @if($receiver->company_name)({{ $receiver->company_name }})@endif</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" rows="6" class="form-control" required>{{ old('message') }}</textarea>
                        <small class="text-muted">Describe your proposal and the expected results.</small>
                    </div>
                    <button class="btn btn-primary">Send request</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
