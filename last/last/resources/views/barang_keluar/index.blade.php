@extends('layouts.app') 

@section('title', 'Barang Keluar')

@section('content') 

<div class="container-fluid px-4">
    <h1 class="mt-4">BARANG KELUAR</h1>

    <div class="card-header mb-3">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahBarangModal">
            Tambah Barang Keluar
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
                @foreach($barangKeluar as $item)
                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>
                        @if($item->gambar)
                            <img src="{{ asset('storage/gambar/' . $item->gambar) }}" 
                                 alt="{{ $item->nama_barang }}" 
                                 width="100"
                                 onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; console.log('Error loading image');">
                        @else
                            <span>Tidak ada gambar</span>
                        @endif
                    </td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ Auth::user()->name }}</td>
                    <!-- <td> -->
                        <!-- <button type="button" class="btn btn-warning btn-sm"  -->
                                <!-- data-toggle="modal" data-target="#edit{{ $item->id }}">Edit</button> -->
                        <!-- <button type="button" class="btn btn-danger btn-sm"  -->
                                <!-- data-toggle="modal" data-target="#delete{{ $item->id }}">Hapus</button> -->
                    <!-- </td> -->
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
                <form method="POST" action="{{ route('barangKeluar.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="date" name="tanggal" class="form-control mb-2" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="form-group position-relative">
                            <input type="text" 
                                   id="nama_barang" 
                                   name="nama_barang" 
                                   class="form-control mb-2" 
                                   placeholder="Cari nama barang..." 
                                   autocomplete="off" 
                                   required>
                            <div id="searchResults" class="autocomplete-suggestions" style="display: none;"></div>
                        </div>

                        <div class="form-group">
                            <input type="file" name="gambar" class="form-control mb-2">
                        </div>

                        <div class="form-group">
                            <input type="text" 
                                   name="deskripsi" 
                                   placeholder="Deskripsi" 
                                   class="form-control mb-2" 
                                   required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="jumlah" placeholder="Jumlah Barang" 
                                   class="form-control mb-2" min="1" required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="admin" class="form-control mb-2" 
                                   value="{{ Auth::user()->name }}" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('nama_barang');
    const searchResults = document.getElementById('searchResults');
    const deskripsiInput = document.querySelector('input[name="deskripsi"]');
    const gambarInfo = document.querySelector('input[name="gambar"]').parentElement;
    const adminInput = document.querySelector('input[name="admin"]');
    let timeoutId;

    // Set admin name from logged in user
    adminInput.value = '{{ Auth::user()->name ?? "R Mochamad Irfan Kusuma Indrayana" }}';
    adminInput.readOnly = true;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value;
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
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
                        // Reset form fields
                        deskripsiInput.value = '';
                        deskripsiInput.readOnly = false;
                        gambarInfo.innerHTML = `
                            <input type="file" name="gambar" class="form-control mb-2">
                        `;
                        return;
                    }

                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'autocomplete-suggestion';
                        div.textContent = `${item.namaBarang} (Stok: ${item.stok})`;
                        div.addEventListener('click', () => {
                            // Fill all fields with item data
                            searchInput.value = item.namaBarang;
                            deskripsiInput.value = item.deskripsi;
                            deskripsiInput.readOnly = true;
                            
                            // Update gambar field to show current image and make it readonly
                            if (item.gambar) {
                                gambarInfo.innerHTML = `
                                    <div class="mb-2">
                                        <img src="/storage/gambar/${item.gambar}" alt="${item.namaBarang}" 
                                             style="max-width: 100px; max-height: 100px;">
                                        <input type="hidden" name="gambar" value="${item.gambar}">
                                        <div class="mt-2">
                                            <small class="text-muted">Gunakan gambar yang ada atau upload baru:</small>
                                            <input type="file" name="gambar" class="form-control mt-1">
                                        </div>
                                    </div>
                                `;
                            } else {
                                gambarInfo.innerHTML = `
                                    <input type="file" name="gambar" class="form-control mb-2">
                                `;
                            }
                            
                            // Set max value for jumlah input
                            const jumlahInput = document.querySelector('input[name="jumlah"]');
                            jumlahInput.max = item.stok;
                            jumlahInput.placeholder = `Maksimal: ${item.stok}`;
                            
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

    // Validate jumlah before form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const jumlahInput = document.querySelector('input[name="jumlah"]');
        const max = parseInt(jumlahInput.max);
        const value = parseInt(jumlahInput.value);

        if (value > max) {
            e.preventDefault();
            alert(`Jumlah tidak boleh melebihi stok tersedia (${max})`);
        }
    });

    // Sembunyikan hasil pencarian saat klik di luar
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection