@extends('layouts.app')

@section('title', 'Invoice Pesanan')

@section('content')
<div class="main-wrapper justify-content-center"> 
    <div class="bg-primary card p-4 position-relative" style="border-radius: 15px; width: 100%;">

        <div class="text-center mb-4">
            <h4 class="text-white fw-bold m-0">DETAIL PESANAN</h4>
        </div>

        <div class="bg-light text-dark p-4 rounded">
            {{-- Header --}}
            <div class="row align-items-center gx-4">
                <div class="col-3 text-end px-3">
                    <img src="{{ asset('/logo/lOGO.png') }}" alt="Logo" width="160">
                </div>

                <div class="col-6 text-center px-3">
                    <h5 class="fw-bold">INVOICE PEMBAYARAN</h5>
                    <div style="height: 2px; background-color: #000; width: 100%; margin: 20px 0;"></div>
                    <p class="mt-0 mb-0">
                        Jl. Suharso, Jungke RT 01/ RW 01 Karanganyar (Lor Kelurahan Jungke)<br>
                        085103032721
                    </p>
                </div>

                <div class="col-3 text-start px-3">
                    <p class="mb-1 fw-bold">Menyediakan:</p>
                    <p class="mb-0">
                        <span>- Tenda Pernikahan</span><br>
                        <span>- Kursi</span><br>
                        <span>- Alat Pesta</span><br>
                        <span>- Soundsystem</span>
                    </p>
                </div>
            </div>

            <div style="height: 2px; background-color: #000; width: 100%; margin: 20px 0;"></div>

            {{-- Informasi Pesanan --}}
            <div class="row mb-4 px-3">
                <div class="col-md-4 mb-2 text-center">
                    <strong>Nomor Pesanan:</strong> {{ $pesanan->id }}
                </div>
                <div class="col-md-4 mb-2">
                    <strong>Data Penyewa:</strong><br>
                    <span style="font-weight: 600;">Nama: </span> {{ $pesanan->nama_pemesan }}<br>
                    <span style="font-weight: 600;">No Telepon: </span> {{ $pesanan->no_telepon }}<br>
                    <span style="font-weight: 600;">Alamat: </span> {{ $pesanan->alamat }}
                </div>
                <div class="col-md-4 mb-2 text-center ">
                    <strong>Tanggal Acara:</strong> {{ \Carbon\Carbon::parse($pesanan->tanggal_acara)->format('d/m/Y') }}
                </div>
            </div>

            {{-- Tabel Detail Pesanan --}}
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Sewa</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($pesanan->details as $index => $item)
                        @php $total += $item->subtotal; @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->barang_id }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Total --}}
            <table class="table table-bordered w-100">
                <tr>
                    <td class="text-start"><strong>Jumlah Item Barang:</strong> {{ count($pesanan->details) }}</td>
                    <td class="text-end"><strong>Total:</strong> Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </table>

            {{-- Catatan --}}
            <p class="mt-3 px-2"><strong>Perhatian:</strong><br>Barang rusak, pecah, hilang, tanggungan penyewa <strong>(mengganti)</strong></p>
        </div>

        {{-- Tombol Cetak --}}
        <div class="text-center mt-3">
            <a href="{{ route('invoice.cetak', $pesanan->id) }}" class="btn btn-primary mb-3 fw-semibold" target="_blank">Cetak</a>
            <a href="{{ route('pesanan.index') }}" class="btn btn-secondary mb-3 fw-bold">Kembali</a>
        </div>

    </div>
</div>
@endsection
