<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  
    }

    public function index()
    {

        $now = Carbon::now();

        // Total Item
        $totalItem = Barang::count();

        // Total Transaksi bulan ini
        $totalTransaksi = Pesanan::whereMonth('tanggal_acara', $now->month)
                                ->whereYear('tanggal_acara', $now->year)
                                ->count();

        // Omset bulan ini (hanya dari pesanan yang sudah selesai)
        $IdPesananSelesai = Pesanan::whereMonth('tanggal_acara', $now->month)
                                    ->whereYear('tanggal_acara', $now->year)
                                    ->where('status', 'Selesai')
                                    ->pluck('id');

        $omset = DetailPesanan::whereIn('pesanan_id', $IdPesananSelesai)
                            ->sum('subtotal');
                            
        // Pesanan dalam 7 hari kedepan
        $upcomingPesanan = Pesanan::whereBetween('tanggal_acara', [$now->startOfDay(),
            $now->copy()->addDays(7)->endOfDay()
        ])
        ->orderBy('tanggal_acara')
        ->get();

        // Statistik Kategori (kategori dan jumlah stok)
        $kategoriStat = Barang::select('kategori', DB::raw('count(*) as total'))
                            ->groupBy('kategori')
                            ->get();

        // Statistik Barang paling sering disewa
        $barangStat = DetailPesanan::select('nama_barang', DB::raw('count(*) as total'))
                                ->groupBy('nama_barang')
                                ->orderByDesc('total')
                                ->get();

        // Data Penyewaan per bulan tahun ini
        $penyewaanStat = Pesanan::select(DB::raw('MONTH(tanggal_acara) as bulan'), DB::raw('count(*) as total'))
                                ->whereYear('tanggal_acara', $now->year)
                                ->groupBy(DB::raw('MONTH(tanggal_acara)'))
                                ->get();

        // ✅ Kirim semua variabel ke view
        return view('dashboard', compact(
            'totalItem',
            'totalTransaksi',
            'omset',
            'upcomingPesanan',
            'kategoriStat',
            'barangStat',
            'penyewaanStat'
        ));
    }
}