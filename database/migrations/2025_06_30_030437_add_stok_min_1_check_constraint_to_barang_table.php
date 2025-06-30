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
        // Tambahkan check constraint stok minimal 1
        DB::statement('ALTER TABLE barang ADD CONSTRAINT chk_stok_min_1 CHECK (stok >= 1)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus check constraint
        DB::statement('ALTER TABLE barang DROP CONSTRAINT chk_stok_min_1');
    }
};
