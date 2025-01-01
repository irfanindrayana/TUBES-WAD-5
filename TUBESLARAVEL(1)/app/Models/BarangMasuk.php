<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuks';
    protected $fillable = [
        'tanggal',        
        'gambar',         
        'nama_barang',    
        'jumlah',         
        'keterangan',     
        'admin',          
    ];

    protected $dates = ['created_at', 'updated_at', 'tanggal'];
    protected $attributes = [
        'gambar' => null,
        'keterangan' => null,
        'admin' => 'Unknown', 
    ];
}
