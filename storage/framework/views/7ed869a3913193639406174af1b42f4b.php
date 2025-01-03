

<?php $__env->startSection('konten'); ?>
<div class="container">
    <h4 class="my-4">Data Absensi</h4>

    <?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>


    <table class="table table-bordered table-responsive"
        style="color: black; width: 100%; font-size: 0.85em; table-layout: fixed;">
        <thead class="table table-primary" style="color: black;">
            <tr class="font font-weight-bold">
                <th style="width: 1%; padding: 1px;">No</th>
                <th style="width: 1%; padding: 1px">Nama User</th>
                <th style="width: 1%; padding: 1px">Jam Masuk</th>
                <th style="width: 1%; padding: 1px">Jam Pulang</th>
                <th style="width: 1%; padding: 1px">Titik Lokasi</th>
                <th style="width: 1%; padding: 1px">Foto</th>
                <th style="width: 1%; padding: 1px;">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($loop->iteration); ?></td>
                <td><?php echo e($item->user->name); ?></td>
                <td><?php echo e($item->jam_masuk); ?></td>
                <td><?php echo e($item->jam_pulang); ?></td>
                <td><?php echo e($item->titik_lokasi); ?></td>
                <td>
                    <?php if($item->foto): ?>
                    <img src="<?php echo e(asset($item->foto)); ?>" alt="Foto Absensi" width="50">
                    <?php else: ?>
                    <span>No Foto</span>
                    <?php endif; ?>
                </td>
                <td>
                    <form action="<?php echo e(route('absensi.destroy', $item->id)); ?>" method="POST"
                        class="d-inline-block">
                        <?php echo csrf_field(); ?>
                        <a href="#"
                            onclick="if(confirm('Yakin ingin menghapus data ini?')) { this.closest('form').submit(); return false; }"
                            style="display: inline-block;">
                            <img src="<?php echo e(asset('asset/img/icon/delete.png')); ?>"
                                style="height: 35px; width: 35px;" alt="Hapus">
                        </a>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\absensi\dashboard.blade.php ENDPATH**/ ?>