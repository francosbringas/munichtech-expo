<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CollaborationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollaborationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sent = Auth::user()->sentCollaborationRequests()->with('receiver')->latest()->get();
        $received = Auth::user()->receivedCollaborationRequests()->with('sender')->latest()->get();

        return view('collaborations.index', compact('sent', 'received'));
    }

    public function create()
    {
        $receivers = User::where('id', '!=', Auth::id())
            ->orderBy('role')
            ->orderBy('company_name')
            ->get();

        return view('collaborations.create', compact('receivers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => ['required', 'exists:users,id', 'not_in:' . Auth::id()],
            'message' => ['required', 'string', 'min:20', 'max:1200'],
        ]);

        $requestModel = CollaborationRequest::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $data['receiver_id'],
            'message' => $data['message'],
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Collaboration request created',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => 'Receiver ID: ' . $data['receiver_id'],
        ]);

        return redirect()->route('collaborations.index')->with('success', 'Solicitud de colaboración enviada.');
    }

    public function respond(Request $request, CollaborationRequest $collaboration)
    {
        abort_unless($collaboration->receiver_id === Auth::id(), 403);

        $data = $request->validate([
            'action' => ['required', 'in:accepted,rejected'],
        ]);

        $collaboration->status = $data['action'];
        $collaboration->responded_at = now();
        $collaboration->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Collaboration request ' . $data['action'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => 'Request ID: ' . $collaboration->id,
        ]);

        return redirect()->route('collaborations.index')->with('success', 'Solicitud de colaboración ' . $data['action'] . '.');
    }
}
