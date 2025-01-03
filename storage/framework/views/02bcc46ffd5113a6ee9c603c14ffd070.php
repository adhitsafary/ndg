<?php $__env->startSection('konten'); ?>
    <div class="container">
        <h1 class="my-4">Edit File</h1>

        <form action="<?php echo e(route('file.update', $file->id)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('POST'); ?> <!-- Pastikan menggunakan POST method -->

            <!-- Input untuk mengubah nama file -->
            <div class="mb-3">
                <label for="file_name" class="form-label">Edit File Name</label>
                <input type="text" name="file_name" class="form-control" id="file_name" value="<?php echo e($file->file_name); ?>">
            </div>

            <!-- Input untuk mengubah file jika diperlukan -->
            <div class="mb-3">
                <label for="file" class="form-label">Replace File (Optional)</label>
                <input type="file" name="file" class="form-control" id="file">
            </div>

            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\files\edit.blade.php ENDPATH**/ ?>