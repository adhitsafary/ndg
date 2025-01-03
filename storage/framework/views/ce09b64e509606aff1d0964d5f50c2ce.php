<?php $__env->startSection('konten'); ?>
    <div class="p-5">
        <div class="pl-5 pr-5 mb-4">
            <div class="row mb-2 align-items-center">
                <div class="col-md-6" style="color: black">
                    <form action="<?php echo e(route('pembayaran.csbayar')); ?>" method="GET" class="form-inline">
                        <div class="input-group">
                            <input type="text" name="search" id="search" class="form-control font-weight-bold"
                                value="<?php echo e(request('search')); ?>" placeholder="Cari berdasarkan ID Pelanggan">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary ml-2">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if(!empty($pelanggan) && count($pelanggan) > 0): ?>
                <table class="table table-bordered table-responsive" style="color: black;">
                    <thead class="table table-primary">
                        <tr class="font-weight-bold">
                            <th>ID Pelanggan</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No Telepon</th>
                            <th>Aktivasi</th>
                            <th>Harga Paket</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pelanggan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($item->id_plg); ?></td>
                                <td><?php echo e($item->nama_plg); ?></td>
                                <td><?php echo e($item->alamat_plg); ?></td>
                                <td><?php echo e($item->no_telepon_plg); ?></td>
                                <td><?php echo e($item->aktivasi_plg); ?></td>
                                <td><?php echo e(number_format($item->harga_paket, 0, ',', '.')); ?></td>
                                <td><a href="<?php echo e(route('pelanggan.detail', $item->id_plg)); ?>" class="btn btn-warning btn-sm">Detail</a></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">
                    <?php if(request('search')): ?>
                        Tidak ada pelanggan dengan ID <?php echo e(request('search')); ?> ditemukan.
                    <?php else: ?>
                        Silakan masukkan ID pelanggan untuk melakukan pencarian.
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pembayaran\csbayar.blade.php ENDPATH**/ ?>