<?php $__env->startSection('konten'); ?>
    <div class="container">
        <h1 class="my-4">Tambah Pemberitahuan</h1>

        <form action="<?php echo e(route('pemberitahuan.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>


            <div class="mb-3">
                <label for="pesan" class="form-label">Pesan</label>
                <textarea name="pesan" class="form-control" id="pesan" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo e(route('pemberitahuan.index')); ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pemberitahuan\create.blade.php ENDPATH**/ ?>