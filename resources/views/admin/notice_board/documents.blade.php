@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$role_id = Auth::guard('admin')->user()->role_id;
$admin_society_id = Auth::guard('admin')->user()->society_id;

$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'notice_board/';

?>

<div class="content-page">

  <!-- Start content -->
  <div class="content">

    <div class="container-fluid">

      <div class="row">
        <div class="col-xl-12">
          <div class="breadcrumb-holder">
            <h1 class="main-title float-left">Notice Board Documents</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Notice Board Documents</li>
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
            <h3>Upload Documents</h3>

          </div>

          
          <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="notice_id" value="{{$notice->id}}">
            <div class="card-body d-flex">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
            
                <input type="file" name="file[]" multiple class="form-control">
              </div>

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
            <h3>Notice Board Documents</h3>
            <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
            <a href="{{ url($back_url)}}" class="btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <table id="dataTable" class="table table-bordered table-hover display" style="width:100%">
                <thead>
                  <tr>
                   <th scope="col">#ID</th>
                   <th scope="col">File</th>
                   <th scope="col">Action</th>
                 </tr>
               </thead>
               <tbody>
                <?php if(!empty($documents)){
                  foreach($documents as $doc){
                     $image_name = $doc->file;
                    if($storage->exists($path.$image_name)){
                    ?>
                    <tr>
                     <td>{{$doc->id}}</td>


                     <td>
                       <?php if($doc->type == 'jpg' || $doc->type == 'jpeg' || $doc->type == 'png'){?>
                        <a href="{{ url('public/storage/'.$path.$image_name) }}" target="_blank"><img src="{{ url('public/storage/notice_board/demojpg.png') }}" height="50" width="80"></a>

                       <?php }else if($doc->type == 'pdf'){?>
                        <a href="{{ url('public/storage/'.$path.$image_name) }}" target="_blank"><img src="{{ url('public/storage/notice_board/pdf.png') }}"  height="50" width="80"></a>

                       <?php }else if($doc->type == 'xls' || $doc->type == 'csv' || $doc->type == 'xlsx'){?>
                        <a href="{{ url('public/storage/'.$path.$image_name) }}" target="_blank"><img src="{{ url('public/storage/notice_board/xls.jpg') }}"  height="50" width="80"></a>

                       <?php }?>

                     </td>




               
                     <td><a title="Edit" onclick="return confirm('Are You Want To Delete?')" href="{{route($routeName.'.notice_board.delete_document',$doc->id.'?back_url='.$BackUrl)}}"><i class="fa fa-edit">Delete</i></a></td>
                   </tr>
                 <?php }}}?>
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
