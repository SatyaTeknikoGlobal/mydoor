<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$admins_id = (isset($admins->id))?$admins->id:'';
$name = (isset($admins->name))?$admins->name:'';
$description = (isset($admins->description))?$admins->description:'';
$location = (isset($admins->location))?$admins->location:'';
$state_id = (isset($admins->state_id))?$admins->state_id:'';
$city_id = (isset($admins->city_id))?$admins->city_id:'';
$username = (isset($admins->username))?$admins->username:'';
$phone = (isset($admins->phone))?$admins->phone:'';
$address = (isset($admins->address))?$admins->address:'';
$society_id = (isset($admins->society_id))?$admins->society_id:'';
$is_approve = (isset($admins->is_approve))?$admins->is_approve:'';
$role_id = (isset($admins->role_id))?$admins->role_id:'';
$email = (isset($admins->email))?$admins->email:'';




$status = (isset($admins->status))?$admins->status:'';


$routeName = CustomHelper::getSadminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';



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


                            <input type="hidden" name="id" value="<?php echo e($admins_id); ?>">

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
                                <label for="userName">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" value="<?php echo e(old('name', $name)); ?>" id="name" class="form-control"  maxlength="255" placeholder="Enter Name" />

                                <?php echo $__env->make('snippets.errors_first', ['param' => 'name'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>



                            <div class="form-group">
                                <label for="userName">UserName<span class="text-danger">*</span></label>
                                <input type="text" name="username" value="<?php echo e(old('username', $username)); ?>" id="username" class="form-control"  maxlength="255" placeholder="Enter Username For Login" />

                                <?php echo $__env->make('snippets.errors_first', ['param' => 'username'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>


                            <div class="form-group">
                                <label for="userName">Email<span class="text-danger">*</span></label>
                                <input type="text" name="email" value="<?php echo e(old('email', $email)); ?>" id="email" class="form-control"  maxlength="255" placeholder="Enter Email" />

                                <?php echo $__env->make('snippets.errors_first', ['param' => 'email'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>


                            <div class="form-group">
                                <label for="userName">Role<span class="text-danger">*</span></label>
                                <select name="role_id" class="form-control">
                                    <option value="" selected disabled>Select Role</option>
                                    <?php if(!empty($roles)){
                                        foreach($roles as $role){
                                            ?>
                                            <option value="<?php echo e($role->id); ?>" <?php if($role->id == $role_id) echo "selected"?>><?php echo e($role->name); ?></option>
                                        <?php }}?>
                                    </select>

                                    <?php echo $__env->make('snippets.errors_first', ['param' => 'role_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>


                                <div class="form-group">
                                    <label for="userName">Phone<span class="text-danger">*</span></label>
                                    <input type="text" name="phone" value="<?php echo e(old('phone', $phone)); ?>" id="phone" class="form-control" placeholder="Enter Phone"  maxlength="255" />


                                    <?php echo $__env->make('snippets.errors_first', ['param' => 'phone'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group">
                                    <label for="userName">Address<span class="text-danger">*</span></label>
                                    <input type="text" name="address" value="<?php echo e(old('address', $address)); ?>" id="address" class="form-control" placeholder="Enter Address"  maxlength="255" />


                                    <?php echo $__env->make('snippets.errors_first', ['param' => 'phone'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>


                            <div class="form-group">
                                <label for="userName">State<span class="text-danger">*</span></label>
                                <select name="state_id" class="form-control select2" id="state_id">
                                    <option value="" selected disabled>Select State</option>
                                    <?php if(!empty($states)){
                                        foreach($states as $state){
                                            ?>
                                            <option value="<?php echo e($state->id); ?>" <?php if($state->id == $state_id) echo "selected"?>><?php echo e($state->name); ?></option>
                                        <?php }}?>
                                    </select>

                                    <?php echo $__env->make('snippets.errors_first', ['param' => 'state_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>




                            <div class="form-group">
                                <label for="userName">City<span class="text-danger">*</span></label>
                                <select name="city_id" id="city_id" class="form-control select2">
                                    <option value="" selected disabled>Select City</option>
                                    <?php if(!empty($cities)){
                                        foreach($cities as $city){
                                            ?>
                                            <option value="<?php echo e($city->id); ?>" <?php if($city->id == $city_id) echo "selected"?>><?php echo e($city->name); ?></option>
                                        <?php }}?>
                                    </select>

                                    <?php echo $__env->make('snippets.errors_first', ['param' => 'city_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>






                                <div class="form-group">
                                    <label for="userName">Password<span class="text-danger">*</span></label>
                                    <input type="password" name="password" value="" id="password" class="form-control" placeholder="Enter Password"  maxlength="255" />


                                    <?php echo $__env->make('snippets.errors_first', ['param' => 'password'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>


                                    <div class="form-group">
                                        <label>Approve Status</label>
                                        <div>
                                         Approved: <input type="radio" name="is_approve" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                                         &nbsp;
                                         Not Approved: <input type="radio" name="is_approve" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                                         <?php echo $__env->make('snippets.errors_first', ['param' => 'is_approve'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                     </div>
                                 </div>
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
</script><?php /**PATH /home/appmantr/public_html/mydoor/resources/views/admin/admins/form.blade.php ENDPATH**/ ?>