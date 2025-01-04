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
            CREATE TABLE peminjaman (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NULL,
                nama_peminjam VARCHAR(255) NOT NULL,
                nama_barang VARCHAR(255) NOT NULL,
                gambar VARCHAR(255) NULL,
                tanggal_pinjam DATE NOT NULL,
                tanggal_kembali DATE NULL,
                status VARCHAR(255) NOT NULL DEFAULT "dipinjam",
                admin VARCHAR(255) NULL,
                jumlah_barang INT NOT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS peminjaman');
    }
};
