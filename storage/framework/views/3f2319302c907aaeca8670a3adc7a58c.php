<!DOCTYPE html>
<html lang="en">
<head>
<?php echo app('Illuminate\Foundation\Vite')->reactRefresh(); ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Hidrología</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="<?php echo e(URL::asset('css/font-awesome.min.css')); ?>" integrity="" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo e(URL::asset('css/css.css')); ?>">

    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo e(URL::asset('css/bootstrap.min.css')); ?>" integrity="" crossorigin="anonymous">

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                    Hidrología
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <!-- <li><a href="<?php echo e(url('/home')); ?>">Home</a></li> -->
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <!-- <?php if(Auth::guest()): ?>
                        <li><a href="<?php echo e(url('/login')); ?>">Login</a></li>
                        <li><a href="<?php echo e(url('/register')); ?>">Register</a></li>
                    <?php else: ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo e(url('/logout')); ?>"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    -->
                </ul>
            </div>
        </div>
    </nav>

    <?php echo $__env->yieldContent('content'); ?>

    <!-- JavaScripts -->
    <script src="<?php echo e(URL::asset('js/jquery.min.js')); ?>" integrity="" crossorigin="anonymous"></script>
    <script src="<?php echo e(URL::asset('js/bootstrap.min.js')); ?>" integrity="" crossorigin="anonymous"></script>
    
</body>
</html>
<?php /**PATH D:\BDComercial\Hidrologia_Site\resources\views/layouts/app.blade.php ENDPATH**/ ?>