

<?php $__env->startSection('title', 'Inventory'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h1 class="mt-4 mb-4">GUDANG</h1>
    
    <div class="card">
        <div class="card-header">
            <?php if(Auth::user()->isAdmin()): ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahBarangModal">
                    Tambah Barang
                </button>
                <a href="<?php echo e(route('home.export')); ?>" class="btn btn-info">Export Data</a>
            <?php endif; ?>
        </div>

        <!-- Modal Tambah Barang -->
        <div class="modal fade" id="tambahBarangModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">TAMBAH BARANG</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="POST" action="<?php echo e(route('home.store')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" name="namaBarang" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Gambar Barang</label>
                                <div class="image-preview mb-2" id="imagePreview">
                                    <img id="preview" src="#" alt="Preview" style="display: none; max-width: 100%; max-height: 200px;">
                                    <div id="placeholder-text" class="text-center">
                                        <p class="mb-0">Preview gambar akan muncul di sini</p>
                                    </div>
                                </div>
                                <input type="file" name="gambar" class="form-control" id="gambar">
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Stok Awal</label>
                                <input type="number" name="stok" class="form-control" required min="0">
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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

            <?php $__currentLoopData = $datastok; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>PERHATIAN!</strong> Stok <strong><?php echo e($item->namaBarang); ?></strong> akan habis.
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Gambar</th>
                            <th>Deskripsi</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $homes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index->id); ?></td>
                            <td><?php echo e($index->namaBarang); ?></td>
                            <td>
                                <?php if($index->gambar && Storage::disk('public')->exists('gambar/' . $index->gambar)): ?>
                                    <img src="<?php echo e(asset('storage/gambar/' . $index->gambar)); ?>" 
                                         alt="<?php echo e($index->namaBarang); ?>" 
                                         width="100"
                                         class="img-thumbnail">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('storage/gambar/default.png')); ?>" 
                                         alt="Default" 
                                         width="100"
                                         class="img-thumbnail">
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($index->deskripsi); ?></td>
                            <td><?php echo e($index->stok); ?></td>
                            <td>
                                <?php if(Auth::user()->isAdmin()): ?>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit<?php echo e($index->id); ?>" data-toggle="tooltip" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?php echo e($index->id); ?>" data-toggle="tooltip" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal Edit -->
    <?php $__currentLoopData = $homes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="edit<?php echo e($item->id); ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">EDIT BARANG</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="<?php echo e(route('home.update',$item->id)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" name="namaBarang" value="<?php echo e($item->namaBarang); ?>" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Gambar Barang</label>
                            <div class="image-preview mb-2" id="imagePreview<?php echo e($item->id); ?>">
                                <img id="preview<?php echo e($item->id); ?>" 
                                     src="<?php echo e($item->gambar && Storage::disk('public')->exists('gambar/' . $item->gambar) 
                                           ? asset('storage/gambar/' . $item->gambar) 
                                           : asset('storage/gambar/default.png')); ?>" 
                                     alt="Preview" 
                                     style="display: block; max-width: 100%; max-height: 200px;">
                            </div>
                            <input type="file" name="gambar" class="form-control" id="gambar<?php echo e($item->id); ?>">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" required><?php echo e($item->deskripsi); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" name="stok" value="<?php echo e($item->stok); ?>" class="form-control" required min="0">
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
                    <h4 class="modal-title">HAPUS BARANG</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="<?php echo e(route('home.destroy', $item->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <div class="modal-body">
                        <p>Apakah anda yakin menghapus <strong><?php echo e($item->namaBarang); ?>?</strong></p>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->startPush('scripts'); ?>
<style>
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

/* Style untuk tombol aksi */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    margin: 0 2px;
}

.btn-sm i {
    font-size: 1rem;
}

/* Efek hover untuk tombol */
.btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi tooltip
    $('[data-toggle="tooltip"]').tooltip();

    const gambarInput = document.getElementById('gambar');
    const preview = document.getElementById('preview');
    const placeholderText = document.getElementById('placeholder-text');

    gambarInput.addEventListener('change', function(e) {
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

    <?php $__currentLoopData = $homes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        const editGambarInput<?php echo e($item->id); ?> = document.getElementById('gambar<?php echo e($item->id); ?>');
        const editPreview<?php echo e($item->id); ?> = document.getElementById('preview<?php echo e($item->id); ?>');

        editGambarInput<?php echo e($item->id); ?>.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    editPreview<?php echo e($item->id); ?>.src = e.target.result;
                    editPreview<?php echo e($item->id); ?>.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\last\resources\views/home/index.blade.php ENDPATH**/ ?>