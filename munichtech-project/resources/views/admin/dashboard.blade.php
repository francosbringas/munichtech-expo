@extends('layouts.app')

@section('content')
<div class="row gy-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-start border-4 border-primary">
            <div class="card-body">
                <h5>Total usuarios</h5>
                <p class="display-6 mb-0">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-start border-4 border-success">
            <div class="card-body">
                <h5>Proyectos</h5>
                <p class="display-6 mb-0">{{ $totalProjects }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-start border-4 border-warning">
            <div class="card-body">
                <h5>Colaboraciones activas</h5>
                <p class="display-6 mb-0">{{ $activeCollaborations }}</p>
            </div>
        </div>
    </div>
</div>
<div class="row gy-4 mt-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">System Health / Audit Logs</div>
            <div class="card-body">
                <p class="text-muted">Monitoreo básico de eventos de seguridad, accesos e integridad del sistema.</p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>IP</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLogs as $log)
                                <tr>
                                    <td>{{ $log->user?->name ?? 'Sistema' }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header">Estado del entorno</div>
            <div class="card-body">
                <p class="mb-2">Base de datos: SQLite</p>
                <p class="mb-2">Framework: Laravel 11</p>
                <p class="mb-2">Seguridad: CSRF activo y validación de entrada</p>
                <p class="mb-2">Protección: consultas Eloquent seguras</p>
            </div>
        </div>
    </div>
</div>
@endsection
