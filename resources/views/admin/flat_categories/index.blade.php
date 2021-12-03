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
            <h1 class="main-title float-left">Flat Categories</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Flat Categories</li>
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
              <h3>Flat Categories List</h3>
              @if(CustomHelper::isAllowedSection('flat_categories' , $roleId , $type='add'))
              <span class="pull-right">
                  <a href="{{ route($routeName.'.flat_categories.add', ['back_url'=>$BackUrl]) }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus" aria-hidden="true"></i> Add New Flat</a>
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
                     <th scope="col">Name</th>
                     <th scope="col">Sq. Feet</th>
                     <th scope="col">Maintainance Cost</th>
                     <th scope="col">Status</th>
                     <th scope="col">Date Created</th>
                     <th scope="col">Action</th>
                   </tr>
                 </thead>
                 <tbody>
                  <?php if(!empty($categories)){
                    foreach($categories as $cat){
                    ?>

                   <tr>
                     <td>{{$cat->id}}</td>
                     <td>
                      <?php  
                      $society = \App\Society::where('id',$cat->society_id)->first();
                      echo $society->name ?? '';
                      ?>
                     </td>
                     <td>{{$cat->name}}</td>
                     <td>{{$cat->square_feet}}</td>
                     <td>{{$cat->maintainance_fee}}</td>
                     <td>

                      <?php 
                      if($cat->status == 1){
                        echo "Active";
                      }else{
                        echo "InActive";
                      }
                      ?>


                     </td>
                     <td>{{$cat->created_at}}</td>
                     <td>
                       <a title="Edit" href="{{route($routeName.'.flat_categories.edit',$cat->id.'?back_url='.$BackUrl)}}"><i class="fa fa-edit">Edit</i></a>
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

