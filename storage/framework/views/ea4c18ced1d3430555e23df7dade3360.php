<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <h6 class="text text-center text-black mt-3"> Tambah Data Karyawan</h6>
        <form action="<?php echo e(route('karyawan.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <label for="">Nama Karyawan</label>
            <input type="text" name="nama" class="form-control mt-2">
            <label for="">KTP</label>
            <input type="text" name="ktp" class="form-control mt-2">
            <label for="">Alamat</label>
            <input type="text" name="alamat" class="form-control mt-2">
            <label for="">No Telpon</label>
            <input type="text" name="no_telepon" class="form-control mt-2">
            <label for="">Posisi</label>
            <input type="text" name="posisi" class="form-control mt-2">
            <label for="">Gaji</label>
            <input type="text" name="gaji" class="form-control mt-2">
            <label for="">Tanggal Gajihan</label>
            <input type="text" name="tgl_gajihan" class="form-control mt-2">
            <label for="">Mulai Kerja</label>
            <input type="date" name="mulai_kerja" class="form-control mt-2">
            <label for="">Keterangan</label>
            <input type="text" name="keterangan" class="form-control mt-2"> <br>
            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*" required>
            </div>


            <button class="btn btn-primary btn-sm">Simpan</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('superadmin.layout_superadmin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\karyawan\create.blade.php ENDPATH**/ ?>