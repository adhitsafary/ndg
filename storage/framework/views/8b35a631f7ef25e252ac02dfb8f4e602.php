<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <h6 class="text text-center text-black mt-3"> Edit Data Karyawan : <?php echo e($karyawan->nama); ?> </h6>
        <form action="<?php echo e(route('karyawan.update', $karyawan->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <label for="">Nama Karyawan</label>
            <input type="text" name="nama" value="<?php echo e($karyawan->nama); ?>" class="form-control mt-2">
            <label for="">KTP</label>
            <input type="text" name="ktp" value="<?php echo e($karyawan->ktp); ?>" class="form-control mt-2">
            <label for="">Alamat</label>
            <input type="text" name="alamat" value="<?php echo e($karyawan->alamat); ?>" class="form-control mt-2">
            <label for="">No Telepon</label>
            <input type="text" name="no_telepon" value="<?php echo e($karyawan->no_telepon); ?>" class="form-control mt-2">
            <label for="">Posisi</label>
            <input type="text" name="posisi" value="<?php echo e($karyawan->posisi); ?>" class="form-control mt-2">
            <label for="">Gaji</label>
            <input type="text" name="gaji" value="<?php echo e($karyawan->gaji); ?>" class="form-control mt-2">
            <label for="">Tanggal Gajihan</label>
            <input type="date" name="tgl_gajihan" value="<?php echo e($karyawan->tgl_gajihan); ?>" class="form-control mt-2">

            <label for="">Mulai kerja</label>
            <input type="date" name="mulai_kerja" value="<?php echo e($karyawan->mulai_kerja); ?>" class="form-control mt-2">
            <label for="">Keterangan</label>
            <input type="text" name="keterangan" value="<?php echo e($karyawan->keterangan); ?>" class="form-control mt-2"> <br>
            <div class="form-group">
                <label for="foto">Foto (Biarkan kosong jika tidak ingin mengganti):</label>
                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*">
                <?php if($karyawan->foto): ?>
                    <div class="mt-2">
                        <img src="<?php echo e(asset('storage/' . $karyawan->foto)); ?>" alt="Foto Pengguna"
                            style="max-width: 100px; border-radius: 5px;">
                    </div>
                <?php endif; ?>
            </div>


            <button class="btn btn-primary btn-sm">Simpan</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('superadmin.layout_superadmin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\karyawan\edit.blade.php ENDPATH**/ ?>