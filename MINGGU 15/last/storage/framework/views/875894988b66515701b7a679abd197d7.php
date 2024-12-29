

<?php $__env->startSection('title', 'Barang Keluar'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">BARANG KELUAR</h1>
    
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahBarangModal">
                Tambah Barang Keluar
            </button>
        </div>

        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="dataTable" id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Gambar</th>
                            <th>Jumlah</th>
                            <th>Deskripsi</th>
                            <th>Admin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $barangKeluar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(\Carbon\Carbon::parse($index->tanggal)->format('d/m/Y H:i:s')); ?></td>
                            <td><?php echo e($index->nama_barang); ?></td>
                            <td>
                                <?php if($index->gambar): ?>
                                    <img src="<?php echo e(asset('storage/gambar/' . $index->gambar)); ?>" 
                                         alt="<?php echo e($index->nama_barang); ?>" 
                                         width="100"
                                         onerror="if (!this.getAttribute('data-tried-noimage')) {
                                                     this.setAttribute('data-tried-noimage', 'true');
                                                     this.src='<?php echo e(asset('storage/gambar/no-image.png')); ?>';
                                                 }">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('storage/gambar/no-image.png')); ?>" 
                                         alt="No Image Available" 
                                         width="100">
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($index->jumlah); ?></td>
                            <td><?php echo e($index->deskripsi); ?></td>
                            <td><?php echo e($index->user_name); ?></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit<?php echo e($index->id); ?>">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?php echo e($index->id); ?>">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahBarangModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang Keluar</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="<?php echo e(route('barangKeluar.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="datetime-local" name="tanggal" class="form-control" 
                                   value="<?php echo e(\Carbon\Carbon::now()->format('Y-m-d\TH:i:s')); ?>" 
                                   required>
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

                        <div class="form-group mb-3">
                            <label>Gambar Barang</label>
                            <div class="image-preview mb-2" id="imagePreview">
                                <img id="preview" src="#" alt="Preview" style="display: none; max-width: 100%; max-height: 200px;">
                                <div id="placeholder-text" class="text-center">
                                    <p class="mb-0">Gambar akan muncul saat barang dipilih</p>
                                </div>
                            </div>
                            <input type="file" name="gambar_baru" class="form-control" style="display: none;">
                            <input type="hidden" name="gambar_default" id="selected_gambar">
                            <button type="button" class="btn btn-sm btn-secondary mt-2" id="ganti_gambar">
                                Ganti Gambar
                            </button>
                        </div>

                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" 
                                   name="jumlah" 
                                   id="jumlah_barang"
                                   class="form-control" 
                                   min="1"
                                   required 
                                   readonly>
                            <div class="invalid-feedback">
                                Jumlah tidak boleh melebihi stok yang tersedia atau kurang dari 1
                            </div>
                            <small class="text-muted">Stok tersedia: <span id="stok_tersedia">0</span></small>
                        </div>

                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="deskripsi" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <input type="text" name="admin" class="form-control mb-2" 
                                   value="<?php echo e(Auth::user()->name); ?>" readonly>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
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

#preview {
    max-width: 100%;
    max-height: 200px;
    display: none;
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

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    $('#datatablesSimple').DataTable({
        "order": [[0, "desc"]], // Urutkan berdasarkan tanggal terbaru
        "pageLength": 10,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data yang tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('nama_barang');
    const searchResults = document.getElementById('searchResults');
    const preview = document.getElementById('preview');
    const placeholderText = document.getElementById('placeholder-text');
    const selectedGambar = document.getElementById('selected_gambar');
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
            jumlahInput.readOnly = true;
            jumlahInput.value = '';
            stokTersedia.textContent = '0';
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
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\last\resources\views/barang_keluar/index.blade.php ENDPATH**/ ?>