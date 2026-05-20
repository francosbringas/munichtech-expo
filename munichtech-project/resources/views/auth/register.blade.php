@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">Register</div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Enterprise / Startup</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Professional Description</label>
                            <textarea name="bio" rows="3" class="form-control">{{ old('bio') }}</textarea>
                        </div>
                    </div>
                    <button class="btn btn-success mt-4 w-100">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
