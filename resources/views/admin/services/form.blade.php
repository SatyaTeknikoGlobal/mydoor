@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$services_id = (isset($services->id))?$services->id:'';
$name = (isset($services->name))?$services->name:'';
$society_id = (isset($services->society_id))?$services->society_id:'';
$status = (isset($services->status))?$services->status:'';
$image = (isset($services->image))?$services->image:'';


$routeName = CustomHelper::getSadminRouteName();
$storage = Storage::disk('public');
$path = 'services/';
//prd($storage);
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

                            <input type="hidden" name="id" value="{{$services_id}}">


                            <div class="form-group">
                                <label for="userName">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" />

                                @include('snippets.errors_first', ['param' => 'name'])
                            </div>




                            <div class="form-group">
                                <label for="userName">Image<span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control" />

                                @include('snippets.errors_first', ['param' => 'name'])
                            </div>

                            <?php
                            if(!empty($image)){
                                if($storage->exists($path.$image)){
                                    ?>
                                    <div class=" image_box" style="display: inline-block">
                                        <a href="{{ url('storage/app/public/'.$path.$image) }}" target="_blank">
                                            <img src="{{ url('storage/app/public/'.$path.'thumb/'.$image) }}" style="width:70px;">
                                        </a>
                                    </div>
                                    <?php
                                }
                            }
                            ?>



                            
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


@include('sadmin.common.footer')
<script>
    CKEDITOR.replace( 'description' );
</script>