@extends('layouts.app')
@section('title', 'List Data Barang')
@section('content')
<div class="main-wrapper justify-content-center"> 
    <div class="bg-primary card p-4 position-relative" style="border-radius: 15px; width: 100%;">
        <form method="GET" action="{{ route('barang.index') }}">
            <div class="d-flex align-items-center justify-content-between gap-3 mb-4">
                <!-- Judul -->
                <div class="flex-grow-1">
                    <h4 class="text-white fw-bold m-0">LIST DATA BARANG</h4>
                </div>
        
                <!-- Input search nama & tombol -->
                <div class="d-flex gap-2" style="max-width: 400px;">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari barang">
                    <button class="btn btn-primary fw-semibold" type="submit">Cari</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary fw-semibold">Reset</a>
                </div>
            </div>
        </form>

        <!-- Alert Success -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Scrollable Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle bg-white">
                <thead class="table-primary" style="position: sticky; top: 0;">
                    <tr>
                        <th>No</th>
                        <th>ID Barang</th>
                        <th>Foto Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori Barang</th>
                        <th>Harga Sewa</th>
                        <th>Stok yang Ada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barang as $item)
                        <tr class="align-middle text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->id }}</td>
                            <td>
                                @if ($item->foto)
                                    <img src="{{ asset($item->foto) }}" width="100" height="150" style="object-fit:cover;">
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kategori }}</td>
                            <td>Rp. {{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>
                                <!-- Button Edit -->
                                <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-sm btn-secondary mb-3 fw-semibold">Edit</a>

                                <!-- Button Hapus -->
                                <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-primary mb-3 fw-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tombol Tambah Barang -->
        <div class="mt-4 text-center">
            <a href="{{ route('barang.tambah') }}" class="btn btn-primary mb-3 fw-semibold">Tambah Barang</a>
        </div>
        
    </div>
</div>
@endsection
