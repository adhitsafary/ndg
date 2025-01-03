<?php $__env->startSection('konten'); ?>
    <div class="  pl-5 pr-5 mb-4">
        <!-- Form Filter dan Pencarian -->
        <div class="row mb-2 align-items-center">
            <div class="col-md-3">
                <!-- Form Pencarian -->
                <form action="<?php echo e(route('pelangganof.index')); ?>" method="GET" class="form-inline">
                    <!-- Input Pencarian -->
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control"
                            value="<?php echo e(request('search')); ?>" placeholder="Pencarian">
                    </div>
                    <!-- Tombol Cari -->
                    <button type="submit" name="action" value="search" class="btn btn-danger ml-2">Cari</button>
                </form>
            </div>

            <div class="col-md-6 text-center">

              
                    
                        <!-- Tombol Total Keseluruhan -->
                        <button class="btn btn-primary btn-lg mt-2 font-weight-bold"
                            style="cursor: default; background: linear-gradient(45deg, #ecc100, #ecc100); color: #000000;">
                            Total Pelanggan Off : Rp <?php echo e(number_format($totalJumlahPembayaranKeseluruhan, 0, ',', '.')); ?> ||
                            User :
                            <?php echo e(number_format($totalPelangganKeseluruhan, 0, ',', '.')); ?>

                        </button>
                <!-- Modal -->
     

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


        <th class="mt-2">
            <form action="<?php echo e(route('pelangganof.index')); ?>" method="GET">
                <select name="tgl_tagih_plg" id="tgl_tagih_plg">
                    <option value="">Tanggal Tagih</option>
                    <?php for($i = 1; $i <= 33; $i++): ?>
                        <option value="<?php echo e($i); ?>"
                            <?php echo e(request('tgl_tagih_plg') == $i ? 'selected' : ''); ?>>
                            <?php echo e($i); ?>

                        </option>
                    <?php endfor; ?>
                </select>
                <select name="paket_plg" id="paket_plg">
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
  
                <select name="harga_paket" id="harga_paket">
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
             
                <select name="status_pembayaran">
                    <option value="">Semua Status</option>
                    <option value="sudah_bayar">Sudah Bayar</option>
                    <option value="belum_bayar">Belum Bayar</option>
                </select>
                <button type="submit" class="btn btn-primary ">Filter</button>
            </form>
        </th>


        <table class="table table-bordered table-responsive mt-1" style="color: black;">
            <thead class="table table-danger " style="color: black;">
                <tr class="font-weight-bold">
                    <th class="">No</th>
                    <th>ID</th>
                    <th>
                        <form action="<?php echo e(route('pelangganof.index')); ?>" method="GET">
                            <!-- Filter lainnya... -->

                            <label for="order_nama">Nama</label><br>
                            <select name="order_nama" id="order_nama">
                                <option value="asc">A-Z</option>
                                <option value="desc">Z-A</option>
                            </select>

                            <button type="submit">Filter</button>
                        </form>

                    </th>
                    <th>
                        <form action="<?php echo e(route('pelangganof.index')); ?>" method="GET">
                            <!-- Filter lainnya... -->

                            <label for="order_alamat">Alamat</label><br>
                            <select name="order_alamat" id="order_alamat">
                                <option value="asc">A-Z</option>
                                <option value="desc">Z-A</option>
                            </select>

                            <button type="submit">Filter</button>
                        </form>

                    </th>



            
                    <th>No Telpon</th>
                    <th>Aktivasi</th>
                    <th>
                        <form class="filterForm" method="GET" action="<?php echo e(route('pelangganof.index')); ?>">
                            <label for="order_keterangan">Paket</label><br>
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
                        <form class="filterForm" method="GET" action="<?php echo e(route('pelangganof.index')); ?>">
                            <label for="order_keterangan">Harga</label><br>
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
                        <form class="filterForm" method="GET" action="<?php echo e(route('pelangganof.index')); ?>">
                            <label for="order_keterangan">Tanggal Tagih</label><br>
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
                    <th>
                        <form action="<?php echo e(route('pelangganof.index')); ?>" method="GET">
                            <!-- Filter lainnya... -->

                            <label for="order_keterangan">Keterangan</label><br>
                            <select name="order_keterangan" id="order_keterangan">
                                <option value="asc">A-Z</option>
                                <option value="desc">Z-A</option>
                            </select>

                            <button type="submit">Filter</button>
                        </form>

                    </th>
                    <th>Bayar Terakhir</th>

                    <th>
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Label Status -->
                            <span>Status Pembayaran</span>
                            <!-- Form Filter -->
                            <div class="col-md-3 text-right">
                                <form action="<?php echo e(route('pelangganof.index')); ?>" method="GET" class="form-inline"
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
                                            <option value="UnBlock"
                                                <?php echo e(request('status_pembayaran') == 'UnBlock' ? 'selected' : ''); ?>>
                                                UnBlock
                                            </option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </th>

                    <th>Detail</th>


                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pelangganof; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="font-weight-bold">
                        <td><?php echo e($no + 1); ?></td>
                        <td><?php echo e($item->id_plg); ?></td>
                        <td><?php echo e($item->nama_plg); ?></td>
                        <td><?php echo e($item->alamat_plg); ?></td>
                        <td><?php echo e($item->no_telepon_plg); ?></td>
                        <td><?php echo e($item->aktivasi_plg); ?></td>
                        <td><?php echo e($item->paket_plg); ?></td>
                        <td><?php echo e(number_format($item->harga_paket, 0, ',', '.')); ?></td>
                        <td><?php echo e($item->tgl_tagih_plg); ?></td>

                        <td><?php echo e($item->keterangan_plg); ?></td>
                        <td><?php echo e($item->created_at); ?></td>
                        <td><?php echo e($item->status_pembayaran); ?></td>

                        <td>
                            <a href="<?php echo e(route('pelangganof.detail', $item->id)); ?>" class="btn btn-warning btn-sm">Detail
                            </a>
                        </td>



                        <script>
                            function validateForm(form) {
                                let requiredFields = ['id_plg', 'nama_plg', 'alamat_plg', 'no_telepon_plg', 'paket_plg', 'odp', 'maps'];
                                for (let field of requiredFields) {
                                    if (!form[field].value) {
                                        alert('Form ' + field + ' tidak boleh kosong!');
                                        return false;
                                    }
                                }
                                return true;
                            }
                        </script>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="13" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\baru\dashboard2\resources\views/pelangganof/index.blade.php ENDPATH**/ ?>