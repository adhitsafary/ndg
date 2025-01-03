<?php $__env->startSection('konten'); ?>
    <div class="container">
        <h4>Edit User</h4>

        <form action="<?php echo e(route('users.update', $user->id)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo e($user->name); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo e($user->email); ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="teknisi" <?php echo e($user->role == 'teknisi' ? 'selected' : ''); ?>>Teknisi</option>
                    <option value="admin" <?php echo e($user->role == 'admin' ? 'selected' : ''); ?>>Admin</option>
                    <option value="superadmin" <?php echo e($user->role == 'superadmin' ? 'selected' : ''); ?>>Superadmin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password (Biarkan kosong jika tidak ingin mengganti):</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="foto">Foto (Biarkan kosong jika tidak ingin mengganti):</label>
                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*">
                <?php if($user->foto): ?>
                    <div class="mt-2">
                        <img src="<?php echo e(asset($user->foto)); ?>" alt="Foto Pengguna"
                            style="max-width: 100px; border-radius: 5px;">
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\users\edit.blade.php ENDPATH**/ ?>