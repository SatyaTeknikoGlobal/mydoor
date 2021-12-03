@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$servicedetails_id = (isset($servicedetails->id))?$servicedetails->id:'';
$name = (isset($servicedetails->title))?$servicedetails->title:'';
$society_id = (isset($servicedetails->society_id))?$servicedetails->society_id:'';
$status = (isset($servicedetails->status))?$servicedetails->status:'';
$image = (isset($servicedetails->image))?$servicedetails->image:'';
$service_id = (isset($servicedetails->service_id))?$servicedetails->service_id:'';
$price = (isset($servicedetails->price))?$servicedetails->price:'';
$description = (isset($servicedetails->description))?$servicedetails->description:'';


$routeName = CustomHelper::getSadminRouteName();
$storage = Storage::disk('public');
$path = 'servicedetails/';
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

                            <input type="hidden" name="id" value="{{$servicedetails_id}}">
                            <div class="form-group">
                                <label for="userName">Select Service Category<span class="text-danger">*</span></label>
                                <select name="service_id" class="form-control select2">
                                    <option value="" selected disabled>Select Service Category</option>
                                    <?php if(!empty($services)){
                                        foreach($services as $ser){
                                            ?>

                                            <option value="{{$ser->id}}" <?php if($ser->id == $service_id) echo 'selected'?>>{{$ser->name}}</option>
                                        <?php }}?>



                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="userName">Title<span class="text-danger">*</span></label>
                                    <input type="text" name="title" value="{{ old('name', $name) }}" id="name" class="form-control" placeholder="Enter Title"  maxlength="255" />

                                    @include('snippets.errors_first', ['param' => 'title'])
                                </div>


                                <div class="form-group">
                                    <label for="userName">Price<span class="text-danger">*</span></label>
                                    <input type="text" name="price" value="{{ old('price', $price) }}" id="price" class="form-control" placeholder="Enter Price"  maxlength="255" />

                                    @include('snippets.errors_first', ['param' => 'price'])
                                </div>



                                <div class="form-group">
                                    <label for="userName">Image<span class="text-danger">*</span></label>
                                    <input type="file" name="image" class="form-control" />

                                    @include('snippets.errors_first', ['param' => 'image'])
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
                                    <label for="userName">Description<span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" id="description">{{old('description',$description)}}</textarea>
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


@include('sadmin.common.footer')
<script>
    CKEDITOR.replace( 'description' );
</script>