@extends('layouts.app')

@section('title', 'New Collaboration')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header card-header-dark">New Collaboration Request</div>
            <div class="card-body">
                <form method="POST" action="{{ route('collaborations.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="receiver_id">Select user <span class="text-danger">*</span></label>
                        <select name="receiver_id" id="receiver_id" class="form-select @error('receiver_id') is-invalid @enderror" required>
                            <option value="">Choose a collaborator</option>
                            @foreach($users as $receiver)
                                <option value="{{ $receiver->id }}" @selected(old('receiver_id', $preselectedReceiverId ?? '') == $receiver->id)>
                                    {{ $receiver->name }} — {{ $receiver->role }}
                                    @if($receiver->company_name) ({{ $receiver->company_name }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('receiver_id')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="message">Message <span class="text-danger">*</span></label>
                        <textarea
                            name="message"
                            id="message"
                            rows="5"
                            class="form-control @error('message') is-invalid @enderror"
                            required
                            minlength="20"
                            maxlength="1000"
                            placeholder="Describe your collaboration proposal, goals, and expected outcomes (minimum 20 characters)."
                        >{{ old('message') }}</textarea>
                        <small class="text-muted">Minimum 20 characters, maximum 1000. Be specific about scope, timeline, and value for both parties.</small>
                        @error('message')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send Request</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
