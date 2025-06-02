<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Barryvdh\DomPDF\Facade\Pdf; 

class PesananController extends Controller
{
    public function create()
    {
        $barangs = Barang::all();
        return view('pesanan.create', compact('barangs'));
    }

    public function index(Request $request)
    {
        // Query untuk mengambil pesanan
        $query = Pesanan::query();

        // Filter pencarian berdasarkan nama pemesan jika ada input search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_pemesan', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan tanggal mulai dan tanggal akhir jika ada
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai != '') {
            $query->whereDate('tanggal_acara', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_akhir') && $request->tanggal_akhir != '') {
            $query->whereDate('tanggal_acara', '<=', $request->tanggal_akhir);
        }
        
        // Ambil data pesanan beserta detailnya
        $pesanans = $query->with('details')->latest()->get();

        return view('pesanan.index', compact('pesanans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'no_telepon' => 'required|string|min:10',
            'alamat' => 'required|string',
            'tanggal_acara' => 'required|date|after_or_equal:today',
            'detail_pesanan' => 'required|string',
        ]);

        // Simpan ke tabel pesanan
        $pesanan = Pesanan::create([
            'nama_pemesan' => $request->nama_pemesan,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'tanggal_acara' => $request->tanggal_acara,
            'status' => 'Belum Selesai',
        ]);

        $detailList = json_decode($request->detail_pesanan, true);
        $total = 0;
        $dataDetail = [];

        foreach ($detailList as $item) {
            $subtotal = $item['jumlah'] * $item['harga'];
            $total += $subtotal;

            $dataDetail[] = [
                'pesanan_id' => $pesanan->id,
                'barang_id' => $item['id'],
                'nama_barang' => $item['nama'],
                'jumlah' => $item['jumlah'],
                'harga_sewa' => $item['harga'],
                'subtotal' => $subtotal,
                'total' => 0, // sementara 0, akan diisi hanya 1 baris
                'created_at' => now(),
                'updated_at' => now()
            ];

             // Kurangi stok
            Barang::where('id', $item['id'])->decrement('stok', $item['jumlah']);
        }

        // Simpan semua data detail ke database
        DetailPesanan::insert($dataDetail);

        // Update kolom total hanya pada baris terakhir
        $lastDetail = DetailPesanan::where('pesanan_id', $pesanan->id)->latest('id')->first();
        if ($lastDetail) {
            $lastDetail->update(['total' => $total]);
        }

        return redirect()->route('pesanan.tambah')->with('success', 'Pesanan berhasil disimpan!');
    }
    
    public function selesai($id)
    {
        // Cari pesanan berdasarkan ID
        $pesanan = Pesanan::findOrFail($id);
        
        // Periksa apakah pesanan statusnya 'Belum Selesai'
        if ($pesanan->status === 'Belum Selesai') {
            // Ubah status pesanan menjadi 'Selesai'
            $pesanan->status = 'Selesai';
            
            // Kembalikan stok barang di tabel barang
            foreach ($pesanan->details as $detail) {
                $barang = Barang::findOrFail($detail->barang_id);
                $barang->stok += $detail->jumlah;  // Tambahkan jumlah yang dipesan kembali ke stok
                $barang->save();
            }
            
            // Simpan perubahan status pesanan
            $pesanan->save();
    
            // Redirect dengan pesan sukses
            return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil ditandai sebagai selesai, stok barang telah dikembalikan.');
        }
    
        // Jika status sudah selesai, beri pesan error
        return redirect()->route('pesanan.index')->with('error', 'Pesanan ini sudah selesai.');
    }
    
    public function invoice($id)
    {
        $pesanan = Pesanan::with('details')->findOrFail($id);
        return view('pesanan.invoice', compact('pesanan'));
    }

    public function cetakPdf($id)
    {
        $pesanan = Pesanan::with('details')->findOrFail($id);
        $pdf = Pdf::loadView('pesanan.invoice_pdf', compact('pesanan'));
        return $pdf->download('invoice-pesanan-' . $pesanan->id . '.pdf');
    }

    public function report(Request $request)
    {
        // Query untuk mengambil pesanan
        $query = Pesanan::query();
        
        // Filter berdasarkan tanggal mulai dan tanggal akhir jika ada
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai != '') {
            $query->whereDate('tanggal_acara', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_akhir') && $request->tanggal_akhir != '') {
            $query->whereDate('tanggal_acara', '<=', $request->tanggal_akhir);
        }
        // Ambil data pesanan beserta detailnya
        $query->where('status', 'Selesai');
        $pesanan = $query->with('details')->latest()->get();
        return view('report.index', compact('pesanan'));
    }
    
    public function reportPdf(Request $request)
    {
        $query = Pesanan::query();

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_acara', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_acara', '<=', $request->tanggal_akhir);
        }

        // Ambil data pesanan beserta detailnya
        $query->where('status', 'Selesai');
        $pesanan = $query->with('details')->latest()->get();

        $pdf = Pdf::loadView('report.report_pdf', [
            'pesanan' => $pesanan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
        ]);

        return $pdf->download('report-penyewaan.pdf');
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::with('details')->findOrFail($id);

        if ($request->status === 'Selesai' || $request->status === 'Dibatalkan') {
            foreach ($pesanan->details as $detail) {
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->stok += $detail->jumlah; // kembalikan stok
                    $barang->save();
                }
            }

            // Update status pesanan
            $pesanan->status = $request->status;
            $pesanan->save();

            // Jika status "Selesai", maka total tetap bisa masuk laporan
            // Jika status "Dibatalkan", tidak perlu diproses ke laporan

            return redirect()->route('pesanan.index')->with('success', 'Status pesanan berhasil diubah.');
        }

        return back()->with('error', 'Status tidak valid.');
    }
}
