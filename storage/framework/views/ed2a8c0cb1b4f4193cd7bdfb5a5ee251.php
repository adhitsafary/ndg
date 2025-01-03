<?php $__env->startSection('konten'); ?>
    <div class="ml-5 mr-5 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4>Kirim Pesan WhatsApp Reminder</h4>
            </div>
            <div class="card-body">

                <?php if(session('status')): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert"
                        style="background-color: #009b08; color: #1dff1d; border-color: #ffeeba;">
                        <?php echo e(session('status')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('peringatan.create')); ?>" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" name="search" placeholder="Nama Pelanggan"
                                class="form-control border-primary" value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" name="alamat_plg" placeholder="Alamat" class="form-control border-primary"
                                value="<?php echo e(request('alamat_plg')); ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <select name="tgl_tagih_plg" id="tgl_tagih_plg" class="form-control border-primary"
                                onchange="this.form.submit();">
                                <option value="">Tanggal Tagih</option>
                                <?php for($i = 1; $i <= 31; $i++): ?>
                                    <option value="<?php echo e($i); ?>"
                                        <?php echo e(request('tgl_tagih_plg') == $i ? 'selected' : ''); ?>>
                                        <?php echo e($i); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type="submit" class="btn btn-success w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <form action="<?php echo e(route('peringatan.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group mb-3">
                        <label for="target" class="form-label">Pilih Target:</label>
                        <select name="target[]" id="target" class="form-control form-control-lg border-primary" multiple
                            style="height: 300px;" onchange="updateMessage()">
                            <?php $__currentLoopData = $pelanggan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->no_telepon_plg); ?>"
                                    data-tgl_tagih="<?php echo e(\Carbon\Carbon::now()->setDay($item->tgl_tagih_plg)->format('d F Y')); ?>"
                                    data-nama="<?php echo e($item->nama_plg); ?>" data-paket="<?php echo e($item->paket_plg); ?>">
                                    <?php echo e($item->nama_plg); ?> - <?php echo e($item->no_telepon_plg); ?> - <?php echo e($item->alamat_plg); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="message" class="form-label">Pesan:</label>
                        <textarea name="message" id="message" class="form-control border-primary" rows="10" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 w-100">Kirim Pesan</button>
                </form>
            </div>
            <br>
        </div>
    </div>

    <script>
        function updateMessage() {
            let select = document.getElementById('target');
            let message = '';

            for (let option of select.selectedOptions) {
                let tglTagih = option.getAttribute('data-tgl_tagih');
                let nama = option.getAttribute('data-nama');
                let paket = option.getAttribute('data-paket');

                message += `Assalamualaikum selamat siang. \n`;
                message += `Bapak/Ibu ${nama}, kami dari Net Net, mengingatkan pembayaran paket ${paket}. \n`;
                message += `Tanggal jatuh tempo: ${tglTagih}. Bisa bayar hari ini melalui transfer atau Dana? 🙏🏻\n`;
            }

            document.getElementById('message').value = message;
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\whatsapp\peringatan.blade.php ENDPATH**/ ?>