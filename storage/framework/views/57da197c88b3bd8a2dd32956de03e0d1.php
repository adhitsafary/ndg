<?php $__env->startSection('konten'); ?>
    <div class="container mb-4">
        <!-- Form Filter dan Pencarian -->
        <form action="<?php echo e(route('pengeluaran.index')); ?>" method="GET" class="form-inline mb-4 ">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" value="<?php echo e(request('search')); ?>"
                    placeholder="Pencarian">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>

        <a href="/pengeluaran/create" class="btn btn-success">Buat Pengeluaran</a>
        <a href="/pemasukan" class="btn btn-success">Data pemasukan</a>
        <div style="display: flex; justify-content: center;" class="mb-3">

            <h5 style="color: black;" class="font font-weight-bold">Data Pengeluaran</h5>
        </div>

        <table class="table table-bordered" style="color: black;">
            <thead class="table table-primary " style="color: black;">
                <tr>
                    <th>No</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pengeluaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($no + 1); ?></td>
                        <td><?php echo e(number_format($item->jumlah)); ?></td>
                        <td><?php echo e($item->keterangan); ?></td>
                        <td><?php echo e($item->created_at); ?></td>
                        <td> <a href="<?php echo e(route('pengeluaran.edit', $item->id)); ?>" class="btn btn-warning btn-sm">Edit</a>

                            <form action="<?php echo e(route('pengeluaran.destroy', $item->id)); ?>" method="POST"
                                class="d-inline-block">
                                <?php echo csrf_field(); ?>
                         
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

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pengeluaran\index.blade.php ENDPATH**/ ?>