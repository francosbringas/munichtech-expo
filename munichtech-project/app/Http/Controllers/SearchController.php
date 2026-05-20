<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'query' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'in:' . implode(',', User::ROLES)],
            'company_name' => ['nullable', 'string', 'max:255'],
            'project_status' => ['nullable', 'string', 'in:planning,active,paused,completed'],
        ]);

        $users = User::query();
        $projects = Project::query();

        if (! empty($filters['query'])) {
            $users->where(function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['query'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['query'] . '%');
            });

            $projects->where(function ($query) use ($filters) {
                $query->where('title', 'like', '%' . $filters['query'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['query'] . '%');
            });
        }

        if (! empty($filters['role'])) {
            $users->where('role', $filters['role']);
        }

        if (! empty($filters['company_name'])) {
            $users->where('company_name', 'like', '%' . $filters['company_name'] . '%');
            $projects->where('company_name', 'like', '%' . $filters['company_name'] . '%');
        }

        if (! empty($filters['project_status'])) {
            $projects->where('status', $filters['project_status']);
        }

        $users = $users->orderBy('name')->limit(50)->get();
        $projects = $projects->with('owner')->orderBy('updated_at', 'desc')->limit(50)->get();

        return view('search.results', compact('users', 'projects', 'filters'));
    }
}
