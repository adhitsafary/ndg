<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <!-- Filter Tanggal -->
        <form action="<?php echo e(route('perbaikan.rekapTeknisi')); ?>" method="GET" class="mb-3">
            <div class="input-group" style="color: black;">
                <input type="text" name="search" id="search" class="form-control font-weight-bold" style="color: black;"
                    value="<?php echo e(request('search')); ?>" placeholder="Pencarian">
            </div>

            <div class="row">
                <div class="col-md-5">
                    <div class="form-group" style="color: black;">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                            value="<?php echo e(request('start_date', $startDate->format('Y-m-d'))); ?>">
                    </div>
                </div>
                <div class="col-md-5" style="color: black;">
                    <div class="form-group">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date" class="form-control"
                            value="<?php echo e(request('end_date', $endDate->format('Y-m-d'))); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="filter_button">&nbsp;</label>
                        <button type="submit" class="btn btn-danger btn-block">Filter</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Wrapper untuk menempatkan elemen secara sejajar -->
        <div class="d-flex justify-content-between align-items-center" style="color: black;">
            <!-- Menampilkan Total Perbaikan -->
            <div>
                <h5>Total Perbaikan: <?php echo e($totalPerbaikan); ?></h5>
            </div>

            <!-- Tombol Cetak PDF -->
            <div class="mb-2">
                <form action="<?php echo e(route('perbaikan.printRekapTeknisi')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="start_date"
                        value="<?php echo e(request('start_date', $startDate->format('Y-m-d'))); ?>">
                    <input type="hidden" name="end_date" value="<?php echo e(request('end_date', $endDate->format('Y-m-d'))); ?>">
                    <button type="submit" class="btn btn-danger">Cetak PDF</button>
                </form>
            </div>
        </div>


        <!-- Tabel Rekap Data Teknisi -->
        <table class="table table-bordered " style="color: black;">
            <thead class="table table-danger">
                <tr>
                    <th style="color: black;">Teknisi</th>
                    <th class="text text-black" style="color: black;">Total Perbaikan</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $rekap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="font-weight-bold">
                        <td><?php echo e($data->teknisi); ?></td>
                        <td><?php echo e($data->total); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>


        <div>
            <table class="table table-bordered  " style="color: black;">
                <thead class="table table-primary " style="color: black;">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No Telpon</th>
                        <th>Teknisi</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>


                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $perbaikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="font-weight-bold">
                            <td><?php echo e($no + 1); ?></td>
                            <td><?php echo e($item->id_plg); ?></td>
                            <td><?php echo e($item->nama_plg); ?></td>
                            <td><?php echo e($item->alamat_plg); ?></td>
                            <td><?php echo e($item->no_telepon_plg); ?></td>
                            <td><?php echo e($item->teknisi); ?></td>
                            <td><?php echo e($item->keterangan_plg); ?></td>
                            <td><?php echo e($item->created_at); ?></td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="13" class="text-center">Tidak ada data ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\perbaikan\rekap_teknisi.blade.php ENDPATH**/ ?>