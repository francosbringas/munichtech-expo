<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');

        $filters = [
            'q'      => $query,
            'type'   => $type,
            'role'   => $request->get('role', ''),
            'status' => $request->get('status', ''),
        ];

        $users = collect();
        $projects = collect();

        if ($type === 'all' || $type === 'users') {
            $users = User::query()
                ->where('is_active', true)
                ->when($query, function ($q) use ($query) {
                    $q->where(function ($b) use ($query) {
                        $b->where('name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%")
                            ->orWhere('company_name', 'like', "%{$query}%")
                            ->orWhere('interests', 'like', "%{$query}%");
                    });
                })
                ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
                ->limit(20)
                ->get();
        }

        if ($type === 'all' || $type === 'projects') {
            $projects = Project::query()
                ->where('admin_status', 'active')
                ->when($query, function ($q) use ($query) {
                    $q->where(function ($b) use ($query) {
                        $b->where('title', 'like', "%{$query}%")
                            ->orWhere('description', 'like', "%{$query}%")
                            ->orWhere('tags', 'like', "%{$query}%");
                    });
                })
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
                ->with('owner')
                ->limit(20)
                ->get();
        }

        return view('search.results', compact('users', 'projects', 'query', 'type', 'filters'));
    }
}
