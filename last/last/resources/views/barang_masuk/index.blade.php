@extends('layouts.app') 

@section('title', 'Barang Masuk')

@push('styles')
<style>
.search-results {
    position: absolute;
    background: white;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-top: none;
    z-index: 1000;
}
.search-result-item {
    padding: 8px 12px;
    cursor: pointer;
}
.search-result-item:hover {
    background-color: #f8f9fa;
}
.search-container {
    position: relative;
}

.image-preview {
    width: 100%;
    min-height: 100px;
    border: 2px solid #ddd;
    border-radius: 3px;
    position: relative;
    overflow: hidden;
    background-color: #ffffff;
    padding: 15px;
    margin-bottom: 15px;
}

.image-preview-input {
    position: relative;
    overflow: hidden;
    margin: 0px;    
    color: #333;
    background-color: #fff;
    border-color: #ccc;    
}

.image-preview-input input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}

.image-preview-input-title {
    margin-left: 2px;
}

.preview-image {
    max-width: 100%;
    max-height: 200px;
    display: none;
    margin: 10px auto;
}

#placeholder-text {
    color: #333;
}

#imageInput {
    display: inline-block;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    padding: 6px 12px;
    cursor: pointer;
}
</style>
@endpush

@section('content') 

<div class="container-fluid px-4">
    <h1 class="mt-4">BARANG MASUK</h1>

    <div class="card-header mb-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahBarangModal">
            Tambah Barang Masuk
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
                    <th>Nama</th>
                    <th>Gambar</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Admin</th>
                    <!-- <th>Aksi</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach($barangMasuk as $item)
                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>
                        @if($item->gambar)
                            @php
                                $imagePath = 'storage/gambar/' . $item->gambar;
                            @endphp
                            <img src="{{ asset($imagePath) }}" 
                                 alt="{{ $item->nama_barang }}"
                                 width="100"
                                 onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                        @else
                            <img src="{{ asset('images/no-image.png') }}" 
                                 alt="No Image" 
                                 width="100">
                        @endif
                    </td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ Auth::user()->name }}</td>
                    
                    <!-- <td> -->
                        <!-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit{{ $item->id }}">Edit</button> -->
                        <!-- <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete{{ $item->id }}">Hapus</button> -->
                    <!-- </td> -->
                </tr>
                <div class="modal fade" id="edit{{ $item->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Barang Masuk</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="POST" action="{{ route('barangMasuk.update', $item->id) }}">
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
                                <h4 class="modal-title">Hapus Barang Masuk</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="POST" action="{{ route('barangMasuk.destroy', $item->id) }}">
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
                    <h4 class="modal-title">Tambah Barang Masuk</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form method="POST" action="{{ route('barangMasuk.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Tanggal -->
                        <div class="form-group mb-3">
                            <label>Tanggal</label>
                            <input type="date" 
                                   name="tanggal" 
                                   class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ date('Y-m-d') }}"
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Nama Barang -->
                        <div class="search-container mb-2">
                            <label>Nama Barang</label>
                            <input type="text" 
                                   id="search_barang" 
                                   name="nama_barang" 
                                   placeholder="Masukkan Nama Barang" 
                                   class="form-control @error('nama_barang') is-invalid @enderror"
                                   required>
                            <div id="search_results" class="search-results d-none">
                                <!-- Results will be populated here -->
                            </div>
                        </div>

                        <!-- Gambar -->
                        <div class="form-group mb-3">
                            <label>Gambar</label>
                            <input type="file" 
                                   name="gambar" 
                                   class="form-control @error('gambar') is-invalid @enderror"
                                   accept="image/*" 
                                   required>
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <!-- <img id="preview" class="preview-image"> -->
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group mb-3">
                            <label>Deskripsi</label>
                            <input type="text" 
                                   name="deskripsi" 
                                   class="form-control @error('deskripsi') is-invalid @enderror"
                                   placeholder="Masukkan deskripsi" 
                                   required>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jumlah -->
                        <div class="form-group mb-3">
                            <label>Jumlah</label>
                            <input type="number" 
                                   name="jumlah" 
                                   class="form-control @error('jumlah') is-invalid @enderror"
                                   placeholder="Masukkan jumlah" 
                                   min="1" 
                                   required>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Admin (hidden) -->
                        <input type="hidden" name="admin" value="{{ Auth::user()->name }}">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let searchTimeout;
const searchInput = document.getElementById('search_barang');
const searchResults = document.getElementById('search_results');

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const keyword = this.value;
    
    if (keyword.length < 2) {
        searchResults.classList.add('d-none');
        return;
    }

    searchTimeout = setTimeout(() => {
        fetch(`/search-barang?keyword=${keyword}`)
            .then(response => response.json())
            .then(data => {
                searchResults.innerHTML = '';
                
                if (data.length === 0) {
                    searchResults.innerHTML = '<div class="search-result-item">Barang tidak ditemukan</div>';
                } else {
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'search-result-item';
                        div.textContent = item.namaBarang;
                        div.addEventListener('click', () => {
                            searchInput.value = item.namaBarang;
                            document.getElementById('deskripsi').value = item.deskripsi;
                            
                            // Update tampilan gambar
                            if (item.gambar) {
                                const preview = document.getElementById('preview');
                                preview.src = `{{ asset('storage/gambar') }}/${item.gambar}`;
                                preview.style.display = 'block';
                                preview.onerror = function() {
                                    this.src = '{{ asset('images/no-image.png') }}';
                                };
                                document.getElementById('placeholder-text').innerHTML = `
                                    <p class="mb-2">Gunakan gambar yang ada atau upload baru:</p>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <input type="file" 
                                               name="gambar" 
                                               id="imageInput"
                                               class="form-control" 
                                               style="width: auto;"
                                               accept="image/*"
                                               onchange="previewImage(this)">
                                    </div>
                                `;
                                document.getElementById('existing_gambar').value = item.gambar;
                            } else {
                                document.getElementById('preview').style.display = 'none';
                                document.getElementById('placeholder-text').innerHTML = `
                                    <p class="mb-2">Upload gambar baru:</p>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <input type="file" 
                                               name="gambar" 
                                               id="imageInput"
                                               class="form-control" 
                                               style="width: auto;"
                                               accept="image/*"
                                               onchange="previewImage(this)">
                                    </div>
                                `;
                            }
                            
                            searchResults.classList.add('d-none');
                        });
                        searchResults.appendChild(div);
                    });
                }
                searchResults.classList.remove('d-none');
            })
            .catch(error => {
                console.error('Error:', error);
                searchResults.innerHTML = '<div class="search-result-item">Terjadi kesalahan</div>';
                searchResults.classList.remove('d-none');
            });
    }, 300);
});

// Hide results when clicking outside
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.add('d-none');
    }
});

function previewImage(input) {
    const preview = document.getElementById('preview');
    const placeholderText = document.getElementById('placeholder-text');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholderText.innerHTML = `
                <p class="mb-2">Gunakan gambar yang ada atau upload baru:</p>
                <div class="d-flex align-items-center justify-content-center">
                    <input type="file" 
                           name="gambar" 
                           id="imageInput"
                           class="form-control" 
                           style="width: auto;"
                           accept="image/*"
                           onchange="previewImage(this)">
                </div>
            `;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Jika ada gambar yang sudah dipilih sebelumnya
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    if (imageInput.files && imageInput.files[0]) {
        previewImage(imageInput);
    }
});
</script>
@endpush

@endsection
