<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    // -------------------------
    // Login Method
    // -------------------------
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, $request->remember)) {
        $user = Auth::user();

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            return redirect()->back()->withErrors([
                'email' => 'You must verify your email before logging in.',
            ])->withInput();
        }

        // Remember me cookies (optional)
        if ($request->remember) {
            Cookie::queue('userEmail', $request->email, 120);
            Cookie::queue('userPassword', $request->password, 120);
        }

        // ✅ Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('home_admin')->with('success', 'Welcome back Admin 🚀');
        } else {
            return redirect()->route('home_customer')->with('success', 'Welcome back 🎮');
        }
    }

    // Invalid credentials
    return redirect()->back()->withErrors([
        'email' => 'Invalid Credentials! Log In with Registered Email and Password',
        'password' => 'Invalid Credentials! Log In with Registered Email and Password'
    ])->withInput();
}


    // -------------------------
    // Logout Method
    // -------------------------
    public function logout()
    {
        Auth::logout();
        Cookie::queue(Cookie::forget('userEmail'));
        Cookie::queue(Cookie::forget('userPassword'));
        return redirect()->route('home_guest');
    }

    // -------------------------
    // Registration Method
    // -------------------------
    public function register_logic(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'role' => 'customer',
        ]);

        // Send email verification
        event(new Registered($user));

        return redirect()->route('login_page')->with('success', 'Registration successful! Please check your email to verify your account.');
    }
}
