<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->with('owner')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'status'      => ['required', 'in:planning,active,paused,completed'],
            'tags'        => ['nullable', 'string', 'max:500'],
        ]);

        $project = Project::create([
            'owner_id'   => Auth::id(),
            'title'      => $validated['title'],
            'description' => $validated['description'],
            'status'     => $validated['status'],
            'tags'       => $validated['tags'],
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Proyecto creado exitosamente.');
    }

    public function show(Project $project)
    {
        if (!$project->hasUser(Auth::user())) {
            abort(403, 'No tienes acceso a este proyecto.');
        }

        $project->load(['owner', 'members.user', 'milestones', 'tasks.assignee']);

        return view('projects.show', compact('project'));
    }
}
