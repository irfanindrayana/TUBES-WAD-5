@extends('layouts.app') <!-- Menggunakan layout app.blade.php -->

@section('title', 'Gudang') <!-- Menentukan judul halaman -->

@section('content') <!-- Bagian konten -->

    <div class="container-fluid px-4">
        <h1 class="mt-4">GUDANG</h1>
            <div class="card-header">
                @if(Auth::user()->isAdmin())
                    <a href="{{ url('/stock') }}" class="btn btn-info">Export Data</a>
                @endif
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
                    <th>ID </th>
                    <th>Nama</th>
                    <th>Gambar</th>
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
                    <td>
                        @if($index->gambar)
                            <!-- Debug info -->
                            @php
                                $imagePath = storage_path('app/public/gambar/' . $index->gambar);
                                $imageUrl = asset('storage/gambar/' . $index->gambar);
                            @endphp
                            <div style="display:none">
                                File exists: {{ file_exists($imagePath) ? 'Yes' : 'No' }}
                                Path: {{ $imagePath }}
                                URL: {{ $imageUrl }}
                            </div>
                            
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $index->namaBarang }}" 
                                 width="100"
                                 onerror="console.log('Error loading image:', this.src);">
                        @else
                            <span>Tidak ada gambar</span>
                        @endif
                    </td>
                    <td>{{ $index->deskripsi }}</td>
                    <td>{{ $index->stok }}</td>
                    <td>
                        @if(Auth::user()->isAdmin())
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit{{ $index->id }}">Edit</button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete{{ $index->id }}">Hapus</button>
                        @endif
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

    @endsection
