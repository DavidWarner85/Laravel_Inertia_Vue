<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate
        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed']
        ]);
        // Register
        $user = User::create($fields);
        // Login
        Auth::login($user);
        // Redirect
        return redirect()
            ->route('dashboard')
            ->with('message', 'Welcome to Laravel Inertia Vue app');
    }
    public function login(Request $request)
    {
        // Validate
        $fields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        // Login if credentials correct
        if (Auth::attempt($fields, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
        // Return back if credentials fail
        return back()->withErrors([
            'email' => 'Invalid Email!',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        // Logout
        Auth::logout();
        // invalidate session
        $request->session()->invalidate();
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        // Redirect to Home
        return redirect()->route('home');
    }
}

