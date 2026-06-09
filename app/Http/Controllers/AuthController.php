<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isOwner() 
                ? redirect()->route('owner.dashboard') 
                : redirect()->route('fields.index');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isOwner()) {
                return redirect()->intended(route('owner.dashboard'))
                    ->with('success', 'Synchronized Owner Session Active.');
            }

            return redirect()->intended(route('fields.index'))
                ->with('success', 'Synchronized Player Session Active.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return Auth::user()->isOwner() 
                ? redirect()->route('owner.dashboard') 
                : redirect()->route('fields.index');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['required', 'in:player,owner'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        if ($user->isOwner()) {
            return redirect()->route('owner.dashboard')
                ->with('success', 'Welcome to raketinAJA Command Center. Let\'s setup your fields.');
        }

        return redirect()->route('fields.index')
            ->with('success', 'Recruit session established. Welcome to the Arena!');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if user exists to give realistic feedback
        $exists = User::where('email', $request->email)->exists();

        if ($exists) {
            return back()->with('success', 'A simulated secure recovery link has been dispatched to: ' . $request->email);
        }

        return back()->withErrors([
            'email' => 'Identity not found in the encrypted recovery database.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('fields.index')
            ->with('success', 'Securely logged out. Session terminated.');
    }
}
