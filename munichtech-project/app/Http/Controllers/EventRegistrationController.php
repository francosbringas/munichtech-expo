<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    public function index()
    {
        $registrations = Auth::user()->eventRegistrations()->latest()->paginate(10);

        return view('events.index', compact('registrations'));
    }

    public function create()
    {
        $categories = [
            'free'      => 'Free Entry',
            'startup'   => 'Startup Pass',
            'investor'  => 'Investor Pass',
            'company'   => 'Company Pass',
            'hackathon' => 'Hackathon Pass',
        ];

        return view('events.register', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_category'      => ['required', 'in:free,startup,investor,company,hackathon'],
            'special_requirements' => ['nullable', 'string', 'max:500'],
        ]);

        $existing = Auth::user()->eventRegistrations()
            ->where('ticket_category', $validated['ticket_category'])
            ->first();

        if ($existing) {
            return back()->withErrors(['ticket_category' => 'You are already registered for this category.']);
        }

        EventRegistration::create([
            'user_id'              => Auth::id(),
            'ticket_category'      => $validated['ticket_category'],
            'special_requirements' => $validated['special_requirements'] ?? null,
            'status'               => 'pending',
        ]);

        return redirect()->route('events.index')->with('success', 'Registration request submitted. Pending confirmation.');
    }
}
