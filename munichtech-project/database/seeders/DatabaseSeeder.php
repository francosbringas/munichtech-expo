<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\CollaborationRequest;
use App\Models\EventRegistration;
use App\Models\EventTicketCategory;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectTask;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin MunichTech',
            'email' => 'admin@munichtech.test',
            'role' => 'Company',
            'company_name' => 'MunichTech Admin',
            'phone' => '+49 89 1234 5678',
            'bio' => 'Administrador del sistema con enfoque en seguridad y operaciones.',
            'is_admin' => true,
            'password' => Hash::make('SecurePass123!'),
        ]);

        $startup = User::create([
            'name' => 'Valentina Torres',
            'email' => 'valentina@innovabiz.test',
            'role' => 'Startup',
            'company_name' => 'InnovaBiz',
            'phone' => '+49 176 555 3179',
            'bio' => 'CEO de InnovaBiz construyendo soluciones de IA para salud.',
            'password' => Hash::make('Password123!'),
        ]);

        $investor = User::create([
            'name' => 'Marcelo Ruiz',
            'email' => 'marcelo@capitalvc.test',
            'role' => 'Investor',
            'company_name' => 'CapitalVC',
            'phone' => '+49 151 8877 0099',
            'bio' => 'Inversor en tecnología, movilidad y ciudades inteligentes.',
            'password' => Hash::make('Password123!'),
        ]);

        $serviceProvider = User::create([
            'name' => 'Ana Weber',
            'email' => 'ana@cybersecure.test',
            'role' => 'Service Provider',
            'company_name' => 'CyberSecure GmbH',
            'phone' => '+49 89 9988 7766',
            'bio' => 'Proveedora de servicios de ciberseguridad y compliance para pymes.',
            'password' => Hash::make('Password123!'),
        ]);

        $company = User::create([
            'name' => 'Carlos López',
            'email' => 'carlos@techcorp.test',
            'role' => 'Company',
            'company_name' => 'TechCorp Solutions',
            'phone' => '+49 89 4411 2233',
            'bio' => 'Director de innovación en TechCorp Solutions.',
            'password' => Hash::make('Password123!'),
        ]);

        $attendee = User::create([
            'name' => 'Marina Gutiérrez',
            'email' => 'marina@attendee.test',
            'role' => 'Attendee',
            'company_name' => 'Consultoría Ágora',
            'phone' => '+49 179 3344 5566',
            'bio' => 'Asistente de eventos con interés en startups y networking ejecutivo.',
            'password' => Hash::make('Password123!'),
        ]);

        $hackParticipant = User::create([
            'name' => 'Leo Krauss',
            'email' => 'leo@hackathon.test',
            'role' => 'Hackathon Participant',
            'company_name' => 'Open Innovation Team',
            'phone' => '+49 160 2233 4455',
            'bio' => 'Participante activo en hackathons de IoT y desarrollo colaborativo.',
            'password' => Hash::make('Password123!'),
        ]);

        EventTicketCategory::create(['name' => 'Standard', 'description' => 'Acceso general a MunichTech EXPO.', 'price' => 49.00]);
        EventTicketCategory::create(['name' => 'VIP', 'description' => 'Acceso prioritario, networking VIP y kit de bienvenida.', 'price' => 129.00]);
        EventTicketCategory::create(['name' => 'Startup Pass', 'description' => 'Acceso para startups con pitch y mentoría.', 'price' => 79.00]);
        EventTicketCategory::create(['name' => 'Investor Pass', 'description' => 'Acceso especial para inversores y mesas privadas.', 'price' => 99.00]);

        EventRegistration::create([ 'user_id' => $startup->id, 'category_id' => 3, 'ticket_type' => 'Startup Pass', 'status' => 'confirmed' ]);
        EventRegistration::create([ 'user_id' => $investor->id, 'category_id' => 4, 'ticket_type' => 'Investor Pass', 'status' => 'confirmed' ]);
        EventRegistration::create([ 'user_id' => $attendee->id, 'category_id' => 1, 'ticket_type' => 'Standard', 'status' => 'confirmed' ]);

        $collaboration = CollaborationRequest::create([
            'sender_id' => $startup->id,
            'receiver_id' => $serviceProvider->id,
            'message' => 'Busco un socio experto en ciberseguridad para integrar un MVP IoT con protección de datos y detección de intrusiones.',
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        CollaborationRequest::create([
            'sender_id' => $hackParticipant->id,
            'receiver_id' => $company->id,
            'message' => 'Me gustaría colaborar en un prototipo de plataforma de eventos conectados para gestionar asistentes y métricas en tiempo real.',
            'status' => 'pending',
        ]);

        $project = Project::create([
            'owner_id' => $startup->id,
            'collaboration_request_id' => $collaboration->id,
            'title' => 'Plataforma de Seguridad IoT para Expo',
            'description' => 'Desarrollo de una plataforma colaborativa para monitorear dispositivos IoT, evaluar amenazas y gestionar proyectos en tiempo real.',
            'progress' => 42,
            'status' => 'active',
            'company_name' => 'InnovaBiz',
        ]);

        $milestone1 = ProjectMilestone::create([
            'project_id' => $project->id,
            'title' => 'Definir arquitectura de seguridad',
            'description' => 'Revisión de requisitos, diseño de capas de defensa y plan de validación para el sistema IoT.',
            'due_date' => now()->addWeeks(2)->toDateString(),
            'completed' => false,
        ]);

        ProjectTask::create([ 'milestone_id' => $milestone1->id, 'title' => 'Mapear activos IoT', 'description' => 'Crear inventario de dispositivos y vectores de acceso.', 'assigned_to_id' => $serviceProvider->id, 'due_date' => now()->addWeek()->toDateString(), 'completed' => false ]);
        ProjectTask::create([ 'milestone_id' => $milestone1->id, 'title' => 'Evaluar riesgos de red', 'description' => 'Realizar análisis de puertos y servicios expuestos.', 'assigned_to_id' => $serviceProvider->id, 'due_date' => now()->addWeeks(2)->toDateString(), 'completed' => false ]);

        AuditLog::create([ 'user_id' => $admin->id, 'action' => 'Seeder executed', 'ip_address' => '127.0.0.1', 'user_agent' => 'Seeder script', 'details' => 'Initial data population including users, tickets, collaborations and projects.' ]);
        AuditLog::create([ 'user_id' => $startup->id, 'action' => 'Created project', 'ip_address' => '127.0.0.1', 'user_agent' => 'Seeder script', 'details' => 'Project ID: ' . $project->id ]);
    }
}
