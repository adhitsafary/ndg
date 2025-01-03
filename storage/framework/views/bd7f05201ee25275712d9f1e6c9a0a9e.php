<!DOCTYPE html>
<html>
<head>
    <title>Rekap Data Teknisi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
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
  
    <h4 class="text-center">Dari <?php echo e($startDate->format('d/m/Y')); ?> sampai <?php echo e($endDate->format('d/m/Y')); ?></h4>
    <table>
        <thead>
            <tr>
                <th>Teknisi</th>
                <th>Total Perbaikan</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $rekap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>Tim <?php echo e($data->teknisi); ?></td>
                    <td><?php echo e($data->total); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <h4 class="text-right">Total Perbaikan: <?php echo e($totalPerbaikan); ?></h4>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\perbaikan\print_rekap_teknisi.blade.php ENDPATH**/ ?>