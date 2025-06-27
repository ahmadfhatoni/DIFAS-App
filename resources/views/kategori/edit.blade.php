@extends('layouts.app')

@section('title', 'Form Edit Kategori')

@section('content')
<div class="main-wrapper justify-content-center"> 
    <div class="bg-primary card p-4 position-relative" style="border-radius: 15px; width: 100%;">

        <div class="text-center mb-4">
            <h4 class="text-white fw-bold m-0">FORM EDIT KATEGORI</h4>
        </div>

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

        <!-- Alert Success -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form -->
        <div class="card p-3 bg-secondary">
            <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">ID Kategori</label>
                    <input type="text" class="form-control" value="{{ $kategori->id }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $kategori->nama) }}" required>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary mb-3 fw-semibold">Simpan Perubahan</button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary mb-3 fw-semibold">Batalkan</a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
