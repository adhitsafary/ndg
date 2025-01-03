<?php $__env->startSection('konten'); ?>
<div class="  pl-5 pr-5 mb-4">
    <!-- Form Filter dan Pencarian -->
    <div class="row align-items-center">
        <table class="table table-bordered mt-2">
            <thead class="custom-cell head">
                <tr>
                    <th>Total Filter</th>
                    <th>Total Sudah Bayar</th>
                    <th>Total Belum Bayar</th>
                    <th>Total Isolir</th>
                <!-- <th>Total Block</th>
                    <th>Total Unblock</th> -->
                    <th>Total Keseluruhan</th>
                    <th>Tersisa</th>
                    <th>Total Masuk</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="custom-cell primary">
                        Rp <?php echo e(number_format($totalJumlahPembayaranfilter, 0, ',', '.')); ?> User:
                        <?php echo e(number_format($totalPelangganfilter, 0, ',', '.')); ?>

                    </td>

                    <td class="custom-cell info">
                        Rp <?php echo e(number_format($totalPembayaranSudahBayar, 0, ',', '.')); ?> User: <?php echo e($totalSudahBayar); ?>

                    </td>

                    <td class="custom-cell warning">
                        Rp <?php echo e(number_format($totalPembayaranBelumBayar, 0, ',', '.')); ?> User: <?php echo e($totalBelumBayar); ?>

                    </td>

                    <td class="custom-cell danger">
                        <a href="<?php echo e(route('pelanggan.isolir')); ?>"> Rp
                            <?php echo e(number_format($totalPembayaranIsolir, 0, ',', '.')); ?> User: <?php echo e($totalIsolir); ?></a>
                    </td>

               <!--     <td class="custom-cell danger">
                        <a href="<?php echo e(route('pelanggan.block')); ?>"> Rp
                            <?php echo e(number_format($totalPembayaranBlock, 0, ',', '.')); ?> User: <?php echo e($totalBlock); ?> </a>
                    </td>

                    <td class="custom-cell success">
                        <a href="<?php echo e(route('pelanggan.unblock')); ?>"> Rp
                            <?php echo e(number_format($totalPembayaranUnblock, 0, ',', '.')); ?> User: <?php echo e($totalUnblock); ?> </a>
                    </td> -->



                    <td class="custom-cell primary-yellow">
                        Rp <?php echo e(number_format($totalJumlahPembayaranKeseluruhan, 0, ',', '.')); ?> User:
                        <?php echo e(number_format($totalPelangganKeseluruhan, 0, ',', '.')); ?>

                    </td>
                    <td class="custom-cell primary-red">
                        Rp <?php echo e(number_format($sisaPembayaran, 0, ',', '.')); ?> User:
                        <?php echo e(number_format($sisaUser, 0, ',', '.')); ?>

                    </td>

                    <td class="custom-cell primary-green">
                        Rp <?php echo e(number_format($totalJumlahPembayaran, 0, ',', '.')); ?> User:
                        <?php echo e(number_format($totalPelangganBayar, 0, ',', '.')); ?>

                    </td>


                </tr>
            </tbody>
        </table>

        <style>
            .custom-cell {
                padding: 10px;
                text-align: center;
                font-size: 1.0em;
                font-weight: bold;
                cursor: pointer;
                color: white;
            }

            .custom-cell.head {
                background: #530096;
                /* Biru */
            }

            .custom-cell.info {
                background: #17a2b8;
                /* Biru */
            }

            .custom-cell.warning {
                background: #ffc107;
                /* Kuning */
                color: black;
            }

            .custom-cell.danger {
                background: #dc3545;
                /* Merah */
            }

            .custom-cell.success {
                background: #28a745;
                /* Hijau */
            }

            .custom-cell.primary {
                background: #007bff;
                /* Biru tua */
            }

            .custom-cell.primary-yellow {
                background: #ecc100;
                /* Kuning terang */
                color: black;
            }

            .custom-cell.primary-red {
                background: #ff0000;
                /* Merah terang */
            }

            .custom-cell.primary-green {
                background: rgb(32, 190, 0);
                /* Hijau terang */
            }

            .table-bordered {
                border: 1px solid #dee2e6;
                width: 100%;
            }

            .table th,
            .table td {
                border: 1px solid #dee2e6;
                vertical-align: middle;
            }

            .table {
                width: 100%;
                table-layout: fixed;
                /* Membuat lebar kolom rata */
            }

            a {
                color: white;
                text-decoration: none;
            }

            a:hover {
                text-decoration: underline;
            }
        </style>

    </div>

    <!-- End Form Filter dan pencarian -->

    <div class="d-flex align-items-center justify-content-between mt-2">
        <form action="<?php echo e(route('pelanggan.index')); ?>" method="GET" class="form-inline d-flex" style="color: black;">
            <div class="input-group" style="color: black;">
                <input type="text" name="search" id="search" class="form-control font-weight-bold" style="color: black;"
                    value="<?php echo e(request('search')); ?>" placeholder="Pencarian">
            </div>
            <button type="submit" name="action" value="search" class="btn btn-danger ml-2">Cari</button>
        </form>

        <div class="mx-auto text-center mr-3">
            <h3 class="font-weight-bold" style="
        background: linear-gradient(45deg,rgb(60, 105, 0),rgb(0, 81, 148)); /* Gradasi hijau ke biru */
        -webkit-background-clip: text; /* Clip background pada teks */
        -webkit-text-fill-color: transparent; /* Jadikan teks transparan agar gradasi terlihat */
        font-size: 2em; /* Ukuran font */
        display: inline-block; /* Agar padding sesuai */
    ">
                Data Pelanggan
            </h3>
        </div>

        <div class="col-md-3 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Ekspor
                </button>
                <div class="dropdown-menu">
                    <a href="<?php echo e(route('pelanggan.export', [
                        'format' => 'pdf',
                        'tgl_tagih_plg' => request('tgl_tagih_plg'),
                        'paket_plg' => request('paket_plg'),
                        'harga_paket' => request('harga_paket'),
                        'status_pembayaran' => request('status_pembayaran'),
                    ])); ?>" class="dropdown-item">PDF</a>

                    <a href="<?php echo e(route('pelanggan.export', [
                        'format' => 'excel',
                        'tgl_tagih_plg' => request('tgl_tagih_plg'),
                        'paket_plg' => request('paket_plg'),
                        'harga_paket' => request('harga_paket'),
                        'status_pembayaran' => request('status_pembayaran'),
                    ])); ?>" class="dropdown-item">Excel</a>
                </div>

            </div>
        </div>

    </div>


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

    <!-- Tampilkan jumlah total pembayaran dan jumlah pelanggan -->


    <div class="">

        <th class="mt-2">
            <form action="<?php echo e(route('pelanggan.index')); ?>" method="GET">
                <select name="tgl_tagih_plg" id="tgl_tagih_plg">
                    <option value="">Tanggal Tagih</option>
                    <?php for($i = 1; $i <= 33; $i++): ?>
                        <option value="<?php echo e($i); ?>" <?php echo e(request('tgl_tagih_plg') == $i ? 'selected' : ''); ?>>
                        <?php echo e($i); ?>

                        </option>
                        <?php endfor; ?>
                </select>
                <select name="paket_plg" id="paket_plg">
                    <option value="">Paket</option>
                    <?php for($i = 1; $i <= 7; $i++): ?>
                        <option value="<?php echo e($i); ?>" <?php echo e(request('paket_plg') == $i ? 'selected' : ''); ?>>
                        <?php echo e($i); ?>

                        </option>
                        <?php endfor; ?>
                        <option value="vcr" <?php echo e(request('paket_plg') == 'vcr' ? 'selected' : ''); ?>>
                            vcr
                        </option>
                </select>

                <select name="harga_paket" id="harga_paket">
                    <option value="">Harga</option>
                    <option value="50000" <?php echo e(request('jumlah_pembayaran') == '50000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(50000, 0, ',', '.')); ?>

                    </option>
                    <option value="75000" <?php echo e(request('jumlah_pembayaran') == '75000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(75000, 0, ',', '.')); ?>

                    </option>
                    <option value="100000" <?php echo e(request('jumlah_pembayaran') == '100000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(100000, 0, ',', '.')); ?>

                    </option>
                    <option value="105000" <?php echo e(request('jumlah_pembayaran') == '105000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(105000, 0, ',', '.')); ?>

                    </option>
                    <option value="115000" <?php echo e(request('jumlah_pembayaran') == '115000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(115000, 0, ',', '.')); ?>

                    </option>

                    <option value="120000" <?php echo e(request('jumlah_pembayaran') == '120000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(120000, 0, ',', '.')); ?>

                    </option>
                    <option value="125000" <?php echo e(request('jumlah_pembayaran') == '125000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(125000, 0, ',', '.')); ?>

                    </option>
                    <option value="150000" <?php echo e(request('jumlah_pembayaran') == '150000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(150000, 0, ',', '.')); ?>

                    </option>
                    <option value="165000" <?php echo e(request('jumlah_pembayaran') == '165000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(165000, 0, ',', '.')); ?>

                    </option>
                    <option value="175000" <?php echo e(request('jumlah_pembayaran') == '175000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(175000, 0, ',', '.')); ?>

                    </option>
                    <option value="205000" <?php echo e(request('jumlah_pembayaran') == '205000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(205000, 0, ',', '.')); ?>

                    </option>
                    <option value="250000" <?php echo e(request('jumlah_pembayaran') == '250000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(250000, 0, ',', '.')); ?>

                    </option>
                    <option value="265000" <?php echo e(request('jumlah_pembayaran') == '265000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(265000, 0, ',', '.')); ?>

                    </option>
                    <option value="305000" <?php echo e(request('jumlah_pembayaran') == '305000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(305000, 0, ',', '.')); ?>

                    </option>
                    <option value="750000" <?php echo e(request('jumlah_pembayaran') == '750000' ? 'selected' : ''); ?>>
                        <?php echo e(number_format(750000, 0, ',', '.')); ?>

                    </option>
                    <option value="vcr" <?php echo e(request('jumlah_pembayaran') == 'vcr' ? 'selected' : ''); ?>>
                        vcr
                    </option>
                </select>

                <select name="status_pembayaran">
                    <option value="">Semua Status</option>
                    <option value="sudah_bayar">Sudah Bayar</option>
                    <option value="belum_bayar">Belum Bayar</option>
                </select>

                <input type="date" id="updated_at" name="updated_at" value="<?php echo e(request()->get('updated_at')); ?>">
                <button type="submit" class="btn btn-primary ">Filter</button>
            </form>
        </th>


        <table class="table table-bordered table-responsive"
            style="color: black; width: 100%; font-size: 0.85em; table-layout: fixed;">
            <thead class="custom-cell danger" style="color: white;">
                <tr class="font-weight-bold">
                    <th style="width: 1%; padding: 1px;">No</th>
                    <th style="width: 1%; padding: 1px;">ID</th>
                    <th style="width: 1%; padding: 1px;">Nama</th>
                    <th style="width: 1%; padding: 1px;">Alamat</th>
                    <th style="width: 1%; padding: 1px;">No Telpon</th>
                    <th style="width: 1%; padding: 1px;">Aktivasi</th>
                    <th style="width: 1%; padding: 1px;">Paket</th>
                    <th style="width: 1%; padding: 1px;">Harga</th>
                    <th style="width: 1%; padding: 0; margin: 0; text-align: center;">Tanggal Tagih</th>
                    <th style="width: 1%; padding: 1px;">Keterangan</th>
                    <th style="width: 1%; padding: 1px;">Bayar Terakhir</th>
                    <th style="width: 1%; padding: 1px;">Status Pembayaran</th>

                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pelanggan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="">
                    <td style="padding: 1px;">
                        <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>"
                            style="text-decoration: none; color: inherit;">
                            <?php echo e(($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration); ?>

                        </a>
                    </td>

                    <!-- ID Pelanggan -->
                    <td style="padding: 1px;">
                        <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>"
                            style="text-decoration: none; color: inherit;">
                            <?php echo e($item->id_plg); ?>

                        </a>
                    </td>

                    <!-- Nama Pelanggan -->
                    <td style="padding: 1px;">
                        <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>"
                            style="text-decoration: none; color: inherit;">
                            <?php echo e($item->nama_plg); ?>

                        </a>
                    </td>

                    <td style="padding: 1px;">
                        <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>"
                            style="text-decoration: none; color: inherit;">
                            <?php echo e($item->alamat_plg); ?>

                        </a>
                    </td>

                    <td style="padding: 1px;">
                        <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>"
                            style="text-decoration: none; color: inherit;">
                            <?php echo e($item->no_telepon_plg); ?>

                        </a>
                    </td>
                    <td style="padding: 1px;">
                        <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>"
                            style="text-decoration: none; color: inherit;">
                            <?php echo e($item->aktivasi_plg); ?>

                        </a>
                    </td>
                    <td style="padding: 1px;">
                        <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>"
                            style="text-decoration: none; color: inherit;">
                            <?php echo e($item->paket_plg); ?>

                        </a>
                    </td>
                    <td style="padding: 1px;">
                        <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>"
                            style="text-decoration: none; color: inherit;">
                            <?php echo e(number_format($item->harga_paket, 0, ',', '.')); ?>

                        </a>
                    </td>
                    <td style="width: 1%; padding: 0; margin: 0; text-align: center;">
                        <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>"
                            style="text-decoration: none; color: inherit;">
                            <?php echo e($item->tgl_tagih_plg); ?>

                        </a>
                    </td>
                    <td style="padding: 1px;">
                        <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>"
                            style="text-decoration: none; color: inherit;">
                            <?php echo e($item->keterangan_plg); ?>

                        </a>
                    </td>

                    <td style="padding: 1px;">
                        <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>"
                            style="text-decoration: none; color: inherit;">
                            <?php echo e(optional($item->pembayaranTerakhir)->tanggal_pembayaran
                            ? \Carbon\Carbon::parse($item->pembayaranTerakhir->tanggal_pembayaran)->locale('id')->isoFormat('MMMM Y')
                            : '-'); ?>

                        </a>
                    </td>

                    <!---
                                <td style="padding: 1px;">
                                    <span class="badge <?php echo e(strcasecmp($item->status_pembayaran, 'Sudah Bayar') === 0 ? 'bg-success' : 'bg-danger'); ?> text-white">
                                        <?php echo e($item->status_pembayaran); ?>

                                    </span>
                                </td>
                                    -->
                    <td class="row" style="padding: 2px; font-size: 0.8em; height: 10px;">

                        <select name="tanggal_pembayaran" class="form-control ml-4" onchange="this.form.submit()"
                            style="width: 16%;  padding: 2px; height: 25px;">
                            <option value="">Riwayat Pembayaran</option>
                            <?php $__currentLoopData = $item->pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pembayaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $tanggalPembayaran = \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran);
                            $isDanger = $tanggalPembayaran->lessThan(now()->startOfMonth());
                            ?>
                            <option value="<?php echo e($tanggalPembayaran->format('Y-m-d')); ?>">
                                <?php echo e($tanggalPembayaran->locale('id')->isoFormat('MMMM Y')); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!$item->pembayaran->count()): ?>
                            <option value="">Belum Ada Pembayaran</option>
                            <?php endif; ?>
                        </select>
                        <span
                            class="badge <?php echo e(strcasecmp($item->status_pembayaran, 'Sudah Bayar') === 0 ? 'bg-success' : 'bg-danger'); ?> text-white ml-2"
                            style="font-size: 0.75em; height: 20px; line-height: 20px;"><?php echo e($item->status_pembayaran); ?></span>
                    </td>




                    <!--  <td style="padding: 0; margin: 0; text-align: center;">
                        <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>" class="btn btn-warning btn-xs" style="padding: 2px 5px; font-size: 0.75em;">Detail</a>
                    </td> -->

                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="14" class="text-center" style="padding: 10px;">Tidak ada data ditemukan</td>
                </tr>
                <?php endif; ?>



            </tbody>
        </table>


    </div>
    <div class="d-flex justify-content-center">
        <?php echo e($pelanggan->links('pagination::bootstrap-4')); ?>

    </div>
    <?php $__env->stopSection(); ?>
    <script>
        function showBayarModal(id, namaPlg, hargaPaket) {
            document.getElementById('pelangganId').value = id;
            document.getElementById('pembayaranDetails').innerText =
                `Nama Pelanggan: ${namaPlg}\nHarga Paket: Rp. ${hargaPaket}`;

            var form = document.getElementById('bayarForm');
            form.action = `/pelanggan/${id}/bayar`; // Set action URL with the ID

            var bayarModal = new bootstrap.Modal(document.getElementById('bayarModal'));
            bayarModal.show();
        }
    </script>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pelanggan\index.blade.php ENDPATH**/ ?>