<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Pesanan Controller
 * 
 * Handles order management functionality
 */
class PesananController extends Controller
{
    /**
     * Show the form for creating a new order.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $barangs = Barang::all();
        return view('pesanan.create', compact('barangs'));
    }

    /**
     * Display a listing of orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Pesanan::query();

        // Filter search by customer name if search input exists
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_pemesan', 'like', '%' . $request->search . '%');
        }

        // Filter by start and end date if provided
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai != '') {
            $query->whereDate('tanggal_acara', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_akhir') && $request->tanggal_akhir != '') {
            $query->whereDate('tanggal_acara', '<=', $request->tanggal_akhir);
        }
        
        $pesanans = $query->with('details')->latest()->get();

        return view('pesanan.index', compact('pesanans'));
    }

    /**
     * Store a newly created order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemesan' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'no_telepon' => 'required|string|min:10|max:15|regex:/^[0-9+\-\s()]+$/',
            'alamat' => 'required|string|max:500|min:10',
            'tanggal_acara' => 'required|date|after_or_equal:today',
            'detail_pesanan' => 'required|string',
        ], [
            'nama_pemesan.regex' => 'Nama pemesan hanya boleh berisi huruf dan spasi.',
            'nama_pemesan.required' => 'Nama pemesan harus diisi.',
            'no_telepon.required' => 'Nomor telepon harus diisi.',
            'no_telepon.min' => 'Nomor telepon minimal 10 digit.',
            'no_telepon.max' => 'Nomor telepon maksimal 15 digit.',
            'no_telepon.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, dan karakter +, -, (, ).',
            'alamat.required' => 'Alamat harus diisi.',
            'alamat.min' => 'Alamat minimal 10 karakter.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',
            'tanggal_acara.required' => 'Tanggal acara harus diisi.',
            'tanggal_acara.after_or_equal' => 'Tanggal acara harus hari ini atau setelahnya.',
            'detail_pesanan.required' => 'Detail pesanan harus diisi.',
        ]);

        // Save to orders table
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
                'total' => 0, // temporary 0, will be filled only in 1 row
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Reduce stock
            Barang::where('id', $item['id'])->decrement('stok', $item['jumlah']);
        }

        // Save all detail data to database
        DetailPesanan::insert($dataDetail);

        // Update total column only on the last row
        $lastDetail = DetailPesanan::where('pesanan_id', $pesanan->id)->latest('id')->first();
        if ($lastDetail) {
            $lastDetail->update(['total' => $total]);
        }

        return redirect()->route('pesanan.tambah')
            ->with('success', 'Pesanan berhasil disimpan!');
    }
    
    /**
     * Mark order as completed.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function selesai($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        // Check if order status is 'Belum Selesai'
        if ($pesanan->status === 'Belum Selesai') {
            // Change order status to 'Selesai'
            $pesanan->status = 'Selesai';
            
            // Return stock to items table
            foreach ($pesanan->details as $detail) {
                $barang = Barang::findOrFail($detail->barang_id);
                $barang->stok += $detail->jumlah;  // Add back the ordered quantity to stock
                $barang->save();
            }
            
            // Save order status change
            $pesanan->save();
    
            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil ditandai sebagai selesai, stok barang telah dikembalikan.');
        }
    
        return redirect()->route('pesanan.index')
            ->with('error', 'Pesanan ini sudah selesai atau tidak dapat diubah.');
    }
    
    /**
     * Show invoice for the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function invoice($id)
    {
        $pesanan = Pesanan::with('details')->findOrFail($id);
        return view('pesanan.invoice', compact('pesanan'));
    }

    /**
     * Generate PDF invoice for the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cetakPdf($id)
    {
        $pesanan = Pesanan::with('details')->findOrFail($id);
        $pdf = Pdf::loadView('pesanan.invoice_pdf', compact('pesanan'));
        return $pdf->download('invoice-pesanan-' . $pesanan->id . '.pdf');
    }

    /**
     * Display order report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function report(Request $request)
    {
        $query = Pesanan::query();
        
        // Filter by start and end date if provided
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai != '') {
            $query->whereDate('tanggal_acara', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_akhir') && $request->tanggal_akhir != '') {
            $query->whereDate('tanggal_acara', '<=', $request->tanggal_akhir);
        }
        
        $query->where('status', 'Selesai');
        $pesanan = $query->with('details')->latest()->get();
        
        return view('report.index', compact('pesanan'));
    }
    
    /**
     * Generate PDF report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reportPdf(Request $request)
    {
        $query = Pesanan::query();

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_acara', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_acara', '<=', $request->tanggal_akhir);
        }

        $query->where('status', 'Selesai');
        $pesanan = $query->with('details')->latest()->get();

        $pdf = Pdf::loadView('report.report_pdf', [
            'pesanan' => $pesanan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
        ]);

        return $pdf->download('report-penyewaan.pdf');
    }

    /**
     * Update order status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::with('details')->findOrFail($id);
        $oldStatus = $pesanan->status;
        $newStatus = $request->status;

        // Only return stock if status changes from 'Belum Selesai' to 'Selesai' or 'Dibatalkan'
        if ($oldStatus === 'Belum Selesai' && ($newStatus === 'Selesai' || $newStatus === 'Dibatalkan')) {
            foreach ($pesanan->details as $detail) {
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->stok += $detail->jumlah; // return stock
                    $barang->save();
                }
            }

            // Update order status
            $pesanan->status = $newStatus;
            $pesanan->save();

            $statusText = $newStatus === 'Selesai' ? 'selesai' : 'dibatalkan';
            return redirect()->route('pesanan.index')
                ->with('success', "Pesanan berhasil ditandai sebagai {$statusText}, stok barang telah dikembalikan.");
        }

        // If status is already 'Selesai' or 'Dibatalkan', just update without returning stock
        if ($oldStatus === 'Selesai' || $oldStatus === 'Dibatalkan') {
            $pesanan->status = $newStatus;
            $pesanan->save();

            return redirect()->route('pesanan.index')
                ->with('success', 'Status pesanan berhasil diupdate.');
        }

        return redirect()->route('pesanan.index')
            ->with('error', 'Status tidak valid atau tidak dapat diubah.');
    }
}
