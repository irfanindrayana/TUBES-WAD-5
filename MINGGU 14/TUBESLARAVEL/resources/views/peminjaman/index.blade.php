@extends('layouts.app')

@section('title', 'Peminjaman Barang')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">PEMINJAMAN BARANG</h1>

    <div class="card-header mb-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahPeminjamanModal">
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

        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Nama Peminjam</th>
                    <th>Nama Barang</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman as $pinjam)
                <tr>
                    <td>{{ $pinjam->nama_peminjam }}</td>
                    <td>{{ $pinjam->nama_barang }}</td>
                    <td>{{ $pinjam->tanggal_pinjam }}</td>
                    <td>{{ $pinjam->tanggal_kembali ?? '-' }}</td>
                    <td>{{ $pinjam->status }}</td>
                    <td>{{ Auth::user()->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahPeminjamanModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Peminjaman Baru</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="{{ route('peminjaman.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Nama Peminjam</label>
                            <input type="text" name="nama_peminjam" class="form-control" required>
                        </div>
                        <div class="form-group mb-3 position-relative">
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
                        <div class="form-group mb-3">
                            <label>Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam" class="form-control" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Tanggal Kembali</label>
                            <input type="date" name="tanggal_kembali" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Jumlah Barang</label>
                            <input type="number" 
                                   name="jumlah_barang" 
                                   class="form-control" 
                                   min="1" 
                                   required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan style untuk autocomplete -->
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
    function setupSearch(inputElement, resultsElement) {
        let timeoutId;
        const jumlahInput = inputElement.closest('.modal-body').querySelector('input[name="jumlah_barang"]');

        // Fungsi untuk setup validasi jumlah barang
        function setupJumlahValidation(input, maxStok) {
            input.max = maxStok;
            input.min = 1;
            input.value = ''; // Reset nilai
            input.placeholder = `Maksimal: ${maxStok}`;

            // Hapus event listener lama jika ada
            const newInput = input.cloneNode(true);
            input.parentNode.replaceChild(newInput, input);

            // Tambah event listener baru
            newInput.addEventListener('input', function() {
                const value = parseInt(this.value);
                if (value > maxStok) {
                    this.setCustomValidity(`Jumlah barang tidak boleh melebihi stok yang tersedia (${maxStok})`);
                    this.reportValidity();
                    this.value = maxStok;
                } else if (value < 1) {
                    this.setCustomValidity('Jumlah minimal adalah 1');
                    this.reportValidity();
                    this.value = 1;
                } else {
                    this.setCustomValidity('');
                }
            });

            return newInput;
        }

        inputElement.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const query = this.value;
            
            if (query.length < 2) {
                resultsElement.style.display = 'none';
                if (jumlahInput) {
                    jumlahInput.value = '';
                    jumlahInput.placeholder = 'Pilih barang terlebih dahulu';
                    jumlahInput.setCustomValidity('Pilih barang terlebih dahulu');
                }
                return;
            }

            timeoutId = setTimeout(() => {
                fetch(`/search-barang?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsElement.innerHTML = '';
                        resultsElement.style.display = 'block';

                        if (data.length === 0) {
                            resultsElement.innerHTML = '<div class="not-found">Barang tidak ditemukan</div>';
                            if (jumlahInput) {
                                jumlahInput.value = '';
                                jumlahInput.placeholder = 'Barang tidak ditemukan';
                                jumlahInput.setCustomValidity('Barang tidak ditemukan');
                            }
                            return;
                        }

                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'autocomplete-suggestion';
                            div.textContent = `${item.namaBarang} (Stok: ${item.stok})`;
                            div.addEventListener('click', () => {
                                inputElement.value = item.namaBarang;
                                resultsElement.style.display = 'none';
                                
                                if (jumlahInput) {
                                    const newJumlahInput = setupJumlahValidation(jumlahInput, item.stok);
                                    jumlahInput.parentNode.replaceChild(newJumlahInput, jumlahInput);
                                }
                            });
                            resultsElement.appendChild(div);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        resultsElement.innerHTML = '<div class="not-found">Terjadi kesalahan</div>';
                    });
            }, 300);
        });
    }

    // Setup search untuk form tambah
    const searchInput = document.getElementById('nama_barang');
    const searchResults = document.getElementById('searchResults');
    if (searchInput && searchResults) {
        setupSearch(searchInput, searchResults);
    }

    // Setup search untuk form edit
    const editInputs = document.querySelectorAll('.nama_barang_edit');
    editInputs.forEach(input => {
        const results = input.nextElementSibling;
        setupSearch(input, results);
    });

    // Validasi form sebelum submit
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const jumlahInput = this.querySelector('input[name="jumlah_barang"]');
            if (jumlahInput) {
                const value = parseInt(jumlahInput.value);
                const max = parseInt(jumlahInput.max);
                const min = parseInt(jumlahInput.min);

                if (!jumlahInput.validity.valid) {
                    e.preventDefault();
                    jumlahInput.reportValidity();
                    return;
                }
            }
        });
    });

    // Sembunyikan hasil pencarian saat klik di luar
    document.addEventListener('click', function(e) {
        const searchResults = document.querySelectorAll('.autocomplete-suggestions');
        searchResults.forEach(results => {
            if (!results.previousElementSibling.contains(e.target) && !results.contains(e.target)) {
                results.style.display = 'none';
            }
        });
    });

    // Validasi tanggal
    const tanggalPinjamInputs = document.querySelectorAll('input[name="tanggal_pinjam"]');
    const tanggalKembaliInputs = document.querySelectorAll('input[name="tanggal_kembali"]');

    tanggalPinjamInputs.forEach((input, index) => {
        input.addEventListener('change', function() {
            if (tanggalKembaliInputs[index]) {
                tanggalKembaliInputs[index].min = this.value;
            }
        });
    });

    const today = new Date().toISOString().split('T')[0];
    tanggalPinjamInputs.forEach(input => {
        input.min = today;
    });
});
</script>
@endpush

@endsection
