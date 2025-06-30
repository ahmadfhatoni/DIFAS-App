@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="main-wrapper justify-content-center"> 
    <div class="bg-primary card p-4 position-relative" style="border-radius: 15px; width: 100%;">
        <div class="text-center mb-4">
            <h4 class="text-white fw-bold m-0">FORM GANTI PASSWORD</h4>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Password tidak bisa diubah!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card p-3 bg-secondary">
            <form action="{{ route('password.change.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary fw-semibold me-2">Ubah Password</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary fw-semibold">Batalkan</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
