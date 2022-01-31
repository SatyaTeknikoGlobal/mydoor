@include('admin.common.header')
<?php



$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
if(empty($back_url)){
  $back_url = 'admin/cities';
}

$name = (isset($city->name))?$city->name:'';
    //$country_id = (isset($city->cityState->country_id))?$city->cityState->country_id:99;
$state_id=(isset($city->state_id))?$city->state_id:'';
$status = (isset($city->status))?$city->status:1;
$image = (isset($city->img))?$city->img:'';


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

              <input type="hidden" name="id" value="{{$state_id}}">



              <div class="form-group">
                <label for="userName">State Name<span class="text-danger">*</span></label>
                <select class="select2 form-control form-select" name="state" id="state">
                 <option value="" selected disabled>Select State Name</option>
                 <?php 

                 if(!empty($state)){
                  foreach($state as $s) 
                    {?>
                      <option <?php if($state_id==$s->id) { echo 'selected';    } ?> value="{{$s->id}}">{{$s->name}}</option>
                    <?php  } }  ?>
                  </select>
                  @include('snippets.errors_first', ['param' => 'name'])
                </div>



                <div class="form-group">
                <label for="exampleInputEmail1" class="form-label">City Name</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter City Name" name="name" value="{{ old('name', $name) }}">
                @include('snippets.errors_first', ['param' => 'name'])
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