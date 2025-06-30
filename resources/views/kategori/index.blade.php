@extends('layouts.app')

@section('title', 'List Data Kategori')

@section('content')
<div class="main-wrapper justify-content-center"> 
    <div class="bg-primary card p-4 position-relative" style="border-radius: 15px; width: 100%;">
        <div class="text-center mb-4">
            <h4 class="text-white fw-bold m-0">DATA KATEGORI</h4>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle bg-white">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategori as $index => $kat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $kat->nama }}</td>
                            <td>
                                <a href="{{ route('kategori.edit', $kat->id) }}" class="btn btn-sm btn-primary fw-semibold mb-1">Edit</a>
                                <button type="button" class="btn btn-sm btn-danger fw-semibold mb-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalHapusKategori"
                                    data-id="{{ $kat->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-muted text-center">Belum ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('kategori.tambah') }}" class="btn btn-sidebar fw-semibold">Tambah Kategori</a>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapusKategori" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="formHapusKategori">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHapusLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-danger fw-semibold px-4" style="flex: 1;">Hapus</button>
                    <button type="button" class="btn btn-primary fw-semibold px-4" style="flex: 1;" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script -->
<script>
    const modalHapus = document.getElementById('modalHapusKategori');
    modalHapus.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const form = modalHapus.querySelector('#formHapusKategori');
        form.action = `/kategori/${id}`; // Sesuaikan dengan route
    });
</script>
@endsection
