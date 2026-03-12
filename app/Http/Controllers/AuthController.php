<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user()->role);
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    private function redirectByRole(string $role)
    {
        if ($role === 'admin') {
            return redirect()->route('participants.index');
        }
        if ($role === 'scanner') {
            return redirect()->route('scan.index');
        }
        if ($role === 'user') {
            // Buscar participante por correo
            $user = Auth::user();
            $participant = \App\Models\Participant::where('correo', $user->email)->first();
            if ($participant && $participant->qr_code) {
                return redirect()->route('visitors.dashboard', ['code' => $participant->qr_code]);
            }
            return redirect()->route('home')->with('error', 'No se encontró tu registro de participante.');
        }
        return redirect()->route('home');
    }
}
