<?php $__env->startSection('konten'); ?>
    <div class=" p-5 mt-4">
        <div class="row mb-4">
            <div class="col-md-9">
                <form method="GET" action="<?php echo e(route('pembayaran.filter')); ?>" class="form-inline">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control"
                            value="<?php echo e(request('search')); ?>" placeholder="Pencarian">
                        <div class="form-group">
                            <input type="date" name="date_start" id="date_start" class="form-control"
                                value="<?php echo e($date_start); ?>">
                        </div>
                        <div class="form-group">
                            <input type="date" name="date_end" id="date_end" class="form-control"
                                value="<?php echo e($date_end); ?>">
                        </div>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3 text-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Ekspor
                    </button>
                    <div class="dropdown-menu">
                        <a href="<?php echo e(route('pembayaran.export', ['format' => 'pdf', 'date_start' => request('date_start'), 'date_end' => request('date_end')])); ?>"
                            class="dropdown-item">PDF</a>
                        <a href="<?php echo e(route('pembayaran.export', ['format' => 'excel', 'date_start' => request('date_start'), 'date_end' => request('date_end')])); ?>"
                            class="dropdown-item">Excel</a>
                    </div>
                </div>
            </div>
        </div>
        <div style="color: black" class="h6 font-weight-bold">
            <strong>Total Jumlah Pembayaran : </strong> <?php echo e(number_format($totalJumlahPembayaran)); ?>

            <strong class="mr-5"></strong>
            <strong>Total Jumlah Pelanggan Bayar : </strong> <?php echo e($totalPelanggan); ?>

        </div>

        <div style="display: flex; justify-content: center;" class="mb-2">

            <h5 style="color: black;" class="font font-weight-bold">Data Pembayaran</h5>
        </div>

        <!-- Tabel Pembayaran -->
        <table class="table table-bordered table-responsive" style="color: black;">
            <thead class="table table-primary " style="color: black;">
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>No Telp</th>
                    <th>Alamat</th>
                    <th>
                        <form class="filterForm" method="GET" action="<?php echo e(route('pembayaran.filter')); ?>">
                            <div class="form-group">
                                <select name="paket_plg" id="paket_plg" onchange="this.form.submit();">
                                    <option value="">Paket</option>
                                    <?php for($i = 1; $i <= 7; $i++): ?>
                                        <option value="<?php echo e($i); ?>"
                                            <?php echo e(request('paket_plg') == $i ? 'selected' : ''); ?>>
                                            <?php echo e($i); ?>

                                        </option>
                                    <?php endfor; ?>
                                    <option value="vcr" <?php echo e(request('paket_plg') == 'vcr' ? 'selected' : ''); ?>>
                                        vcr
                                    </option>
                                </select>
                            </div>
                        </form>
                        Paket
                    </th>
                    <th>
                        <form id="filterForm" method="GET" action="<?php echo e(route('pembayaran.filter')); ?>">
                            <div class="form-group">

                                <select name="bulan" id="bulan"
                                    onchange="document.getElementById('filterForm').submit();">
                                    <option value="">Pilih Bulan</option>
                                    <?php $__currentLoopData = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bulan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($index + 1); ?>"
                                            <?php echo e(request('bulan') == $index + 1 ? 'selected' : ''); ?>>
                                            <?php echo e($bulan); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </form>
                        <label for="bulan">Bulan Bayar Terakhir</label>
                    </th>

                    <th>

                        <form class="filterForm" method="GET" action="<?php echo e(route('pembayaran.filter')); ?>">
                            <div class="form-group">
                                <input type="date" name="created_at" id="created_at" value="<?php echo e(request('created_at')); ?>"
                                    onchange="this.form.submit();">
                            </div>
                        </form>
                        Tanggal Pembayaran
                    </th>
                    <th>
                        <form class="filterForm" method="GET" action="<?php echo e(route('pembayaran.filter')); ?>">
                            <div class="form-group">
                                <select name="tgl_tagih_plg" id="tgl_tagih_plg" onchange="this.form.submit();">
                                    <option value="">Tanggal Tagih</option>
                                    <?php for($i = 1; $i <= 33; $i++): ?>
                                        <option value="<?php echo e($i); ?>"
                                            <?php echo e(request('tgl_tagih_plg') == $i ? 'selected' : ''); ?>>
                                            <?php echo e($i); ?>

                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </form>
                        Tanggal Tagih
                    </th>
                    <th>
                        <form class="filterForm" method="GET" action="<?php echo e(route('pembayaran.filter')); ?>">
                            <div class="form-group">
                                <select name="jumlah_pembayaran" id="jumlah_pembayaran" onchange="this.form.submit();">
                                    <option value="">Harga</option>

                                    <option value="50000" <?php echo e(request('jumlah_pembayaran') == '50000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(50000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="75000" <?php echo e(request('jumlah_pembayaran') == '75000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(75000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="100000"
                                        <?php echo e(request('jumlah_pembayaran') == '100000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(100000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="105000"
                                        <?php echo e(request('jumlah_pembayaran') == '105000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(105000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="115000"
                                        <?php echo e(request('jumlah_pembayaran') == '115000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(115000, 0, ',', '.')); ?>

                                    </option>

                                    <option value="120000"
                                        <?php echo e(request('jumlah_pembayaran') == '120000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(120000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="125000"
                                        <?php echo e(request('jumlah_pembayaran') == '125000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(125000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="150000"
                                        <?php echo e(request('jumlah_pembayaran') == '150000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(150000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="165000"
                                        <?php echo e(request('jumlah_pembayaran') == '165000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(165000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="175000"
                                        <?php echo e(request('jumlah_pembayaran') == '175000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(175000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="205000"
                                        <?php echo e(request('jumlah_pembayaran') == '205000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(205000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="250000"
                                        <?php echo e(request('jumlah_pembayaran') == '250000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(250000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="265000"
                                        <?php echo e(request('jumlah_pembayaran') == '265000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(265000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="305000"
                                        <?php echo e(request('jumlah_pembayaran') == '305000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(305000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="750000"
                                        <?php echo e(request('jumlah_pembayaran') == '750000' ? 'selected' : ''); ?>>
                                        <?php echo e(number_format(750000, 0, ',', '.')); ?>

                                    </option>
                                    <option value="vcr" <?php echo e(request('jumlah_pembayaran') == 'vcr' ? 'selected' : ''); ?>>
                                        vcr
                                    </option>

                                </select>
                            </div>
                        </form>
                        Jumlah Pembayaran
                    </th>
                    <th>Metode Pembayaran</th>
                    <th>Keterangan</th>
                    <th>Admin</th>
                    <th>Edit</th>
                    <th>Hapus</th>
                    <th>Print</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e(($pembayaran->currentPage() - 1) * $pembayaran->perPage() + $loop->iteration); ?></td>
                        <td><?php echo e($item->nama_plg); ?></td>
                        <td><?php echo e($item->no_telepon_plg); ?></td>
                        <td><?php echo e($item->alamat_plg); ?></td>
                        <td><?php echo e($item->paket_plg); ?></td>
                        <!-- ambil bulan saja dan convert ke text misal bulan 09 jadi september -->
                        <td><?php echo e(\Carbon\Carbon::parse($item->tanggal_pembayaran)->format('F')); ?></td>
                        <td><?php echo e($item->created_at); ?></td>
                        <td><?php echo e($item->tgl_tagih_plg); ?></td>
                        <td><?php echo e(number_format($item->jumlah_pembayaran, 0, ',', '.')); ?></td>
                        <td><?php echo e($item->metode_transaksi); ?></td>
                        <td><?php echo e($item->keterangan_plg); ?></td>
                        <td><?php echo e($item->admin_name); ?></td>
                        <td>
                            <a href="<?php echo e(route('pembayaran.edit', $item->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                        <td>
                            <form action="<?php echo e(route('pembayaran.destroy', $item->id)); ?>" method="POST"
                                class="d-inline-block">
                                <?php echo csrf_field(); ?>

                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm"
                                onclick="printPayment(<?php echo e($no + 1); ?>, '<?php echo e($item->nama_plg); ?>')">Print</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data pembayaran ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        <?php echo e($pembayaran->links('pagination::bootstrap-4')); ?>

    </div>

    <script>
        function printPayment(rowNumber, namaPelanggan) {
            var row = document.querySelectorAll('table tbody tr')[rowNumber - 1];
            var nama = row.cells[1].innerText;
            var alamat = row.cells[2].innerText;
            var tglBayar = row.cells[3].innerText;
            var jumlahBayar = row.cells[4].innerText;

            var printContent = `
        <div style="border: 1px solid #000; padding: 20px; width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">
            <div style="border-bottom: 2px solid #000; text-align: center; padding-bottom: 10px; margin-bottom: 10px;">
                <h2 style="font-size: 16px; margin: 0;">KWITANSI PEMBAYARAN INTERNET BULANAN</h2>
            </div>
            <p><strong>Nama Pelanggan:</strong> ${nama}</p>
            <p><strong>Alamat:</strong> ${alamat}</p>
            <p><strong>Tanggal Pembayaran:</strong> ${tglBayar}</p>
            <p><strong>Jumlah Pembayaran:</strong> Rp ${jumlahBayar}</p>
        </div>
        `;

            var printWindow = window.open('', '', 'height=600,width=400');
            printWindow.document.write('<html><head><title>Struk Pembayaran</title></head><body>');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\psb\index.blade.php ENDPATH**/ ?>