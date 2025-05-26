<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';
    protected $primaryKey = 'id';
    protected $fillable = [
        'foto', 
        'nama', 
        'kategori',
        'harga_sewa', 
        'stok'
    ];
    public $incrementing = false; // karena id akan manual
    protected $keyType = 'string'; // jika bukan integer

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }

}