<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Process the login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
            ],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'email' => 'The email or password is incorrect.',
                ])
                ->onlyInput('email');
        }

        // Regenerate the session after successful login.
        $request->session()->regenerate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | CEO/Admin
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'CEO/Admin') {
            return redirect()->route('admin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Finance
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'Finance') {
            return redirect()->route('finance.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Procurement
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'Procurement') {
            return redirect()->route('procurement.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Invalid Role
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'Your account does not have a valid BiteSync role.',
            ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}