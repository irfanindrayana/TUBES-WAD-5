@extends('layouts.app') 

@section('title', 'Barang Keluar')

@section('content') 

<div class="container-fluid px-4">
    <h1 class="mt-4">BARANG KELUAR</h1>

    <div class="card-header mb-3">
    </div>

    <div class="card-body">
        
        @foreach($stokHampirHabis as $item)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>PERHATIAN!</strong> Stok <strong>{{ $item->nama_barang }}</strong> hampir habis.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endforeach

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Gambar</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Admin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangKeluar as $item)
                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->Gambar }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->Jumlah }}</td>
                    <td>{{ $item->Keterangan}}</td>
                    <td>{{ $item->Admin}}</td>
                    <td>{{ $item->Aksi}}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit{{ $item->id }}">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete{{ $item->id }}">Hapus</button>
                    </td>
                </tr>
                <div class="modal fade" id="edit{{ $item->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Barang Keluar</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="POST" action="{{ route('barangKeluar.update', $item->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <input type="text" name="nama_barang" value="{{ $item->nama_barang }}" class="form-control mb-2" required>
                                    <input type="text" name="deskripsi" value="{{ $item->deskripsi }}" class="form-control mb-2" required>
                                    <input type="number" name="jumlah" value="{{ $item->jumlah }}" class="form-control mb-2" required>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete{{ $item->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Hapus Barang Keluar</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="POST" action="{{ route('barangKeluar.destroy', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus <strong>{{ $item->nama_barang }}</strong>?
                                    <button type="submit" class="btn btn-danger mt-2">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="tambahBarangModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang Keluar</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="{{ route('barangKeluar.store') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="date" name="tanggal" class="form-control mb-2" required>
                        <input type="text" name="nama_barang" placeholder="Nama Barang" class="form-control mb-2" required>
                        <input type="text" name="deskripsi" placeholder="Deskripsi" class="form-control mb-2" required>
                        <input type="number" name="jumlah" placeholder="Jumlah Barang" class="form-control mb-2" required>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection