<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1 class="mb-4">Halaman Pembayaran</h1>

        <!-- Informasi Pelanggan -->
        <div class="card">
            <div class="card-body">
                <h4>Nama: <?php echo e($pelanggan->nama_plg); ?></h4>
                <p>Alamat: <?php echo e($pelanggan->alamat_plg); ?></p>
                <p>Paket: <?php echo e($pelanggan->paket_plg); ?></p>
                <p>Harga Paket: <?php echo e(number_format($pelanggan->harga_paket, 0, ',', '.')); ?></p>
                <p>Bulan Terakhir Pembayaran: <?php echo e($lastMonth); ?></p>
            </div>
        </div>

        <!-- Form Proses Pembayaran -->
        <form action="<?php echo e(route('pelanggan.processPayment', $pelanggan->id)); ?>" method="POST" class="mt-4">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-primary btn-lg">Bayar</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\automatispayment\payment.blade.php ENDPATH**/ ?>