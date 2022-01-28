<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <link rel="shortcut icon" href="<?php echo e(asset('public/assets1/img/logo-xx.png')); ?>">
    <title>UBERIZE Admin</title>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/assets1/lib/perfect-scrollbar/css/perfect-scrollbar.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/assets1/lib/material-design-icons/css/material-design-iconic-font.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/assets1/lib/jquery.vectormap/jquery-jvectormap-1.2.2.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/assets1/lib/jqvmap/jqvmap.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/assets1/lib/datetimepicker/css/bootstrap-datetimepicker.min.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('public/assets1/css/app.css')); ?>" type="text/css"/>
  </head>

  <?php echo $__env->make('snippets.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->make('snippets.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<body class="be-splash-screen">
    <div class="be-wrapper be-login">
      <div class="be-content">
        <div class="main-content container-fluid">
          <div class="splash-container">
            <div class="card card-border-color card-border-color-primary">
              <div class="card-header"><img class="logo-img" src="<?php echo e(asset('public/assets1/img/logo-xx.png')); ?>" alt="logo" width="{conf.logoWidth}" height="200" width="200"><span class="splash-description">Admin Login</span></div>
              <div class="card-body">
                <form action="<?php echo e(url('admin/login')); ?>" method="post">
                <?php echo csrf_field(); ?>

                  <div class="login-form">
                    <div class="form-group">
                      <input class="form-control" id="username" name="username" type="text" placeholder="Username" autocomplete="off">

                         <?php echo $__env->make('snippets.errors_first', ['param' => 'username'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    </div>
                    <div class="form-group">
                      <input class="form-control" id="password" name="password" type="password" placeholder="Password">
                         <?php echo $__env->make('snippets.errors_first', ['param' => 'password'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    </div>
                    <div class="form-group row login-submit">
                      <div class="col-6"><button type="submit" class="btn btn-primary btn-xl">Sign in</button></div>


                          <div class="col-6"><a href="<?php echo e(route('admin.register')); ?>" class="btn btn-primary btn-xl">Sign Up</a></div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>



<?php /**PATH /home/appmantr/public_html/mydoor/resources/views/admin/login/index.blade.php ENDPATH**/ ?>