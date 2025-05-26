<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function show($id)
    {
        $pesanan = Pesanan::with('details')->findOrFail($id);
        $total = $pesanan->details->sum('subtotal');
        $jumlahItem = $pesanan->details->sum('jumlah');

        return view('invoice.show', compact('pesanan', 'total', 'jumlahItem'));
    }

    public function cetak($id)
    {
        $pesanan = Pesanan::with('details')->findOrFail($id);
        $total = $pesanan->details->sum('subtotal');
        $jumlahItem = $pesanan->details->sum('jumlah');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('/invoice/invoice_pdf', compact('pesanan'));
        return $pdf->download('invoice_pesanan_' . str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) . '.pdf');
        //$pdf = Pdf::loadView('invoice.show', compact('pesanan', 'total', 'jumlahItem'));
        //return $pdf->download('invoice_pesanan_'.$pesanan->id.'.pdf');
    }
}