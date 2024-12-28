@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Hasil Pencarian untuk "{{ $query }}"</h1>

    @if($barang->isEmpty() && $barangMasuk->isEmpty() && $barangKeluar->isEmpty() && $peminjaman->isEmpty())
        <div class="alert alert-info">
            Tidak ditemukan hasil untuk pencarian "{{ $query }}"
        </div>
    @else
        @if($barang->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Stok Barang</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Gambar</th>
                                    <th>Stok</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barang as $item)
                                <tr>
                                    <td>{{ $item->namaBarang }}</td>
                                    <td>
                                        @if($item->gambar)
                                            <img src="{{ asset('storage/gambar/' . $item->gambar) }}" 
                                                 alt="{{ $item->namaBarang }}" 
                                                 width="100"
                                                 onerror="if (!this.getAttribute('data-tried-noimage')) {
                                                             this.setAttribute('data-tried-noimage', 'true');
                                                             this.src='{{ asset('storage/gambar/no-image.png') }}';
                                                         }">
                                        @else
                                            <img src="{{ asset('storage/gambar/no-image.png') }}" 
                                                 alt="No Image Available" 
                                                 width="100">
                                        @endif
                                    </td>
                                    <td>{{ $item->stok }}</td>
                                    <td>{{ $item->deskripsi }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($barangMasuk->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Barang Masuk</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Gambar</th>
                                    <th>Jumlah</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangMasuk as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>
                                        @if($item->gambar)
                                            <img src="{{ asset('storage/gambar/' . $item->gambar) }}" 
                                                 alt="{{ $item->nama_barang }}" 
                                                 width="100"
                                                 onerror="if (!this.getAttribute('data-tried-noimage')) {
                                                             this.setAttribute('data-tried-noimage', 'true');
                                                             this.src='{{ asset('storage/gambar/no-image.png') }}';
                                                         }">
                                        @else
                                            <img src="{{ asset('storage/gambar/no-image.png') }}" 
                                                 alt="No Image Available" 
                                                 width="100">
                                        @endif
                                    </td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ $item->deskripsi }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($barangKeluar->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Barang Keluar</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Gambar</th>
                                    <th>Jumlah</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangKeluar as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>
                                        @if($item->gambar)
                                            <img src="{{ asset('storage/gambar/' . $item->gambar) }}" 
                                                 alt="{{ $item->nama_barang }}" 
                                                 width="100"
                                                 onerror="if (!this.getAttribute('data-tried-noimage')) {
                                                             this.setAttribute('data-tried-noimage', 'true');
                                                             this.src='{{ asset('storage/gambar/no-image.png') }}';
                                                         }">
                                        @else
                                            <img src="{{ asset('storage/gambar/no-image.png') }}" 
                                                 alt="No Image Available" 
                                                 width="100">
                                        @endif
                                    </td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ $item->deskripsi }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($peminjaman->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Peminjaman</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Peminjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjaman as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y H:i:s') : '-' }}</td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->jumlah_barang }}</td>
                                    <td>{{ $item->nama_peminjam }}</td>
                                    <td>
                                        @if($item->status == 'Dipinjam')
                                            <span class="badge badge-warning">{{ $item->status }}</span>
                                        @else
                                            <span class="badge badge-success">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection 