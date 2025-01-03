<!DOCTYPE html>
<html>
<head>
    <title>Data Pembayaran</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Data Pembayaran</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Tanggal Pembayaran</th>
                <th>Jumlah Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($no + 1); ?></td>
                    <td><?php echo e($item->nama_plg); ?></td>
                    <td><?php echo e($item->alamat_plg); ?></td>
                    <td><?php echo e($item->tanggal_pembayaran); ?></td>
                    <td><?php echo e(number_format($item->jumlah_pembayaran, 0, ',', '.')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pembayaran\pdf.blade.php ENDPATH**/ ?>