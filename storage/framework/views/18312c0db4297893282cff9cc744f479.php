<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <h4 class="">Edit Data Pelanggan</h4><br>
        <form id="editPelangganForm" action="<?php echo e(route('pelangganof.update', $pelangganof->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <label for="" class="mt mt-3">ID</label>
            <input type="text" name="id_plg" value="<?php echo e($pelangganof->id_plg); ?>" class="form-control " readonly>
            <div class="invalid-feedback" id="id_plgError">Field ID tidak boleh kosong.</div>

            <label for="" class="mt mt-3">Nama Pelanggan</label>
            <input type="text" name="nama_plg" value="<?php echo e($pelangganof->nama_plg); ?>" class="form-control "
                id="nama_plg">
            <div class="invalid-feedback" id="nama_plgError">Field Nama Pelanggan tidak boleh kosong.</div>

            <label for="" class="mt mt-3">Alamat</label>
            <input type="text" name="alamat_plg" value="<?php echo e($pelangganof->alamat_plg); ?>" class="form-control "
                id="alamat_plg">
            <div class="invalid-feedback" id="alamat_plgError">Field Alamat tidak boleh kosong.</div>

            <label for="" class="mt mt-3">No Telpon</label>
            <input type="text" name="no_telepon_plg" value="<?php echo e($pelangganof->no_telepon_plg); ?>" class="form-control "
                id="no_telepon_plg">
            <div class="invalid-feedback" id="no_telepon_plgError">Field No Telpon tidak boleh kosong.</div>

            <label for="" class="mt mt-3">Aktivasi</label>
            <input type="text" name="aktivasi_plg" value="<?php echo e($pelangganof->aktivasi_plg); ?>" class="form-control "
                id="aktivasi_plg">
            <div class="invalid-feedback" id="aktivasi_plgError">Field Aktivasi tidak boleh kosong.</div>

            <label for="" class="mt mt-3">Tanggal Tagih</label>
            <input type="text" name="tgl_tagih_plg" value="<?php echo e($pelangganof->tgl_tagih_plg); ?>" class="form-control "
                id="aktivasi_plg">
            <div class="invalid-feedback" id="aktivasi_plgError">Field Tanggal Tagih tidak boleh kosong.</div>

            <label for="" class="mt mt-3">Paket</label>
            <input type="text" name="paket_plg" value="<?php echo e($pelangganof->paket_plg); ?>" class="form-control "
                id="paket_plg">
            <div class="invalid-feedback" id="paket_plgError">Field Paket tidak boleh kosong.</div>

            <label for="" class="mt mt-3">Harga Paket</label>
            <input type="text" name="harga_paket" value="<?php echo e($pelangganof->harga_paket); ?>" class="form-control "
                id="harga_paket">
            <div class="invalid-feedback" id="harga_paketError">Field Harga Paket tidak boleh kosong.</div>


            <label for="" class="mt mt-3">Status Pembayaran</label>
            <input type="text" name="status_pembayaran" value="<?php echo e($pelangganof->status_pembayaran); ?>" class="form-control "
                id="status_pembayaran">
            <div class="invalid-feedback" id="harga_paketError">Field Harga Paket tidak boleh kosong.</div>


            <label for="" class="mt mt-3">ODP</label>
            <input type="text" name="odp" value="<?php echo e($pelangganof->odp); ?>" class="form-control " id="odp">
            <div class="invalid-feedback" id="odpError">Field ODP tidak boleh kosong. Jika tidak ada, tulis "0".</div>

            <label for="" class="mt mt-3">Latitude</label>
            <input type="text" name="latitude" value="<?php echo e($pelangganof->latitude); ?>" class="form-control "
                id="latitude">
            <div class="invalid-feedback" id="latitudeError">Field Latitude tidak boleh kosong.</div>

            <label for="" class="mt mt-3">Longitude</label>
            <input type="text" name="longitude" value="<?php echo e($pelangganof->longitude); ?>" class="form-control "
                id="longitude">
            <div class="invalid-feedback" id="longitudeError">Field Longitude tidak boleh kosong.</div>

            <label for="" class="mt mt-3">Keterangan</label>
            <input type="text" name="keterangan_plg" value="<?php echo e($pelangganof->keterangan_plg); ?>" class="form-control "
                id="keterangan_plg">
            <div class="invalid-feedback" id="keterangan_plgError">Field Keterangan tidak boleh kosong.</div> <br>

            <button type="submit" class="btn btn-primary btn-sm">Simpan</button> <br> <br> <br>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        document.getElementById('editPelangganForm').addEventListener('submit', function(e) {
            let isValid = true;

            // Function to check field and show error
            function checkField(id) {
                const field = document.getElementById(id);
                const error = document.getElementById(id + 'Error');
                if (field.value.trim() === "") {
                    field.classList.add('is-invalid');
                    error.style.display = 'block';
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    error.style.display = 'none';
                }
            }

            // Check each field
            checkField('nama_plg');
            checkField('alamat_plg');
            checkField('no_telepon_plg');
            checkField('aktivasi_plg');
            checkField('paket_plg');
            checkField('harga_paket');

            // Special case for ODP
            const odp = document.getElementById('odp');
            if (odp.value.trim() === "") {
                odp.value = '0'; // Set default value "0"
            }
            checkField('odp');

            checkField('latitude');
            checkField('longitude');
            checkField('keterangan_plg');

            // Prevent form submission if any field is invalid
            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pelangganof\edit.blade.php ENDPATH**/ ?>