<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php if(session('alert')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo e(session('alert')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <h4 style="color: black">Riwayat Pembayaran - <?php echo e($pelanggan->nama_plg); ?></h4>
        <table class="table table-bordered table-responsive mt-3" style="color: black">
            <thead class="table table-primary" style="color: black">
                <tr class="">
                    <th>No</th>
                    <th>ID PEL</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>Metode Pembayaran</th>
                    <th>Tanggal Tagih</th>
                    <th>Paket</th>
                    <th>Jumlah Pembayaran</th>
                    <th>Tanggal Pembayaran</th>
                    <th>Untuk Bulan</th>
                    <th>Keterangan Pembayaran</th>
                    <th>Admin</th>
                    <th>Edit</th>
                    <th>Hapus</th>
                    <th>Print</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $bayar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($no + 1); ?></td>
                        <td><?php echo e($bayar->id_plg); ?></td>
                        <td><?php echo e($bayar->nama_plg); ?></td>
                        <td><?php echo e($bayar->alamat_plg); ?></td>
                        <td><?php echo e($bayar->metode_transaksi); ?></td>
                        <td><?php echo e($bayar->tgl_tagih_plg); ?></td>
                        <td><?php echo e($bayar->paket_plg); ?></td>
                        <td><?php echo e(number_format($bayar->jumlah_pembayaran, 0, ',', '.')); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($bayar->created_at)->locale('id')->translatedFormat('l, d F Y H:i:s')); ?>

                        </td>
                        <td><?php echo e($bayar->tanggal_pembayaran); ?></td>
                        <td><?php echo e($bayar->keterangan_plg); ?></td>
                        <td><?php echo e($bayar->admin_name); ?></td>
                        <td>
                            <a href="<?php echo e(route('pembayaran.edit', $bayar->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                        <td>
                            <form action="<?php echo e(route('pembayaran.destroy', $bayar->id)); ?>" method="POST"
                                class="d-inline-block">
                                <?php echo csrf_field(); ?>

                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm"
                                onclick="printPayment(<?php echo e($no + 1); ?>, '<?php echo e($bayar->nama_plg); ?>')">Print</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <a href="/pelanggan" class="btn btn-primary">
            << Kembali</a>
    </div>

    <!-- QRCode.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        function printPayment(rowNumber, namaPelanggan) {
            // Get data from the row
            var row = document.querySelectorAll('table tbody tr')[rowNumber - 1];
            var id = row.cells[1].innerText;
            var nama = row.cells[2].innerText;
            var alamat = row.cells[3].innerText;
            var metode_transaksi = row.cells[4].innerText;
            var tglBayar = row.cells[8].innerText;
            var jumlahBayar = row.cells[7].innerText;

            // Create the print content resembling the DANA receipt format with additional logo and colors
            var printContent = `
              <div style="border: 1px solid #000; padding: 20px; width: 350px; margin: 0 auto; font-family: Arial, sans-serif;">
    <!-- Bagian Header -->
    <div style="border-bottom: 2px solid #000; text-align: center; padding-bottom: 10px; margin-bottom: 10px;">
        <h2 style="font-size: 16px; margin: 0;">KWITANSI PEMBAYARAN INTERNET BULANAN</h2>
    </div>

    <!-- Bagian untuk logo dan teks "NetNet Digital" -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <!-- Logo di sebelah kiri -->
        <div style="flex: 1; text-align: left;">
            <img src="<?php echo e(asset('asset/img/netnet.jpg')); ?>" style="height: 30px; width: 30px;">
        </div>

        <!-- Teks "NetNet Digital" di tengah -->
        <div style="flex: 2; text-align: center;">
            <h2 style="font-size: 16px; margin: 0;">NetNet Digital</h2>
        </div>

        <!-- Logo di sebelah kanan -->
        <div style="flex: 1; text-align: right;">
            <img src="<?php echo e(asset('asset/img/logo_hayat.png')); ?>" style="height: 30px; width: 60px;">
        </div>
    </div>

    <!-- Informasi pelanggan -->
    <p><strong>ID Pelanggan:</strong> ${id}</p>
    <p><strong>Nama Pelanggan:</strong> ${nama}</p>
    <p><strong>Alamat:</strong> ${alamat}</p>
    <p><strong>Metode Pembayaran:</strong> ${metode_transaksi}</p>
    <p><strong>Tanggal Pembayaran:</strong> ${tglBayar}</p>
    <p><strong>Jumlah Pembayaran:</strong> Rp ${jumlahBayar}</p>

    <!-- QR Code -->
    <div class="text-center" id="qrcode" style="text-align: center; margin: 20px 0;"></div>

    <!-- Ucapan terima kasih -->
    <div style="text-align: center; font-size: 12px;">
        <p>Terima kasih telah melakukan pembayaran</p>
        <p>--- NetNet Digital ---</p>
    </div>
</div>

            `;

            // Open new window for printing
            var printWindow = window.open('', '', 'height=600,width=400');
            printWindow.document.write('<html><head><title>Struk Pembayaran</title>');
            printWindow.document.write('<style>body { font-family: Arial, sans-serif; }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Wait for the window to load
            printWindow.onload = function() {
                // Print after loading
                printWindow.print();
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pelanggan\historypembayaran.blade.php ENDPATH**/ ?>