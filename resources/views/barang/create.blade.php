@extends('layouts.app')

@section('title', 'Form Tambah Barang')

@section('content')
<div class="main-wrapper justify-content-center"> 
    <div class="bg-primary card p-4 position-relative" style="border-radius: 15px; width: 100%;">
        <div class="text-center mb-4">
            <h4 class="text-white fw-bold m-0">FORM TAMBAH BARANG</h4>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
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
        <div class="card p-3 bg-secondary">
            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Foto Barang (JPG, JPEG, PNG)</label>
                    <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori Barang</label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga Sewa Rp.</label>
                    <input type="number" name="harga_sewa" class="form-control" min="1" value="{{ old('harga_sewa') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" class="form-control" min="1" value="{{ old('stok') }}" required>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary fw-semibold me-2">Simpan</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary fw-semibold">Batalkan</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
