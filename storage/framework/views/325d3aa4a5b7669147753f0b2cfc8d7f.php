<?php $__env->startSection('konten'); ?>
    <div class="mb-4 m-r-4 ml-4">
        <!-- Form Filter dan Pencarian -->
        <form action="<?php echo e(route('kasbon.index')); ?>" method="GET" class="form-inline mb-4 ">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" value="<?php echo e(request('search')); ?>"
                    placeholder="Pencarian">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>
        <h5 class="font font-weight-bold" style="color: black">Data Kasbon</h5>
        <table class=" table table-bordered  font-weight-bold" style="color: black;">
            <thead class="table table-primary font font-weight-bold" style="color: black;">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $kasbon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($no + 1); ?></td>
                        <td><?php echo e($item->nama); ?></td>
                        <td><?php echo e($item->jumlah); ?></td>
                        <td><?php echo e($item->tanggal); ?></td>
                        <td><?php echo e($item->keterangan); ?></td>
                        <td> <a href="<?php echo e(route('kasbon.edit', $item->id)); ?>" class="btn btn-warning btn-sm">Edit</a>

                            <form action="<?php echo e(route('kasbon.destroy', $item->id)); ?>" method="POST" class="d-inline-block">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
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

<?php echo $__env->make('superadmin.layout_superadmin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\karyawan\kasbon\index.blade.php ENDPATH**/ ?>