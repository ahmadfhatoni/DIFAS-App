<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Invoice Controller
 * 
 * Handles invoice generation and display functionality
 */
class InvoiceController extends Controller
{
    /**
     * Display the specified invoice.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $pesanan = Pesanan::with('details')->findOrFail($id);
        $total = $pesanan->details->sum('subtotal');
        $jumlahItem = $pesanan->details->sum('jumlah');

        return view('invoice.show', compact('pesanan', 'total', 'jumlahItem'));
    }

    /**
     * Generate PDF invoice for the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cetak($id)
    {
        $pesanan = Pesanan::with('details')->findOrFail($id);
        $total = $pesanan->details->sum('subtotal');
        $jumlahItem = $pesanan->details->sum('jumlah');

        $pdf = Pdf::loadView('/invoice/invoice_pdf', compact('pesanan'));
        return $pdf->download('invoice_pesanan_' . str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }
}