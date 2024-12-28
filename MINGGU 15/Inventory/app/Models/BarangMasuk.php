<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

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
        'admin',
        'user_id'
    ];

    protected $dates = ['tanggal', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
