<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
            public function store(LoginRequest $request): RedirectResponse
        {
            $request->authenticate();
            $request->session()->regenerate();

            $user = auth()->user();

            // Agar role_id 1 hai, toh dashboard par bhejo
            if ($user->role_id == 1) {
                return redirect()->intended(route('admin.dashboard')); // Yahan apna exact dashboard route name dein
            }

            // Baqi sabhi users ko home page par bhejo aur session se intended URL clear kar do taake wo dashboard na ja sakein
            return redirect()->to('/');
        }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}