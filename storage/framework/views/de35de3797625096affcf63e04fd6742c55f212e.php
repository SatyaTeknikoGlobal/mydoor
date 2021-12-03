<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
$roleId = Auth::guard('admin')->user()->role_id; 

?>

<div class="content-page">

  <!-- Start content -->
  <div class="content">

    <div class="container-fluid">

      <div class="row">
        <div class="col-xl-12">
          <div class="breadcrumb-holder">
            <h1 class="main-title float-left">Society</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Society</li>
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
              <h3>Society List</h3>
              <?php if(CustomHelper::isAllowedSection('society' , $roleId , $type='add')): ?>
              <span class="pull-right">
                  <a href="<?php echo e(route($routeName.'.society.add', ['back_url'=>$BackUrl])); ?>" class="btn btn-primary btn-sm"><i class="fas fa-user-plus" aria-hidden="true"></i> Add New Society</a>
              </span>
              <?php endif; ?>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover display" style="width:100%">
                  <thead>
                    <tr>
                     <th scope="col">#ID</th>
                     <th scope="col">Name</th>
                     <th scope="col">Location</th>
                     <th scope="col">State</th>

                     <th scope="col">City</th>

                     <th scope="col">Status</th>
                     <th scope="col">Date Created</th>
                     <th scope="col">Action</th>
                   </tr>
                 </thead>
               </table>
             </div>
             <!-- end table-responsive-->

           </div>
           <!-- end card-body-->

         </div>
         <!-- end card-->

       </div>

     </div>
     <!-- end row-->

   </div>
   <!-- END container-fluid -->

 </div>
 <!-- END content -->

</div>
<!-- END content-page -->



<?php echo $__env->make('admin.common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
  var i = 1;

  var table = $('#dataTable').DataTable({
   ordering: false,
   processing: true,
   serverSide: true,
   ajax: '<?php echo e(route($routeName.'.society.get_society')); ?>',
   columns: [
   { data: 'id', name: 'id' },
   { data: 'name', name: 'name' ,searchable: false, orderable: false},
   { data: 'location', name: 'location'},
   { data: 'state', name: 'state'},
   { data: 'city', name: 'location'},
   { data: "status",name: 'status'},
   { data: 'created_at', name: 'created_at' },
   { data: 'action', searchable: false, orderable: false }

   ],
});

function change_society_status(society_id){
  var status = $('#change_society_status'+society_id).val();


   var _token = '<?php echo e(csrf_token()); ?>';

            $.ajax({
                url: "<?php echo e(route($routeName.'.society.change_society_status')); ?>",
                type: "POST",
                data: {society_id:society_id, status:status},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    if(resp.success){
                      alert(resp.message);
                    }else{
                      alert(resp.message);
                      
                    }
                }
            });


}


</script><?php /**PATH /home/appmantr/public_html/mydoor/resources/views/admin/society/index.blade.php ENDPATH**/ ?>