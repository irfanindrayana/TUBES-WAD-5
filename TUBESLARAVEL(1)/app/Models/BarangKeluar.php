<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    /** @use HasFactory<\Database\Factories\BarangKeluarFactory> */
    use HasFactory;
    protected $table = 'barang_keluars';
    protected $fillable = [
        'tanggal',
        'nama_barang',
        'deskripsi',
        'jumlah'
    ];

    protected $dates = ['created_at', 'updated_at', 'tanggal'];
    protected $attributes = [
        'gambar' => null,
        'keterangan' => null,
        'admin' => 'Unknown', 
    ];
}
