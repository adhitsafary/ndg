<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1 class="mb-4">Bayar Tagihan</h1>
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('automatispayment.index')); ?>" method="GET">
            <div class="form-group">
                <input type="text" name="q" class="form-control" placeholder="Cari berdasarkan ID atau Nama"
                    value="<?php echo e($query ?? ''); ?>">
            </div>
            <button type="submit" class="btn btn-primary mt-2">Cari</button>
        </form>

        <div class="mt-4">
            <?php if(!$query): ?>
                <p class="text-muted">Silakan masukkan ID atau Nama untuk mencari data pelanggan.</p>
            <?php elseif($pelanggan->isEmpty()): ?>
                <p class="text-muted">Tidak ditemukan hasil untuk "<?php echo e($query); ?>"</p>
            <?php else: ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pelanggan</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Aktivasi</th>
                            <th>Paket</th>
                            <th>Harga</th>
                            <th>Tanggal Tagih</th>
                            <th>Keterangan</th>
                            <th>Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pelanggan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration); ?></td>
                                <td><?php echo e($item->id_plg); ?></td>
                                <td><?php echo e($item->nama_plg); ?></td>
                                <td><?php echo e($item->alamat_plg); ?></td>
                                <td><?php echo e($item->no_telepon_plg); ?></td>
                                <td><?php echo e($item->aktivasi_plg); ?></td>
                                <td><?php echo e($item->paket_plg); ?></td>
                                <td><?php echo e(number_format($item->harga_paket, 0, ',', '.')); ?></td>
                                <td><?php echo e($item->tgl_tagih_plg); ?></td>
                                <td><?php echo e($item->keterangan_plg); ?></td>
                                <td>
                                    <?php echo e(optional($item->pembayaran->last())->tanggal_pembayaran ?? 'Belum Bayar'); ?>

                                </td>
                                <td>
                                    <a href="<?php echo e(route('pelanggan.payment', $item->id)); ?>"
                                        class="btn btn-success btn-sm">Bayar</a>
                                </td>

                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <?php echo e($pelanggan->links()); ?>

            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\automatispayment\index.blade.php ENDPATH**/ ?>