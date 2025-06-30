<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Dashboard Controller
 * 
 * Handles dashboard functionality including statistics and data display
 */
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the dashboard with statistics and data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $now = Carbon::now();

        // Get total items
        $totalItem = Barang::count();

        // Get total transactions this month
        $totalTransaksi = Pesanan::whereMonth('tanggal_acara', $now->month)
                                ->whereYear('tanggal_acara', $now->year)
                                ->count();

        // Get revenue this month (only from completed orders)
        $idPesananSelesai = Pesanan::whereMonth('tanggal_acara', $now->month)
                                    ->whereYear('tanggal_acara', $now->year)
                                    ->where('status', 'Selesai')
                                    ->pluck('id');

        $omset = DetailPesanan::whereIn('pesanan_id', $idPesananSelesai)
                            ->sum('subtotal');
                            
        // Get upcoming orders in next 7 days
        $upcomingPesanan = Pesanan::whereBetween('tanggal_acara', [
                $now->startOfDay(),
                $now->copy()->addDays(7)->endOfDay()
            ])
            ->orderBy('tanggal_acara')
            ->get();

        // Category statistics (category and stock count)
        $kategoriStat = Barang::join('kategori', 'barang.kategori_id', '=', 'kategori.id')
                            ->select('kategori.nama as kategori', DB::raw('count(*) as total'))
                            ->groupBy('kategori.id', 'kategori.nama')
                            ->get();

        // Most frequently rented items statistics
        $barangStat = DetailPesanan::select('nama_barang', DB::raw('count(*) as total'))
                                ->groupBy('nama_barang')
                                ->orderByDesc('total')
                                ->get();

        // Monthly rental data for this year
        $penyewaanStat = Pesanan::select(
                DB::raw('MONTH(tanggal_acara) as bulan'), 
                DB::raw('count(*) as total')
            )
            ->whereYear('tanggal_acara', $now->year)
            ->groupBy(DB::raw('MONTH(tanggal_acara)'))
            ->get();

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