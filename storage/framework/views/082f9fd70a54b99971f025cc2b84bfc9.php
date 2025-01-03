<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>Login</title>
    <style>
        .login-container {
            max-width: 400px;
            margin: auto;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-logo {
            max-width: 150px;
            height: auto;
        }

        @media (max-width: 576px) {
            .login-container {
                max-width: 90%;
            }

            .login-logo {
                max-width: 100px;
            }
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-container">
            <img class="d-block mx-auto login-logo mb-3" src="<?php echo e(asset('template2/img/logo/logo.png')); ?>"
                alt="Logo">
                <h3 class=" text-center" style="font font-weight">Absensi</h3>
            <h5 class="text-center mb-4">Net Digital Grup</h5>
           
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($item); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" value="<?php echo e(old('email')); ?>" name="email" class="form-control" required>
                </div>
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <input type="checkbox" id="show-password" onclick="togglePassword()"
                        class="form-check-input position-absolute"
                        style="top: 50%; right: 10px; transform: translateY(-50%);">
                    <label for="show-password" class="form-check-label" style="margin-left: 5px;"></label>
                </div>
                <div class="mb-3 d-grid">
                    <button name="submit" type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>

            <script>
                function togglePassword() {
                    var passwordField = document.getElementById("password");
                    if (passwordField.type === "password") {
                        passwordField.type = "text";
                    } else {
                        passwordField.type = "password";
                    }
                }
            </script>

        </div>
    </div>

    <!-- JS and Popper.js bundle -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz4fnFO9gybBogGzF3j7hHd9wgLkaQKQUH0aZnM+z4o+7WTf5RXFuAr7Pp" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"
        integrity="sha384-EdkOBOo27AqWwVn4MbWiqlO8kB5gBQFLYJvpFfb9FwgJFW9jWk5MGYpkvJml3IIA" crossorigin="anonymous">
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\laravel\baru\2025\dashboard2\resources\views\absensi\index.blade.php ENDPATH**/ ?>