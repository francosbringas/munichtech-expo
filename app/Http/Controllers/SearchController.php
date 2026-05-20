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

        $users = [];
        $projects = [];

        if ($type === 'all' || $type === 'users') {
            $users = User::where('name', 'like', "%{$query}%")
                ->orWhere('company_name', 'like', "%{$query}%")
                ->when($request->filled('role'), function ($q) {
                    $q->where('role', request('role'));
                })
                ->limit(20)
                ->get();
        }

        if ($type === 'all' || $type === 'projects') {
            $projects = Project::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->when($request->filled('status'), function ($q) {
                    $q->where('status', request('status'));
                })
                ->with('owner')
                ->limit(20)
                ->get();
        }

        return view('search.results', compact('users', 'projects', 'query', 'type'));
    }
}
