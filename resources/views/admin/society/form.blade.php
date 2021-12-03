@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$society_id = (isset($society->id))?$society->id:'';
$name = (isset($society->name))?$society->name:'';
$description = (isset($society->description))?$society->description:'';
$location = (isset($society->location))?$society->location:'';
$state_id = (isset($society->state_id))?$society->state_id:'';
$city_id = (isset($society->city_id))?$society->city_id:'';




$status = (isset($society->status))?$society->status:'';


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

                            <input type="hidden" name="id" value="{{$society_id}}">


                            <div class="form-group">
                                <label for="userName">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" />

                                @include('snippets.errors_first', ['param' => 'name'])
                            </div>


                            <div class="form-group">
                                <label for="userName">Location<span class="text-danger">*</span></label>
                                <input type="text" name="location" value="{{ old('location', $location) }}" id="location" class="form-control"  maxlength="255" />

                                @include('snippets.errors_first', ['param' => 'location'])
                            </div>




                            <div class="form-group">
                                <label for="userName">State<span class="text-danger">*</span></label>
                                <select name="state_id" class="form-control" id="state_id">
                                    <option value="" selected disabled>Select State</option>
                                    <?php if(!empty($states)){
                                        foreach($states as $state){
                                        ?>

                                        <option value="{{$state->id}}" <?php if($state->id == $state_id) echo 'selected'?>>{{$state->name}}</option>
                                    <?php }}?>
                                </select>

                                @include('snippets.errors_first', ['param' => 'state_id'])
                            </div>


                            <div class="form-group">
                                <label for="userName">City<span class="text-danger">*</span></label>
                                 <select name="city_id" class="form-control" id="city_id">
                                 <option value="" selected disabled>Select City</option>
                                    <?php if(!empty($cities)){
                                        foreach($cities as $city){
                                        ?>
                                        <option value="{{$city->id}}" <?php if($city->id == $city_id) echo 'selected'?>>{{$city->name}}</option>
                                    <?php }}?>
                                </select>

                                @include('snippets.errors_first', ['param' => 'name'])
                            </div>




                            <div class="form-group">
                                <label for="userName">Description<span class="text-danger">*</span></label>
                               
                                <textarea id="description" name="description">{{old('description',$description)}}</textarea>
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


@include('sadmin.common.footer')
<script>
    CKEDITOR.replace( 'description' );
</script>

  <script type="text/javascript">
       $('#state_id').on('change', function()
       {

        var _token = '{{ csrf_token() }}';
        var state_id = $('#state_id').val();
        $.ajax({
          url: "{{ route('get_city') }}",
          type: "POST",
          data: {state_id:state_id},
          dataType:"HTML",
          headers:{'X-CSRF-TOKEN': _token},
          cache: false,
          success: function(resp){
           $('#city_id').html(resp);
         }
       });
      });
    </script>