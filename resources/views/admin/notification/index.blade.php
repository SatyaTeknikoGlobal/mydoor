@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
?>

<div class="content-page">

  <!-- Start content -->
  <div class="content">

    <div class="container-fluid">

      <div class="row">
        <div class="col-xl-12">
          <div class="breadcrumb-holder">
            <h1 class="main-title float-left">Send Notification</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Send Notification</li>
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
            <h3>Send Notification To Society Users</h3>
          </div>

          <form method="POST" action="" enctype='multipart/form-data'>
            {{csrf_field()}}
            <div class="card-body d-flex">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
               <select class="form-control select2" name="user_id1">
                <option value="" selected disabled>Select Society</option>

                <?php if(!empty($societies)){
                  foreach($societies as $society){
                    ?>
                    <option value="{{$society->id}}">{{$society->name}}</option>
                  <?php }}?>

                </select>
              </div>

              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                <input type="text" name="title1" value="" placeholder="Enter Title" class="form-control">
              </div>
            </div>


            <div class="card-body d-flex">
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
               <textarea class="form-control" name="text1" placeholder="Enter Description"></textarea>
             </div>



             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
              <input type="file" name="image1" class="form-control" value="" placeholder="Enter Amount">
            </div>

          </div>

          <div class="card-body d-flex">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-2">
             <button class="btn btn-success">Submit</button>

           </div>
         </div>

       </div>
     </form>

   </div>

 </div>




 <div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <div class="card mb-3">
     <div class="card-header">
      <h3>Send Notification To Block Wise</h3>
    </div>

    <form method="POST" action="" enctype='multipart/form-data'>
      {{csrf_field()}}
      <div class="card-body d-flex">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
         <select class="form-control select2" name="user_id1">
          <option value="0">All</option>
        </select>
      </div>

      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
        <input type="text" name="title1" value="My Door" placeholder="Enter Title" class="form-control">
      </div>
    </div>


    <div class="card-body d-flex">
     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
       <textarea class="form-control" name="text1" placeholder="Enter Description"></textarea>
     </div>



     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
      <input type="file" name="image1" class="form-control" value="" placeholder="Enter Amount">
    </div>

  </div>

  <div class="card-body d-flex">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-2">
     <button class="btn btn-success">Submit</button>

   </div>
 </div>

</div>
</form>

</div>

</div>







<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <div class="card mb-3">
     <div class="card-header">
      <h3>Send Notification To Specific User</h3>
    </div>

    <form method="POST" action="" enctype='multipart/form-data'>
      {{csrf_field()}}
      <div class="card-body d-flex">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
         <select class="form-control select2" name="user_id1">
          <option value="0">All</option>
        </select>
      </div>

      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
        <input type="text" name="title1" value="My Door" placeholder="Enter Title" class="form-control">
      </div>
    </div>


    <div class="card-body d-flex">
     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
       <textarea class="form-control" name="text1" placeholder="Enter Description"></textarea>
     </div>



     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
      <input type="file" name="image1" class="form-control" value="" placeholder="Enter Amount">
    </div>

  </div>

  <div class="card-body d-flex">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-2">
     <button class="btn btn-success">Submit</button>

   </div>
 </div>

</div>
</form>

</div>

</div>




<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <div class="card mb-3">
     <div class="card-header">
      <h3>Send Notification To Society Guards</h3>
    </div>

    <form method="POST" action="" enctype='multipart/form-data'>
      {{csrf_field()}}
      <div class="card-body d-flex">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
         <select class="form-control select2" name="user_id1">
          <option value="0">All</option>
        </select>
      </div>

      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
        <input type="text" name="title1" value="My Door" placeholder="Enter Title" class="form-control">
      </div>
    </div>


    <div class="card-body d-flex">
     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
       <textarea class="form-control" name="text1" placeholder="Enter Description"></textarea>
     </div>



     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
      <input type="file" name="image1" class="form-control" value="" placeholder="Enter Amount">
    </div>

  </div>

  <div class="card-body d-flex">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-2">
     <button class="btn btn-success">Submit</button>

   </div>
 </div>

</div>
</form>

</div>

</div>







</div>
<!-- END container-fluid -->

</div>
<!-- END content -->

</div>
<!-- END content-page -->



@include('admin.common.footer')

