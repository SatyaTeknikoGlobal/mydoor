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
            <h1 class="main-title float-left">Flat Owner Details - {{$societyuser->name ??''}}</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Flat Owner Details</li>
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
              <h3>Family Members List</h3>

              <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
              <a href="{{ url($back_url)}}" class="btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover display" style="width:100%">
                  <thead>
                    <tr>
                     <th scope="col">#ID</th>
                     <th scope="col">Name</th>
                     <th scope="col">Phone</th>
                     <th scope="col">Approve/Not</th>
                     <th scope="col">Status</th>
                     <th scope="col">Date Created</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php if(!empty($users)){
                    foreach($users as $user){
                     $sta = '';
                     $sta1 ='';
                     if($user->is_approve == 1){
                      $sta1 = 'selected';
                    }else{
                      $sta = 'selected';
                    }






                    ?>
                    <tr>
                      <td>{{$user->id}}</td>
                      <td>{{$user->name}}</td>
                      <td>{{$user->phone}}</td>
                      <td>
                        <select id='change_flatowner_approve{{$user->id}}' onchange='change_flatowner_approve({{$user->id}})'>
                          <option value='1' {{$sta1}}>Approved</option>
                          <option value='0' {{$sta}}>Not Approved</option>
                        </select>

                      </td>
                      <td>
                        <?php 
                        $sta = '';
                        $sta1 ='';
                        if($user->status == 1){
                          $sta1 = 'selected';
                        }else{
                          $sta = 'selected';
                        }



                        ?>
                        <select id='change_flatowner_status{{$user->id}}' onchange='change_flatowner_status({{$user->id}})'>
                          <option value='1' {{$sta1}}>Active</option>
                          <option value='0' {{$sta}}>InActive</option>
                        </select>



                      </td>
                      <td>{{$user->created_at}}</td>
                    </tr>





                  <?php }}?>
                </tbody>
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




    <div class="row">

      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3">
          <div class="card-header">
            <h3>Vehicles</h3>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <table id="dataTable1" class="table table-bordered table-hover display" style="width:100%">
                <thead>
                  <tr>
                   <th scope="col">#ID</th>
                   <th scope="col">Vehicle Name</th>
                   <th scope="col">Vehicle No</th>
                   <th scope="col">Vehicle Type</th>
                   <th scope="col">Status</th>
                 </tr>
               </thead>
               <tbody>
                 <?php if(!empty($velhicles)){
                  foreach($velhicles as $velhicle){
                    ?>
                  <tr>
                    <td>{{$velhicle->id}}</td>
                    <td>{{$velhicle->car_name}}</td>
                    <td>{{$velhicle->vehicle_no}}</td>
                    <td>
                      <?php 
                      if($velhicle->type == 2){
                        echo "Two Wheeler";
                      }
                      if($velhicle->type == 4){
                        echo "Four Wheeler";
                      }
                      ?>
                        


                    </td>

                    <td>
                      <?php 
                      $sta = '';
                      $sta1 ='';
                      if($velhicle->status == 1){
                        $sta1 = 'selected';
                      }else{
                        $sta = 'selected';
                      }
                      ?>
                      <select id='change_vehicle_status{{$velhicle->id}}' onchange='change_vehicle_status({{$velhicle->id}})'>
                        <option value='1' {{$sta1}}>Active</option>
                        <option value='0' {{$sta}}>InActive</option>
                      </select>



                    </td>
                  </tr>





                <?php }}?>
              </tbody>
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










  <div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
      <div class="card mb-3">
        <div class="card-header">
          <h3>Daily Helps</h3>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table id="dataTable2" class="table table-bordered table-hover display" style="width:100%">
              <thead>
                <tr>
                 <th scope="col">#ID</th>
                 <th scope="col">Service Name</th>
                 <th scope="col">User Name</th>
                 <th scope="col">User Image</th>

                 <th scope="col">Phone</th>
                 <th scope="col">Status</th>
               </tr>
             </thead>
             <tbody>
               <?php if(!empty($daily_helps)){
                foreach($daily_helps as $user){
                 $service = \App\Services::where('id',$user->service_id)->first();
                 $service_user = \App\ServiceUser::where('id',$user->service_user_id)->first();
                 ?>
                 <tr>
                  <td>{{$user->id}}</td>
                  <td>{{$service->name ?? ''}}</td>
                  <td>{{$service_user->name ??'' }}</td>

                  <td>

                    <?php 
                    $image = isset($service_user->image) ? $service_user->image :'';
                    $storage = Storage::disk('public');
                    $path = 'service_user/';
                    if(!empty($image)){
                      if($storage->exists($path.$image)){?>
                        <a href="{{url('/storage/app/public/'.$path.'/'.$image)}}" target='_blank'><img src="{{url('/storage/app/public/'.$path.'/'.$image)}}" style='width:70px;'></a>";

                      <?php }}

                      ?>


                    </td>




                    <td><a href="tel:{{$service_user->phone ??'' }}">{{$service_user->phone ??'' }}</a></td>

                    <td>
                      <?php 
                      $sta = '';
                      $sta1 ='';
                      if($user->status == 1){
                        $sta1 = 'selected';
                      }else{
                        $sta = 'selected';
                      }
                      ?>
                      <select id='change_daily_help_status{{$user->id}}' onchange='change_daily_help_status({{$user->id}})'>
                        <option value='1' {{$sta1}}>Active</option>
                        <option value='0' {{$sta}}>InActive</option>
                      </select>
                    </td>
                  </tr>





                <?php }}?>
              </tbody>
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


<script type="text/javascript">
  $(document).ready(function(){
    $('#dataTable1').DataTable();
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#dataTable2').DataTable();
  });
</script>



<script>


  function change_flatowner_status(user_id){
    var status = $('#change_flatowner_status'+user_id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.flatowners.change_flatowner_status') }}",
      type: "POST",
      data: {user_id:user_id, status:status},
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




  function change_vehicle_status(velhicle_id){
    var status = $('#change_vehicle_status'+velhicle_id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.flatowners.change_vehicle_status') }}",
      type: "POST",
      data: {velhicle_id:velhicle_id, status:status},
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


  function change_daily_help_status(help_id){
    var status = $('#change_daily_help_status'+help_id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.flatowners.change_daily_help_status') }}",
      type: "POST",
      data: {help_id:help_id, status:status},
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


