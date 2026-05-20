<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\EventRegistration;
use App\Models\EventTicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $registrations = Auth::user()->eventRegistrations()->with('category')->latest()->get();

        return view('events.index', compact('registrations'));
    }

    public function create()
    {
        $categories = EventTicketCategory::orderBy('price')->get();

        return view('events.register', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:event_ticket_categories,id'],
            'ticket_type' => ['required', 'string', 'max:100'],
        ]);

        $registration = EventRegistration::create([
            'user_id' => Auth::id(),
            'category_id' => $data['category_id'],
            'ticket_type' => $data['ticket_type'],
            'status' => 'confirmed',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Event registration created',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => 'Registration ID: ' . $registration->id,
        ]);

        return redirect()->route('events.index')->with('success', 'Registro para MunichTech EXPO completado.');
    }
}
