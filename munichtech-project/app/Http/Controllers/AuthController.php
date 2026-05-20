<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register', [
            'roles' => User::ROLES,
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'role'          => ['required', 'in:' . implode(',', User::ROLES)],
            'company_name'  => ['nullable', 'string', 'max:255'],
            'phone_prefix'  => ['nullable', 'string', 'max:6'],
            'phone_number'  => ['nullable', 'string', 'max:20'],
            'interests'     => ['nullable', 'string', 'max:500'],
            'bio'           => ['nullable', 'string', 'max:1000'],
        ]);

        $phone = null;
        if (! empty($validated['phone_number'])) {
            $prefix = $validated['phone_prefix'] ?? '+49';
            $phone = trim($prefix . ' ' . $validated['phone_number']);
        }

        $user = User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'role'         => $validated['role'],
            'company_name' => $validated['company_name'] ?? null,
            'phone'        => $phone,
            'interests'    => $validated['interests'] ?? null,
            'bio'          => $validated['bio'] ?? null,
            'is_active'    => true,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful. Welcome to MunichTech EXPO!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && ! $user->is_active) {
            return back()->withErrors([
                'email' => 'Your account is deactivated. Please contact the administrator.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Signed in successfully.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials are invalid.',
        ])->onlyInput('email');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Google authentication failed.']);
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update(['google_id' => $googleUser->getId()]);
            } else {
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'role'      => 'Attendee',
                    'password'  => Hash::make(uniqid('', true)),
                    'is_active' => true,
                ]);
            }
        }

        if (! $user->is_active) {
            return redirect()->route('login')->withErrors(['error' => 'Your account is deactivated.']);
        }

        Auth::login($user, remember: true);

        return redirect()->route('dashboard')->with('success', 'Google sign-in successful.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Signed out successfully.');
    }
}
