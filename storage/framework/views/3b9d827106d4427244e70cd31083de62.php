<?php $__env->startSection('konten'); ?>
    <div class="  pl-5 pr-5 mb-4">
        <!-- Form Filter dan Pencarian -->
        <div class="row mb-2 align-items-center">
            <div class="col-md-5">
                <!-- Form Pencarian -->
                <form action="<?php echo e(route('isolir.index')); ?>" method="GET" class="form-inline">
                    <!-- Input Pencarian -->
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control"
                            value="<?php echo e(request('search')); ?>" placeholder="Pencarian">
                    </div>
                    <!-- Tombol Cari -->
                    <button type="submit" name="action" value="search" class="btn btn-danger ml-2 ">Cari</button>
                    <div class="ml-5">
                        <button class="btn btn-primary btn-lg mt-2"
                            style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                            Pembayaran : Rp <?php echo e(number_format($totalJumlahPembayaran, 0, ',', '.')); ?> || User :
                            <?php echo e(number_format($totalPelanggan, 0, ',', '.')); ?>

                        </button>
                    </div>
                </form>
            </div>

            <div class="col-md-6 text-center">
                <!-- Teks Data isolir -->
                <a href="/pelanggan" class="btn btn-primary btn-lg mt-2"
                    style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                    Data Pelanggan</a>
                <div class="btn btn-primary btn-lg mt-2" data-toggle="modal" data-target="#filterModal"
                    style="cursor: default; background: linear-gradient(45deg, #ff0000, #ff8c00); color: #ffffff;">
                    Data Isolir
                </div>
                <a href="/pelangganof" class="btn btn-primary btn-lg mt-2"
                    style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                    Data Pelanggan Off</a>
                <!-- Modal -->
                <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterModalLabel">Filter Isolir</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="filterForm" method="GET" action="<?php echo e(route('isolir.filterTagihindex')); ?>">
                                    <div class="form-group">
                                        <label for="paket_plg">Paket</label>
                                        <select name="paket_plg" id="paket_plg">
                                            <option value="">Pilih Paket</option>
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
                                    <div class="form-group">
                                        <label for="tgl_tagih_plg">Tanggal Tagih</label>
                                        <select name="tgl_tagih_plg" id="tgl_tagih_plg">
                                            <option value="">Pilih Tanggal Tagih</option>
                                            <?php for($i = 1; $i <= 32; $i++): ?>
                                                <option value="<?php echo e($i); ?>"
                                                    <?php echo e(request('tgl_tagih_plg') == $i ? 'selected' : ''); ?>>
                                                    <?php echo e($i); ?>

                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="document.querySelector('.filterForm').submit();">Terapkan Filter</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <div class="">
            <table class="table table-bordered table-responsive " style="color: black;">
                <thead class="table table-danger " style="color: black;">
                    <tr>
                        <th class="">No</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No Telpon</th>
                        <th>Aktivasi</th>
                        <th>
                            <form class="filterForm" method="GET" action="<?php echo e(route('isolir.filterTagihindex')); ?>">
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
                            <form class="filterForm" method="GET" action="<?php echo e(route('isolir.filterTagihindex')); ?>">
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
                            <form class="filterForm" method="GET" action="<?php echo e(route('isolir.filterTagihindex')); ?>">
                                <div class="form-group">
                                    <select name="tgl_tagih_plg" id="tgl_tagih_plg" onchange="this.form.submit();">
                                        <option value="">Tanggal Tagih</option>
                                        <?php for($i = 1; $i <= 32; $i++): ?>
                                            <option value="<?php echo e($i); ?>"
                                                <?php echo e(request('tgl_tagih_plg') == $i ? 'selected' : ''); ?>>
                                                <?php echo e($i); ?>

                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </form>
                        </th>

                        <th>ODP</th>
                        <th>Longitude</th>
                        <th>Latitude</th>
                        <th>Keterangan</th>
                        <th>Tanggal Isolir</th>
                        <th>
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Label Status -->
                                <span>Status</span>

                                <!-- Form Filter -->
                                <div class="col-md-3 text-right" style="margin-left: 10px;">
                                    <form action="<?php echo e(route('isolir.index')); ?>" method="GET" class="form-inline"
                                        id="filterForm">
                                        <div class="input-group">
                                            <select name="status_pembayaran" id="status_pembayaran" class="form-control"
                                                onchange="document.getElementById('filterForm').submit();">
                                                <option value="">Semua</option>
                                                <option value="belum_bayar"
                                                    <?php echo e(request('status_pembayaran') == 'belum_bayar' ? 'selected' : ''); ?>>
                                                    Belum
                                                    Bayar</option>
                                                <option value="sudah_bayar"
                                                    <?php echo e(request('status_pembayaran') == 'sudah_bayar' ? 'selected' : ''); ?>>
                                                    Sudah
                                                    Bayar</option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </th>
                        <th>Riwayat Pembayaran</th>

                        <th>Aktifkan Kembali</th>



                        <!-- <th>Riwayat pembayaran</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $isolir; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="font-weight-bold">
                            <td><?php echo e(($isolir->currentPage() - 1) * $isolir->perPage() + $loop->iteration); ?></td>
                            <td><?php echo e($item->id_plg); ?></td>
                            <td><?php echo e($item->nama_plg); ?></td>
                            <td><?php echo e($item->alamat_plg); ?></td>
                            <td><?php echo e($item->no_telepon_plg); ?></td>
                            <td><?php echo e($item->aktivasi_plg); ?></td>
                            <td><?php echo e($item->paket_plg); ?></td>
                            <td><?php echo e(number_format($item->harga_paket, 0, ',', '.')); ?></td>
                            <td><?php echo e($item->tgl_tagih_plg); ?></td>

                            <td><?php echo e($item->odp); ?></td>

                            <td><?php echo e($item->longitude); ?></td>
                            <td><?php echo e($item->latitude); ?></td>

                            <td><?php echo e($item->keterangan_plg); ?></td>
                            <td><?php echo e($item->created_at); ?></td>
                            <td>
                                <?php if($item->status_pembayaran === 'Sudah Bayar'): ?>
                                    <span class="badge badge-success p-3">Sudah Bayar</span>
                                <?php else: ?>
                                    <span class="badge badge-danger p-3">Belum Bayar</span>
                                <?php endif; ?>
                            </td>
                            <td> <a href="<?php echo e(route('isolir.historypembayaran', $item->id)); ?>"
                                    class="btn btn-info btn-sm">Riwayat Pembayaran</a>
                            </td>


                            <td>
                                <a href="#" class="btn btn-success btn-sm"
                                    onclick="showReaktivasiModal(<?php echo e($item->id); ?>, '<?php echo e($item->nama_plg); ?>', <?php echo e($item->harga_paket); ?>)">Reaktif</a>
                            </td>

                            <!-- Modal Reaktivasi -->
                            <div class="modal fade" id="reaktivasiModal" tabindex="-1"
                                aria-labelledby="reaktivasiModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="reaktivasiModalLabel">Reaktivasi Pelanggan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <!-- Modal Form -->
                                        <form id="reaktivasiForm" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" id="pelangganId">
                                            <div class="modal-body">
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
                                                        Reaktivasi</label>
                                                    <input type="text" class="form-control" id="keterangan_plg"
                                                        name="keterangan_plg">
                                                </div>

                                                <!-- Detail Pelanggan -->
                                                <div class="mb-3">
                                                    <p id="reaktivasiDetails"></p>
                                                </div>
                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Reaktif</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>




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
            <?php echo e($isolir->links('pagination::bootstrap-4')); ?>

        </div>
    <?php $__env->stopSection(); ?>
    <script>
        function showReaktivasiModal(id, namaPlg, hargaPaket) {
            document.getElementById('pelangganId').value = id;
            document.getElementById('reaktivasiDetails').innerText =
                `Nama Pelanggan: ${namaPlg}\nHarga Paket: Rp. ${hargaPaket}`;

            var form = document.getElementById('reaktivasiForm');
            form.action = `/isolir/${id}/reactivate`; // Set action URL untuk reaktivasi

            var reaktivasiModal = new bootstrap.Modal(document.getElementById('reaktivasiModal'));
            reaktivasiModal.show();
        }
    </script>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\belum_bayar\index.blade.php ENDPATH**/ ?>