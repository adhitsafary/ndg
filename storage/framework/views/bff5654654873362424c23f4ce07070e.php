
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Piutang Hari Ini</title>
</head>
<body>

    <h1>Total Pembayaran Piutang Hari Ini</h1>

    <p><strong>Total Jumlah Pembayaran Piutang: Rp <?php echo e(number_format($totalPembayaran, 0, ',', '.')); ?></strong></p>
    <p><strong>Jumlah Pelanggan yang Membayar Piutang Hari Ini: <?php echo e($jumlahPelanggan); ?></strong></p>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pembayaran_mudah\coba.blade.php ENDPATH**/ ?>