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
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id(); // BIGINT, AUTO_INCREMENT, PRIMARY KEY
            $table->unsignedBigInteger('pesanan_id'); // foreign key ke pesanan
            $table->unsignedBigInteger('barang_id'); // foreign key ke barang
            $table->string('nama_barang', 100); // salinan nama barang
            $table->integer('jumlah');
            $table->integer('harga_sewa');
            $table->integer('subtotal');
            $table->integer('total')->nullable();
            $table->timestamps();

            // Tambahkan foreign key constraint jika tabel terkait sudah ada
            $table->foreign('pesanan_id')->references('id')->on('pesanan')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
