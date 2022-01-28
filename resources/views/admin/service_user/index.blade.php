@include('admin.common.header')

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
            <h1 class="main-title float-left">All Service Users</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">All Service Users</li>
            </ol>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <!-- end row -->
            @include('snippets.errors')
            @include('snippets.flash')
      <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="card mb-3">
            <div class="card-header">
              <h3>All Service Users List</h3>
              @if(CustomHelper::isAllowedSection('service_user' , $roleId , $type='add'))
              <span class="pull-right">
                  <a href="{{ route($routeName.'.service_user.add', ['back_url'=>$BackUrl]) }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus" aria-hidden="true"></i> Add New User</a>
              </span>
              @endif
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover display" style="width:100%">
                  <thead>
                    <tr>
                     <th scope="col">#ID</th>
                     <th scope="col">Society Name</th>
                     <th scope="col">Service Name</th>
                     <th scope="col">Name</th>
                     <th scope="col">Phone</th>
                     <th scope="col">Image</th>
                     <th scope="col">ID Proof</th>
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



@include('admin.common.footer')

<script>
  var i = 1;

  var table = $('#dataTable').DataTable({
   ordering: false,
   processing: true,
   serverSide: true,
   ajax: '{{ route($routeName.'.service_user.get_service_users') }}',
   columns: [
   { data: 'id', name: 'id' },
   { data: 'society_id', name: 'society_id' ,searchable: false, orderable: false},
   { data: 'service_id', name: 'service_id'},
   { data: 'name', name: 'name'},
   { data: 'phone', name: 'phone'},
   { data: 'image', name: 'image'},
   { data: 'id_proof', name: 'id_proof'},
   { data: 'status', name: 'status' },
   { data: 'created_at', name: 'created_at' },

   { data: 'action', searchable: false, orderable: false }

   ],
});

function change_status(admin_id){
  var status = $('#change_status'+admin_id).val();


   var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.service_user.change_status') }}",
                type: "POST",
                data: {admin_id:admin_id, status:status},
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




</script>