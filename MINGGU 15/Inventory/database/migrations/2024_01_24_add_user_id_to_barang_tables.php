<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Buat tabel barang_keluars jika belum ada
        if (!Schema::hasTable('barang_keluars')) {
            Schema::create('barang_keluars', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained();
                $table->date('tanggal');            
                $table->string('gambar')->nullable(); 
                $table->string('nama_barang');      
                $table->integer('jumlah');          
                $table->text('deskripsi')->nullable(); 
                $table->string('admin')->nullable();   
                $table->timestamps();
            });
        } else {
            Schema::table('barang_keluars', function (Blueprint $table) {
                if (!Schema::hasColumn('barang_keluars', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained()->after('id');
                }
            });
        }

        // Buat tabel barang_masuks jika belum ada
        if (!Schema::hasTable('barang_masuks')) {
            Schema::create('barang_masuks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained();
                $table->date('tanggal');            
                $table->string('gambar')->nullable(); 
                $table->string('nama_barang');      
                $table->integer('jumlah');          
                $table->text('deskripsi')->nullable(); 
                $table->string('admin')->nullable();   
                $table->timestamps();
            });
        } else {
            Schema::table('barang_masuks', function (Blueprint $table) {
                if (!Schema::hasColumn('barang_masuks', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained()->after('id');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('barang_keluars')) {
            Schema::table('barang_keluars', function (Blueprint $table) {
                if (Schema::hasColumn('barang_keluars', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
            });
        }

        if (Schema::hasTable('barang_masuks')) {
            Schema::table('barang_masuks', function (Blueprint $table) {
                if (Schema::hasColumn('barang_masuks', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
            });
        }
    }
}; 