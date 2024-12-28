@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Tambah Peminjaman Baru</h2>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('peminjaman.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="nama_peminjam" class="form-label">Nama Peminjam</label>
                            <input type="text" 
                                class="form-control @error('nama_peminjam') is-invalid @enderror" 
                                id="nama_peminjam" 
                                name="nama_peminjam" 
                                value="{{ old('nama_peminjam') }}" 
                                required>
                            @error('nama_peminjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <select class="form-control @error('nama_barang') is-invalid @enderror" 
                                id="nama_barang" 
                                name="nama_barang" 
                                required>
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->namaBarang }}">
                                        {{ $item->namaBarang }} (Stok: {{ $item->stok }})
                                    </option>
                                @endforeach
                            </select>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" 
                                class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                                id="tanggal_pinjam" 
                                name="tanggal_pinjam" 
                                value="{{ old('tanggal_pinjam') ?? date('Y-m-d') }}" 
                                required>
                            @error('tanggal_pinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali (Opsional)</label>
                            <input type="date" 
                                class="form-control @error('tanggal_kembali') is-invalid @enderror" 
                                id="tanggal_kembali" 
                                name="tanggal_kembali" 
                                value="{{ old('tanggal_kembali') }}">
                            @error('tanggal_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Set minimum date untuk tanggal_kembali berdasarkan tanggal_pinjam
    document.getElementById('tanggal_pinjam').addEventListener('change', function() {
        document.getElementById('tanggal_kembali').min = this.value;
    });

    // Set tanggal minimal hari ini untuk tanggal_pinjam
    document.getElementById('tanggal_pinjam').min = new Date().toISOString().split('T')[0];
</script>
@endpush

@endsection