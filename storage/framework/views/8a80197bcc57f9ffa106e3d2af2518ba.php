<?php $__env->startSection('konten'); ?>
    <div class="container">
        <h1 class="my-4">NDG Drive</h1>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <a href="<?php echo e(route('file.create')); ?>" class="btn btn-danger mb-3">Upload File baru</a>

        <div class="d-flex align-items-center justify-content-between mt-2">
            <form action="<?php echo e(route('file.index')); ?>" method="GET" class="form-inline d-flex" style="color: black;">
                <div class="input-group" style="color: black;">
                    <input type="text" name="search" id="search" class="form-control font-weight-bold mr-2"
                        style="color: black;" value="<?php echo e(request('search')); ?>" placeholder="Pencarian">
                </div>
                <input type="date" id="updated_at" name="updated_at" value="<?php echo e(request()->get('updated_at')); ?>">
                <button type="submit" name="action" value="search" class="btn btn-danger ml-2">Cari</button>
            </form>
        </div>


        <div class="container">


            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>File</th>
                        <th>Ukuran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td class="d-flex align-items-center">
                                <?php if(in_array(pathinfo($file->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'svg'])): ?>
                                    <a href="<?php echo e(Storage::url($file->file_path)); ?>" target="_blank">
                                        <img src="<?php echo e(Storage::url($file->file_path)); ?>" alt="Preview" class="img-thumbnail"
                                            style="width: 50px; height: 50px; margin-right: 10px;">
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(Storage::url($file->file_path)); ?>" target="_blank">
                                        <img src="<?php echo e(asset('images/default-file-preview.png')); ?>" alt="No Preview"
                                            class="img-thumbnail" style="width: 50px; height: 50px; margin-right: 10px;">
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(Storage::url($file->file_path)); ?>" target="_blank"><?php echo e($file->file_name); ?></a>
                            </td>
                            <td><?php echo e(number_format($file->file_size / 1048576, 2)); ?> MB</td>

                            <!-- Dropdown Action Button -->
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-danger dropdown-toggle" type="button"
                                        id="actionDropdown<?php echo e($file->id); ?>" data-bs-toggle="dropdown"
                                        aria-expanded="false">

                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="actionDropdown<?php echo e($file->id); ?>">
                                        <!-- Menghapus tindakan "Lihat" karena sudah langsung bisa diklik pada nama file -->
                                        <li><a class="dropdown-item"
                                                href="<?php echo e(route('file.download', $file->id)); ?>">Download</a></li>
                                        <li><a class="dropdown-item" href="<?php echo e(route('file.edit', $file->id)); ?>">Edit</a>
                                        </li>
                                        <li>
                                            <form action="<?php echo e(route('file.destroy', $file->id)); ?>" method="POST"
                                                style="display:inline;">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center">No files uploaded yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\files\index.blade.php ENDPATH**/ ?>