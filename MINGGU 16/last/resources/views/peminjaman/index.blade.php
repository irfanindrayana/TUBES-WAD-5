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
                            @if(Auth::user()->isAdmin())
                                <th>Aksi</th>
                            @endif
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
                                @if(strtolower($index->status) == 'dipinjam')
                                    <span class="badge badge-warning">{{ ucfirst($index->status) }}</span>
                                @else
                                    <span class="badge badge-success">{{ ucfirst($index->status) }}</span>
                                @endif
                            </td>
                            @if(Auth::user()->isAdmin())
                                <td>
                                    @if(strtolower($index->status) == 'dipinjam')
                                        <form action="{{ route('peminjaman.return', $index->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Kembalikan Barang">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('peminjaman.invoice', $index->id) }}" class="btn btn-info btn-sm" title="Cetak Invoice">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit{{ $index->id }}" data-toggle="tooltip" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete{{ $index->id }}" data-toggle="tooltip" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
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

                        <div class="form-group position-relative">
                            <label>Nama Peminjam</label>
                            <input type="text" 
                                id="nama_peminjam" 
                                name="nama_peminjam" 
                                class="form-control" 
                                placeholder="Cari nama member..." 
                                autocomplete="off" 
                                required>
                            <div id="searchResultsMember" class="autocomplete-suggestions" style="display: none;"></div>
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
                            <div id="searchResultsBarang" class="autocomplete-suggestions" style="display: none;"></div>
                            <small class="text-muted">Stok tersedia: <span id="stok_tersedia">0</span></small>
                        </div>

                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" 
                                id="jumlah_barang" 
                                name="jumlah_barang" 
                                class="form-control" 
                                required 
                                min="1"
                                readonly>
                            <div class="invalid-feedback">
                                Jumlah melebihi stok tersedia
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Gambar Barang</label>
                            <div class="image-preview mb-2">
                                <img id="preview_gambar" src="{{ asset('storage/gambar/no-image.png') }}" 
                                    alt="Preview" 
                                    style="max-width: 100%; max-height: 200px; display: none;">
                                <div id="placeholder-text" class="text-center">
                                    <p class="mb-0">Gambar akan muncul saat barang dipilih</p>
                                </div>
                            </div>
                            <input type="file" name="gambar_baru" id="gambar_baru" class="form-control" style="display: none;">
                            <input type="hidden" name="gambar_default" id="gambar_default">
                            <button type="button" class="btn btn-sm btn-secondary mt-2" id="ganti_gambar" style="display: none;">
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

                        <div class="form-group position-relative">
                            <label>Nama Peminjam</label>
                            <input type="text" 
                                id="nama_peminjam_edit_{{ $item->id }}" 
                                name="nama_peminjam" 
                                class="form-control nama-peminjam-edit" 
                                placeholder="Cari nama member..." 
                                value="{{ $item->nama_peminjam }}"
                                autocomplete="off" 
                                required>
                            <div id="searchResultsMemberEdit_{{ $item->id }}" class="autocomplete-suggestions" style="display: none;"></div>
                        </div>

                        <div class="form-group position-relative">
                            <label>Nama Barang</label>
                            <input type="text" 
                                id="nama_barang_edit_{{ $item->id }}" 
                                name="nama_barang" 
                                class="form-control nama-barang-edit" 
                                placeholder="Cari nama barang..." 
                                value="{{ $item->nama_barang }}"
                                autocomplete="off" 
                                required>
                            <div id="searchResultsBarangEdit_{{ $item->id }}" class="autocomplete-suggestions" style="display: none;"></div>
                            <div class="mt-2">
                                <small class="text-muted">Stok tersedia: <span id="stok_tersedia_edit_{{ $item->id }}">0</span></small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" 
                                name="jumlah_barang" 
                                id="jumlah_barang_edit_{{ $item->id }}" 
                                class="form-control" 
                                value="{{ $item->jumlah_barang }}"
                                required 
                                min="1">
                            <div class="invalid-feedback">
                                Jumlah tidak boleh melebihi stok yang tersedia atau kurang dari 1
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Gambar Barang</label>
                            <div class="image-preview mb-2" id="imagePreviewEdit_{{ $item->id }}">
                                @if($item->gambar)
                                    <img id="preview_gambar_edit_{{ $item->id }}"
                                        src="{{ asset('storage/gambar/' . $item->gambar) }}" 
                                        alt="{{ $item->nama_barang }}" 
                                        style="max-width: 100%; max-height: 200px;"
                                        onerror="if (!this.getAttribute('data-tried-noimage')) {
                                                    this.setAttribute('data-tried-noimage', 'true');
                                                    this.src='{{ asset('storage/gambar/no-image.png') }}';
                                                }">
                                @else
                                    <img id="preview_gambar_edit_{{ $item->id }}"
                                        src="{{ asset('storage/gambar/no-image.png') }}" 
                                        alt="No Image Available" 
                                        style="max-width: 100%; max-height: 200px;">
                                @endif
                            </div>
                            <input type="file" name="gambar_baru" id="gambar_baru_edit_{{ $item->id }}" class="form-control" style="display: none;" onchange="previewEditImage(this, {{ $item->id }})">
                            <input type="hidden" name="gambar_default" value="{{ $item->gambar }}">
                            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="document.getElementById('gambar_baru_edit_{{ $item->id }}').click()">
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
                        <p>Apakah Anda yakin ingin menghapus data peminjaman ini?</p>
                        <div class="text-center">
                            <button type="submit" class="btn btn-danger">Hapus</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

@push('scripts')
<script>
// Fungsi pencarian untuk nama peminjam
const namaPeminjamInput = document.getElementById('nama_peminjam');
const searchResultsMember = document.getElementById('searchResultsMember');

namaPeminjamInput.addEventListener('input', function() {
    const query = this.value;
    
    if (query.length < 2) {
        searchResultsMember.style.display = 'none';
        return;
    }

    fetch(`/search-member?query=${query}`)
        .then(response => response.json())
        .then(data => {
            searchResultsMember.innerHTML = '';
            searchResultsMember.style.display = 'block';
            
            data.forEach(member => {
                const div = document.createElement('div');
                div.className = 'suggestion-item';
                div.textContent = member.name;
                div.addEventListener('click', () => {
                    namaPeminjamInput.value = member.name;
                    searchResultsMember.style.display = 'none';
                });
                searchResultsMember.appendChild(div);
            });
        });
});

// Fungsi pencarian untuk nama barang
const namaBarangInput = document.getElementById('nama_barang');
const searchResultsBarang = document.getElementById('searchResultsBarang');
const jumlahInput = document.getElementById('jumlah_barang');
const stokTersedia = document.getElementById('stok_tersedia');
const previewGambar = document.getElementById('preview_gambar');
const placeholderText = document.getElementById('placeholder-text');
const gambarDefault = document.getElementById('gambar_default');
const gantiGambarBtn = document.getElementById('ganti_gambar');
const gambarBaruInput = document.getElementById('gambar_baru');

namaBarangInput.addEventListener('input', function() {
    const query = this.value;
    
    if (query.length < 2) {
        searchResultsBarang.style.display = 'none';
        return;
    }

    fetch(`/search-barang?query=${query}`)
        .then(response => response.json())
        .then(data => {
            searchResultsBarang.innerHTML = '';
            searchResultsBarang.style.display = 'block';
            
            data.forEach(barang => {
                const div = document.createElement('div');
                div.className = 'suggestion-item';
                div.textContent = `${barang.namaBarang} (Stok: ${barang.stok})`;
                div.addEventListener('click', () => {
                    namaBarangInput.value = barang.namaBarang;
                    stokTersedia.textContent = barang.stok;
                    
                    // Set jumlah maksimal dan aktifkan input
                    jumlahInput.max = barang.stok;
                    jumlahInput.value = 1;
                    jumlahInput.readOnly = false;
                    
                    // Tampilkan gambar
                    if (barang.gambar) {
                        previewGambar.src = `/storage/gambar/${barang.gambar}`;
                        gambarDefault.value = barang.gambar;
                    } else {
                        previewGambar.src = '/storage/gambar/no-image.png';
                        gambarDefault.value = 'no-image.png';
                    }
                    previewGambar.style.display = 'block';
                    placeholderText.style.display = 'none';
                    gantiGambarBtn.style.display = 'block';
                    
                    searchResultsBarang.style.display = 'none';
                });
                searchResultsBarang.appendChild(div);
            });
        });
});

// Validasi jumlah barang
jumlahInput.addEventListener('input', function() {
    const max = parseInt(this.max);
    const value = parseInt(this.value);
    
    if (value > max) {
        this.value = max;
        this.classList.add('is-invalid');
    } else if (value < 1) {
        this.value = 1;
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

// Ganti gambar
gantiGambarBtn.addEventListener('click', function() {
    gambarBaruInput.click();
});

gambarBaruInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewGambar.src = e.target.result;
            previewGambar.style.display = 'block';
            placeholderText.style.display = 'none';
        };
        reader.readAsDataURL(this.files[0]);
    }
});

// Tutup hasil pencarian saat klik di luar
document.addEventListener('click', function(e) {
    if (!e.target.closest('.form-group.position-relative')) {
        searchResultsMember.style.display = 'none';
        searchResultsBarang.style.display = 'none';
    }
});

// Fungsi untuk preview gambar pada modal edit
function previewEditImage(input, itemId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = document.getElementById('preview_gambar_edit_' + itemId);
        
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<style>
.form-group.position-relative {
    position: relative;
}

.autocomplete-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1050;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: none;
}

.suggestion-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.suggestion-item:hover {
    background-color: #f8f9fa;
}

.suggestion-item:last-child {
    border-bottom: none;
}

.image-preview {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 200px;
    background-color: #f8f9fa;
}

#placeholder-text {
    color: #6c757d;
}

.is-invalid {
    border-color: #dc3545;
}

.is-invalid ~ .invalid-feedback {
    display: block;
}
</style>
@endpush
@endsection
