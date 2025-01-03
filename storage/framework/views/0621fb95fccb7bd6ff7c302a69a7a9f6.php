<?php $__env->startSection('konten'); ?>
    <div class="container">

        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Paket Terjual</h6>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $paketTop5; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // Hitung persentase user dari total user
                            $percentage = ($paket->total_user / $totalUsers) * 100;
                        ?>
                        <div class="mb-3">
                            <div class="small text-gray-500">Harga <?php echo e(number_format($paket->harga_paket, 0, ',', '.')); ?>

                                <div class="small float-right"><b><?php echo e($paket->total_user); ?> User</b>
                                </div>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo e($percentage); ?>%"
                                    aria-valuenow="<?php echo e($paket->total_user); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div id="extraPaket" style="display:none;">
                        <?php $__currentLoopData = $paketRemaining; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                // Hitung persentase user dari total user
                                $percentage = ($paket->total_user / $totalUsers) * 100;
                            ?>
                            <div class="mb-3">
                                <div class="small text-gray-500">Paket <?php echo e($paket->harga_paket); ?>

                                    <div class="small float-right"><b><?php echo e($paket->total_user); ?>

                                            User</b></div>
                                </div>
                                <div class="progress" style="height: 12px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: <?php echo e($percentage); ?>%" aria-valuenow="<?php echo e($paket->total_user); ?>"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a class="m-0 small text-primary card-link" href="#" id="showMoreBtn">Lihat
                        Lebih Banyak <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </div>


    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pelanggan\paket_terjual.blade.php ENDPATH**/ ?>