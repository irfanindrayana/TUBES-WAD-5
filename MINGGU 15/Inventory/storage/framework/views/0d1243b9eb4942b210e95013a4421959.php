

<?php $__env->startSection('title', 'Barang Masuk'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">BARANG MASUK</h1>
    
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahBarangModal">
                Tambah Barang Masuk
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
                <table id="datatablesSimple" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Gambar</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $barangMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                            <td>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?php echo e($index->id); ?>">Edit</button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?php echo e($index->id); ?>">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <h4 class="modal-title">TAMBAH BARANG MASUK</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="<?php echo e(route('barangMasuk.store')); ?>" enctype="multipart/form-data">
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
                            <input type="number" name="jumlah" class="form-control" required min="1">
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

    <!-- Modal Edit -->
    <?php $__currentLoopData = $barangMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="edit<?php echo e($item->id); ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">EDIT BARANG MASUK</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="<?php echo e(route('barangMasuk.update', $item->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="datetime-local" name="tanggal" 
                                   value="<?php echo e(\Carbon\Carbon::parse($item->tanggal)->format('Y-m-d\TH:i:s')); ?>" 
                                   class="form-control" 
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Nama Barang</label>
                            <select name="nama_barang" class="form-control" required>
                                <?php $__currentLoopData = $barang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($brg->namaBarang); ?>" <?php echo e($brg->namaBarang == $item->nama_barang ? 'selected' : ''); ?>>
                                        <?php echo e($brg->namaBarang); ?> (Stok: <?php echo e($brg->stok); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah" value="<?php echo e($item->jumlah); ?>" class="form-control" required min="1">
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="deskripsi" class="form-control" required><?php echo e($item->deskripsi); ?></textarea>
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
    <div class="modal fade" id="delete<?php echo e($item->id); ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">HAPUS BARANG MASUK</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="<?php echo e(route('barangMasuk.destroy', $item->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin menghapus data barang masuk <strong><?php echo e($item->nama_barang); ?></strong>?</p>
                        <div class="text-center">
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('nama_barang');
    const searchResults = document.getElementById('searchResults');
    const preview = document.getElementById('preview');
    const placeholderText = document.getElementById('placeholder-text');
    const selectedGambar = document.getElementById('selected_gambar');
    const gantiGambarBtn = document.getElementById('ganti_gambar');
    const gambarBaruInput = document.querySelector('input[name="gambar_baru"]');
    let timeoutId;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value;
        
        if (query.length < 1) {
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
                        preview.style.display = 'none';
                        placeholderText.style.display = 'block';
                        selectedGambar.value = '';
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
                                preview.style.display = 'none';
                                placeholderText.style.display = 'block';
                                selectedGambar.value = '';
                            }

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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\last\resources\views/barang_masuk/index.blade.php ENDPATH**/ ?>