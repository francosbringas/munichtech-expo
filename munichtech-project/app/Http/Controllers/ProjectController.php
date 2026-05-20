<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CollaborationRequest;
use App\Models\Project;
use App\Models\ProjectMilestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{

    public function index()
    {
        $projects = Project::with('milestones.tasks')->where('owner_id', Auth::id())->latest()->get();

        return view('projects.index', compact('projects'));
    }

    public function create(Request $request)
    {
        $collaboration = null;

        if ($request->filled('collaboration_request_id')) {
            $collaboration = CollaborationRequest::where('id', $request->query('collaboration_request_id'))
                ->where('status', CollaborationRequest::STATUS_ACCEPTED)
                ->where(function ($query) {
                    $query->where('sender_id', Auth::id())->orWhere('receiver_id', Auth::id());
                })
                ->firstOrFail();
        }

        return view('projects.create', compact('collaboration'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'collaboration_request_id' => ['nullable', 'exists:collaboration_requests,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'progress' => ['required', 'integer', 'between:0,100'],
            'status' => ['required', 'in:planning,active,paused,completed'],
            'milestone_title' => ['nullable', 'string', 'max:255'],
            'milestone_description' => ['nullable', 'string', 'max:1000'],
            'milestone_due_date' => ['nullable', 'date'],
        ]);

        $project = Project::create([
            'owner_id' => Auth::id(),
            'collaboration_request_id' => $data['collaboration_request_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'],
            'company_name' => $data['company_name'] ?? null,
            'progress' => $data['progress'],
            'status' => $data['status'],
        ]);

        if (! empty($data['milestone_title'])) {
            ProjectMilestone::create([
                'project_id' => $project->id,
                'title' => $data['milestone_title'],
                'description' => $data['milestone_description'] ?? null,
                'due_date' => $data['milestone_due_date'] ?? null,
            ]);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Project created',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => 'Project ID: ' . $project->id,
        ]);

        return redirect()->route('projects.index')->with('success', 'Proyecto creado con éxito.');
    }

    public function show(Project $project)
    {
        abort_unless($project->owner_id === Auth::id(), 403);

        $project->load('milestones.tasks');

        return view('projects.show', compact('project'));
    }
}
