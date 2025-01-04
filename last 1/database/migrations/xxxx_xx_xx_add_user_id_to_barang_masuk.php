<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Check if user_id column exists
        $columnExists = DB::select("SHOW COLUMNS FROM barang_masuks LIKE 'user_id'");
        
        if (empty($columnExists)) {
            // Add user_id column if it doesn't exist
            DB::statement('
                ALTER TABLE barang_masuks
                ADD COLUMN user_id BIGINT UNSIGNED NOT NULL AFTER id,
                ADD FOREIGN KEY (user_id) REFERENCES users(id)
            ');
        }
    }

    public function down()
    {
        // Check if user_id column exists
        $columnExists = DB::select("SHOW COLUMNS FROM barang_masuks LIKE 'user_id'");
        
        if (!empty($columnExists)) {
            DB::statement('
                ALTER TABLE barang_masuks
                DROP FOREIGN KEY barang_masuks_user_id_foreign,
                DROP COLUMN user_id
            ');
        }
    }
}; 