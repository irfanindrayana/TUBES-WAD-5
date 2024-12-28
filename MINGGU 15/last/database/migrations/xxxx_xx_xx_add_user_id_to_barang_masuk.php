<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('barang_masuks', function (Blueprint $table) {
            if (!Schema::hasColumn('barang_masuks', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained();
            }
        });
    }

    public function down()
    {
        Schema::table('barang_masuks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}; 