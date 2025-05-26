<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $fillable = [
        'nama_pemesan',
        'no_telepon',
        'alamat',
        'tanggal_acara',
    ];
    public function details()
    {
        return $this->hasMany(DetailPesanan::class);
    }
    
    public function getTotalDariDetailAttribute()
    {
        return optional($this->details->last())->total ?? 0;
    }
}
