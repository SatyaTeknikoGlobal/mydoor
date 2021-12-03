@include('merchant.common.header')
<?php
$business_name = isset($user->business_name) ? $user->business_name : old('business_name');
$email = isset($user->email) ? $user->email : old('email');
$address = isset($user->address) ? $user->address : old('address');
$phone = isset($user->phone) ? $user->phone : old('phone');
$username = isset($user->username) ? $user->username : old('username');
$state_id = isset($user->state_id) ? $user->state_id : old('state_id');

$city_id = isset($user->city_id) ? $user->city_id : old('city_id');


?>


<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Profile</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Profile</a></li>
              <li class="breadcrumb-item active" aria-current="page">Profile </li>
            </ol>
          </nav>
        </div>

      </div>
    </div>
  </div>
</div>


<div class="container-fluid mt--6">
  <div class="row">


    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <div class="card-title">Update Profile</div>
          <hr>

          <form action="" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
              <label for="input-6">Business Name</label>
              <input type="text" class="form-control form-control-rounded" id="input-6" placeholder="Enter Your Business Name" name="business_name" value="{{$business_name}}">
              @include('snippets.errors_first', ['param' => 'business_name'])

            </div>
            <div class="form-group">
              <label for="input-8">Email</label>
              <input type="text" class="form-control form-control-rounded" id="input-8" placeholder="Enter Your Email" name="email" value="{{$email}}">
              @include('snippets.errors_first', ['param' => 'email'])
            </div>


            <div class="form-group">
              <label for="input-9">Choose State</label>
              <select class="form-control" id="state_id" name="state_id">
                <option value="" selected disabled>Select State</option>
                <?php if(!empty($states)){
                  foreach($states as $state){
                    ?>
                    <option value="{{$state->id}}" <?php if($state_id == $state->id) echo "selected"?>>{{$state->name}}</option>

                  <?php }}?>
                </select>

                @include('snippets.errors_first', ['param' => 'state_id'])
              </div>


              <div class="form-group">
                <label for="input-9">Choose City</label>
                <select class="form-control" id="city_id" name="city_id">
                  <option value="" selected disabled>Select City</option>
                   <?php if(!empty($cities)){
                  foreach($cities as $city){
                    ?>
                    <option value="{{$city->id}}" <?php if($city_id == $city->id) echo "selected"?>>{{$city->name}}</option>

                  <?php }}?>
                </select>
                @include('snippets.errors_first', ['param' => 'city_id'])
              </div>



              <div class="form-group">
                <label for="input-10">Phone</label>
                <input type="text" class="form-control form-control-rounded" id="input-10" placeholder="Enter Phone" name="phone" value="{{$phone}}">
                @include('snippets.errors_first', ['param' => 'phone'])
              </div>
              <div class="form-group">
                <label for="input-10">Profile Image</label>
                <input type="file" class="form-control" id="readonlyInput"
                value="" name="file">
                @include('snippets.errors_first', ['param' => 'file'])
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="icon-lock"></i> Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <div class="card-title">Update Password</div>
            <hr>
            <form action="{{route('merchant.change_password')}}" method="post" enctype="multipart/form-data">
              {!! csrf_field() !!}
              <div class="form-group">
                <label for="input-4">Old Password</label>
                <input type="text" class="form-control" id="input-4" placeholder="Enter Old Password" name="old_password">
                @include('snippets.errors_first', ['param' => 'old_password'])
              </div>
              <div class="form-group">
                <label for="input-4"> Password</label>
                <input type="text" class="form-control" id="input-4" placeholder="Enter Password" name="new_password">
                @include('snippets.errors_first', ['param' => 'new_password'])

              </div>
              <div class="form-group">
                <label for="input-5">Confirm Password</label>
                <input type="text" class="form-control" id="input-5" placeholder="Confirm Password"  name="confirm_password">
                @include('snippets.errors_first', ['param' => 'confirm_password'])

              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="icon-lock"></i> Update</button>
              </div>
            </form>
          </div>
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