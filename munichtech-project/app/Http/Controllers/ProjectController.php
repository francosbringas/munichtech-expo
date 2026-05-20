<?php

namespace App\Http\Controllers;

use App\Models\CollaborationRequest;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Project::query()
            ->where(function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhereHas('members', fn ($m) => $m->where('user_id', $user->id));
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->with('owner')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $matchmakingSuggestions = User::findMatchmakingSuggestions($user);

        return view('projects.index', compact('projects', 'matchmakingSuggestions'));
    }

    public function create(Request $request)
    {
        $collaboration = null;
        $partner = null;

        if ($request->filled('collaboration_id')) {
            $collaboration = CollaborationRequest::with(['sender', 'receiver'])
                ->where('status', 'accepted')
                ->where(function ($q) {
                    $q->where('sender_id', Auth::id())
                        ->orWhere('receiver_id', Auth::id());
                })
                ->findOrFail($request->collaboration_id);

            $partner = $collaboration->sender_id === Auth::id()
                ? $collaboration->receiver
                : $collaboration->sender;
        }

        return view('projects.create', compact('collaboration', 'partner'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                    => ['required', 'string', 'max:255'],
            'description'              => ['required', 'string', 'min:10'],
            'status'                   => ['required', 'in:planning,active,paused,completed'],
            'tags'                     => ['nullable', 'string', 'max:500'],
            'collaboration_request_id' => ['nullable', 'exists:collaboration_requests,id'],
        ]);

        if (! empty($validated['collaboration_request_id'])) {
            $collaboration = CollaborationRequest::where('id', $validated['collaboration_request_id'])
                ->where('status', 'accepted')
                ->where(function ($q) {
                    $q->where('sender_id', Auth::id())
                        ->orWhere('receiver_id', Auth::id());
                })
                ->firstOrFail();
        }

        $project = Project::create([
            'owner_id'                 => Auth::id(),
            'collaboration_request_id' => $validated['collaboration_request_id'] ?? null,
            'title'                    => $validated['title'],
            'description'              => $validated['description'],
            'status'                   => $validated['status'],
            'tags'                     => $validated['tags'] ?? null,
        ]);

        ProjectMember::create([
            'project_id' => $project->id,
            'user_id'    => Auth::id(),
            'role'       => 'owner',
        ]);

        if (isset($collaboration)) {
            $partnerId = $collaboration->sender_id === Auth::id()
                ? $collaboration->receiver_id
                : $collaboration->sender_id;

            ProjectMember::firstOrCreate(
                ['project_id' => $project->id, 'user_id' => $partnerId],
                ['role' => 'contributor']
            );
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        if (! $project->hasUser(Auth::user())) {
            abort(403, 'You do not have access to this project.');
        }

        $project->load(['owner', 'members', 'milestones.tasks.assignee']);

        return view('projects.show', compact('project'));
    }
}
