<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan kolom kategori_id ada dan bertipe INT UNSIGNED (sesuai kategori.id)
        if (!Schema::hasColumn('barang', 'kategori_id')) {
            DB::statement('ALTER TABLE barang ADD COLUMN kategori_id INT UNSIGNED NULL AFTER id');
        } else {
            DB::statement('ALTER TABLE barang MODIFY COLUMN kategori_id INT UNSIGNED NULL');
        }
        
        // Drop foreign key lama jika ada
        try {
            DB::statement('ALTER TABLE barang DROP FOREIGN KEY barang_kategori_id_foreign');
        } catch (Exception $e) {
            // Foreign key tidak ada, lanjutkan
        }
        
        // Tambah foreign key baru
        DB::statement('ALTER TABLE barang ADD CONSTRAINT barang_kategori_id_foreign FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key
        try {
            DB::statement('ALTER TABLE barang DROP FOREIGN KEY barang_kategori_id_foreign');
        } catch (Exception $e) {
            // Foreign key tidak ada
        }
    }
};
