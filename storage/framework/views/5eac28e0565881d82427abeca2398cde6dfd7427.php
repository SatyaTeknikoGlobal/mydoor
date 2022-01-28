<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'category/';
?>





<div class="content-page">

    <!-- Start content -->
    <div class="content">

        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="breadcrumb-holder">
                        <h1 class="main-title float-left">Settings</h1>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item active">Settings</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->


            <?php echo $__env->make('snippets.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('snippets.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


                        <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3><i class="far fa-hand-pointer"></i>Settings</h3>
                        </div>

                        <div class="card-body">

                         <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                            <?php echo e(csrf_field()); ?>


                         
                            <div class="form-group">
                                <label for="userName">Privacy<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="privacy" name="privacy"><?php echo e($settings->privacy ?? ''); ?></textarea>
                                <?php echo $__env->make('snippets.errors_first', ['param' => 'privacy'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>



                            <div class="form-group">
                                <label for="userName">Terms & Condition<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="terms" name="terms"><?php echo e($settings->terms ?? ''); ?></textarea>
                                <?php echo $__env->make('snippets.errors_first', ['param' => 'terms'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>


  
                            <div class="form-group">
                                <label for="userName">About<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="about" name="about"><?php echo e($settings->about ?? ''); ?></textarea>
                                <?php echo $__env->make('snippets.errors_first', ['param' => 'about'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>


                   

                 <div class="form-group text-right m-b-0">
                    <button class="btn btn-primary" type="submit">
                        Submit
                    </button>
                </div>

            </form>

        </div>
    </div><!-- end card-->
</div>



</div>

</div>
<!-- END container-fluid -->

</div>
<!-- END content -->

</div>
<!-- END content-page -->







  <?php echo $__env->make('admin.common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

 <script>
              CKEDITOR.replace( 'privacy' );
              CKEDITOR.replace( 'terms' );
              CKEDITOR.replace( 'about' );
          </script><?php /**PATH /home/appmantr/public_html/mydoor/resources/views/admin/home/settings.blade.php ENDPATH**/ ?>