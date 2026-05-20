<?php

namespace App\Http\Controllers;

use App\Models\CollaborationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollaborationController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $sentRequests = CollaborationRequest::where('sender_id', $userId)
            ->with('receiver')
            ->latest()
            ->paginate(10, ['*'], 'sent');

        $receivedRequests = CollaborationRequest::where('receiver_id', $userId)
            ->with('sender')
            ->latest()
            ->paginate(10, ['*'], 'received');

        return view('collaborations.index', compact('sentRequests', 'receivedRequests'));
    }

    public function create(Request $request)
    {
        $preselectedReceiverId = $request->get('receiver_id');

        $users = User::where('id', '!=', Auth::id())
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            })
            ->when($request->filled('role'), function ($q) use ($request) {
                $q->where('role', $request->role);
            })
            ->paginate(12)
            ->withQueryString();

        return view('collaborations.create', compact('users', 'preselectedReceiverId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'message'     => ['nullable', 'string', 'max:500'],
        ]);

        if ($validated['receiver_id'] == Auth::id()) {
            return back()->withErrors(['receiver_id' => 'You cannot collaborate with yourself.']);
        }

        $existing = CollaborationRequest::where('sender_id', Auth::id())
            ->where('receiver_id', $validated['receiver_id'])
            ->first();

        if ($existing) {
            return back()->withErrors(['receiver_id' => 'You have already sent a request to this user.']);
        }

        CollaborationRequest::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'message'     => $validated['message'],
        ]);

        return redirect()->route('collaborations.index')
            ->with('success', 'Collaboration request sent.');
    }

    public function respond(Request $request, CollaborationRequest $collaboration)
    {
        if ($collaboration->receiver_id != Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'action' => ['required', 'in:accept,reject'],
        ]);

        if ($validated['action'] === 'accept') {
            $collaboration->accept();
            return back()->with('success', 'Collaboration request accepted.');
        } else {
            $collaboration->reject();
            return back()->with('success', 'Collaboration request rejected.');
        }
    }
}
