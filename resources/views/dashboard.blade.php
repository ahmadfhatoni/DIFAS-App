@extends('layouts.app')

@section('title', 'Dashboard DIFAS App')

@section('content')
<div class="main-wrapper justify-content-center">

    {{-- Kartu Statistik --}}
    <div class="row text-white mb-4">
        <div class="col-sm-12 col-md-4 mb-3">
            <div class="bg-primary p-4 rounded shadow d-flex justify-content-between align-items-center">
                <div>
                    <h6>Total Barang</h6>
                    <h2>{{ $totalItem }}</h2>
                </div>
                <img src="{{ asset('logo/BARANG.png') }}" alt="Barang Icon" style="width: 40px; height: 40px;">
            </div>
        </div>
        <div class="col-sm-12 col-md-4 mb-3">
            <div class="bg-primary p-4 rounded shadow d-flex justify-content-between align-items-center">
                <div>
                    <h6>Total Transaksi Bulan Ini</h6>
                    <h2>{{ $totalTransaksi }}</h2>
                </div>
                <img src="{{ asset('logo/TRANSAKSI.png') }}" alt="Transaksi Icon" style="width: 40px; height: 40px;">
            </div>
        </div>
        <div class="col-sm-12 col-md-4 mb-3">
            <div class="bg-primary p-4 rounded shadow d-flex justify-content-between align-items-center">
                <div>
                    <h6>Pendapatan Bulan Ini</h6>
                    <h2>Rp. {{ number_format($omset, 0, ',', '.') }}</h2>
                </div>
                <img src="{{ asset('logo/PENDAPATAN.png') }}" alt="Pendapatan Icon" style="width: 40px; height: 40px;">
            </div>
        </div>
    </div>

    {{-- Pesanan 7 Hari ke Depan --}}
    <div class="bg-primary p-4 rounded shadow mb-4">
        <h6 class="fw-semibold mb-3">Pesanan 7 Hari ke Depan</h6>

        @if ($upcomingPesanan->isEmpty())
            <p class="text-mute text-white">Tidak ada pesanan dalam 7 hari ke depan.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle bg-white">
                    <thead class="table-primary" style="position: sticky; top: 0;">
                        <tr>
                            <th>Tanggal Acara</th>
                            <th>Nama Pemesan</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($upcomingPesanan as $pesanan)
                            <tr onclick="window.location='{{ route('invoice.show', $pesanan->id) }}';" style="cursor:pointer;">
                                <td>{{ \Carbon\Carbon::parse($pesanan->tanggal_acara)->translatedFormat('d M Y') }}</td>
                                <td>{{ $pesanan->nama_pemesan }}</td>
                                <td>{{ $pesanan->no_telepon }}</td>
                                <td>{{ $pesanan->alamat }}</td>
                                <td>
                                    <span class="badge bg-{{ $pesanan->status == 'Selesai' ? 'success' : 'warning' }}">
                                        {{ $pesanan->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>


    {{-- Chart Row --}}
    <div class="row mb-4">
        <div class="col-sm-12 col-md-6 mb-3">
            <div class="bg-primary p-4 rounded shadow text-white">
                <h6>Statistik Kategori</h6>
                <div style="position: relative; height:300px;">
                    <canvas id="kategoriChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-3">
            <div class="bg-primary p-4 rounded shadow text-white">
                <h6>Statistik Barang</h6>
                <div style="position: relative; height:300px;">
                    <canvas id="barangChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Penyewaan --}}
    <div class="bg-primary p-4 rounded shadow text-white mb-3">
        <h6>Data Penyewaan</h6>
        <div style="position: relative; height:300px;">
            <canvas id="penyewaanChart"></canvas>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: 'white' } },
            tooltip: { bodyColor: 'white', titleColor: 'white' }
        },
        scales: {
            x: {
                ticks: { color: 'white' },
                grid: { color: 'rgba(255,255,255,0.2)' }
            },
            y: {
                ticks: { color: 'white' },
                grid: { color: 'rgba(255,255,255,0.2)' }
            }
        }
    };

    // Statistik Kategori
    new Chart('kategoriChart', {
        type: 'bar',
        data: {
            labels: {!! json_encode($kategoriStat->pluck('kategori')) !!},
            datasets: [{ label:'Jenis', data:{!! json_encode($kategoriStat->pluck('total')) !!}, backgroundColor:['white'] }]
        },
        options: chartOptions
    });

    // Statistik Barang
    new Chart('barangChart', {
        type: 'bar',
        data: {
            labels: {!! json_encode($barangStat->pluck('nama_barang')) !!},
            datasets: [{ label:'Disewa', data:{!! json_encode($barangStat->pluck('total')) !!}, backgroundColor:'white' }]
        },
        options: chartOptions
    });

    // Data Penyewaan Bulanan
    const bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const penyewaanData = Array(12).fill(0);
    @foreach ($penyewaanStat as $stat)
      penyewaanData[{{ $stat->bulan - 1 }}] = {{ $stat->total }};
    @endforeach
    new Chart('penyewaanChart', {
        type: 'bar',
        data: { labels: bulanLabels, datasets:[{ label:'Penyewaan', data: penyewaanData, backgroundColor:['White'] }]},
        options: chartOptions
    });
});
</script>
@endpush
