<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades
Auth;

class EventRegistrationController extends Controller
{
    public function index()
    {
        $registrations = Auth::user()->eventRegistrations()->paginate(10);

        return view('events.index', compact('registrations'));
    }

    public function create()
    {
        $categories = ['free' => 'Entrada Gratuita', 'startup' => 'Startup', 'investor' => 'Inversionista', 'company' => 'Empresa', 'hackathon' => 'Hackathon'];

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
            return back()->withErrors(['ticket_category' => 'Ya te has registrado para esta categoría.']);
        }

        EventRegistration::create([
            'user_id'                => Auth::id(),
            'ticket_category'        => $validated['ticket_category'],
            'special_requirements'   => $validated['special_requirements'],
            'confirmed_at'           => now(),
        ]);

        return redirect()->route('events.index')->with('success', 'Registro de evento completado.');
    }
}
