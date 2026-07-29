<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Mostra il form di registrazione
    public function showRegister()
    {
        return view('auth.register');
    }

    // Gestisce l'invio del form di registrazione
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('posts.index')->with('success', 'Registrazione completata con successo!');
    }

    // Mostra il form di login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Gestisce l'invio del form di login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Previene attacchi di Session Fixation
            return redirect()->intended(route('posts.index'))->with('success', 'Bentornato!');
        }

        return back()->withErrors([
            'email' => 'Le credenziali inserite non sono corrette.',
        ])->onlyInput('email');
    }

    // Gestisce il logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('posts.index')->with('success', 'Logout effettuato.');
    }
}