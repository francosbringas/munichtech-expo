<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\CollaborationRequest;
use App\Models\EventRegistration;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectMilestone;
use App\Models\ProjectTask;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin MunichTech',
            'email' => 'admin@munichtech.test',
            'role' => 'Company',
            'company_name' => 'MunichTech Admin',
            'phone' => '+49 89 1234 5678',
            'interests' => 'Security, Operations, AI',
            'bio' => 'System administrator focused on security and operations.',
            'is_admin' => true,
            'is_active' => true,
            'password' => Hash::make('SecurePass123!'),
        ]);

        $startup = User::create([
            'name' => 'Valentina Torres',
            'email' => 'valentina@innovabiz.test',
            'role' => 'Startup',
            'company_name' => 'InnovaBiz',
            'phone' => '+49 176 555 3179',
            'interests' => 'AI, Health, IoT',
            'bio' => 'CEO of InnovaBiz building AI solutions for healthcare.',
            'password' => Hash::make('Password123!'),
        ]);

        $investor = User::create([
            'name' => 'Marcelo Ruiz',
            'email' => 'marcelo@capitalvc.test',
            'role' => 'Investor',
            'company_name' => 'CapitalVC',
            'phone' => '+49 151 8877 0099',
            'interests' => 'AI, Mobility, Smart Cities',
            'bio' => 'Technology investor focused on mobility and smart cities.',
            'password' => Hash::make('Password123!'),
        ]);

        $serviceProvider = User::create([
            'name' => 'Ana Weber',
            'email' => 'ana@cybersecure.test',
            'role' => 'Service Provider',
            'company_name' => 'CyberSecure GmbH',
            'phone' => '+49 89 9988 7766',
            'interests' => 'Security, Compliance, Cloud',
            'bio' => 'Cybersecurity and compliance service provider for SMEs.',
            'password' => Hash::make('Password123!'),
        ]);

        $company = User::create([
            'name' => 'Carlos López',
            'email' => 'carlos@techcorp.test',
            'role' => 'Company',
            'company_name' => 'TechCorp Solutions',
            'phone' => '+49 89 4411 2233',
            'interests' => 'AI, Events, Innovation',
            'bio' => 'Director of innovation at TechCorp Solutions.',
            'password' => Hash::make('Password123!'),
        ]);

        $attendee = User::create([
            'name' => 'Marina Gutiérrez',
            'email' => 'marina@attendee.test',
            'role' => 'Attendee',
            'company_name' => 'Consultoría Ágora',
            'phone' => '+49 179 3344 5566',
            'interests' => 'Networking, Startups',
            'bio' => 'Event attendee with a focus on startups and executive networking.',
            'password' => Hash::make('Password123!'),
        ]);

        $hackParticipant = User::create([
            'name' => 'Leo Krauss',
            'email' => 'leo@hackathon.test',
            'role' => 'Hackathon Participant',
            'company_name' => 'Open Innovation Team',
            'phone' => '+49 160 2233 4455',
            'interests' => 'IoT, Development, AI',
            'bio' => 'Active hackathon participant in IoT and collaborative development.',
            'password' => Hash::make('Password123!'),
        ]);

        EventRegistration::create([
            'user_id' => $startup->id,
            'ticket_category' => 'startup',
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        EventRegistration::create([
            'user_id' => $investor->id,
            'ticket_category' => 'investor',
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        EventRegistration::create([
            'user_id' => $attendee->id,
            'ticket_category' => 'free',
            'status' => 'pending',
        ]);

        $acceptedCollaboration = CollaborationRequest::create([
            'sender_id' => $startup->id,
            'receiver_id' => $serviceProvider->id,
            'message' => 'Looking for a cybersecurity expert to integrate an IoT MVP with data protection.',
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        CollaborationRequest::create([
            'sender_id' => $hackParticipant->id,
            'receiver_id' => $company->id,
            'message' => 'I would like to collaborate on a connected event platform prototype.',
            'status' => 'pending',
        ]);

        $project = Project::create([
            'owner_id' => $startup->id,
            'collaboration_request_id' => $acceptedCollaboration->id,
            'title' => 'IoT Security Platform for Expo',
            'description' => 'Development of a collaborative platform to monitor IoT devices, assess threats, and manage projects in real time.',
            'progress' => 42,
            'status' => 'active',
            'admin_status' => 'active',
            'tags' => 'IoT, Security, Cloud',
        ]);

        ProjectMember::create([
            'project_id' => $project->id,
            'user_id' => $serviceProvider->id,
            'role' => 'contributor',
        ]);

        $milestone1 = ProjectMilestone::create([
            'project_id' => $project->id,
            'title' => 'Define security architecture',
            'description' => 'Requirements review, defense layer design, and validation plan for the IoT system.',
            'target_date' => now()->addWeeks(2)->toDateString(),
            'status' => 'in_progress',
        ]);

        ProjectMilestone::create([
            'project_id' => $project->id,
            'title' => 'Event dashboard integration',
            'description' => 'Connect the MVP with the MunichTech EXPO collaboration module.',
            'target_date' => now()->addWeeks(4)->toDateString(),
            'status' => 'pending',
        ]);

        ProjectTask::create([
            'project_id' => $project->id,
            'milestone_id' => $milestone1->id,
            'title' => 'Map IoT assets',
            'description' => 'Create an inventory of devices and access vectors.',
            'assigned_to' => $serviceProvider->id,
            'due_date' => now()->addWeek()->toDateString(),
            'status' => 'in_progress',
            'priority' => 'high',
        ]);

        ProjectTask::create([
            'project_id' => $project->id,
            'milestone_id' => $milestone1->id,
            'title' => 'Assess network risks',
            'description' => 'Perform analysis of exposed ports and services.',
            'assigned_to' => $serviceProvider->id,
            'due_date' => now()->addWeeks(2)->toDateString(),
            'status' => 'todo',
            'priority' => 'medium',
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'Seeder executed',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder script',
            'details' => 'Initial data population.',
        ]);

        AuditLog::create([
            'user_id' => $startup->id,
            'action' => 'Created project',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder script',
            'details' => 'Project ID: ' . $project->id,
        ]);
    }
}
