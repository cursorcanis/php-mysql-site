<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Ports index.php (login) + logout.php.
class AuthController extends Controller
{
    public function show(Request $request)
    {
        if ($request->session()->get('vault_auth')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $userOk = hash_equals(config('vault.user'), $data['username']);
        $passOk = password_verify($data['password'], (string) config('vault.pass_hash'));

        if ($userOk && $passOk) {
            $request->session()->regenerate();
            $request->session()->put('vault_auth', true);

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withInput($request->only('username'))
            ->withErrors(['login' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('vault_auth');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
