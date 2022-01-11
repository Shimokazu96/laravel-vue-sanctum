<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Laravel</title>

    <!-- Scripts -->
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
</head>

<body>
    <div id="app">
        <router-view />
    </div>
</body>

</html>
<?php /**PATH Z:\repository_ubuntu_sda1_hdd\shimooka_dev\laravel-vue-sanctum\resources\views/app.blade.php ENDPATH**/ ?>