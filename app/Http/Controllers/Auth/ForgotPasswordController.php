<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use DB;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Kirim link reset password ke email.
     * Hanya email admin yang diizinkan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Cek apakah email ada di tabel users
        $emailExists = DB::table('users')->where('email', $request->email)->exists();

        if (! $emailExists) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar di sistem.',
            ]);
        }
        // Kirim link reset password
        $response = Password::sendResetLink(
            $request->only('email')
        );

        return $response === Password::RESET_LINK_SENT
            ? back()->with('status', __($response))
            : back()->withErrors(['email' => __($response)]);
    }
}
