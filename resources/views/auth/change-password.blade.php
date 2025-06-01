@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div class="main-wrapper justify-content-center"> 
    <div class="bg-primary card p-4 position-relative" style="border-radius: 15px; background-color: #3066BE; width: 100%;">

    <div class="container">
        <div class="text-center mb-4">
            <h4 class="text-white fw-bold m-0">GANTI PASSWORD</h4>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-0">
                <div class="card p-4 bg-secondary">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.change.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" name="new_password" required minlength="8">
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" name="new_password_confirmation" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary fw-semibold">Ubah Password</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary fw-semibold">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
