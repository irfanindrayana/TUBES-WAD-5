

<?php $__env->startSection('title', 'Detail Barang'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-3">DETAIL BARANG</h1>
    <div class="mb-4">
        <a href="<?php echo e(route('home.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <?php if(Auth::user()->isAdmin()): ?>
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#stokMinimalModal">
                <i class="fas fa-exclamation-triangle"></i> Stok Minimal
            </button>
        <?php endif; ?>
    </div>
    
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="image-container mb-3">
                        <?php if($barang->gambar && Storage::disk('public')->exists('gambar/' . $barang->gambar)): ?>
                            <img src="<?php echo e(asset('storage/gambar/' . $barang->gambar)); ?>" 
                                 alt="<?php echo e($barang->namaBarang); ?>" 
                                 class="img-fluid rounded">
                        <?php else: ?>
                            <img src="<?php echo e(asset('storage/gambar/default.png')); ?>" 
                                 alt="Default" 
                                 class="img-fluid rounded">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">ID Barang</th>
                            <td><?php echo e($barang->id); ?></td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td><?php echo e($barang->namaBarang); ?></td>
                        </tr>
                        <tr>
                            <th>Stok</th>
                            <td><?php echo e($barang->stok); ?></td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td><?php echo e($barang->deskripsi); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Histori Perubahan -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Histori Perubahan</h5>
        </div>
        <div class="card-body">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-3" id="historyTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="masuk-tab" data-toggle="tab" href="#masuk" role="tab">
                        Barang Masuk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="keluar-tab" data-toggle="tab" href="#keluar" role="tab">
                        Barang Keluar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pinjam-tab" data-toggle="tab" href="#pinjam" role="tab">
                        Peminjaman
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="historyTabContent">
                <!-- Barang Masuk -->
                <div class="tab-pane fade show active" id="masuk" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $barangMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $masuk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e(\Carbon\Carbon::parse($masuk->tanggal)->format('d/m/Y H:i:s')); ?></td>
                                    <td><?php echo e($masuk->jumlah); ?></td>
                                    <td><?php echo e($masuk->deskripsi); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data barang masuk</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Barang Keluar -->
                <div class="tab-pane fade" id="keluar" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $barangKeluar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keluar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e(\Carbon\Carbon::parse($keluar->tanggal)->format('d/m/Y H:i:s')); ?></td>
                                    <td><?php echo e($keluar->jumlah); ?></td>
                                    <td><?php echo e($keluar->deskripsi); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data barang keluar</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Peminjaman -->
                <div class="tab-pane fade" id="pinjam" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Peminjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $peminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pinjam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e(\Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y H:i:s')); ?></td>
                                    <td>
                                        <?php if($pinjam->tanggal_kembali): ?>
                                            <?php echo e(\Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d/m/Y H:i:s')); ?>

                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($pinjam->nama_peminjam); ?></td>
                                    <td>
                                        <?php if($pinjam->status == 'dipinjam'): ?>
                                            <span class="badge bg-warning">Dipinjam</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Dikembalikan</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data peminjaman</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Stok Minimal -->
    <div class="modal fade" id="stokMinimalModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Set Stok Minimal</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="<?php echo e(route('home.updateStokMinimal', $barang->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Stok Minimal</label>
                            <input type="number" name="stok_minimal" class="form-control" 
                                   value="<?php echo e($barang->stok_minimal ?? 5); ?>" min="1" required>
                            <small class="form-text text-muted">
                                Sistem akan memberikan peringatan ketika stok barang kurang dari nilai ini
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.image-container {
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
    background: #fff;
}

.image-container img {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: contain;
}

.table th {
    background-color: #f8f9fa;
}

.nav-tabs .nav-link {
    color: #495057;
}

.nav-tabs .nav-link.active {
    font-weight: bold;
}

.badge {
    padding: 0.5em 1em;
}
</style>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\last\resources\views/home/detail.blade.php ENDPATH**/ ?>