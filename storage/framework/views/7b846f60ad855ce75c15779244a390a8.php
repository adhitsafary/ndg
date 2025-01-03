<?php $__env->startSection('konten'); ?>
    <div class="container">
        <h1>Mutasi Harian Periode <?php echo e(now()->format('F Y')); ?></h1>

        <table class="table table-bordered table-responsive">
            <thead class="table table-primary">
                <tr>
                    <th>Tanggal</th>
                    <th>Tagihan Awal</th>
                    <th>Pemasukan Agustus</th>
                    <th>Pemasukan September</th>
                    <th>Saldo Akhir</th>
                    <th>Total Koreksi</th>
                    <th>Belum Tertagih</th>
                    <th>Pemasukan Harian</th>
                    <th>Piutang</th>
                    <th>Piutang Masuk</th>
                    <th>Pendapatan Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $rekapMutasiHarian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rekap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($rekap['tanggal']); ?></td>
                        <td><?php echo e(number_format($rekap['tagihan_awal'], 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($rekap['pemasukan_agustus'], 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($rekap['pemasukan_september'], 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($rekap['saldo_akhir'], 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($rekap['total_koreksi'], 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($rekap['belum_tertagih'], 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($rekap['pemasukan_harian'], 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($rekap['piutang'], 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($rekap['piutang_masuk'], 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($rekap['pendapatan_total'], 2)); ?>%</td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\rekap_mutasi_harian\index.blade.php ENDPATH**/ ?>