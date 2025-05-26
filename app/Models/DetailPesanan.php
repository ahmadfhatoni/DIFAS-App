<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanan';
    protected $fillable = [
        'pesanan_id',
        'nama_barang',
        'barang_id',
        'jumlah',
        'harga_sewa',
        'subtotal',
        'total',
    ];
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

}
