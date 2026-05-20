<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CollaborationRequest;
use App\Models\EventRegistration;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers           = User::count();
        $totalProjects        = Project::count();
        $activeCollaborations = CollaborationRequest::where('status', 'accepted')->count();
        $pendingRequests      = CollaborationRequest::where('status', 'pending')->count();
        $totalEventRegs       = EventRegistration::count();
        $recentLogs           = AuditLog::with('user')->latest()->limit(15)->get();
        $usersByRole          = User::selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role');

        $users         = User::latest()->limit(20)->get();
        $registrations = EventRegistration::with('user')->latest()->limit(20)->get();
        $projects      = Project::with('owner')->latest()->limit(20)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProjects',
            'activeCollaborations',
            'pendingRequests',
            'totalEventRegs',
            'recentLogs',
            'usersByRole',
            'users',
            'registrations',
            'projects'
        ));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', 'in:' . implode(',', User::ROLES)],
        ]);

        $user->role = $data['role'];
        $user->save();

        $this->logAction('Admin updated user role', "User #{$user->id} role → {$data['role']}");

        return back()->with('success', "Role for {$user->name} has been updated.");
    }

    public function toggleUserActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot deactivate your own account.']);
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $this->logAction('Admin toggled user active status', "User #{$user->id} is_active → " . ($user->is_active ? 'true' : 'false'));

        return back()->with('success', "Account status for {$user->name} has been updated.");
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot change your own administrator status.']);
        }

        $user->is_admin = ! $user->is_admin;
        $user->save();

        $this->logAction('Admin toggled user admin status', "User #{$user->id} is_admin → " . ($user->is_admin ? 'true' : 'false'));

        return back()->with('success', "Administrator status for {$user->name} has been updated.");
    }

    public function updateEventRegistrationStatus(Request $request, EventRegistration $registration)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled'],
        ]);

        $registration->status = $data['status'];
        $registration->confirmed_at = $data['status'] === 'confirmed' ? now() : null;
        $registration->save();

        $this->logAction('Admin updated event registration status', "Registration #{$registration->id} → {$data['status']}");

        return back()->with('success', 'Registration status has been updated.');
    }

    public function updateProjectAdminStatus(Request $request, Project $project)
    {
        $data = $request->validate([
            'admin_status' => ['required', 'in:active,inactive,suspended'],
        ]);

        $project->admin_status = $data['admin_status'];
        $project->save();

        $this->logAction('Admin updated project admin status', "Project #{$project->id} admin_status → {$data['admin_status']}");

        return back()->with('success', "Administrative status for \"{$project->title}\" has been updated.");
    }

    private function logAction(string $action, string $details): void
    {
        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details'    => $details,
        ]);
    }
}
