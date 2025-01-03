<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <form action="<?php echo e(route('perbaikan.update', $perbaikan->id)); ?>" method="POST">
            <?php echo csrf_field(); ?><br>
            <h6>Edit Data Perbaikan</h6><br>
            <label for="">ID</label>
            <input type="text" name="id_plg" value="<?php echo e($perbaikan->id_plg); ?>" class="form-control mt-2">
            <label for="">Nama Pelanggan</label>
            <input type="text" name="nama_plg" value="<?php echo e($perbaikan->nama_plg); ?>" class="form-control mt-2">
            <label for="">Alamat</label>
            <input type="text" name="alamat_plg" value="<?php echo e($perbaikan->alamat_plg); ?>" class="form-control mt-2">
            <label for="">No Telpon</label>
            <input type="text" name="no_telepon_plg" value="<?php echo e($perbaikan->no_telepon_plg); ?>" class="form-control mt-2">
            <label for="">Paket</label>
            <input type="text" name="paket_plg" value="<?php echo e($perbaikan->paket_plg); ?>" class="form-control mt-2">
            <label for="">ODP</label>
            <input type="text" name="odp" value="<?php echo e($perbaikan->odp); ?>" class="form-control mt-2">
            <label for="">Maps</label>
            <input type="text" name="maps" value="<?php echo e($perbaikan->maps); ?>" class="form-control mt-2">
            <label for="">Teknisi</label>

            <div class="form-group">
                <label for="teknisi">Teknisi</label>
                <select id="teknisi" name="teknisi" class="form-control mt-2">
                    <option value="">Pilih Teknisi</option> <!-- Opsi kosong untuk tidak memilih teknisi -->
                    <option value="Tim 1 Deden - Agis">Tim 1 Deden - Agis</option>
                    <option value="Tim 2 Mursidi - Dindin">Tim 2 Mursidi - Dindin</option>
                    <option value="Tim 3 Isep - Indra">Tim 3 Isep - Indra</option>
                </select>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <select id="keterangan" name="keterangan" class="form-control mt-2">
                    <option value="">Pilih Gangguan</option>
                    <option value="Modem error / matot">Modem error / matot</option>
                    <option value="Los / modem merah">Los / modem merah</option>
                    <option value="PSB">PSB</option>
                </select>
                <div class="invalid-feedback" id="keteranganError">Field Keterangan tidak boleh kosong, bila tidak ada tulis
                    "0".</div>
            </div>
            <button class="btn btn-primary btn-sm">Simpan</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\perbaikan\edit.blade.php ENDPATH**/ ?>