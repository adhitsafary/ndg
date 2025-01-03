<?php $__env->startSection('konten'); ?>
    <div class="container">
        <h1>Filter Pelanggan Berdasarkan Tanggal Tagih</h1>

        <th>
            <form class="filterForm" method="GET" action="<?php echo e(route('pelanggan.filterTagihindex')); ?>">
                <div class="form-group">
                    <select name="paket_plg" id="paket_plg" onchange="document.querySelector('.filterForm').submit();">
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
                </div>
                <div class="form-group">
                    <select name="tgl_tagih_plg" id="tgl_tagih_plg"
                        onchange="document.querySelector('.filterForm').submit();">
                        <option value="">Tanggal Tagih</option>
                        <?php for($i = 1; $i <= 32; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e(request('tgl_tagih_plg') == $i ? 'selected' : ''); ?>>
                                <?php echo e($i); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </form>
        </th>
        <th>Harga Paket</th>


        <?php if(isset($pelanggan) && count($pelanggan) > 0): ?>
            <h2 class="mt-5">Hasil Filter Tanggal Tagih: <?php echo e($tanggal); ?></h2>
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>No Telepon</th>
                        <th>Tanggal Tagih</th>
                        <th>Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pelanggan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($plg->id_plg); ?></td>
                            <td><?php echo e($plg->nama_plg); ?></td>
                            <td><?php echo e($plg->alamat_plg); ?></td>
                            <td><?php echo e($plg->no_telepon_plg); ?></td>
                            <td><?php echo e($plg->tgl_tagih_plg); ?></td>
                            <td><?php echo e($plg->status_pembayaran); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="mt-5">Tidak ada data pelanggan untuk tanggal tagih tersebut.</p>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pelanggan\index_tagih.blade.php ENDPATH**/ ?>