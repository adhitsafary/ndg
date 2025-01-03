<?php $__env->startSection('konten'); ?>

<div class="container mt-4">
    <h6 class="text text-center text-black mt-3"> Edit Data Pengeluaran : <?php echo e($pengeluaran -> nama); ?> </h6>
    <form action="<?php echo e(route('pengeluaran.update', $pengeluaran->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <label for="">Jumlah</label>
        <input type="text" name="jumlah" value="<?php echo e($pengeluaran -> jumlah); ?>" class="form-control mt-2">

        <label for="">Keterangan</label>
        <input type="text" name="keterangan" value="<?php echo e($pengeluaran -> keterangan); ?>" class="form-control mt-2">

        <button class="btn btn-primary btn-sm">Simpan</button>
    </form>
</div>



<?php $__env->stopSection(); ?>


<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pengeluaran\edit.blade.php ENDPATH**/ ?>