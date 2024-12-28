

<?php $__env->startSection('title', 'Hasil Pencarian'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Hasil Pencarian untuk "<?php echo e($query); ?>"</h1>

    <?php if($barang->isEmpty() && $barangMasuk->isEmpty() && $barangKeluar->isEmpty() && $peminjaman->isEmpty()): ?>
        <div class="alert alert-info">
            Tidak ditemukan hasil untuk pencarian "<?php echo e($query); ?>"
        </div>
    <?php else: ?>
        <?php if($barang->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Stok Barang</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Gambar</th>
                                    <th>Stok</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $barang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($item->namaBarang); ?></td>
                                    <td>
                                        <?php if($item->gambar): ?>
                                            <img src="<?php echo e(asset('storage/gambar/' . $item->gambar)); ?>" 
                                                 alt="<?php echo e($item->namaBarang); ?>" 
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
                                    <td><?php echo e($item->stok); ?></td>
                                    <td><?php echo e($item->deskripsi); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($barangMasuk->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Barang Masuk</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Gambar</th>
                                    <th>Jumlah</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $barangMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(\Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i:s')); ?></td>
                                    <td><?php echo e($item->nama_barang); ?></td>
                                    <td>
                                        <?php if($item->gambar): ?>
                                            <img src="<?php echo e(asset('storage/gambar/' . $item->gambar)); ?>" 
                                                 alt="<?php echo e($item->nama_barang); ?>" 
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
                                    <td><?php echo e($item->jumlah); ?></td>
                                    <td><?php echo e($item->deskripsi); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($barangKeluar->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Barang Keluar</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Gambar</th>
                                    <th>Jumlah</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $barangKeluar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(\Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i:s')); ?></td>
                                    <td><?php echo e($item->nama_barang); ?></td>
                                    <td>
                                        <?php if($item->gambar): ?>
                                            <img src="<?php echo e(asset('storage/gambar/' . $item->gambar)); ?>" 
                                                 alt="<?php echo e($item->nama_barang); ?>" 
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
                                    <td><?php echo e($item->jumlah); ?></td>
                                    <td><?php echo e($item->deskripsi); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($peminjaman->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Peminjaman</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Peminjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $peminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(\Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y H:i:s')); ?></td>
                                    <td><?php echo e($item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y H:i:s') : '-'); ?></td>
                                    <td><?php echo e($item->nama_barang); ?></td>
                                    <td><?php echo e($item->jumlah_barang); ?></td>
                                    <td><?php echo e($item->nama_peminjam); ?></td>
                                    <td>
                                        <?php if($item->status == 'Dipinjam'): ?>
                                            <span class="badge badge-warning"><?php echo e($item->status); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-success"><?php echo e($item->status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\last\resources\views/search/results.blade.php ENDPATH**/ ?>