@extends('layouts.app')
@section('title', 'List Data Pesanan')

@section('content')
<div class="main-wrapper justify-content-center"> 
    @if(session('success'))
        <div class="alert alert-success mb-3">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger mb-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="card p-4" style="border-radius: 15px; background-color: #3066BE; width: 100%;">
        <!-- Filter & Search -->
        <form method="GET" action="{{ route('pesanan.index') }}">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
                <div class="flex-grow-1">
                    <h4 class="text-white fw-bold m-0">LIST DATA PESANAN</h4>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="form-control" style="width: 150px;">
                    <span class="text-white align-self-center fw-semibold">Sampai</span>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="form-control" style="width: 150px;">
                </div>
                <div class="d-flex gap-2" style="width: 300px;">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama pemesan">
                    <button class="btn btn-primary fw-semibold" type="submit">Cari</button>
                    <a href="{{ route('pesanan.index') }}" class="btn btn-secondary fw-semibold">Reset</a>
                </div>
            </div>
        </form>

        <!-- Tabel -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle bg-white">
                <thead class="table-primary" style="position: sticky; top: 0;">
                    <tr>
                        <th>No</th>
                        <th>ID Pesanan</th>
                        <th>Nama Pemesan</th>
                        <th>Tanggal Acara</th>
                        <th>Total Pesanan</th>
                        <th>Status</th>
                        <th>Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanans as $index => $pesanan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pesanan->id }}</td>
                            <td>{{ $pesanan->nama_pemesan }}</td>
                            <td>{{ \Carbon\Carbon::parse($pesanan->tanggal_acara)->format('d M Y') }}</td>
                            <td>Rp {{ number_format($pesanan->total_dari_detail, 0, ',', '.') }}</td>
                            <td>
                                @if($pesanan->status === 'Belum Selesai')
                                    <button class="btn btn-primary fw-semibold px-3 text-center" data-bs-toggle="modal" data-bs-target="#modalKonfirmasi" data-id="{{ $pesanan->id }}">
                                        Selesai / Batalkan
                                    </button>
                                @elseif($pesanan->status === 'Selesai')
                                    <span class="btn btn-secondary fw-semibold disabled px-3 text-center" style="width: 160px;">
                                        {{ $pesanan->status }}
                                    </span>
                                @elseif($pesanan->status === 'Dibatalkan')
                                    <span class="btn btn-danger fw-semibold disabled px-3 text-center" style="width: 160px;">
                                        {{ $pesanan->status }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($pesanan->status === 'Dibatalkan')
                                    <button class="btn btn-danger fw-semibold disabled" style="width: 100px;">Invoice</button>
                                @else
                                    <a href="{{ route('invoice.show', $pesanan->id) }}" class="btn btn-primary fw-semibold" style="width: 100px;">Invoice</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Belum ada data pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tombol Tambah -->
        <div class="mt-4 text-center">
            <a href="{{ route('pesanan.tambah') }}" class="btn btn-primary mb-3 fw-semibold">Tambah Pesanan</a>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('pesanan.updateStatus', 0) }}" id="formStatusPesanan">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" id="statusInput">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title " id="modalKonfirmasiLabel">Ubah Status Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p>Pesanan akan ditandai sebagai <strong>Selesai</strong> atau <strong>Batalkan</strong> pesanan ini</p>
                </div>
                <div class="modal-footer d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-primary fw-semibold px-4" style="flex: 1;" onclick="submitStatus('Selesai')">
                        Selesai
                    </button>
                    <button type="button" class="btn btn-danger fw-semibold px-4" style="flex: 1;" onclick="submitStatus('Dibatalkan')">
                        Batalkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script -->
<script>
    let formStatus = document.getElementById('formStatusPesanan');
    const statusInput = document.getElementById('statusInput');

    document.querySelectorAll('[data-bs-target="#modalKonfirmasi"]').forEach(button => {
        button.addEventListener('click', function () {
            const pesananId = this.getAttribute('data-id');
            formStatus.action = formStatus.action.replace(/\/\d+$/, '/' + pesananId);
        });
    });

    function submitStatus(status) {
        statusInput.value = status;
        formStatus.submit();
    }
</script>
@endsection