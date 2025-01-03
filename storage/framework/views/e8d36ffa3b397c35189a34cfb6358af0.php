<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Redirect</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let routes = [
                '<?php echo e(route('index')); ?>',
                '<?php echo e(route('teknisi')); ?>',
                '<?php echo e(route('pelanggan.index')); ?>'
            ];

            let index = 0;

            function redirect() {
                window.location.href = routes[index]; // Redirect ke route yang sesuai
                index = (index + 1) % routes.length; // Reset ke 0 setelah mencapai akhir
            }

            setTimeout(redirect, 0); // Mulai langsung saat halaman dimuat
            setInterval(redirect, 10000); // Pindah route setiap 10 detik
        });
    </script>
</head>
<body>
    <h1>Auto Redirect Demo</h1>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pindahroute.blade.php ENDPATH**/ ?>