<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update barang_keluars table
        DB::statement('
            ALTER TABLE barang_keluars 
            MODIFY COLUMN tanggal DATETIME NOT NULL
        ');

        // Update peminjaman table
        DB::statement('
            ALTER TABLE peminjaman 
            MODIFY COLUMN tanggal_pinjam DATETIME NOT NULL,
            MODIFY COLUMN tanggal_kembali DATETIME NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert barang_keluars table
        DB::statement('
            ALTER TABLE barang_keluars 
            MODIFY COLUMN tanggal DATE NOT NULL
        ');

        // Revert peminjaman table
        DB::statement('
            ALTER TABLE peminjaman 
            MODIFY COLUMN tanggal_pinjam DATE NOT NULL,
            MODIFY COLUMN tanggal_kembali DATE NULL
        ');
    }
};
