<?php $__env->startSection('konten'); ?>

<div class="container mt-4">
    <h6 class="text text-center text-black mt-3"> Edit Data rekap pemasangan : <?php echo e($rekap_pemasangan -> nama); ?> </h6>
    <form action="<?php echo e(route('rekap_pemasangan.update', $rekap_pemasangan->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>


        <label for="">ID Pelanggan</label>
        <input type="text" name="id_plg" value="<?php echo e($rekap_pemasangan -> id_plg); ?>" class="form-control mt-2">

        <label for="">nik</label>
        <input type="text" name="nik" value="<?php echo e($rekap_pemasangan -> nik); ?>" class="form-control mt-2">

        <label for="">Nama</label>
        <input type="text" name="nama" value="<?php echo e($rekap_pemasangan -> nama); ?>" class="form-control mt-2">
        <label for="">Alamat</label>
        <input type="text" name="alamat" value="<?php echo e($rekap_pemasangan -> alamat); ?>" class="form-control mt-2">

        <label for="">No Telepon</label>
        <input type="text" name="no_telpon" value="<?php echo e($rekap_pemasangan -> no_telpon); ?>" class="form-control mt-2">
        <label for="">Aktivasi</label>
        <input type="date" name="tgl_aktivasi" value="<?php echo e($rekap_pemasangan -> tgl_aktivasi); ?>" class="form-control mt-2">

        <label for="">Paket</label>
        <input type="text" name="paket_plg" value="<?php echo e($rekap_pemasangan -> paket_plg); ?>" class="form-control mt-2">
        <label for="">Harga Paket</label>
        <input type="text" name="harga_paket" value="<?php echo e($rekap_pemasangan -> harga_paket); ?>" class="form-control mt-2">

        <label for="">Jatuh Tempo</label>
        <input type="text" name="jt" value="<?php echo e($rekap_pemasangan -> jt); ?>" class="form-control mt-2">
        <label for="">Status</label>
        <input type="text" name="status" value="<?php echo e($rekap_pemasangan -> status); ?>" class="form-control mt-2">

        <label for="">Pengajuan</label>
        <input type="date" name="tgl_pengajuan" value="<?php echo e($rekap_pemasangan -> tgl_pengajuan); ?>" class="form-control mt-2">
        <label for="">Registrasi</label>
        <input type="text" name="registrasi" value="<?php echo e($rekap_pemasangan -> registrasi); ?>" class="form-control mt-2">

        <label for="">Marketing</label>
        <input type="text" name="marketing" value="<?php echo e($rekap_pemasangan -> marketing); ?>" class="form-control mt-2"> <br>


        <button class="btn btn-primary btn-sm">Simpan</button> <br><br>
    </form>
</div>



<?php $__env->stopSection(); ?>


<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\rekap_pemasangan\edit.blade.php ENDPATH**/ ?>