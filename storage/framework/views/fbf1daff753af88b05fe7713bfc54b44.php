<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Detail Pelanggan Off</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informasi Pelanggan</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>ID:</strong> <?php echo e($pelangganof->id_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Nama:</strong> <?php echo e($pelangganof->nama_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Alamat:</strong> <?php echo e($pelangganof->alamat_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>No Telepon:</strong> <?php echo e($pelangganof->no_telepon_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Aktivasi:</strong> <?php echo e($pelangganof->aktivasi_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Longitude :</strong> <?php echo e($pelangganof->longitude); ?>

                            </li>


                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Detail Paket</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Paket:</strong> <?php echo e($pelangganof->paket_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Harga Paket:</strong><?php echo e(number_format($pelangganof->harga_paket, 0, ',', '.')); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Tanggal Tagih:</strong><?php echo e($pelangganof->tgl_tagih_plg); ?>

                            </li>

                            <li class="list-group-item">
                                <strong>Keterangan :</strong> <?php echo e($pelangganof->keterangan_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>ODP :</strong> <?php echo e($pelangganof->odp); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Latitude:</strong> <?php echo e($pelangganof->latitude); ?>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="<?php echo e(route('aktifkan_pelanggan', $pelangganof->id)); ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Apakah <?php echo e($pelangganof->nama_plg); ?> Akan di Aktifkan kembali?')">Aktifkan</a>
                        <a href="<?php echo e(route('pelangganof.edit', $pelangganof->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form action="<?php echo e(route('pelangganof.destroy', $pelangganof->id)); ?>" method="POST"
                            class="d-inline-block">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                    </div>
                    <a href="<?php echo e(route('pelangganof.index')); ?>" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\baru\dashboard2\resources\views/pelangganof/detail.blade.php ENDPATH**/ ?>