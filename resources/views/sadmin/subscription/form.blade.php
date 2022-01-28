@include('admin.common.header')
<?php
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
if(empty($back_url)){
  $back_url = 'admin/states';
}
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$name = (isset($subscription->name))?$subscription->name:'';


$cat_id = (isset($subscription->cat_id))?$subscription->cat_id:'';

$description = (isset($subscription->description))?$subscription->description:'';

$amount = (isset($subscription->amount))?$subscription->amount:'';

$duration = (isset($subscription->duration))?$subscription->duration:'';


$subscription_id = (isset($subscription->id))?$subscription->id:'';

$status = (isset($subscription->status))?$subscription->status:1;

$storage = Storage::disk('public');
$path = 'subscription/';
$routeName = CustomHelper::getAdminRouteName();


?>

<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Subscription</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Subscription</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{$page_heading}}</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <a href="{{ route($ADMIN_ROUTE_NAME.'.subscription.index')}}" class="btn btn-sm btn-neutral">Back</a>
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


          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label" for="example2cols2Input">Choose Category</label>

              <select class="form-control" name="cat_id">
                <option selected value="" disabled>Select Category</option>
                <?php if(!empty($categories)){
                  foreach($categories as $cat){?>

                    <option value="{{$cat->id}}" <?php if($cat_id == $cat->id) echo "selected";?>>{{$cat->name}}</option>
                  <?php }}?>
                </select>
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
                <label class="form-control-label" for="example2cols2Input">Amount</label>

                <input type="text" name="amount" value="{{ old('amount', $amount) }}" placeholder="Enter Amount" class="form-control">
                
              </div>
            </div>

          </div>



          <div class="row">

             <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label" for="example2cols2Input">Duration(In Days)</label>

                <input type="text" name="duration" value="{{ old('duration', $duration) }}" placeholder="Enter Duration" class="form-control">
                
              </div>
            </div>




           <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label" for="example2cols2Input">Status</label>

              <select class="form-control" name="status">
                <option value="1" <?php if($status==1) { echo 'selected';  } ?>>Active</option>
                <option value="0" <?php if($status==0) { echo 'selected';  } ?>>InActive</option>
              </select>
            </div>
          </div>
          
        </div>

        <button class="btn btn-primary" type="submit">Submit form</button>

      </form>
    </div>
  </div>
</div>









@include('admin.common.footer')
<script type="text/javascript">
  $("#state_id").change(function(){
  var state_id = $(this).val();
   var _token = '{{ csrf_token() }}';
      $.ajax({
        url: "{{ route($routeName.'.vendors.get_city') }}",
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


