<?php $__env->startSection('konten'); ?>
    <div class="pl-5 pr-5">
        <div class="mb-4">
            <!-- Form Filter dan Pencarian -->
            <div class="row mb-4">
                <div class="col-md-9">
                    <form action="<?php echo e(route('perbaikan.index')); ?>" method="GET" class="form-inline">
                        <div class="input-group" style="color: black;">
                            <input style="color: black;" type="text" name="search" id="search"
                                class="form-control font-weight-bold" value="<?php echo e(request('search')); ?>"
                                placeholder="Pencarian">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-danger">Cari</button>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="col-md-3 text-right">
                    <a href="/perbaikan/create"  class="btn btn-danger">Buat Perbaikan</a>
                    <a href="/rekap-teknisi" class="btn btn-danger ">Rekap Bulanan Teknisi</a>
                </div>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <!-- Tabel Perbaikan -->
        <table class="table table-bordered table-responsive " style="color: black;">
            <thead class="table table-danger" style="color: black;">
                <tr>
                    <th>No</th>
                    <th>ID Pel</th>
                    <th>Nama Pel</th>
                    <th>Alamat</th>
                    <th>No Hp</th>
                    <th>Paket</th>
                    <th>Odp</th>
                    <th>Maps</th>
                    <th>Teknisi</th>
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
                    <th>Status</th>
                    <th>Selesai</th>
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
                        <td><?php echo e($item->teknisi); ?></td>
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
                        <td><?php echo e(ucfirst($item->status)); ?></td> <!-- Menampilkan status -->
                        <td>
                            <?php if($item->status == 'Proses'): ?>
                                <!-- Hanya tampilkan tombol jika statusnya Proses -->
                                <form action="<?php echo e(route('perbaikan.selesai', $item->id)); ?>" method="POST"
                                    class="d-inline-block">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-success btn-sm">Selesai</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="12" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\perbaikan\index.blade.php ENDPATH**/ ?>