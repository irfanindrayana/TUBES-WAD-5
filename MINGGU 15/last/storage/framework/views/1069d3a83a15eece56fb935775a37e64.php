

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Dashboard</h1>
    </div>

    <!-- Overview Cards -->
    <div class="row">
        <!-- Stock Barang -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Stock Barang</h6>
                            <h2 class="display-4 mb-0"><?php echo e($totalHome ?? 0); ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                    <small>Update: <?php echo e($lastHomeUpdate ?? 'Belum ada data'); ?></small>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo e(route('home.index')); ?>">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Barang Masuk -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Barang Masuk</h6>
                            <h2 class="display-4 mb-0"><?php echo e($totalBarangMasuk ?? 0); ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-arrow-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <small>Terakhir: <?php echo e($lastBarangMasuk ?? 'Belum ada data'); ?></small>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo e(route('barangMasuk.index')); ?>">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Barang Keluar -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Barang Keluar</h6>
                            <h2 class="display-4 mb-0"><?php echo e($totalBarangKeluar ?? 0); ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-arrow-circle-up fa-2x"></i>
                        </div>
                    </div>
                    <small>Terakhir: <?php echo e($lastBarangKeluar ?? 'Belum ada data'); ?></small>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo e(route('barangKeluar.index')); ?>">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Peminjaman -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Peminjaman</h6>
                            <h2 class="display-4 mb-0"><?php echo e($totalPeminjaman ?? 0); ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-handshake fa-2x"></i>
                        </div>
                    </div>
                    <small>Terakhir: <?php echo e($lastPeminjaman ?? 'Belum ada data'); ?></small>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo e(route('peminjaman.index')); ?>">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart dan Aktivitas -->
    <div class="row mt-4">
        <!-- Statistik Chart -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-chart-pie me-1"></i>
                        Statistik Barang
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="myPieChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Aktivitas Terakhir -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-list me-1"></i>
                        Aktivitas Terakhir
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php $__empty_1 = true; $__currentLoopData = $recentActivities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-circle text-primary me-2"></i>
                                        <?php echo e($activity); ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-3">
                                <i class="fas fa-info-circle text-muted mb-2"></i>
                                <p class="text-muted mb-0">Belum ada aktivitas tercatat</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Pie Chart
    var ctx = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ["Stock", "Masuk", "Keluar", "Dipinjam"],
            datasets: [{
                data: [
                    <?php echo e($totalHome ?? 0); ?>, 
                    <?php echo e($totalBarangMasuk ?? 0); ?>, 
                    <?php echo e($totalBarangKeluar ?? 0); ?>, 
                    <?php echo e($totalPeminjaman ?? 0); ?>

                ],
                backgroundColor: ['#0d6efd', '#198754', '#dc3545', '#ffc107'],
            }],
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\last\resources\views/dashboard/index.blade.php ENDPATH**/ ?>