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
        DB::statement('
            ALTER TABLE peminjaman 
            ADD COLUMN gambar VARCHAR(255) NULL AFTER nama_barang
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('
            ALTER TABLE peminjaman 
            DROP COLUMN gambar
        ');
    }
};
