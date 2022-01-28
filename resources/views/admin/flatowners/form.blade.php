@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$flatowners_id = (isset($flatowners->id))?$flatowners->id:'';
$society_id = (isset($flatowners->society_id))?$flatowners->society_id:'';
$block_id = (isset($flatowners->block_id))?$flatowners->block_id:'';
$flat_no = (isset($flatowners->flat_no))?$flatowners->flat_no:'';
$status = (isset($flatowners->status))?$flatowners->status:'';
$name = (isset($flatowners->name))?$flatowners->name:'';
$phone = (isset($flatowners->phone))?$flatowners->phone:'';
$email = (isset($flatowners->email))?$flatowners->email:'';
$location = (isset($flatowners->location))?$flatowners->location:'';
$gender = (isset($flatowners->gender))?$flatowners->gender:'';
$state_id = (isset($flatowners->state_id))?$flatowners->state_id:'';
$city_id = (isset($flatowners->city_id))?$flatowners->city_id:'';



$routeName = CustomHelper::getAdminRouteName();
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

                            <input type="hidden" name="id" value="{{$flatowners_id}}">

                            <div class="form-group">
                                <label for="userName">Society Name<span class="text-danger">*</span></label>
                                <select name="society_id" id="society_id" class="form-control select2">
                                 <option value="" selected disabled>Select Society</option>
                                 <?php if(!empty($societies)){
                                    foreach($societies as $soci){
                                        ?>
                                        <option value="{{$soci->id}}" <?php if($soci->id == $society_id) echo "selected"?>>{{$soci->name}}</option>
                                    <?php }}?>

                                </select>

                                @include('snippets.errors_first', ['param' => 'society_id'])
                            </div>


                            <div class="form-group">
                                <label for="userName">Block Name<span class="text-danger">*</span></label>
                                <select name="block_id" id="block_id" class="form-control select2">
                                 <option value="" selected disabled>Select Block</option>
                                 <?php if(!empty($blocks)){
                                    foreach($blocks as $block){
                                        ?>
                                        <option value="{{$block->id}}" <?php if($block->id == $block_id) echo "selected"?>>{{$block->name}}</option>
                                    <?php }}?>

                                </select>

                                @include('snippets.errors_first', ['param' => 'block_id'])
                            </div>




                            <div class="form-group">
                                <label for="userName">Flat No<span class="text-danger">*</span></label>
                                <select name="flat_no" id="flat_no" class="form-control select2">
                                 <option value="" selected disabled>Select Flat No</option>
                                 <?php if(!empty($flats)){
                                    foreach($flats as $flat){
                                        ?>
                                        <option value="{{$flat->id}}" <?php if($flat->id == $flat_no) echo "selected"?>>{{$flat->flat_no}}</option>
                                    <?php }}?>

                                </select>

                                @include('snippets.errors_first', ['param' => 'flat_no'])
                            </div>






                            <div class="form-group">
                                <label for="userName">Owner Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" placeholder="Enter Name" />

                                @include('snippets.errors_first', ['param' => 'name'])
                            </div>


                            <div class="form-group">
                                <label for="userName">Phone<span class="text-danger">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', $phone) }}" id="phone" class="form-control"  maxlength="255" placeholder="Enter Phone"  />

                                @include('snippets.errors_first', ['param' => 'Phone'])
                            </div>

                            <div class="form-group">
                                <label for="userName">Email<span class="text-danger">*</span></label>
                                <input type="text" name="email" value="{{ old('email', $email) }}" id="email" class="form-control"  maxlength="255" placeholder="Enter Email"  />

                                @include('snippets.errors_first', ['param' => 'email'])
                            </div>

                            <div class="form-group">
                                <label for="userName">Location<span class="text-danger">*</span></label>
                                <input type="text" name="location" value="{{ old('location', $location) }}" id="location" class="form-control"  maxlength="255" placeholder="Enter location"  />

                                @include('snippets.errors_first', ['param' => 'location'])
                            </div>

                            <div class="form-group">
                             <label for="userName">State<span class="text-danger">*</span></label>
                             <select name="state_id" class="form-control" id="state_id">
                                <option value="" selected disabled>Select State</option>
                                <?php if(!empty($states)){
                                  foreach($states as $state){?>
                                    <option value="{{$state->id}}" <?php if($state->id == $state_id) echo "selected"?>>{{$state->name}}</option>
                                <?php }}?>
                            </select>
                            @include('snippets.errors_first', ['param' => 'state_id'])
                        </div>


                        <div class="form-group">
                         <label for="userName">City<span class="text-danger">*</span></label>

                         <select name="city_id" class="form-control" id="city_id">
                          <option value="" selected disabled>Select City</option>
                          <?php if(!empty($cities)){
                              foreach($cities as $city){?>
                                <option value="{{$city->id}}" <?php if($city->id == $city_id) echo "selected"?>>{{$city->name}}</option>
                            <?php }}?>
                        </select>
                        @include('snippets.errors_first', ['param' => 'city_id'])
                    </div>



                    <div class="form-group">
                        <label>Gender</label>
                        <div>
                           Male: <input type="radio" name="gender" value="male" <?php echo ($gender == 'male')?'checked':''; ?> checked>
                           &nbsp;
                           FeMale: <input type="radio" name="gender" value="female" <?php echo ( $gender == 'female')?'checked':''; ?> >

                           @include('snippets.errors_first', ['param' => 'gender'])
                       </div>
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
    $('#society_id').change(function() {
        var society_id = $(this).val();
        var _token = '{{ csrf_token() }}';

        $.ajax({
            url: "{{ route($routeName.'.flats.get_blocks_from_society') }}",
            type: "POST",
            data: {society_id:society_id},
            dataType:"HTML",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            success: function(resp){
                $('#block_id').html(resp);
            }
        });

    });


    $('#block_id').change(function() {
        var block_id = $(this).val();
        var _token = '{{ csrf_token() }}';

        $.ajax({
            url: "{{ route($routeName.'.flatowners.get_flats_from_block') }}",
            type: "POST",
            data: {block_id:block_id},
            dataType:"HTML",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            success: function(resp){
                $('#flat_no').html(resp);
            }
        });

    });


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