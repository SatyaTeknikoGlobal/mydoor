@include('merchant.common.header')
<?php
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
if(empty($back_url)){
  $back_url = 'merchant/states';
}
$ADMIN_ROUTE_NAME = CustomHelper::getMerchantRouteName();

$name = (isset($posts->name))?$posts->name:'';
$post_id = (isset($posts->id))?$posts->id:'';

$merchant_id = (isset($posts->merchant_id))?$posts->merchant_id:'';
$description = (isset($posts->description))?$posts->description:'';

$sub_id = (isset($posts->subscription_id))?$posts->subscription_id:'';



$image = (isset($posts->image))?$posts->image:'';
$state_id = (isset($posts->state_id))?$posts->state_id:'';
$city_id = (isset($posts->city_id))?$posts->city_id:'';
$category_id = (isset($posts->category_id))?$posts->category_id:'';

$status = (isset($posts->status))?$posts->status:1;
$storage = Storage::disk('public');
$path = 'posts/';
$routeName = CustomHelper::getMerchantRouteName();


?>

<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Posts</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Posts</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{$page_heading}}</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <a href="{{ route($ADMIN_ROUTE_NAME.'.posts.index')}}" class="btn btn-sm btn-neutral">Back</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="card mb-4">
    <!-- Card header -->
    <div class="card-header">
      <h3 class="mb-0">{{$page_heading}}</h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
      <!-- Form groups used in grid -->
      <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
        {{ csrf_field() }}


        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label" for="">Name</label>
              <input type="text" class="form-control" id="" value="{{ old('name', $name) }}" name="name" placeholder="Post Name">
              @include('snippets.errors_first', ['param' => 'name'])

            </div>
          </div>


          <div class="col-md-6 ">
            <div class="form-group">
              <label class="form-control-label" for="example2cols2Input">Choose Subscription</label>
              <select class="form-control" name="subscription_id" id="subscription_id">
                <option value="" selected disabled>Choose Subscription</option>

                <?php if(!empty($subcriptions)){
                  foreach($subcriptions as $subs){
                      $sub_detail = \App\Subscription::where('id',$subs->subscription_id)->first();
                  ?>

                  <option value="{{$subs->id}}" <?php if($sub_id == $subs->id) echo "selected"?>>{{$sub_detail->name}}</option>
                <?php }}?>
              </select>
              <p id="sub_details">
                
              </p>
              @include('snippets.errors_first', ['param' => 'subscription_id'])

              </div>
            </div>

          </div>




          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label" for="">Description</label>
                <textarea name="description" placeholder="Description" class="form-control">{{ old('description', $description) }}</textarea>


                @include('snippets.errors_first', ['param' => 'description'])

              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label" for="example2cols2Input">Image</label>

                <input type="file" name="image" value="" class="form-control">
                <?php
              if(!empty($image)){
                if($storage->exists($path.$image)){
                  ?>
                  <div class=" image_box" style="display: inline-block">
                    <a href="{{ url('public/storage/'.$path.$image) }}" target="_blank">
                      <img src="{{ url('public/storage/'.$path.'thumb/'.$image) }}" style="width:70px;">
                    </a>
                    <a href="javascript:void(0)" data-id="{{ $post_id }}" class="del_banner">Delete</a>
                  </div>
                <?php }}?>
              </div>
            </div>

          </div>


           <div class="form-row d-none">
            <div class="col-md-6 col-12">
              <div class="form-group position-relative">
                <label for="form-action-3">Choose State</label>
                <select class=" form-control" placeholder="Choose State" name="state_id" id="state_id" >
                  <option value="" selected disabled>Select your State</option>
                  <?php if(!empty($states) && count($states) > 0){
                    foreach($states as $state){?>
                      <option value="{{$state->id}}" <?php if($state->id == $state_id) echo "selected"?>>{{$state->name}}</option>
                    <?php }}?>
                  </select>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group position-relative">
                  <label for="form-action-4">City</label>
                  <select class=" form-control" placeholder="Choose City" name="city_id" id="city_id" >
                  <option value="" selected disabled>Select your City</option>
                      <?php if(!empty($cities) && count($cities) > 0){
                    foreach($cities as $city){?>
                      <option value="{{$city->id}}" <?php if($city->id == $city_id) echo "selected"?>>{{$city->name}}</option>
                    <?php }}?>
                  </select>

                </div>
              </div>
            </div>




          <div class="row">
           <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label" for="example2cols2Input">Status</label>

              <select class="form-control" name="status">
                <option value="1" <?php if($status==1) { echo 'checked';  } ?>>Active</option>
                <option value="0" <?php if($status==0) { echo 'checked';  } ?>>InActive</option>
              </select>
            </div>
          </div>
          
        </div>

        <button class="btn btn-primary" type="submit">Submit form</button>

      </form>
    </div>
  </div>
</div>









@include('merchant.common.footer')
<script type="text/javascript">
  $("#state_id").change(function(){
  var state_id = $(this).val();
   var _token = '{{ csrf_token() }}';
      $.ajax({
        url: "{{ url('/get_city') }}",
        type: "POST",
        data: {state_id:state_id},
        dataType:"HTML",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        success: function(resp){
         
            $("#city_id").html(resp);

        }
      });
});
</script>


<script type="text/javascript">
  $("#subscription_id").change(function(){
  var subscription_id = $(this).val();
   var _token = '{{ csrf_token() }}';
      $.ajax({
        url: "{{ url('/merchant/get_sub_details') }}",
        type: "POST",
        data: {subscription_id:subscription_id},
        dataType:"HTML",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        success: function(resp){
         
            $("#sub_details").html(resp);

        }
      });
});
</script>


