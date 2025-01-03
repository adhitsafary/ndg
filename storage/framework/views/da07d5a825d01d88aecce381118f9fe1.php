<?php $__env->startSection('konten'); ?>
    <div class="container mb-4">
        <div class="row ml-0">
            <!-- Form Filter dan Pencarian -->

            <form action="<?php echo e(route('karyawan.index')); ?>" method="GET" class="form-inline mb-4 mr-3" >
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control" value="<?php echo e(request('search')); ?>"
                        placeholder="Pencarian">
                    <div class="input-group-append">

                        <button type="submit" class="btn btn-primary">Cari</button>

                    </div>
                </div>
            </form>

            <a href="/masuk/superadmin/karyawan/create" class="mr-2">
                <button class="btn btn-primary">Tambah karyawan</button>
            </a>
        </div>

        <table class="table table-bordered font-weight-bold" style="color: black;">
            <thead class="table table-primary font-weight-bold" style="color: black;">
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>KTP</th>
                    <th>Alamat</th>
                    <th>No Telpon</th>
                    <th>Posisi</th>
                    <th>Mulai Kerja</th>

                    <th>Gaji</th>
                    <th>Tanggal Gajihan</th>

                    <th>Posisi</th>
                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($no + 1); ?></td>
                        <td>
                            <img src="<?php echo e(asset($item->foto)); ?>" alt="Foto Karyawan" style="width: 50px; height: 50px;">
                        </td>

                        <td><?php echo e($item->nama); ?></td>
                        <td><?php echo e($item->ktp); ?></td>
                        <td><?php echo e($item->alamat); ?></td>
                        <td><?php echo e($item->no_telepon); ?></td>
                        <td><?php echo e($item->posisi); ?></td>
                        <td><?php echo e($item->mulai_kerja); ?></td>
                        <td><?php echo e($item->gaji); ?></td>
                        <td><?php echo e($item->tgl_gajihan); ?></td>
                        <td><?php echo e($item->posisi); ?></td>

                        <td>
                            <a href="<?php echo e(route('karyawan.detail', $item->id)); ?>" class="btn btn-warning btn-sm">Detail
                            </a>
                        </td>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="13" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('superadmin.layout_superadmin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\karyawan\index.blade.php ENDPATH**/ ?>