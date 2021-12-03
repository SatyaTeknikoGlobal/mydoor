@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'vendors/';
?>



<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Merchant List For -- {{$sub_name}} Subscriptions</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Merchant</a></li>
              <li class="breadcrumb-item active" aria-current="page">Merchant List</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <a href="{{ route($ADMIN_ROUTE_NAME.'.subscription.index')}}" class="btn btn-sm btn-neutral">Back</a>
          <a href="#" class="btn btn-sm btn-neutral">Filters</a>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Page content -->
<div class="container-fluid mt--6">
  <!-- Table -->
  <div class="row">
    <div class="col">
      <div class="card">
        <!-- Card header -->
        <div class="card-header">
          <h3 class="mb-0">Merchant List</h3>

        </div>

        @include('snippets.errors')
        @include('snippets.flash')
        <div class="table-responsive py-4">
          <table class="table table-flush" id="datatable-basic">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Status</th>
                   
                  </tr>
                </thead>
                <tbody>
                <?php if(!empty($vendors) && count($vendors) > 0){
                  $i=1;
                  foreach($vendors as $ven){
                  ?>
                  <tr>
                    <td>{{$i++}}</td>
                    <td>{{$ven->business_name}}</td>
                    <td> <?php 
                     if(!empty($ven->image)){
                    if($storage->exists($path.$ven->image)){
                    ?>
                    <a href="{{ url('public/storage/'.$path.$ven->image) }}" target="_blank">
                      <img src="{{ url('public/storage/'.$path.'thumb/'.$ven->image) }}" style="width:70px;">
                      </a>
        
                  <?php }}?>

                    </td>
                    <td>
                      <?php 
                      if(!empty($states)){
                        foreach($states as $state){
                          if($state->id == $ven->state_id){
                            echo $state->name;
                          }
                        }
                      }
                      ?>

                    </td>


                    <td>
                    <?php 
                      if(!empty($cities)){
                        foreach($cities as $city){
                          if($city->id == $ven->city_id){
                            echo $city->name;
                          }
                        }
                      }
                      ?>
                    </td>


                    <td><?php if($ven->status == 'active'){?>
                      <div class="ng-star-inserted"><div class="badge badge-pill bg-light-success">Active</div></div>
                    <?php }else{?>
                    <div class="ng-star-inserted"><div class="badge badge-pill bg-light-danger"> InActive </div></div>
                    <?php }?>
                    </td>
                   

                  </tr>
              <?php }}?>
             
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
@include('admin.common.footer')
