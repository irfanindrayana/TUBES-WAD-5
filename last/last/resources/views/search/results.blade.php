@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Hasil Pencarian</h1>
    <p>Menampilkan hasil pencarian untuk: <strong>{{ $query }}</strong></p>

    @if($barang->isEmpty() && $barangMasuk->isEmpty() && $barangKeluar->isEmpty() && $peminjaman->isEmpty())
        <div class="alert alert-info">
            Tidak ditemukan hasil untuk pencarian ini.
        </div>
    @else
        <!-- Hasil dari Gudang -->
        @if($barang->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-warehouse me-1"></i>
                Gudang
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Deskripsi</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barang as $item)
                        <tr>
                            <td>{{ $item->namaBarang }}</td>
                            <td>{{ $item->deskripsi }}</td>
                            <td>{{ $item->stok }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Hasil dari Barang Masuk -->
        @if($barangMasuk->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-sign-in-alt me-1"></i>
                Barang Masuk
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangMasuk as $item)
                        <tr>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->deskripsi }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Hasil dari Barang Keluar -->
        @if($barangKeluar->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-sign-out-alt me-1"></i>
                Barang Keluar
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangKeluar as $item)
                        <tr>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->deskripsi }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Hasil dari Peminjaman -->
        @if($peminjaman->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-handshake me-1"></i>
                Peminjaman
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Peminjam</th>
                            <th>Nama Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman as $item)
                        <tr>
                            <td>{{ $item->nama_peminjam }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->tanggal_pinjam }}</td>
                            <td>{{ $item->tanggal_kembali }}</td>
                            <td>{{ $item->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endif
</div>
@endsection 