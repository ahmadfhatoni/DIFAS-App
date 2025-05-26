<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id(); // id: BIGINT, AUTO_INCREMENT, PRIMARY KEY
            $table->string('foto', 255); // Lokasi atau nama file foto
            $table->string('nama', 100); // Nama barang
            $table->string('kategori', 100); // Kategori barang
            $table->integer('harga_sewa'); // Harga sewa per item
            $table->integer('stok'); // Stok barang
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
