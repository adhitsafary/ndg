<?php $__env->startSection('konten'); ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-xl-12 mb-4">
                <div class="card bg-white shadow-sm">
                    <div class="d-flex align-items-center justify-content-center font-weight-bold"
                        style="height: 50px; font-size: 16px; cursor: pointer; background-color: #1d3cea; color: white; border-radius: 5px;"
                        data-bs-toggle="modal" data-bs-target="#targetModal">
                        Tambah Target Baru
                    </div>

                    <?php if(session('success')): ?>
                        <div class="alert alert-success mt-3" role="alert"
                            style="background-color: #00ff2f; color: rgb(0, 0, 0); border-color: #1d3cea;">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>

        <div class="row">
            <?php $__currentLoopData = $targets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?php echo e($target->nama_target); ?></h5>
                            <button class="btn btn-light" data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal<?php echo e($target->id); ?>"
                                style="border: none; background: none; cursor: pointer;">
                                <img src="<?php echo e(asset('asset/img/delete2.png')); ?>" alt="Hapus"
                                    style="width: 24px; height: 24px;">
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="text-muted">Jumlah Target</div>
                            <h4 class="mb-0"><?php echo e($target->jumlah_target); ?></h4>

                            <div class="text-muted mt-3">Hari Tersisa</div>
                            <h5 class="mb-0"><?php echo e($target->hari_tersisa); ?></h5>

                            <div class="text-muted mt-3">Sisa Target</div>
                            <h5 class="mb-0"><?php echo e($target->sisa_target); ?></h5>

                            <!-- Form untuk update target harian -->
                            <form action="<?php echo e(route('target.update', $target->id)); ?>" method="POST" class="mt-3">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label for="target_update" class="form-label">Masukkan Progress Hari Ini:</label>
                                    <input type="number" id="target_update" name="target_update" class="form-control"
                                        required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Update Target</button>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Konfirmasi Hapus -->
                    <div class="modal fade" id="confirmDeleteModal<?php echo e($target->id); ?>" tabindex="-1"
                        aria-labelledby="confirmDeleteModalLabel<?php echo e($target->id); ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel<?php echo e($target->id); ?>">Konfirmasi
                                        Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus target <strong><?php echo e($target->nama_target); ?></strong>?
                                    Tindakan ini tidak dapat dibatalkan.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="<?php echo e(route('target.destroy', $target->id)); ?>" method="POST"
                                        class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?> <!-- Menggunakan metode DELETE -->
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Modal untuk Menambahkan Target -->
        <div class="modal fade" id="targetModal" tabindex="-1" aria-labelledby="targetModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="targetModalLabel">Masukkan Target Harian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo e(route('target.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="nama_target" class="form-label">Nama Target:</label>
                                <input type="text" id="nama_target" name="nama_target" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="target" class="form-label">Jumlah Target:</label>
                                <input type="number" id="target" name="target" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="hari" class="form-label">Jumlah Hari:</label>
                                <input type="number" id="hari" name="hari" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Simpan Target</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\target_pelanggan\index.blade.php ENDPATH**/ ?>