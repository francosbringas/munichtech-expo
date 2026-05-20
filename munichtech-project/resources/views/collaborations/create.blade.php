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
                        <label class="form-label">Select user</label>
                        <select name="receiver_id" class="form-select @error('receiver_id') is-invalid @enderror" required>
                            <option value="">Choose a collaborator</option>
                            @foreach($users as $receiver)
                                <option value="{{ $receiver->id }}" @selected(old('receiver_id', $preselectedReceiverId ?? '') == $receiver->id)>
                                    {{ $receiver->name }} — {{ $receiver->role }}
                                    @if($receiver->company_name) ({{ $receiver->company_name }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('receiver_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                        <small class="text-muted">Describe your proposal and expected outcomes.</small>
                        @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button class="btn btn-primary w-100">Send Request</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
