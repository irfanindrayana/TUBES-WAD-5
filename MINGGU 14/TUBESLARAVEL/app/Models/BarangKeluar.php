<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;
    
    protected $table = 'barang_keluars';
    protected $fillable = [
        'tanggal',        
        'gambar',         
        'nama_barang',    
        'jumlah',         
        'deskripsi',    
        'admin',          
    ];

    protected $dates = ['tanggal', 'created_at', 'updated_at'];
}
