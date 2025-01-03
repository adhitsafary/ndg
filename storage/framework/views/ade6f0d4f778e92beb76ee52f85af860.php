<?php $__env->startSection('konten'); ?>

<div class="container mt-4">
    <h6 class="text text-center text-black mt-3"> Edit Data kasbon : <?php echo e($kasbon -> nama); ?> </h6>
    <form action="<?php echo e(route('kasbon.update', $kasbon->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <label for="">Nama</label>
        <input type="text" name="nama" value="<?php echo e($kasbon -> nama); ?>"  class="form-control mt-2">
        <label for="">Jumlah</label>
        <input type="text" name="jumlah" value="<?php echo e($kasbon -> jumlah); ?>" class="form-control mt-2">
        <label for="">Tanggal</label>
        <input type="text" name="tanggal" value="<?php echo e($kasbon -> tanggal); ?>" class="form-control mt-2">
        <label for="">Keterangan</label>
        <input type="text" name="tanggal" value="<?php echo e($kasbon -> keterangan); ?>" class="form-control mt-2">

        <button class="btn btn-primary btn-sm">Simpan</button>
    </form>
</div>



<?php $__env->stopSection(); ?>


<?php echo $__env->make('superadmin.layout_superadmin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\karyawan\kasbon\edit.blade.php ENDPATH**/ ?>