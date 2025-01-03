<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Detail Karyawan</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 style="color: black" class="fotn font-weight-bold">Informasi Karyawan</h5>
                        <ul class="list-group  font-weight-bold" style="color: black">
                            <li class="list-group-item">
                                <strong>Nama :</strong> <?php echo e($karyawan->nama); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>KTP :</strong> <?php echo e($karyawan->ktp); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Alamat :</strong> <?php echo e($karyawan->alamat); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>No Telepon :</strong> <?php echo e($karyawan->no_telepon); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Gaji : </strong><?php echo e(number_format($karyawan->gaji, 0, ',', '.')); ?>

                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5  style="color: black" class=" font-weight-bold">Detail Karyawan</h5>
                        <ul class="list-group  font-weight-bold" style="color: black">
                            <li class="list-group-item">
                                <strong>Posisi :</strong> <?php echo e($karyawan->posisi); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Mulai Kerja :</strong> <?php echo e($karyawan->mulai_kerja); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Keterangan :</strong> <?php echo e($karyawan->keterangan); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Tanggal Gajihan :</strong> <?php echo e($karyawan->tgl_gajihan); ?>

                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tabel Riwayat Kasbon -->
                <h5 class="mt-4 font-weight-bold" style=" color: black">Riwayat Kasbon</h5>
                <table class="table table-bordered font-weight-bold" style="color: black">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jumlah Kasbon</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $kasbon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($item->tanggal); ?></td>
                                <td><?php echo e(number_format($item->jumlah, 0, ',', '.')); ?></td>
                                <td><?php echo e($item->keterangan); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                    <!-- Tabel Riwayat Kasbon -->
                    <h5 class="mt-4 font-weight-bold" style=" color: black">Hitungan Gaji</h5>
                    <table class="table table-bordered font-weight-bold" style="color: black">
                        <thead>
                            <tr>
                                <th>Total Gaji</th>
                                <th>Total Kasbon</th>
                                <th>Gaji Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo e(number_format($karyawan->gaji, 0, ',', '.')); ?></td>
                                <td style="color: darkred" class="font font-weight-bold"> <?php echo e(number_format($totalKasbon, 0, ',', '.')); ?></td>
                                <td> <?php echo e(number_format($karyawan->gaji - $totalKasbon, 0, ',', '.')); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    


            </div>
            <div class="card-footer text-right">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="<?php echo e(route('karyawan.edit', $karyawan->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?php echo e(route('kasbon.create', $karyawan->id)); ?>" class="btn btn-sm btn-primary">Tambah
                            Kasbon</a>
                        <form action="<?php echo e(route('karyawan.destroy', $karyawan->id)); ?>" method="POST"
                            class="d-inline-block">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                    </div>
                    <a href="<?php echo e(route('karyawan.index')); ?>" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('superadmin.layout_superadmin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\karyawan\detail.blade.php ENDPATH**/ ?>