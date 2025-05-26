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
        Schema::create('akun', function (Blueprint $table) {
            $table->id(); // ID dengan BIGINT, AUTO_INCREMENT, PRIMARY KEY
            $table->string('username', 100); // Kolom username dengan tipe VARCHAR(100)
            $table->string('password', 255); // Kolom password dengan tipe VARCHAR(255)
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun');
    }
};
