<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    //  Standard registration
    public function showRegister()
    {
        return view('auth.register', [
            'roles' => User::ROLES,
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'          => ['required', 'string', 'in:' . implode(',', User::ROLES)],
            'company_name'  => ['nullable', 'string', 'max:255'],
            'phone_prefix'  => ['nullable', 'string', 'max:10'],
            'phone_number'  => ['nullable', 'string', 'max:30'],
            'interests'     => ['nullable', 'string', 'max:255'],
            'bio'           => ['nullable', 'string', 'max:2000'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        //combine prefix + number into a single phone field
        $phone = null;
        if (!empty($data['phone_number'])) {
            $prefix = $data['phone_prefix'] ?? '';
            $phone  = trim($prefix . ' ' . $data['phone_number']);
        }

        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'role'         => $data['role'],
            'company_name' => $data['company_name'] ?? null,
            'phone'        => $phone,
            'interests'    => $data['interests'] ?? null,
            'bio'          => $data['bio'] ?? null,
            'password'     => Hash::make($data['password']),
        ]);

        Auth::login($user);

        AuditLog::create([
            'user_id'    => $user->id,
            'action'     => 'User registered',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details'    => 'Role: ' . $user->role,
        ]);

        return redirect()->route('dashboard')->with('success', 'Welcome to MunichTech EXPO!');
    }

    //standard login
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($data, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'The credentials do not match our records.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'User login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    //logout
    public function logout(Request $request)
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'User logout',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Session closed successfully.');
    }

    //google OAuth – redirect
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    //google OAuth – callback
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google authentication failed. Please try again.']);
        }

        //1. try to find an existing user with this google_id
        $user = User::where('google_id', $googleUser->getId())->first();

        //2. if not found by google_id, try by email (link accounts)
        if (! $user) {
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                //link existing account to Google
                $user->google_id = $googleUser->getId();
                $user->save();
            }
        }

        //3. if still not found, create a new user
        if (! $user) {
            $user = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'role'      => 'Attendee',   //default role; user can change later
                'password'  => Hash::make(\Illuminate\Support\Str::random(32)), //unusable password
            ]);

            AuditLog::create([
                'user_id'    => $user->id,
                'action'     => 'User registered via Google',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details'    => 'Google ID: ' . $googleUser->getId(),
            ]);
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        AuditLog::create([
            'user_id'    => $user->id,
            'action'     => 'User login via Google',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->intended(route('dashboard'));
    }
}
