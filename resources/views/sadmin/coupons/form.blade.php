@include('merchant.common.header')
<?php
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
if(empty($back_url)){
  $back_url = 'admin/states';
}
$MERCHANT_ROUTE_NAME = CustomHelper::getMerchantRouteName();

$name = (isset($coupons->name))?$coupons->name:'';
$post_id = (isset($coupons->id))?$coupons->id:'';

$merchant_id = (isset($coupons->merchant_id))?$coupons->merchant_id:'';
$description = (isset($coupons->description))?$coupons->description:'';
$image = (isset($coupons->image))?$coupons->image:'';
$state_id = (isset($coupons->state_id))?$coupons->state_id:'';
$city_id = (isset($coupons->city_id))?$coupons->city_id:'';
$category_id = (isset($coupons->category_id))?$coupons->category_id:'';
$expary_date = (isset($coupons->expary_date))?$coupons->expary_date:'';
$coupan_code = (isset($coupons->coupan_code))?$coupons->coupan_code:'';
$expiry_time = (isset($coupons->expiry_time))?$coupons->expiry_time:'';
$status = (isset($coupons->status))?$coupons->status:1;
$storage = Storage::disk('public');
$path = 'coupons/';
$routeName = CustomHelper::getMerchantRouteName();

$sub_id = (isset($coupons->subscription_id))?$coupons->subscription_id:'';

?>

<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Couponss</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Couponss</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{$page_heading}}</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <a href="{{ route($MERCHANT_ROUTE_NAME.'.coupon.index')}}" class="btn btn-sm btn-neutral">Back</a>
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

          <label class="form-control-label" for="">Coupon Code</label><br>
          <div class="input-group">
           
            <input type="text" class="form-control" placeholder="Enter Coupon Code" name="coupan_code" id="coupan_code" value="{{ old('coupan_code', $coupan_code) }}" readonly>
            <?php if(empty($coupan_code)){?>
            <div class="input-group-append">
              <button class="btn btn-secondary" type="button" onclick="coupan_code_generate()">
               <i class="fas fa-redo"></i>

              </button>
            </div>

          <?php }?>
          </div>

          @include('snippets.errors_first', ['param' => 'coupan_code'])

        </div>

        
 <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label" for="">Name</label>
              <input type="text" class="form-control" id="" value="{{ old('name', $name) }}" name="name" placeholder="Coupon Name">
              @include('snippets.errors_first', ['param' => 'name'])

            </div>
          </div>



      </div>

      <div class="row">
         <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label" for="">Expiry Date</label>
                      <input type="date" class="form-control datepicker" id="" value="{{ old('expary_date', $expary_date) }}" name="expary_date">
                      @include('snippets.errors_first', ['param' => 'expary_date'])

                    </div>
                  </div>

           <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label" for="">Expiry Time</label>
                      <input type="time" class="form-control" id="" value="{{ old('expiry_time', $expiry_time) }}" name="expiry_time">
                      @include('snippets.errors_first', ['param' => 'expiry_time'])

                    </div>
                  </div>



      </div>








        <div class="row">
         

          <input type="hidden" name="merchant_id" value="{{Auth::guard('merchant')->user()->id}}">
        
          </div>




          <div class="row">



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
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label" for="">Description</label>
                <textarea name="description" placeholder="Description" class="form-control">{{ old('description', $description) }}</textarea>


                @include('snippets.errors_first', ['param' => 'description'])

              </div>
            </div>
           

          </div>




          <div class="row">

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




          function coupan_code_generate() {
              var _token = '{{ csrf_token() }}';
            $.ajax({
              url: "{{ route($MERCHANT_ROUTE_NAME.'.coupon.coupon_code_generate') }}",
              type: "POST",
              //data: {category_id:category_id},
              dataType:"JSON",
              headers:{'X-CSRF-TOKEN': _token},
              cache: false,
              success: function(resp){

                $("#coupan_code").val(resp.code);

              }
            });
          }
</script>
