@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$role_id = Auth::guard('admin')->user()->role_id;
$admin_society_id = Auth::guard('admin')->user()->society_id;

$banner_id = (isset($banners->id))?$banners->id:'';
$title = (isset($banners->title))?$banners->title:'';
$society_id = (isset($banners->society_id))?$banners->society_id:'';
$image = (isset($banners->image))?$banners->image:'';
$status = (isset($blockes->status))?$blockes->status:'';


$routeName = CustomHelper::getSadminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

?>


<div class="content-page">

    <!-- Start content -->
    <div class="content">

        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="breadcrumb-holder">
                        <h1 class="main-title float-left">{{ $page_heading }}</h1>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item active">{{ $page_heading }}</li>
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
                            <h3><i class="far fa-hand-pointer"></i>{{ $page_heading }}</h3>

                            <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                            <a href="{{ url($back_url)}}" class="btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?>
                        </div>

                        <div class="card-body">

                         <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="{{$banner_id}}">



                                <?php if($role_id == 0){?>
                             <div class="form-group">
                                <label for="userName">Society Name<span class="text-danger">*</span></label>
                               <select name="society_id" class="form-control select2">
                                   <option value="" selected disabled>Select Society</option>
                                   <?php if(!empty($societies)){
                                    foreach($societies as $soci){
                                    ?>
                                    <option value="{{$soci->id}}" <?php if($soci->id == $society_id) echo "selected"?>>{{$soci->name}}</option>
                                <?php }}?>

                               </select>

                                @include('snippets.errors_first', ['param' => 'society_id'])
                            </div>
                        <?php }else{?>
                            <input type="hidden" name="society_id" value="{{$admin_society_id}}">
                            <?php }?>




                            <div class="form-group">
                                <label for="userName">Title<span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ old('title', $title) }}" id="name" class="form-control"  maxlength="255" />

                                @include('snippets.errors_first', ['param' => 'title'])
                            </div>

                              <div class="form-group">
                                <label for="userName">Title<span class="text-danger">*</span></label>
                                <input type="file" name="image" value="{{ old('image', $image) }}" id="image" class="form-control"  maxlength="255" />

                                @include('snippets.errors_first', ['param' => 'image'])
                            </div>

                            
                       <div class="form-group">
                        <label>Status</label>
                        <div>
                         Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                         &nbsp;
                         Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                         @include('snippets.errors_first', ['param' => 'status'])
                     </div>
                 </div>



                 <div class="form-group text-right m-b-0">
                    <button class="btn btn-primary" type="submit">
                        Submit
                    </button>
                </div>

            </form>

        </div>
    </div><!-- end card-->
</div>



</div>

</div>
<!-- END container-fluid -->

</div>
<!-- END content -->

</div>
<!-- END content-page -->


@include('admin.common.footer')
<script>
    CKEDITOR.replace( 'description' );
</script>