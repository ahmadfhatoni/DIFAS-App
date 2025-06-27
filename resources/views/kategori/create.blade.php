@extends('layouts.app')

@section('title', 'Form Tambah Kategori')

@section('content')
<div class="main-wrapper justify-content-center"> 
    <div class="bg-primary card p-4 position-relative" style="border-radius: 15px; width: 100%;">

        <div class="text-center mb-4">
            <h4 class="text-white fw-bold m-0">FORM TAMBAH KATEGORI</h4>
        </div>

        <!-- Alert Success -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Alert Error -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Data tidak bisa disimpan!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form -->
        <div class="card p-3 bg-secondary">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary fw-semibold">Simpan</button>
                    <button type="reset" class="btn btn-secondary fw-semibold">Batalkan</button>                           
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
