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
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! $request->user() || ! $request->user()->is_admin) {
                abort(403, 'Acceso restringido a administradores.');
            }
            return $next($request);
        });
    }

    // ── Dashboard ──────────────────────────────────────────────

    public function index()
    {
        $totalUsers           = User::count();
        $totalProjects        = Project::count();
        $activeCollaborations = CollaborationRequest::where('status', 'accepted')->count();
        $pendingRequests      = CollaborationRequest::where('status', 'pending')->count();
        $totalEventRegs       = EventRegistration::count();
        $recentLogs           = AuditLog::with('user')->latest()->limit(15)->get();

        // Users by role for a quick breakdown
        $usersByRole = User::selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProjects',
            'activeCollaborations',
            'pendingRequests',
            'totalEventRegs',
            'recentLogs',
            'usersByRole'
        ));
    }

    // ── Users ──────────────────────────────────────────────────

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($b) => $b->where('name', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%")
                ->orWhere('company_name', 'like', "%$q%"));
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        // Prevent removing your own admin rights
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes cambiar tu propio estado de administrador.']);
        }

        $user->is_admin = ! $user->is_admin;
        $user->save();

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'Admin toggled user admin status',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details'    => "User #{$user->id} ({$user->email}) is_admin → " . ($user->is_admin ? 'true' : 'false'),
        ]);

        return back()->with('success', "Estado de administrador actualizado para {$user->name}.");
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta.']);
        }

        $email = $user->email;
        $user->delete();

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'Admin deleted user',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details'    => "Deleted user: $email",
        ]);

        return back()->with('success', "Usuario $email eliminado.");
    }

    // ── Event Registrations ────────────────────────────────────

    public function eventRegistrations(Request $request)
    {
        $query = EventRegistration::with('user');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->whereHas('user', fn($b) => $b->where('name', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%"));
        }

        if ($request->filled('ticket_category')) {
            $query->where('ticket_category', $request->ticket_category);
        }

        $registrations = $query->latest()->paginate(20)->withQueryString();

        $categories = EventRegistration::distinct()->pluck('ticket_category');

        return view('admin.event-registrations', compact('registrations', 'categories'));
    }

    public function destroyEventRegistration(EventRegistration $registration)
    {
        $registration->delete();

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'Admin deleted event registration',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details'    => "Registration ID: {$registration->id}",
        ]);

        return back()->with('success', 'Registro de evento eliminado.');
    }

    // ── Collaboration Requests ─────────────────────────────────

    public function collaborations(Request $request)
    {
        $query = CollaborationRequest::with(['sender', 'receiver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->whereHas('sender', fn($b) => $b->where('name', 'like', "%$q%"))
                ->orWhereHas('receiver', fn($b) => $b->where('name', 'like', "%$q%"));
        }

        $collaborations = $query->latest()->paginate(20)->withQueryString();

        return view('admin.collaborations', compact('collaborations'));
    }

    public function destroyCollaboration(CollaborationRequest $collaboration)
    {
        $collaboration->delete();

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'Admin deleted collaboration request',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details'    => "Collaboration ID: {$collaboration->id}",
        ]);

        return back()->with('success', 'Solicitud de colaboración eliminada.');
    }

    // ── Projects ───────────────────────────────────────────────

    public function projects(Request $request)
    {
        $query = Project::with('owner');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where('title', 'like', "%$q%")
                ->orWhere('description', 'like', "%$q%");
        }

        $projects = $query->latest()->paginate(20)->withQueryString();

        return view('admin.projects', compact('projects'));
    }

    public function updateProjectStatus(Request $request, Project $project)
    {
        $data = $request->validate([
            'status' => ['required', 'in:planning,active,paused,completed'],
        ]);

        $project->status = $data['status'];
        $project->save();

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'Admin updated project status',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details'    => "Project #{$project->id} → {$data['status']}",
        ]);

        return back()->with('success', "Estado del proyecto \"{$project->title}\" actualizado.");
    }

    public function destroyProject(Project $project)
    {
        $title = $project->title;
        $project->delete();

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'Admin deleted project',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details'    => "Deleted project: $title",
        ]);

        return back()->with('success', "Proyecto \"$title\" eliminado.");
    }

    // ── Audit Logs ─────────────────────────────────────────────

    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->latest()->paginate(30)->withQueryString();

        return view('admin.audit-logs', compact('logs'));
    }
}
