<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perhitungan Gaji</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Hasil Perhitungan Gaji</h2>
        <form action="<?php echo e(url('/hitung-gaji')); ?>" method="GET">
            <div class="mb-3">
                <label for="user_id" class="form-label">Nama Karyawan</label>
                <select name="user_id" id="user_id" class="form-select" required>
                    <option value="" disabled selected>Pilih Karyawan</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->karyawan->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Hitung Gaji</button>
        </form>

        <?php if(isset($gaji)): ?>
            <div class="mt-5">
                <h4>Detail Gaji:</h4>
                <ul>
                    <li><strong>Nama Karyawan:</strong> <?php echo e($gaji['karyawan']); ?></li>
                    <li><strong>Total Hadir:</strong> <?php echo e($gaji['total_hadir']); ?></li>
                    <li><strong>Total Tidak Hadir:</strong> <?php echo e($gaji['total_tidak_hadir']); ?></li>
                    <li><strong>Gaji Bersih:</strong> Rp<?php echo e(number_format($gaji['gaji_bersih'], 0, ',', '.')); ?></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\absensi\gaji.blade.php ENDPATH**/ ?>