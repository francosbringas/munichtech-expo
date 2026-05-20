<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CollaborationRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->is_admin, 403);

        $totalUsers = User::count();
        $totalProjects = Project::count();
        $activeCollaborations = CollaborationRequest::where('status', 'accepted')->count();
        $recentLogs = AuditLog::with('user')->latest()->limit(10)->get();

        return view('admin.dashboard', compact('totalUsers', 'totalProjects', 'activeCollaborations', 'recentLogs'));
    }
}
