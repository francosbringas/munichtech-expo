@extends('layouts.app')

@section('content')
<div class="row align-items-center">
    <div class="col-lg-7">
        <div class="p-5 bg-white rounded shadow-sm">
            <h1 class="display-5 mb-3">MunichTech EXPO</h1>
            <p class="lead text-muted">Explora oportunidades de networking, registra tu ticket, crea colaboraciones seguras y lanza proyectos con un flujo profesional para startups, inversores y proveedores.</p>
            <div class="d-flex gap-2 mt-4">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Regístrate</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Inicia sesión</a>
                @else
                    <a href="{{ route('projects.index') }}" class="btn btn-primary btn-lg">Ver Proyectos</a>
                    <a href="{{ route('collaborations.index') }}" class="btn btn-outline-secondary btn-lg">Colaboraciones</a>
                @endguest
            </div>
        </div>
    </div>
    <div class="col-lg-5 mt-4 mt-lg-0">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="card-title">Datos clave</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Autenticación con roles especializados</li>
                    <li class="list-group-item">Registro al evento con selección de ticket</li>
                    <li class="list-group-item">Sistema de colaboraciones para crear proyectos</li>
                    <li class="list-group-item">Dashboard de administración y audit logs</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
