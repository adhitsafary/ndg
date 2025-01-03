<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <h3 class="text text-center text-black mt-3"> Edit Data Pembayaran : <?php echo e($pembayaran->nama_plg); ?> </h3>
        <form action="<?php echo e(route('pembayaran.update', $pembayaran->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <label for="paket_plg">Paket Pelanggan</label>
            <input type="text" name="paket_plg" value="<?php echo e(old('paket_plg', $pembayaran->paket_plg)); ?>"
                class="form-control mt-2">

            <label for="jumlah_pembayaran">Jumlah Pembayaran</label>
            <input type="text" name="jumlah_pembayaran"
             value="<?php echo e(old('jumlah_pembayaran', $pembayaran->jumlah_pembayaran)); ?>" class="form-control mt-2">

            <label for="metode_transaksi">Metode Transaksi</label>
            <input type="text" name="metode_transaksi"
            value="<?php echo e(old('metode_transaksi', $pembayaran->metode_transaksi)); ?>" class="form-control mt-2">

            <label for="keterangan_plg">Keterangan Pelanggan</label>
            <input type="text" name="keterangan_plg" value="<?php echo e(old('keterangan_plg', $pembayaran->keterangan_plg)); ?>"
            class="form-control mt-2">

            <label for="created_at">Tanggal</label>
            <input type="datetime-local" name="created_at"
            value="<?php echo e(old('created_at', $pembayaran->created_at->format('Y-m-d\TH:i'))); ?>" class="form-control mt-2">

            <label for="tanggal_pembayaran">Bayar Untuk Bulan</label>
            <input type="month" name="tanggal_pembayaran"

            value="<?php echo e(old('tanggal_pembayaran', \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('Y-m'))); ?>" class="form-control mt-2">




            <button class="btn btn-primary btn-sm mt-4">Simpan</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('superadmin.layout_superadmin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pembayaran\edit.blade.php ENDPATH**/ ?>