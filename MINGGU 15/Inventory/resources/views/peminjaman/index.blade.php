@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">PEMINJAMAN</h1>
    
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahBarangModal">
                Tambah Peminjaman
            </button>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Nama Barang</th>
                            <th>Gambar</th>
                            <th>Jumlah</th>
                            <th>Peminjam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman as $index)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($index->tanggal_pinjam)->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $index->tanggal_kembali ? \Carbon\Carbon::parse($index->tanggal_kembali)->format('d/m/Y H:i:s') : '-' }}</td>
                            <td>{{ $index->nama_barang }}</td>
                            <td>
                                @if($index->gambar)
                                    <img src="{{ asset('storage/gambar/' . $index->gambar) }}" 
                                         alt="{{ $index->nama_barang }}" 
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
                            <td>{{ $index->jumlah_barang }}</td>
                            <td>{{ $index->nama_peminjam }}</td>
                            <td>
                                @if($index->status == 'Dipinjam')
                                    <span class="badge badge-warning">{{ $index->status }}</span>
                                @else
                                    <span class="badge badge-success">{{ $index->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($index->status == 'Dipinjam')
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#return{{ $index->id }}">
                                        Kembalikan
                                    </button>
                                @endif
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit{{ $index->id }}">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete{{ $index->id }}">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="tambahBarangModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">TAMBAH PEMINJAMAN</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="{{ route('peminjaman.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal Pinjam</label>
                            <input type="datetime-local" name="tanggal_pinjam" class="form-control" 
                                   value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i:s') }}" 
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Kembali</label>
                            <input type="datetime-local" name="tanggal_kembali" class="form-control" 
                                   value="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d\TH:i:s') }}" 
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Nama Peminjam</label>
                            <input type="text" name="nama_peminjam" class="form-control" required>
                        </div>

                        <div class="form-group position-relative">
                            <label>Nama Barang</label>
                            <input type="text" 
                                   id="nama_barang" 
                                   name="nama_barang" 
                                   class="form-control" 
                                   placeholder="Cari nama barang..." 
                                   autocomplete="off" 
                                   required>
                            <div id="searchResults" class="autocomplete-suggestions" style="display: none;"></div>
                        </div>

                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" required min="1" readonly>
                            <div class="invalid-feedback">
                                Jumlah tidak boleh melebihi stok yang tersedia atau kurang dari 1
                            </div>
                            <small class="text-muted">Stok tersedia: <span id="stok_tersedia">0</span></small>
                        </div>

                        <div class="form-group mb-3">
                            <label>Gambar Barang</label>
                            <div class="image-preview mb-2" id="imagePreview">
                                <img id="preview_gambar" src="#" alt="Preview" style="display: none; max-width: 100%; max-height: 200px;">
                                <div id="placeholder-text" class="text-center">
                                    <p class="mb-0">Gambar akan muncul saat barang dipilih</p>
                                </div>
                            </div>
                            <input type="file" name="gambar_baru" class="form-control" style="display: none;">
                            <input type="hidden" name="gambar_default" id="gambar_default">
                            <button type="button" class="btn btn-sm btn-secondary mt-2" id="ganti_gambar">
                                Ganti Gambar
                            </button>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    @foreach($peminjaman as $item)
    <div class="modal fade" id="edit{{ $item->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">EDIT PEMINJAMAN</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="{{ route('peminjaman.update', $item->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal Pinjam</label>
                            <input type="datetime-local" name="tanggal_pinjam" 
                                   value="{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('Y-m-d\TH:i:s') }}" 
                                   class="form-control" 
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Kembali</label>
                            <input type="datetime-local" name="tanggal_kembali" 
                                   value="{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('Y-m-d\TH:i:s') }}" 
                                   class="form-control" 
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Nama Peminjam</label>
                            <input type="text" name="nama_peminjam" value="{{ $item->nama_peminjam }}" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Nama Barang</label>
                            <select name="nama_barang" class="form-control select2-edit" required>
                                @foreach($barang as $brg)
                                    <option value="{{ $brg->namaBarang }}" 
                                            data-stok="{{ $brg->stok }}"
                                            data-gambar="{{ $brg->gambar }}"
                                            {{ $brg->namaBarang == $item->nama_barang ? 'selected' : '' }}>
                                        {{ $brg->namaBarang }} (Stok: {{ $brg->stok }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah_barang" id="jumlah_barang_edit_{{ $item->id }}" 
                                   value="{{ $item->jumlah_barang }}" class="form-control" required min="1">
                            <div class="invalid-feedback">
                                Jumlah tidak boleh melebihi stok yang tersedia atau kurang dari 1
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Gambar Barang</label>
                            <div class="image-preview mb-2" id="imagePreview_edit_{{ $item->id }}">
                                <img id="preview_gambar_edit_{{ $item->id }}" 
                                     src="{{ $item->gambar ? asset('storage/gambar/' . $item->gambar) : asset('storage/gambar/no-image.png') }}" 
                                     alt="Preview" 
                                     style="max-width: 100%; max-height: 200px;">
                            </div>
                            <input type="file" name="gambar_baru" class="form-control" style="display: none;">
                            <input type="hidden" name="gambar_default" value="{{ $item->gambar ?? 'no-image.png' }}">
                            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="document.querySelector('#imagePreview_edit_{{ $item->id }} input[type=file]').click();">
                                Ganti Gambar
                            </button>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Return -->
    <div class="modal fade" id="return{{ $item->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">PENGEMBALIAN BARANG</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="{{ route('peminjaman.return', $item->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin mengembalikan <strong>{{ $item->nama_barang }}</strong>?</p>
                        <div class="form-group">
                            <label>Tanggal Kembali</label>
                            <input type="datetime-local" name="tanggal_kembali" class="form-control" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Kembalikan</button>
                        </div>
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
                    <h4 class="modal-title">HAPUS PEMINJAMAN</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="{{ route('peminjaman.destroy', $item->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin menghapus data peminjaman <strong>{{ $item->nama_barang }}</strong>?</p>
                        <div class="text-center">
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.autocomplete-suggestions {
    border: 1px solid #ddd;
    background: #fff;
    cursor: pointer;
    overflow: auto;
    max-height: 200px;
    position: absolute;
    width: 100%;
    z-index: 9999;
}
.autocomplete-suggestion {
    padding: 8px 15px;
    white-space: nowrap;
    overflow: hidden;
}
.autocomplete-suggestion:hover {
    background: #f0f0f0;
}
.not-found {
    padding: 8px 15px;
    color: #dc3545;
}
.image-preview {
    width: 100%;
    min-height: 200px;
    border: 2px dashed #ddd;
    border-radius: 3px;
    position: relative;
    overflow: hidden;
    background-color: #ffffff;
    padding: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}

#preview_gambar {
    max-width: 100%;
    max-height: 200px;
}

#placeholder-text {
    color: #999;
    text-align: center;
}

.table img {
    max-width: 100px;
    height: auto;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.table td {
    vertical-align: middle;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Select2
    $('.select2').select2({
        placeholder: 'Pilih Barang',
        allowClear: true
    });

    $('.select2-edit').select2({
        placeholder: 'Pilih Barang',
        allowClear: true
    });

    const searchInput = document.getElementById('nama_barang');
    const searchResults = document.getElementById('searchResults');
    const preview = document.getElementById('preview_gambar');
    const placeholderText = document.getElementById('placeholder-text');
    const selectedGambar = document.getElementById('gambar_default');
    const gantiGambarBtn = document.getElementById('ganti_gambar');
    const gambarBaruInput = document.querySelector('input[name="gambar_baru"]');
    const jumlahInput = document.getElementById('jumlah_barang');
    const stokTersedia = document.getElementById('stok_tersedia');
    let timeoutId;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value;
        
        if (query.length < 1) {
            searchResults.style.display = 'none';
            preview.src = '#';
            preview.style.display = 'none';
            placeholderText.style.display = 'block';
            return;
        }

        timeoutId = setTimeout(() => {
            fetch(`/search-barang?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    searchResults.style.display = 'block';

                    if (data.length === 0) {
                        searchResults.innerHTML = '<div class="not-found">Barang tidak ditemukan</div>';
                        preview.src = '#';
                        preview.style.display = 'none';
                        placeholderText.style.display = 'block';
                        selectedGambar.value = '';
                        jumlahInput.readOnly = true;
                        jumlahInput.value = '';
                        stokTersedia.textContent = '0';
                        return;
                    }

                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'autocomplete-suggestion';
                        div.textContent = `${item.namaBarang} (Stok: ${item.stok})`;
                        div.style.cursor = 'pointer';
                        
                        div.addEventListener('click', function() {
                            searchInput.value = item.namaBarang;
                            
                            // Update gambar preview
                            if (item.gambar) {
                                preview.src = `/storage/gambar/${item.gambar}`;
                                preview.style.display = 'block';
                                placeholderText.style.display = 'none';
                                selectedGambar.value = item.gambar;

                                // Tambahkan error handler untuk gambar
                                preview.onerror = function() {
                                    if (!this.getAttribute('data-tried-noimage')) {
                                        this.setAttribute('data-tried-noimage', 'true');
                                        this.src = '/storage/gambar/no-image.png';
                                    }
                                };
                            } else {
                                preview.src = '/storage/gambar/no-image.png';
                                preview.style.display = 'block';
                                placeholderText.style.display = 'none';
                                selectedGambar.value = 'no-image.png';
                            }

                            // Update jumlah input
                            jumlahInput.readOnly = false;
                            jumlahInput.max = item.stok;
                            jumlahInput.value = '';
                            jumlahInput.placeholder = `Maksimal: ${item.stok}`;
                            stokTersedia.textContent = item.stok;

                            searchResults.style.display = 'none';
                        });
                        
                        searchResults.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    searchResults.innerHTML = '<div class="not-found">Terjadi kesalahan</div>';
                });
        }, 300);
    });

    // Validasi jumlah barang
    jumlahInput.addEventListener('input', function() {
        const maxStok = parseInt(this.max);
        const value = parseInt(this.value) || 0;
        const isValid = value > 0 && value <= maxStok;
        
        this.classList.toggle('is-invalid', !isValid);
        if (!isValid) {
            if (value <= 0) {
                this.value = 1;
            } else if (value > maxStok) {
                this.value = maxStok;
            }
        }
    });

    // Sembunyikan hasil pencarian saat klik di luar
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Handler untuk tombol ganti gambar
    gantiGambarBtn.addEventListener('click', function() {
        gambarBaruInput.style.display = 'block';
        gambarBaruInput.click();
    });

    // Preview gambar yang diupload
    gambarBaruInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholderText.style.display = 'none';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Tambahkan error handler untuk semua gambar
    document.querySelectorAll('img').forEach(img => {
        img.onerror = function() {
            if (!this.getAttribute('data-tried-noimage')) {
                this.setAttribute('data-tried-noimage', 'true');
                this.src = '/storage/gambar/no-image.png';
            }
        };
    });
});
</script>
@endpush

@endsection
