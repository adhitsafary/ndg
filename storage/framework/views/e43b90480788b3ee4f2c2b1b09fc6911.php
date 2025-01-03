<?php $__env->startSection('konten'); ?>
    <div class="  pl-5 pr-5 mb-4">
        <!-- Form Filter dan Pencarian -->
        <div class="row align-items-center">
            <div class="col-md-6" style="color: black">
                <!-- Form Pencarian -->

                <div class="mb-2">
                    
                    <button  class="btn btn-primary btn-lg mt-2 font-weight-bold"
                    style="cursor: default; background: linear-gradient(45deg, #eeca00, #ff6600d8); color: #ffffff;">
                    Total keseluruhan : <?php echo e(number_format($totalJumlahPembayaranKeseluruhan, 0, ',', '.')); ?> || User : <?php echo e(number_format($totalPelangganKeseluruhan, 0, ',', '.')); ?>

                    </button>
                    <button  class="btn btn-primary btn-lg mt-2 font-weight-bold"
                    style="cursor: default; background: linear-gradient(45deg, #ff0000, #ffc02d); color: #ffffff;">
                    Tersisa : Rp <?php echo e(number_format($sisaPembayaran, 0, ',', '.')); ?> || User : <?php echo e(number_format($sisaUser, 0, ',', '.')); ?>

                    </button>
                    <button  class="btn btn-primary btn-lg mt-2 font-weight-bold"
                    style="cursor: default; background: linear-gradient(45deg, #007bff, #00ff6a); color: #ffffff;">
                    Pembayaran(Filter) : Rp <?php echo e(number_format($totalJumlahPembayaranfilter, 0, ',', '.')); ?> || User : <?php echo e(number_format($totalPelangganfilter, 0, ',', '.')); ?>

                    </button>
                    <button  class="btn btn-primary btn-lg mt-2 font-weight-bold"
                    style="cursor: default; background: linear-gradient(45deg, rgb(32, 190, 0), #ffbb00); color: #ffffff;">
                    Total Masuk : <?php echo e(number_format($totalJumlahPembayaran, 0, ',', '.')); ?> || User : <?php echo e(number_format($totalPelangganBayar, 0, ',', '.')); ?>

                    </button>


                </div>

                <form action="<?php echo e(route('pelanggan.index')); ?>" method="GET" class="form-inline" style="color: black">
                    <!-- Input Pencarian -->
                    <div class="input-group" style="color: black">
                        <input type="text" name="search" id="search" class="form-control font-weight-bold"
                            style="color: black" value="<?php echo e(request('search')); ?>" placeholder="Pencarian">
                    </div>
                    <!-- Tombol Cari -->
                    <button type="submit" name="action" value="search" class="btn btn-danger ml-2">Cari</button>
                    
                    
                </form>
            </div>
        </div>
        <div class="text-right mb-2">

            <!-- Teks Data isolir -->
            <a href="/isolir" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Data Isolir</a>
            <div class="btn btn-danger btn-lg mt-2" data-toggle="modal" data-target="#filterModal"
                style="cursor: default; background: linear-gradient(45deg, #ff0000, #ffc02d); color: #ffffff;">
                Data Pelanggan
            </div>
            <a href="/pelangganof" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Data Pelanggan Off</a>
            <a href="/pembayaran" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Pembayaran</a>
            <a href="/perbaikan" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Perbaikan/PSB</a>
            <a href="/pemasukan" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Pemasukan/Pengeluaran</a>
            <a href="/rekap-harian/" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Rekap Harian</a>
            <a href="/rekap_pemasangan/" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Rekap PSB</a>



            
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

            <!-- Tampilkan jumlah total pembayaran dan jumlah pelanggan -->
       

        <div class="">
            <table class="table table-bordered table-responsive " style="color: black;">
                <thead class="table table-danger " style="color: black;">
                    <tr class="font-weight-bold">
                        <th class="">No</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No Telpon</th>
                        <th>Aktivasi</th>
                        <th>
                            <form class="filterForm" method="GET" action="<?php echo e(route('pelanggan.filterTagihindex')); ?>">
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
                        </th>

                        <th>
                            <form class="filterForm" method="GET" action="<?php echo e(route('pelanggan.filterTagihindex')); ?>">
                                <div class="form-group">
                                    <select name="harga_paket" id="harga_paket" onchange="this.form.submit();">
                                        <option value="">Harga</option>
                                        <option value="50000"
                                            <?php echo e(request('jumlah_pembayaran') == '50000' ? 'selected' : ''); ?>>
                                            <?php echo e(number_format(50000, 0, ',', '.')); ?>

                                        </option>
                                        <option value="75000"
                                            <?php echo e(request('jumlah_pembayaran') == '75000' ? 'selected' : ''); ?>>
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
                                        <option value="vcr"
                                            <?php echo e(request('jumlah_pembayaran') == 'vcr' ? 'selected' : ''); ?>>
                                            vcr
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </th>

                        <th>
                            <form class="filterForm" method="GET" action="<?php echo e(route('pelanggan.filterTagihindex')); ?>">
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
                        </th>
                        <th>Keterangan</th>
                        <th>
                            Terakhir Bayar
                        </th>

                        <th>
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Label Status -->
                                <span>Status</span>

                                <!-- Form Filter -->
                                <div class="col-md-3 text-right">
                                    <form action="<?php echo e(route('pelanggan.index')); ?>" method="GET" class="form-inline"
                                        id="filterForm">
                                        <div class="input-group">
                                            <select name="status_pembayaran" id="status_pembayaran" class="form-control"
                                                onchange="document.getElementById('filterForm').submit();">
                                                <option value="">Semua</option>
                                                <option value="belum_bayar"
                                                    <?php echo e(request('status_pembayaran') == 'belum_bayar' ? 'selected' : ''); ?>>
                                                    Belum Bayar
                                                </option>
                                                <option value="sudah_bayar"
                                                    <?php echo e(request('status_pembayaran') == 'sudah_bayar' ? 'selected' : ''); ?>>
                                                    Sudah Bayar
                                                </option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </th>

                        <th>Detail</th>
                        <th>Bayar</th>
                        <th>Isolir</th>
                        <!-- <th>Riwayat pembayaran</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pelanggan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="font-weight-bold">

                            <td><?php echo e(($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration); ?></td>

                            <td><?php echo e($item->id_plg); ?></td>
                            <td><?php echo e($item->nama_plg); ?></td>
                            <td><?php echo e($item->alamat_plg); ?></td>
                            <td><?php echo e($item->no_telepon_plg); ?></td>
                            <td><?php echo e($item->aktivasi_plg); ?></td>
                            <td><?php echo e($item->paket_plg); ?></td>
                            <td><?php echo e(number_format($item->harga_paket, 0, ',', '.')); ?></td>
                            <td><?php echo e($item->tgl_tagih_plg); ?></td>

                            <td><?php echo e($item->keterangan_plg); ?></td>
                            <td>
                                <?php echo e(optional($item->pembayaranTerakhir)->created_at
                                    ? \Carbon\Carbon::parse($item->pembayaranTerakhir->created_at)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->translatedFormat('l, d F Y H:i:s')
                                    : 'Belum Ada pembayaran'); ?>

                            </td>

                            <td>
                                <form action="<?php echo e(route('pelanggan.updateStatus', $item->id)); ?>" method="POST"
                                    class="form-inline">
                                    <?php echo csrf_field(); ?>
                                    <select name="status_pembayaran" class="form-control" onchange="this.form.submit()">
                                        <option value="Belum Bayar"
                                            <?php echo e(strcasecmp($item->status_pembayaran, 'belum bayar') === 0 ? 'selected' : ''); ?>>
                                            Belum Bayar
                                        </option>
                                        <option value="Sudah Bayar"
                                            <?php echo e(strcasecmp($item->status_pembayaran, 'sudah bayar') === 0 ? 'selected' : ''); ?>>
                                            Sudah Bayar
                                        </option>
                                    </select>
                                </form>

                                <span
                                    class="badge <?php echo e(strcasecmp($item->status_pembayaran, 'Sudah Bayar') === 0 ? 'bg-success' : 'bg-danger'); ?> text-white ml-2"
                                    style="padding: 0.5em 1em; font-size: 1.1em;">
                                    <?php echo e($item->status_pembayaran); ?>

                                </span>
                            </td>


                            <td>
                                <a href="<?php echo e(route('pelanggan.detail', $item->id)); ?>"
                                    class="btn btn-warning btn-sm">Detail</a>
                            </td>
                            <!-- Tombol Bayar -->
                            <td>
                                <a href="#" class="btn btn-success btn-sm"
                                    onclick="showBayarModal(<?php echo e($item->id); ?>, '<?php echo e($item->nama_plg); ?>', <?php echo e($item->harga_paket); ?>)">Bayar</a>
                            </td>
                            <!-- Modal Bayar -->
                            <div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="bayarModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="bayarModalLabel">Pembayaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <!-- Modal Form -->
                                        <form id="bayarForm" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" id="pelangganId">
                                            <div class="modal-body">
                                                <!-- Input Tanggal Pembayaran -->


                                                <div class="mb-3">
                                                    <label for="metodeTransaksi" class="form-label">Metode
                                                        Transaksi</label>
                                                    <select class="form-select" id="metodeTransaksi"
                                                        name="metode_transaksi" required>
                                                        <option value="">Pilih metode</option>
                                                        <option value="CASH">Cash</option>
                                                        <option value="TF">Transfer</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="keterangan_plg" class="form-label">Keterangan
                                                        Pembayaran Pelanggan</label>
                                                    <input type="text" class="form-control" id="keterangan_plg"
                                                        name="keterangan_plg">
                                                </div>

                                                <!-- Detail Pembayaran -->
                                                <div class="mb-3">
                                                    <p id="pembayaranDetails"></p>
                                                </div>
                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Bayar</button>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>

                            <td>
                                <form action="<?php echo e(route('pelanggan.toIsolir', $item->id)); ?>" method="POST"
                                    style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-danger btn-sm">Isolir</button>
                                </form>
                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="19" class="text-center">Tidak ada data ditemukan</td>
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

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pelanggan\indexcoba.blade.php ENDPATH**/ ?>