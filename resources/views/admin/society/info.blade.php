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
            <h1 class="main-title float-left">Society Info - {{$society->name ?? ''}}</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Society Info</li>
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
            <h3>Society Emergency Number Update</h3>

          </div>

          
          <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="society_id" value="{{$society->id}}">
            <div class="card-body d-flex">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                <input type="text" name="title" placeholder="Enter Title" class="form-control">
              </div>
                 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                <input type="text" name="number" placeholder="Enter Number" class="form-control">
              </div>
            </div>



               <div class="card-body d-flex">
            
              
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                <button class="btn btn-primary" type="submit">Submit</button>
              </div>
            </div>




          </form>


        </div>
        <!-- end card-->
      </div>
    </div>




      <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="card mb-3">
            <div class="card-header">
              <h3>Society Information</h3>
               <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
            <a href="{{ url($back_url)}}" class="btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover display" style="width:100%">
                  <thead>
                    <tr>
                     <th scope="col">#ID</th>
                     <th scope="col">Title</th>
                     <th scope="col">Number</th>
                     <th scope="col">Action</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php if(!empty($info)){
                    foreach($info as $in){
                    ?>
                    <tr>
                      <td>{{$in->id}}</td>
                      <td>{{$in->title}}</td>
                      <td>{{$in->number}}</td>
                      <td><a href="{{route($routeName.'.society.delete_info',$in->id.'?back_url='.$BackUrl)}}" onclick="return confirm('Are You Want To Delete??')"><i class="fa fa-edit">Delete</i></a></td>
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

