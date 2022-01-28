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
            <h1 class="main-title float-left">All Admins</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">All Admins</li>
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
              <h3>All Admins List</h3>
              @if(CustomHelper::isAllowedSection('admins' , $roleId , $type='add'))
              <span class="pull-right">
                  <a href="{{ route($routeName.'.admins.add', ['back_url'=>$BackUrl]) }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus" aria-hidden="true"></i> Add New Admin</a>
              </span>
              @endif
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover display" style="width:100%">
                  <thead>
                    <tr>
                     <th scope="col">#ID</th>
                     <th scope="col">Name</th>
                     <th scope="col">UserName</th>
                     <th scope="col">Email</th>
                     <th scope="col">Role</th>
                     <th scope="col">Society Name</th>
                     <th scope="col">Approve/Not Approve</th>
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



@include('admin.common.footer')

<script>
  var i = 1;

  var table = $('#dataTable').DataTable({
   ordering: false,
   processing: true,
   serverSide: true,
   ajax: '{{ route($routeName.'.admins.get_admins') }}',
   columns: [
   { data: 'id', name: 'id' },
   { data: 'name', name: 'name' ,searchable: false, orderable: false},
   { data: 'username', name: 'username'},
   { data: 'email', name: 'email'},
   { data: 'role_id', name: 'role_id'},
   { data: "society_id",name: 'society_id'},
   { data: 'is_approve', name: 'is_approve' },
   { data: 'address', name: 'address' },
   { data: 'state', name: 'state' },
   { data: 'city', name: 'city' },
   { data: 'status', name: 'status' },
   { data: 'created_at', name: 'created_at' },

   { data: 'action', searchable: false, orderable: false }

   ],
});

function change_admins_status(admin_id){
  var status = $('#change_admins_status'+admin_id).val();


   var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.admins.change_admins_status') }}",
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


function change_admins_approve(admin_id){
  var approve = $('#change_admins_approve'+admin_id).val();


   var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.admins.change_admins_approve') }}",
                type: "POST",
                data: {admin_id:admin_id, approve:approve},
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

function change_admins_role(admin_id){
  var role_id = $('#change_admins_role'+admin_id).val();

   var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.admins.change_admins_role') }}",
                type: "POST",
                data: {admin_id:admin_id, role_id:role_id},
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