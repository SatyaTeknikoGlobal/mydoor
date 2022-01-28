<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$service_user_id = (isset($service_user->id))?$service_user->id:'';


$name = (isset($service_user->name))?$service_user->name:'';
$service_id = (isset($service_user->service_id))?$service_user->service_id:'';
$society_id = (isset($service_user->society_id))?$service_user->society_id:'';
$email = (isset($service_user->email))?$service_user->email:'';
$phone = (isset($service_user->phone))?$service_user->phone:'';
$image = (isset($service_user->image))?$service_user->image:'';
$id_proof = (isset($service_user->id_proof))?$service_user->id_proof:'';
$status = (isset($service_user->status))?$service_user->status:'';




$routeName = CustomHelper::getSadminRouteName();
$storage = Storage::disk('public');
$path = 'service_user/';



?>


<div class="content-page">

    <!-- Start content -->
    <div class="content">

        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="breadcrumb-holder">
                        <h1 class="main-title float-left"><?php echo e($page_heading); ?></h1>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item active"><?php echo e($page_heading); ?></li>
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
                            <h3><i class="far fa-hand-pointer"></i><?php echo e($page_heading); ?></h3>

                            <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                            <a href="<?php echo e(url($back_url)); ?>" class="btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?>
                        </div>

                        <div class="card-body">

                         <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                            <?php echo e(csrf_field()); ?>


                            <input type="hidden" name="id" value="<?php echo e($service_user_id); ?>">

                              <div class="form-group">
                                <label for="userName">Society Name<span class="text-danger">*</span></label>
                                <select name="society_id" class="form-control select2">
                                    <option value="" selected disabled>Select Society Name</option>
                                    <?php if(!empty($societies)){
                                        foreach($societies as $society){
                                            ?>
                                            <option value="<?php echo e($society->id); ?>" <?php if($society->id == $society_id) echo "selected"?>><?php echo e($society->name); ?></option>
                                        <?php }}?>
                                    </select>

                                    <?php echo $__env->make('snippets.errors_first', ['param' => 'society_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>


                                    <div class="form-group">
                                <label for="userName">Service Name<span class="text-danger">*</span></label>
                                <select name="service_id" class="form-control select2">
                                    <option value="" selected disabled>Select Service Name</option>
                                    <?php if(!empty($services)){
                                        foreach($services as $service){
                                            ?>
                                            <option value="<?php echo e($service->id); ?>" <?php if($service->id == $service_id) echo "selected"?>><?php echo e($service->name); ?></option>
                                        <?php }}?>
                                    </select>

                                    <?php echo $__env->make('snippets.errors_first', ['param' => 'service_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>





                            <div class="form-group">
                                <label for="userName">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" value="<?php echo e(old('name', $name)); ?>" id="name" class="form-control"  maxlength="255" placeholder="Enter Name" />

                                <?php echo $__env->make('snippets.errors_first', ['param' => 'name'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>





                            <div class="form-group">
                                <label for="userName">Email<span class="text-danger">*</span></label>
                                <input type="text" name="email" value="<?php echo e(old('email', $email)); ?>" id="email" class="form-control"  maxlength="255" placeholder="Enter Email" />

                                <?php echo $__env->make('snippets.errors_first', ['param' => 'email'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>


                                <div class="form-group">
                                    <label for="userName">Phone<span class="text-danger">*</span></label>
                                    <input type="text" name="phone" value="<?php echo e(old('phone', $phone)); ?>" id="phone" class="form-control" placeholder="Enter Phone"  maxlength="255" />


                                    <?php echo $__env->make('snippets.errors_first', ['param' => 'phone'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                 <div class="form-group">
                                <label for="userName">Profile Image<span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control" />

                                <?php echo $__env->make('snippets.errors_first', ['param' => 'name'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>

                            <?php
                            if(!empty($image)){
                                if($storage->exists($path.$image)){
                                    ?>
                                    <div class=" image_box" style="display: inline-block">
                                        <a href="<?php echo e(url('storage/app/public/'.$path.$image)); ?>" target="_blank">
                                            <img src="<?php echo e(url('storage/app/public/'.$path.'thumb/'.$image)); ?>" style="width:70px;">
                                        </a>
                                    </div>
                                    <?php
                                }
                            }
                            ?>





                             <div class="form-group">
                                <label for="userName">ID Proof<span class="text-danger">*</span></label>
                                <input type="file" name="id_proof" class="form-control" />

                                <?php echo $__env->make('snippets.errors_first', ['param' => 'id_proof'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>

                            <?php
                            if(!empty($id_proof)){
                                if($storage->exists($path.$id_proof)){
                                    ?>
                                    <div class=" image_box" style="display: inline-block">
                                        <a href="<?php echo e(url('storage/app/public/'.$path.$id_proof)); ?>" target="_blank">
                                            <img src="<?php echo e(url('storage/app/public/'.$path.'thumb/'.$id_proof)); ?>" style="width:70px;">
                                        </a>
                                    </div>
                                    <?php
                                }
                            }
                            ?>








                                    <div class="form-group">
                                        <label>Status</label>
                                        <div>
                                         Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                                         &nbsp;
                                         Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                                         <?php echo $__env->make('snippets.errors_first', ['param' => 'status'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                     </div>
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


<?php echo $__env->make('sadmin.common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    CKEDITOR.replace( 'description' );
</script>

<script type="text/javascript">
 $('#state_id').on('change', function()
 {

    var _token = '<?php echo e(csrf_token()); ?>';
    var state_id = $('#state_id').val();
    $.ajax({
      url: "<?php echo e(route('get_city')); ?>",
      type: "POST",
      data: {state_id:state_id},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
         $('#city_id').html(resp);
     }
 });
});
</script><?php /**PATH /home/appmantr/public_html/mydoor/resources/views/admin/service_user/form.blade.php ENDPATH**/ ?>