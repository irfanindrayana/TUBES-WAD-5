<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    
    protected $fillable = [
        'user_id',
        'nama_peminjam',
        'nama_barang',
        'gambar',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'admin',
        'jumlah_barang'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
