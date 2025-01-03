

<?php $__env->startSection('konten'); ?>
<div class="container">
    <h4 class="mb-4 text-center">Bayar Tagihan Pelanggan</h4>

    <!-- Informasi Total -->
    <div class="row g-3">


        <div class="col-6 col-md-3 mb-4">
            <div class="bg-info text-white p-2 font-weight-bold ">
                <p class="card-title">Total Pembayaran</p>
                <a href="#" class="card-text"> Rp <?php echo e(number_format($total_user_bayar, 0, ',', '.')); ?> </a>
                <p>User: <?php echo e($total_jml_user); ?></p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-warning text-dark p-2 font-weight-bold">
                <p class="card-title">Tagihan Hari Ini</p>
                <a href="<?php echo e(route('pelanggan.redirect')); ?>" class="card-text"> Rp <?php echo e(number_format($totalTagihanHariIni, 0, ',', '.')); ?> </a>
                <p>User: <?php echo e($jumlahPelangganMembayarHariIni); ?></p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-success text-white p-2 font-weight-bold">
                <p class="card-title">Tertagih</p>
                <a href="<?php echo e(route('pelanggan.sudahbayar')); ?>" class="card-text">Rp <?php echo e(number_format($totalPendapatanharian_semua, 0, ',', '.')); ?></a>
                <p>User: <?php echo e($totalUserHarian_semua); ?></p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-danger text-white p-2 font-weight-bold">
                <p class="card-title">Sisa Tagihan</p>
                <a href="<?php echo e(route('pelanggan.belumbayar')); ?>" class="card-text">Rp <?php echo e(number_format($totalTagihanTertagih, 0, ',', '.')); ?> </a>
                <p>User: <?php echo e($totalUserTertagih); ?></p>
            </div>
        </div>
    </div>


    <!-- Form Pencarian -->
    <form action="<?php echo e(route('pembayaran_mudah.bayar_hp')); ?>" method="GET" class="mt-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Cari berdasarkan ID atau Nama"
                value="<?php echo e($query ?? ''); ?>">

            <button type="submit" class="btn btn-primary">Cari</button>
        </div>
    </form>


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

    <!-- Tabel Pembayaran -->
    <div class="mt-4">
        <?php if(!$query_cari): ?>
        <p class="text-muted">Silakan masukkan ID atau Nama untuk mencari data pelanggan.</p>

        <div>
            <!-- Tabel Pembayaran -->
            <div class="container mt-4">
                <div class="row">
                    <?php $__empty_1 = true; $__currentLoopData = $pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    Pelanggan
                                    <?php echo e(($pembayaran->currentPage() - 1) * $pembayaran->perPage() + $loop->iteration); ?>

                                </h5>
                                <p class="card-text">
                                    <strong>Nama:</strong> <?php echo e($item->nama_plg); ?><br>
                                    <strong>Alamat:</strong> <?php echo e($item->alamat_plg); ?><br>
                                    <strong>Tanggal Tagih:</strong> <?php echo e($item->tgl_tagih_plg); ?><br>
                                    <strong>Harga:</strong> Rp
                                    <?php echo e(number_format($item->jumlah_pembayaran, 0, ',', '.')); ?><br>
                                    <strong>Metode Pembayaran:</strong> <?php echo e($item->metode_transaksi); ?><br>
                                    <strong>Tanggal Pembayaran:</strong> <?php echo e($item->created_at); ?><br>
                                    <strong>Keterangan:</strong> <?php echo e($item->untuk_pembayaran); ?><br>
                                    <strong>Admin:</strong> <?php echo e($item->admin_name); ?>

                                </p>
                                <div class="d-flex justify-content-end">

                                    <form action="<?php echo e(route('pembayaran.destroy', $item->id)); ?>" method="POST"
                                        class="d-inline-block">
                                        <?php echo csrf_field(); ?>
                                        <a href="#"
                                            onclick="if(confirm('Yakin ingin menghapus data ini?')) { this.closest('form').submit(); return false; }"
                                            style="display: inline-block;">
                                            <img src="<?php echo e(asset('asset/img/icon/delete.png')); ?>"
                                                style="height: 35px; width: 35px;" alt="Hapus">
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12 text-center">
                        <p>Tidak ada data pembayaran ditemukan</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
        <div>
            <?php elseif($pelanggan->isEmpty()): ?>
            <p class="text-muted">Tidak ditemukan hasil untuk "<?php echo e($query_cari); ?>"</p>
            <?php else: ?>

            <div class="row">
                <?php $__currentLoopData = $pelanggan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pelanggan
                                <?php echo e(($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration); ?>

                            </h5>
                            <p class="card-text">
                                <strong>ID Pelanggan:</strong> <?php echo e($item->id_plg); ?><br>
                                <strong>Nama:</strong> <?php echo e($item->nama_plg); ?><br>
                                <strong>Alamat:</strong> <?php echo e($item->alamat_plg); ?><br>
                                <strong>Harga:</strong> Rp <?php echo e(number_format($item->harga_paket, 0, ',', '.')); ?><br>
                                <strong>Tanggal Tagih:</strong> <?php echo e($item->tgl_tagih_plg); ?><br>
                                <strong>Status Pembayaran:</strong>
                                <?php echo e(optional($item->pembayaranTerakhir)->tanggal_pembayaran
                    ? \Carbon\Carbon::parse($item->pembayaranTerakhir->tanggal_pembayaran)->locale('id')->isoFormat('MMMM Y')
                    : '-'); ?>

                            </p>
                            <td style="padding: 0; margin: 0; text-align: center;">
                                <a href="#" class="btn btn-success btn-xs" style="padding: 2px 5px; font-size: 0.75em;"
                                    onclick="showBayarModal(<?php echo e($item->id); ?>, '<?php echo e($item->nama_plg); ?>', <?php echo e($item->harga_paket); ?>)">
                                    <img
                                        src="<?php echo e(asset('asset/img/icon/bayar.png')); ?>"
                                        style="height : 30px; width : 30px; " alt=""></a>
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
                                                    <label for="tanggal_pembayaran" class="form-label">Untuk Pembayaran
                                                        <label for="tanggal_pembayaran" class="form-label">Untuk
                                                            Pembayaran
                                                            Bulan</label>
                                                        <input type="month" class="form-select" id="tanggal_pembayaran"
                                                            name="tanggal_pembayaran" placeholder="Pilih bulan">
                                                </div>



                                                <div class="mb-3">
                                                    <label for="metodeTransaksi" class="form-label">Metode
                                                        Transaksi</label>
                                                    <select class="form-select" id="metodeTransaksi"
                                                        name="metode_transaksi" required>
                                                        <option value="">Pilih metode</option>
                                                        <option value="TF">TF</option>
                                                        <option value="CASH">KANTOR</option>

                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="untuk_pembayaran" class="form-label">Status
                                                        Pembayaran</label>
                                                    <select class="form-select" id="untuk_pembayaran"
                                                        name="untuk_pembayaran" required>
                                                        <option value="">Pilih Pembayaran</option>
                                                        <option value="tagihan">Tagihan </option>
                                                        <option value="piutang">Piutang </option>
                                                        <option value="PSB">PSB </option>

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
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>


    <?php $__env->stopSection(); ?>

    <script>
        function showBayarModal(id, namaPlg, hargaPaket) {
            document.getElementById('pelangganId').value = id;
            document.getElementById('pembayaranDetails').innerText =
                `Nama Pelanggan: ${namaPlg}\nHarga Paket: Rp. ${hargaPaket}`;

            var form = document.getElementById('bayarForm');
            form.action = `/pelanggan/${id}/bayar_mudah_hp`; // Set action URL with the ID

            var bayarModal = new bootstrap.Modal(document.getElementById('bayarModal'));
            bayarModal.show();
        }
    </script>
<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pembayaran_mudah\bayar_hp.blade.php ENDPATH**/ ?>