<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $loginValue = $fieldType === 'username' ? strtolower($credentials['login']) : $credentials['login'];

        if (Auth::attempt([$fieldType => $loginValue, 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }

    /**
     * Show the registration form.
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:30', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Account created successfully!');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Quick demo login for testing.
     */
    public function demoLogin(string $username): RedirectResponse
    {
        $user = User::where('username', strtolower($username))->first();

        if (! $user) {
            return redirect()->route('login')->withErrors(['login' => "Demo user @{$username} not found. Please run seeders."]);
        }

        Auth::login($user);

        return redirect()->route('home')->with('success', "Logged in as @{$user->username}!");
    }
}
