<?php $__env->startSection('konten'); ?>
    <div class="container mb-4" style="color: black;">
        <form action="<?php echo e(route('pemasukan.index')); ?>" method="GET" class="form-inline mb-4 ">
            <div class="input-group">
                <input style="color: black;" type="text" name="search" id="search" class="form-control"
                    value="<?php echo e(request('search')); ?>" placeholder="Pencarian">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>

        <a href="<?php echo e(route('users.create')); ?>" class="btn btn-success">Buat User Baru</a>

        <div style="display: flex; justify-content: center;" class="mb-3">
            <h5 style="color: black;" class="font font-weight-bold">Data User Login</h5>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <table class="table table-bordered" style="color: black;">
            <thead class="table table-primary" style="color: black;">
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="font font-weight-bold" style="color: black">
                        <td>
                            <img src="<?php echo e(asset($user->foto)); ?>" alt="Foto Pengguna"
                                style="max-width: 60px; max-height: 60px; border-radius: 50%;">
                        </td>
                        <td><?php echo e($user->name); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td><?php echo e(ucfirst($user->role)); ?></td>
                        <td>
                            <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST"
                                style="display:inline-block;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\users\index.blade.php ENDPATH**/ ?>