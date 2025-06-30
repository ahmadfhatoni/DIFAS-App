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
        // Perbaiki tabel users yang corrupted
        DB::statement('REPAIR TABLE users');
        
        // Atau alternatif untuk InnoDB
        // DB::statement('ALTER TABLE users FORCE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada rollback untuk repair table
    }
};
