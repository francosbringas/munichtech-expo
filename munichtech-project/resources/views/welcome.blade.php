@extends('layouts.app')

@section('content')
<div class="row align-items-center">
    <div class="col-lg-7">
        <div class="p-5 bg-white rounded shadow-sm">
            <h1 class="display-5 mb-3">MunichTech EXPO</h1>
            <p class="lead text-muted">Explore opportunities for networking, register your ticket, create secure collaborations, and launch projects with a professional workflow for startups, investors, and suppliers.</p>
            <div class="d-flex gap-2 mt-4">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Register</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Login</a>
                @else
                    <a href="{{ route('projects.index') }}" class="btn btn-primary btn-lg">View Projects</a>
                    <a href="{{ route('collaborations.index') }}" class="btn btn-outline-secondary btn-lg">Collaborations</a>
                @endguest
            </div>
        </div>
    </div>
    <div class="col-lg-5 mt-4 mt-lg-0">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="card-title">Key Information</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Authentication with specialized roles</li>
                    <li class="list-group-item">Registration for the event with ticket selection</li>
                    <li class="list-group-item">Collaboration system for creating projects</li>
                    <li class="list-group-item">Administration dashboard and audit logs</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
