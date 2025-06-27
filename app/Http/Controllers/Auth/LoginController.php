<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Path untuk redirect setelah login berhasil.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Buat instance controller baru.
     * Hanya guest (belum login) yang dapat mengakses halaman login,
     * kecuali untuk proses logout.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Tampilkan form login.
     *
     * @return \Illuminate\View\View
     
    *public function showLoginForm(): View
    *{
    *    return view('auth.login');
    *}
    */
    public function showLoginForm(Request $request)
    {
        // Cek apakah datang karena session expired
        if ($request->session()->has('session_expired')) {
            Session::flash('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        return view('auth.login');
    }

    /**
     * Tangani permintaan login secara manual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            logger('Login success: ' . Auth::user()->email); // Log untuk debugging
            return redirect()->intended($this->redirectTo);
        }

        // Log info untuk debugging
        Log::info('Login failed for email: ' . $request->email);
        Log::info('User in DB: ', [User::where('email', $request->email)->first()]);

        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * Logout user dari aplikasi.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    /**
     * Tentukan field yang digunakan untuk login.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    // Atau jika pakai method
    protected function authenticated(Request $request, $user)
{
    if ($user->role === 'owner') {
        return redirect()->route('dashboard'); // atau route khusus owner
    } elseif ($user->role === 'admin') {
        return redirect()->route('dashboard');
    }

    // fallback jika role tidak dikenali
    Auth::logout();
    return redirect()->route('login')->withErrors([
        'email' => 'Akun tidak memiliki hak akses yang valid.',
    ]);
}

}
