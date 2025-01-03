<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <h4 class="mb-4">Daftar Pelanggan yang Belum Membayar Bulan Ini</h4>

        <!-- Form Pencarian -->
        <form action="<?php echo e(route('pembayaran.index')); ?>" method="GET" class="form-inline mb-4">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" value="<?php echo e(request('search')); ?>" placeholder="Cari berdasarkan ID atau Nama">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-responsive">
            <thead class="table table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>Tanggal Tagih</th>
                    <th>Tanggal Pembayaran</th>
                    <th>Jumlah Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($item->id_plg); ?></td>
                        <td><?php echo e($item->nama_plg); ?></td>
                        <td><?php echo e($item->alamat_plg); ?></td>
                        <td><?php echo e($item->tgl_tagih_plg); ?></td>
                        <td><?php echo e($item->tanggal_pembayaran ?? 'Belum Membayar'); ?></td>
                        <td><?php echo e($item->jumlah_pembayaran ?? '-'); ?></td>
                        <td>
                            <a href="<?php echo e(route('pelanggan.historypembayaran', $item->id_plg)); ?>" class="btn btn-info btn-sm">Lihat</a>
                            <!-- Tombol lain seperti edit atau delete bisa ditambahkan di sini -->
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada pelanggan yang belum membayar bulan ini</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pembayaran\blm_byr.blade.php ENDPATH**/ ?>