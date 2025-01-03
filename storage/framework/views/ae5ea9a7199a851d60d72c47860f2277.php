<?php $__env->startSection('konten'); ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Detail Pelanggan</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 style="color: black" class="fotn font-weight-bold">Informasi Pelanggan</h5>
                        <ul class="list-group font-weight-bold" style="color: black">
                            <li class="list-group-item">
                                <strong>ID:</strong> <?php echo e($pelanggan->id_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Nama:</strong> <?php echo e($pelanggan->nama_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Alamat:</strong> <?php echo e($pelanggan->alamat_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>No Telepon:</strong> <?php echo e($pelanggan->no_telepon_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Aktivasi:</strong> <?php echo e($pelanggan->aktivasi_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Longitude :</strong> <?php echo e($pelanggan->longitude); ?>

                            </li>


                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: black" class="font-weight-bold">Detail Paket</h5>
                        <ul class="list-group font-weight-bold"  style="color: black">
                            <li class="list-group-item">
                                <strong>Paket:</strong> <?php echo e($pelanggan->paket_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Harga Paket:</strong> <?php echo e(number_format($pelanggan->harga_paket, 0, ',', '.')); ?>


                            </li>
                            <li class="list-group-item">
                                <strong>Tanggal Tagih : </strong>
                               <?php echo e($pelanggan->tgl_tagih_plg); ?>

                            </li>

                            <li class="list-group-item">
                                <strong>Keterangan :</strong> <?php echo e($pelanggan->keterangan_plg); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>ODP :</strong> <?php echo e($pelanggan->odp); ?>

                            </li>
                            <li class="list-group-item">
                                <strong>Latitude:</strong> <?php echo e($pelanggan->latitude); ?>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="<?php echo e(route('pelanggan.edit', $pelanggan->id)); ?>" class="">
                            <img src="<?php echo e(asset('asset/img/icon/edit.png')); ?>" style="height : 40px; width : 40px" alt="">
                        </a>
                        <form action="<?php echo e(route('pelanggan.destroy', $pelanggan->id)); ?>" method="POST"
                            class="d-inline-block">
                            <?php echo csrf_field(); ?>
                            <a href="#" onclick="if(confirm('Yakin ingin menghapus data ini?')) { this.closest('form').submit(); return false; }" style="display: inline-block;">
                                <img src="<?php echo e(asset('asset/img/icon/delete.png')); ?>" style="height: 35px; width: 35px;" alt="Hapus" >
                            </a>
                        </form>

                    <!--    <a href="#" class=""
                            onclick="showBayarModal(<?php echo e($pelanggan->id); ?>, '<?php echo e($pelanggan->nama_plg); ?>', <?php echo e($pelanggan->harga_paket); ?>)">
                        <img src="<?php echo e(asset('asset/img/icon/bayar.png')); ?>" style="heigth : 35px; width : 35px; " alt="">
                        </a>

                        <div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="bayarModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="bayarModalLabel">Pembayaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                  
                                    <form id="bayarForm" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" id="pelangganId">
                                        <div class="modal-body">
                                         
                                            <div class="mb-3">
                                                <label for="tanggalPembayaran" class="form-label">Tanggal
                                                    Pembayaran</label>
                                                <input type="date" class="form-control" id="tanggalPembayaran"
                                                    name="tanggal_pembayaran" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="metodeTransaksi" class="form-label">Metode Transaksi</label>
                                                <select class="form-select" id="metodeTransaksi" name="metode_transaksi"
                                                    required>
                                                    <option value="">Pilih metode</option>
                                                    <option value="CASH">Cash</option>
                                                    <option value="TF">Transfer</option>
                                                </select>
                                            </div>

                          
                                            <div class="mb-3">
                                                <p id="pembayaranDetails"></p>
                                            </div>
                                        </div>

                                    
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Bayar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>  -->
                        <a href="<?php echo e(route('pelanggan.historypembayaran', $pelanggan->id)); ?>"
                            class="btn btn-info btn-sm">
                        <img src="<?php echo e(asset('asset/img/icon/riwayat.png')); ?>" style="height : 35px; width : 35px; " alt="">
                        </a>

                    </div>
                    <div>
                        <a href="<?php echo e(route('pelanggan.index')); ?>" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
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

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\pelanggan\detail.blade.php ENDPATH**/ ?>