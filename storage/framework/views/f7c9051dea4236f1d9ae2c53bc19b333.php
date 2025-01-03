<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <h6 class="text text-center text-black mt-3"> Tambah Data Pemasukan</h6>
        <form action="<?php echo e(route('pemasukan.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <!-- Input jumlah -->
            <label for="jumlah" class=" mt-2">Jumlah:</label>
            <input type="number" name="jumlah" required class="form-control">

            <!-- Input keterangan -->
            <label for="keterangan" class=" mt-2">Keterangan:</label>
            <input type="text" name="keterangan" required class="form-control"> <br>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </form>


    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pemasukan\create.blade.php ENDPATH**/ ?>