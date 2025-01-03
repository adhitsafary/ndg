<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <h6 class="text text-center text-black mt-3"> Tambah Data Karyawan</h6>
        <form action="<?php echo e(route('kasbon.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <!-- Input hidden untuk id_karyawan -->
            <input type="hidden" name="id_karyawan" value="<?php echo e($karyawan->id); ?>"  class="form-control">

            <!-- Nama karyawan -->
            <label for="nama" class=" mt-2">Nama Karyawan:</label>
            <input type="text" name="nama" value="<?php echo e($karyawan->nama); ?>" readonly class="form-control">

            <!-- Input jumlah -->
            <label for="jumlah" class=" mt-2">Jumlah:</label>
            <input type="number" name="jumlah" required class="form-control">

            <!-- Input tanggal -->
            <label for="tanggal" class=" mt-2">Tanggal:</label>
            <input type="date" name="tanggal" required class="form-control ">

            <!-- Input keterangan -->
            <label for="keterangan" class=" mt-2">Keterangan:</label>
            <input type="text" name="keterangan" required class="form-control"> <br>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </form>


    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('superadmin.layout_superadmin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\karyawan\kasbon\create.blade.php ENDPATH**/ ?>