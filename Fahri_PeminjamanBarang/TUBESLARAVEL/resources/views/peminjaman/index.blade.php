@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Daftar Peminjaman</h1>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Peminjaman
                </a>
                <a href="{{ route('home.index') }}" class="btn btn-secondary">
                    <i class="fas fa-warehouse"></i> Kembali ke Gudang
                </a>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Nama Barang</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $index => $pinjam)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pinjam->nama_peminjam }}</td>
                        <td>{{ $pinjam->nama_barang }}</td>
                        <td>{{ $pinjam->tanggal_pinjam }}</td>
                        <td>{{ $pinjam->tanggal_kembali ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $pinjam->status == 'dipinjam' ? 'bg-warning' : 'bg-success' }}">
                                {{ $pinjam->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('peminjaman.edit', $pinjam->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('peminjaman.destroy', $pinjam->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
