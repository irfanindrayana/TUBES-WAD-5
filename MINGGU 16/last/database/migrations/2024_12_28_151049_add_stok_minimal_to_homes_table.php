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
            ALTER TABLE home 
            ADD COLUMN stok_minimal INT NOT NULL DEFAULT 5 AFTER stok,
            ADD COLUMN has_variant BOOLEAN NOT NULL DEFAULT false AFTER stok_minimal
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('
            ALTER TABLE home 
            DROP COLUMN stok_minimal,
            DROP COLUMN has_variant
        ');
    }
};
