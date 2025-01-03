<?php $__env->startSection('konten'); ?>
<div class="container">
    <div class="table">
        <div class="mb-4">
            <!-- Form Filter dan Pencarian -->
    <div class="row mb-4">
        <div class="col-md-9">
            <form action="<?php echo e(route('perbaikan.index')); ?>" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control" value="<?php echo e(request('search')); ?>"
                        placeholder="Pencarian">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-3 text-right">
            <a href="" class="btn btn-primary btn-sm">Pemasangan dan Perbaikan</a>
        </div>
    </div>

            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pel</th>
                        <th>Nama Pel</th>
                        <th>Alamat</th>
                        <th>No Hp</th>
                        <th>Paket</th>
                        <th>Odp</th>
                        <th>Maps</th>
                        <th>Keterangan</th>
                        <th>Keterangan</th>
                        <th>
                            <!-- Link untuk sorting -->
                            <a
                                href="<?php echo e(route('perbaikan.index', array_merge(request()->except('sort'), ['sort' => $sort === 'asc' ? 'desc' : 'asc']))); ?>">
                                Tanggal
                                <?php if($sort === 'asc'): ?>
                                    &uarr; <!-- Icon untuk sorting ascending -->
                                <?php else: ?>
                                    &darr; <!-- Icon untuk sorting descending -->
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $perbaikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($no + 1); ?></td>
                            <td><?php echo e($item->id_plg); ?></td>
                            <td><?php echo e($item->nama_plg); ?></td>
                            <td><?php echo e($item->alamat_plg); ?></td>
                            <td><?php echo e($item->no_telepon_plg); ?></td>
                            <td><?php echo e($item->paket_plg); ?></td>
                            <td><?php echo e($item->odp); ?></td>
                            <td><?php echo e($item->maps); ?></td>
                            <td><?php echo e($item->keterangan); ?></td>
                            <td><?php echo e($item->created_at); ?></td>
                            <td>
                                <a href="<?php echo e(route('perbaikan.edit', $item->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                                <form action="<?php echo e(route('perbaikan.destroy', $item->id)); ?>" method="POST"
                                    style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\perbaikan\teknisi.blade.php ENDPATH**/ ?>