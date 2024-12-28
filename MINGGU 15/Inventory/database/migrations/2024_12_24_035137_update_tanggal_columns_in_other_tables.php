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
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->datetime('tanggal')->change();
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->datetime('tanggal_pinjam')->change();
            $table->datetime('tanggal_kembali')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->date('tanggal')->change();
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->date('tanggal_pinjam')->change();
            $table->date('tanggal_kembali')->nullable()->change();
        });
    }
};
