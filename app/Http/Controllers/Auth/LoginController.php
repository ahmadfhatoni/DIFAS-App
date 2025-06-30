<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\User;

/**
 * Login Controller
 * 
 * Handles user authentication functionality
 */
class LoginController extends Controller
{
    /**
     * Path for redirect after successful login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showLoginForm(Request $request): View
    {
        // Check if coming due to session expired
        if ($request->session()->has('session_expired')) {
            Session::flash('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        return view('auth.login');
    }

    /**
     * Handle login request manually.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            logger('Login success: ' . Auth::user()->email);
            return redirect()->intended($this->redirectTo);
        }

        // Log info for debugging
        Log::info('Login failed for email: ' . $request->email);
        Log::info('User in DB: ', [User::where('email', $request->email)->first()]);

        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * Logout user from application.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    /**
     * Determine the field used for login.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Handle authenticated user redirect based on role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'owner') {
            return redirect()->route('dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        // Fallback if role not recognized
        Auth::logout();
        return redirect()->route('login')->withErrors([
            'email' => 'Akun tidak memiliki hak akses yang valid.',
        ]);
    }
}
