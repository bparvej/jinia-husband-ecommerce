<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user()->load('role');
            if ($user->role && in_array($user->role->name, ['super_admin', 'admin', 'manager'])) {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect('/');
        }

        return view('pages.login', [
            'title' => 'Login — HomeI Admin',
            'error' => null
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user()->load('role');
            
            // Log last login timestamp
            $user->last_login = now();
            $user->save();

            Log::info("User logged in: " . $user->email);

            if ($user->role && in_array($user->role->name, ['super_admin', 'admin', 'manager'])) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect('/');
        }

        Log::warning("Login failed for: " . $request->email);

        return view('pages.login', [
            'title' => 'Login — HomeI Admin',
            'error' => 'Invalid email or password'
        ]);
    }

    public function logout(Request $request)
    {
        $email = Auth::user() ? Auth::user()->email : 'Unknown';
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info("User logged out: " . $email);

        return redirect('/login');
    }
}
