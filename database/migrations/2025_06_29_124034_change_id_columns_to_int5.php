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
        // Drop foreign key constraints terlebih dahulu
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->dropForeign(['pesanan_id']);
            $table->dropForeign(['barang_id']);
        });

        // Ubah kolom ID pada tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('id', false)->change();
        });

        // Ubah kolom ID pada tabel akun
        Schema::table('akun', function (Blueprint $table) {
            $table->unsignedInteger('id', false)->change();
        });

        // Ubah kolom ID pada tabel barang
        Schema::table('barang', function (Blueprint $table) {
            $table->unsignedInteger('id', false)->change();
        });

        // Ubah kolom ID pada tabel pesanan
        Schema::table('pesanan', function (Blueprint $table) {
            $table->unsignedInteger('id', false)->change();
        });

        // Ubah kolom ID pada tabel detail_pesanan
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->unsignedInteger('id', false)->change();
            $table->unsignedInteger('pesanan_id')->change();
            $table->unsignedInteger('barang_id')->change();
        });

        // Ubah kolom ID pada tabel kategori
        Schema::table('kategori', function (Blueprint $table) {
            $table->unsignedInteger('id', false)->change();
        });

        // Ubah kolom user_id pada tabel sessions
        Schema::table('sessions', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->change();
        });

        // Re-add foreign key constraints dengan tipe data baru
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->foreign('pesanan_id')->references('id')->on('pesanan')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints terlebih dahulu
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->dropForeign(['pesanan_id']);
            $table->dropForeign(['barang_id']);
        });

        // Kembalikan ke BIGINT untuk semua tabel
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });

        Schema::table('akun', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });

        Schema::table('barang', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });

        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
            $table->unsignedBigInteger('pesanan_id')->change();
            $table->unsignedBigInteger('barang_id')->change();
        });

        Schema::table('kategori', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        // Re-add foreign key constraints
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->foreign('pesanan_id')->references('id')->on('pesanan')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
        });
    }
};
