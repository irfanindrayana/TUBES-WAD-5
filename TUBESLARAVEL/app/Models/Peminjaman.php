<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    
    protected $fillable = [
        'nama_peminjam',
        'nama_barang',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'admin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
