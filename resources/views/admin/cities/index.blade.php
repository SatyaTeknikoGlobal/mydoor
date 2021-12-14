@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$old_name = (request()->has('name'))?request()->name:'';
$roleId = Auth::guard('admin')->user()->role_id; 


?>

<div class="content-page">

  <!-- Start content -->
  <div class="content">

    <div class="container-fluid">

      <div class="row">
        <div class="col-xl-12">
          <div class="breadcrumb-holder">
            <h1 class="main-title float-left">City List</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">City List</li>
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
              <h3>States List</h3>
                @if(CustomHelper::isAllowedSection('cities' , $roleId , $type='add'))
              <span class="pull-right">
                  <a href="{{ url($routeName.'/cities/save') }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus" aria-hidden="true"></i> Add New City</a>
              </span>
              @endif
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="citydataTable" class="table table-bordered table-hover display" style="width:100%">
                  <thead>
                    <tr>
                    <th class="">Sl No</th>

                     <th class="">Name</th>
                     <th class="">StateName </th>
                     <th class="">Status</th>
                     <th class="">Action</th>
                   </tr>
                 </thead>
               <tbody>

                   <?php if(!empty($cities) && $cities->count() > 0){
                    $i = 1;
                    foreach ($cities as $city){
                     $cityState = (isset($city->cityState))?$city->cityState:'';
                     $stateName = (isset($cityState->name))?$cityState->name:'';


                     ?>
                     <tr>
                      <td>{{$i++}}</td>
                      <td>{{$city->name}}</td>
                      <td>{{$stateName}}</td>
                      <td><?php  echo ($city->status==1)?'Active':'Inactive';  ?></td>
                      <td>
                        <a href="{{route($routeName.'.cities.save', ['id'=>$city->id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>
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
        $('#citydataTable').DataTable();
    });
</script>