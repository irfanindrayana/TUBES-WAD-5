<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuks';
    protected $fillable = [
        'tanggal',
        'nama_barang',
        'gambar',
        'deskripsi',
        'jumlah',
        'admin'
    ];
    protected $guarded = ['id'];

    // protected $dates = ['created_at', 'updated_at', 'tanggal'];
    // protected $attributes = [
    //     'gambar' => null,
    //     'keterangan' => null,
    //     'admin' => 'Unknown', 
    // ];
}
