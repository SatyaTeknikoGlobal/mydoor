<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$flats_id = (isset($flats->id))?$flats->id:'';
$society_id = (isset($flats->society_id))?$flats->society_id:'';
$block_id = (isset($flats->block_id))?$flats->block_id:'';
$flat_no = (isset($flats->flat_no))?$flats->flat_no:'';
$status = (isset($flats->status))?$flats->status:'';


$routeName = CustomHelper::getAdminRouteName();
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


                            <input type="hidden" name="id" value="<?php echo e($flats_id); ?>">

                             <div class="form-group">
                                <label for="userName">Society Name<span class="text-danger">*</span></label>
                               <select name="society_id" id="society_id" class="form-control select2">
                                   <option value="" selected disabled>Select Society</option>
                                   <?php if(!empty($societies)){
                                    foreach($societies as $soci){
                                    ?>
                                    <option value="<?php echo e($soci->id); ?>" <?php if($soci->id == $society_id) echo "selected"?>><?php echo e($soci->name); ?></option>
                                <?php }}?>

                               </select>

                                <?php echo $__env->make('snippets.errors_first', ['param' => 'society_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>


                            <div class="form-group">
                                <label for="userName">Block Name<span class="text-danger">*</span></label>
                               <select name="block_id" id="block_id" class="form-control select2">
                                   <option value="" selected disabled>Select Block</option>
                                   <?php if(!empty($blocks)){
                                    foreach($blocks as $block){
                                    ?>
                                    <option value="<?php echo e($block->id); ?>" <?php if($block->id == $block_id) echo "selected"?>><?php echo e($block->name); ?></option>
                                <?php }}?>

                               </select>

                                <?php echo $__env->make('snippets.errors_first', ['param' => 'block_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>








                            <div class="form-group">
                                <label for="userName">Flat Name<span class="text-danger">*</span></label>
                                <input type="text" name="flat_no" value="<?php echo e(old('flat_no', $flat_no)); ?>" id="flat_no" class="form-control"  maxlength="255" />

                                <?php echo $__env->make('snippets.errors_first', ['param' => 'flat_no'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
    $('#society_id').change(function() {
    var society_id = $(this).val();
    var _token = '<?php echo e(csrf_token()); ?>';

            $.ajax({
                url: "<?php echo e(route($routeName.'.flats.get_blocks_from_society')); ?>",
                type: "POST",
                data: {society_id:society_id},
                dataType:"HTML",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    $('#block_id').html(resp);
                }
            });

   });
</script><?php /**PATH /home/appmantr/public_html/mydoor/resources/views/admin/flats/form.blade.php ENDPATH**/ ?>