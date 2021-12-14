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
            <h1 class="main-title float-left">All Guards</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">All Guards</li>
            </ol>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <!-- end row -->
      @include('snippets.errors')
      @include('snippets.flash')


      <div class="row d-none">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" data-select2-id="10">
          <div class="card mb-3">
           <div class="card-header">
            <h3>Guards</h3>
          </div>

          <form method="POST" action="" >
               {{ csrf_field() }}

             <div class="card-body d-flex" >

              <div class="row">
                
            
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                <select name="type" class="form-control">
                  <option value="CREDIT">CREDIT</option>
                  <option value="DEBIT">DEBIT</option>
                </select>
              </div>

              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                <input type="text" name="amount" class="form-control" value="" placeholder="Enter Amount">
              </div>

                </div>

                

              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
               <button class="btn btn-success">Submit</button>
             </div>

           </div>
         </form>

       </div>

     </div>
   </div>







   <div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
      <div class="card mb-3">
        <div class="card-header">
          <h3>All Guard List</h3>
          @if(CustomHelper::isAllowedSection('guards' , $roleId , $type='add'))
          <span class="pull-right">
            <a href="{{ route($routeName.'.guards.add', ['back_url'=>$BackUrl]) }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus" aria-hidden="true"></i> Add New Guard</a>
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
                 <th scope="col">Email</th>
                 <th scope="col">Phone</th>
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
   ajax: '{{ route($routeName.'.guards.get_guards') }}',
   columns: [
   { data: 'id', name: 'id' },
   { data: 'name', name: 'name' ,searchable: false, orderable: false},
   { data: 'email', name: 'email'},
   { data: 'phone', name: 'phone'},
   { data: "society_id",name: 'society_id'},
   { data: 'is_approve', name: 'is_approve' },
   { data: 'location', name: 'location' },
   { data: 'state', name: 'state' },
   { data: 'city', name: 'city' },
   { data: 'status', name: 'status' },
   { data: 'created_at', name: 'created_at' },

   { data: 'action', searchable: false, orderable: false }

   ],
 });

  function change_guards_status(guard_id){
    var status = $('#change_guards_status'+guard_id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.guards.change_guards_status') }}",
      type: "POST",
      data: {guard_id:guard_id, status:status},
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


  function change_guards_approve(guard_id){
    var approve = $('#change_guards_approve'+guard_id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.guards.change_guards_approve') }}",
      type: "POST",
      data: {guard_id:guard_id, approve:approve},
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