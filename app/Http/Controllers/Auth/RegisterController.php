<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return redirect()->route('dashboard')->with('success', 'User registered successfully.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,owner'],
        ]);
    }

    protected function create(array $data)
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        Log::info('Creating user', $data);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    }
}
