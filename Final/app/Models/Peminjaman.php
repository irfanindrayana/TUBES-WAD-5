<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Peminjaman
{
    protected $table = 'peminjaman';

    public static function getAll()
    {
        return DB::table('peminjaman')
            ->select('peminjaman.*', 'users.name as user_name')
            ->leftJoin('users', 'peminjaman.user_id', '=', 'users.id')
            ->get();
    }

    public static function find($id)
    {
        return DB::table('peminjaman')->where('id', $id)->first();
    }

    public static function create($data)
    {
        return DB::table('peminjaman')->insert($data);
    }

    public static function updateData($id, $data)
    {
        return DB::table('peminjaman')->where('id', $id)->update($data);
    }

    public static function deleteData($id)
    {
        return DB::table('peminjaman')->where('id', $id)->delete();
    }

    public static function getByUserId($userId)
    {
        return DB::table('peminjaman')->where('user_id', $userId)->get();
    }

    public static function getPeminjamanAktif()
    {
        return DB::table('peminjaman')
            ->where('status', 'dipinjam')
            ->get();
    }

    public static function updateStatus($id, $status)
    {
        return DB::table('peminjaman')
            ->where('id', $id)
            ->update(['status' => $status]);
    }

    public static function with($relations)
    {
        if (in_array('user', (array)$relations)) {
            return DB::table('peminjaman')
                ->select('peminjaman.*', 'users.name as user_name', 'users.email as user_email')
                ->leftJoin('users', 'peminjaman.user_id', '=', 'users.id')
                ->get();
        }
        return self::getAll();
    }

    public static function getByNamaBarang($namaBarang)
    {
        return DB::table('peminjaman')
            ->where('nama_barang', $namaBarang)
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();
    }

    public static function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        return DB::table('peminjaman')->where($column, $operator, $value)->get();
    }

    public static function count()
    {
        return DB::table('peminjaman')->count();
    }
}
