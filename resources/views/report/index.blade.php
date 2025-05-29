@extends('layouts.app')
@section('title', 'Report Pesanan')

@section('content')
<div class="main-wrapper justify-content-center"> 
    <div class="bg-primary card p-4 position-relative" style="border-radius: 15px; width: 100%;">

        <!-- Form gabungan search dan filter tanggal -->
        <form method="GET" action="{{ route('report.index') }}">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
                
                <!-- Judul -->
                <div class="flex-grow-1">
                    <h4 class="text-white fw-bold m-0">REPORT PESANAN</h4>
                </div>

                <!-- Input tanggal mulai dan akhir -->
                <div class="d-flex gap-2 flex-wrap">
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="form-control" style="width: 150px;">
                    <span class="text-white align-self-center fw-semibold">Sampai</span>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="form-control" style="width: 150px;">  
                    <button class="btn btn-primary fw-semibold" type="submit">Cari</button>
                    <a href="{{ route('report.index') }}" class="btn btn-secondary fw-semibold">Reset</a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle bg-white">
                <thead class="table-primary" style="position: sticky; top: 0;">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>ID Pesanan</th>
                        <th>Nama Pemesan</th>
                        <th>Kode Barang</th>
                        <th>Deskripsi</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanan as $index => $p)
                        @php
                            $total = $p->getTotalDariDetailAttribute();
                            $rowCount = $p->details->count();
                            
                            $totalRows = $rowCount + 2;
                        @endphp
            
                        {{-- Pesanan --}}
                        <tr>
                            <td rowspan="{{ $totalRows }}">{{ $index + 1 }}</td>
                            <td rowspan="{{ $totalRows }}">{{ \Carbon\Carbon::parse($p->tanggal_acara)->format('d-m-Y') }}</td>
                            <td rowspan="{{ $totalRows }}">{{ $p->id }}</td>
                            <td rowspan="{{ $totalRows }}">{{ $p->nama_pemesan }}</td>
                            <td colspan="4" style="height: 10px;"></td>
                            <td rowspan="{{ $totalRows }}">Rp{{ number_format($total) }}</td>
                        </tr>
            
                        {{-- Detail pesanan --}}
                        @foreach ($p->details as $d)
                            <tr>
                                <td>{{ $d->barang_id }}</td>
                                <td>{{ $d->nama_barang }}</td>
                                <td>{{ $d->jumlah }}</td>
                                <td>Rp{{ number_format($d->subtotal) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" style="height: 10px;"></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">Belum ada data pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        
        <div class="text-center mt-4">
            <a href="{{ route('report.penyewaan.pdf', 
            ['tanggal_mulai' => request('tanggal_mulai'),'tanggal_akhir' => request('tanggal_akhir')]) }}" 
            class="btn btn-primary fw-semibold">Cetak</a>
        </div>
    </div>
</div>
@endsection
