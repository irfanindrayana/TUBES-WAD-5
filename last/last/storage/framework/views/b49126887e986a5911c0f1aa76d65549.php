 <!-- Menggunakan layout app.blade.php -->

<?php $__env->startSection('title', 'Gudang'); ?> <!-- Menentukan judul halaman -->

<?php $__env->startSection('content'); ?> <!-- Bagian konten -->

    <div class="container-fluid px-4">
        <h1 class="mt-4">GUDANG</h1>
            <div class="card-header">
                <?php if(Auth::user()->isAdmin()): ?>
                    <a href="<?php echo e(url('/stock')); ?>" class="btn btn-info">Export Data</a>
                <?php endif; ?>
            </div>

    <div class="card-body">
        <!-- a -->
        <?php $__currentLoopData = $datastok; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>PERHATIAN!</strong> Stok <strong><?php echo e($item->namaBarang); ?></strong> akan habis.
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>ID </th>
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
                        <?php if($index->gambar): ?>
                            <!-- Debug info -->
                            <?php
                                $imagePath = storage_path('app/public/gambar/' . $index->gambar);
                                $imageUrl = asset('storage/gambar/' . $index->gambar);
                            ?>
                            <div style="display:none">
                                File exists: <?php echo e(file_exists($imagePath) ? 'Yes' : 'No'); ?>

                                Path: <?php echo e($imagePath); ?>

                                URL: <?php echo e($imageUrl); ?>

                            </div>
                            
                            <img src="<?php echo e($imageUrl); ?>" 
                                 alt="<?php echo e($index->namaBarang); ?>" 
                                 width="100"
                                 onerror="console.log('Error loading image:', this.src);">
                        <?php else: ?>
                            <span>Tidak ada gambar</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($index->deskripsi); ?></td>
                    <td><?php echo e($index->stok); ?></td>
                    <td>
                        <?php if(Auth::user()->isAdmin()): ?>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?php echo e($index->id); ?>">Edit</button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?php echo e($index->id); ?>">Hapus</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <!-- Modal Edit -->
                <?php $__currentLoopData = $homes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="modal fade" id="edit<?php echo e($item->id); ?>" >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">EDIT BARANG</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="POST" action="<?php echo e(route('home.update',$item->id)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <?php echo e(csrf_field()); ?>

                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?php echo e($item->id); ?>">
                                    <input type="text" name="namaBarang" value="<?php echo e($item->namaBarang); ?>" class="form-control" required><br>
                                    <input type="text" name="deskripsi" value="<?php echo e($item->deskripsi); ?>" class="form-control" required><br>
                                    <button type="submit" class="btn btn-primary">Update</button>
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
                                <?php echo e(csrf_field()); ?>

                                <div class="modal-body">
                                    Apakah anda yakin menghapus <strong><?php echo e($item->namaBarang); ?>?</strong>
                                    <input type="hidden" name="id" value="<?php echo e($item->id); ?>"> <br> <br>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        
    </div>

    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\last\resources\views/home/index.blade.php ENDPATH**/ ?>