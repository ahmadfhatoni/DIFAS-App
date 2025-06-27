<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori'; // agar tidak dicari sebagai 'kategoris'
    
    protected $fillable = ['nama'];
    public function barang()
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }

}
