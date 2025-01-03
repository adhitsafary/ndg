<?php $__env->startSection('konten'); ?>
    <div class=" p-5 ">

        <!--
        <div>
            <div class="ml-2 d-none d-lg-inline text-black text-right">
                <ul class="list-group list-group-flush">
                    <?php if(Auth::user()->role == 'teknisi'): ?>
                        <li class="list-group-item">Menu Teknisi</li>
                    <?php endif; ?>
                    <?php if(Auth::user()->role == 'admin'): ?>
                        <li class="list-group-item">Menu Admin</li>
                    <?php endif; ?>
                    <?php if(Auth::user()->role == 'superadmin'): ?>
                        <li class="list-group-item">Menu SuperAdmin</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div> -->

        <!-- Form Filter dan Pencarian -->
        <div class="row mb-4">
            <div class="col-md-9">
                <form action="<?php echo e(route('teknisi.index')); ?>" method="GET" class="form-inline">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control"
                            value="<?php echo e(request('search')); ?>" placeholder="Pencarian">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-3 text-right">
                <div class="d-flex justify-content-end gap-2">


                    <a class="mt-2 btn btn-danger btn-sm mb-2" href="/logout">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>

                </div>
            </div>
        </div>


        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <!--table-responsive -->

        <table class="table table-bordered " style="color: black;">
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
                            href="<?php echo e(route('teknisi.index', array_merge(request()->except('sort'), ['sort' => $sort === 'asc' ? 'desc' : 'asc']))); ?>">
                            Tanggal
                            <?php if($sort === 'asc'): ?>
                                &uarr; <!-- Icon untuk sorting ascending -->
                            <?php else: ?>
                                &darr; <!-- Icon untuk sorting descending -->
                            <?php endif; ?>
                        </a>
                    </th>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\teknisi\index.blade.php ENDPATH**/ ?>