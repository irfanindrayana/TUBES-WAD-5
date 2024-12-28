@extends('layouts.app') <!-- Menggunakan layout app.blade.php -->

@section('title', 'Gudang') <!-- Menentukan judul halaman -->

@section('content') <!-- Bagian konten -->

    <div class="container-fluid px-4">
        <h1 class="mt-4">GUDANG</h1>
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                        <i class="fas fa-plus"></i> Tambah Barang
                    </button>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-success">
                        <i class="fas fa-hand-holding"></i> Kelola Peminjaman
                    </a>
                </div>
            </div>

    <div class="card-body">
        <!-- a -->
        @foreach($datastok as $item)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>PERHATIAN!</strong> Stok <strong>{{ $item->namaBarang }}</strong> akan habis.
        </div>
        @endforeach
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Deskripsi</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($homes as $index)
                <tr>
                    <td>{{ $index->id }}</td>
                    <td>{{ $index->namaBarang }}</td>
                    <td>{{ $index->deskripsi }}</td>
                    <td>{{ $index->stok }}</td>
                    <td>
                        <!-- Edit dan Hapus Button -->
                        <!-- <form method="POST" action="{{ route('home.destroy', $index->id) }}"> -->
                            <!-- @csrf -->
                            <!-- @method('DELETE') -->
                            <!-- <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit{{ $index->id }}">Edit</button> -->
                            <!-- <button type="submit" class="btn btn-danger">Hapus</button> -->
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit{{ $index->id }}">Edit</button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete{{ $index->id }}">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                
                <!-- Modal Edit -->
                @foreach($homes as $item)
                <div class="modal fade" id="edit{{ $item->id }}" >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">EDIT BARANG</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="POST" action="{{ route('home.update',$item->id) }}">
                                @csrf
                                @method('PUT')
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <input type="text" name="namaBarang" value="{{ $item->namaBarang }}" class="form-control" required><br>
                                    <input type="text" name="deskripsi" value="{{ $item->deskripsi }}" class="form-control" required><br>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal Delete -->
                <div class="modal fade" id="delete{{ $item->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">HAPUS BARANG</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="POST" action="{{  route('home.destroy', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    Apakah anda yakin menghapus <strong>{{ $item->namaBarang }}?</strong>
                                    <input type="hidden" name="id" value="{{ $item->id }}"> <br> <br>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
        
    </div>

    <!-- Tambah Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <!-- Modal Header -->
                <div class="modal-header">
                <h4 class="modal-title">TAMBAH BARANG</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <form  method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <input type="text" name ="namaBarang" placeholder="Nama Barang" class="form-control" required> <br>
                        <input type="text" name ="deskripsi" placeholder="Deskripsi Barang" class="form-control" required><br>
                        <input type="number" name ="stok" placeholder="Jumlah Barang" class="form-control" required><br>
                        <button type="submit" class="btn btn-primary" name="store"> Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
