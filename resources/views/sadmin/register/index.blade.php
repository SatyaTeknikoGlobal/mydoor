<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="shortcut icon" href="{{asset('public/assets1/img/logo-fav.png')}}">
  <title>MyDoor Society Admin</title>
  <link rel="stylesheet" type="text/css" href="{{asset('public/assets1/lib/perfect-scrollbar/css/perfect-scrollbar.css')}}"/>
  <link rel="stylesheet" type="text/css" href="{{asset('public/assets1/lib/material-design-icons/css/material-design-iconic-font.min.css')}}"/>
  <link rel="stylesheet" type="text/css" href="{{asset('public/assets1/lib/jquery.vectormap/jquery-jvectormap-1.2.2.css')}}"/>
  <link rel="stylesheet" type="text/css" href="{{asset('public/assets1/lib/jqvmap/jqvmap.min.css')}}"/>
  <link rel="stylesheet" type="text/css" href="{{asset('public/assets1/lib/datetimepicker/css/bootstrap-datetimepicker.min.css')}}"/>
  <link rel="stylesheet" href="{{asset('public/assets1/css/app.css')}}" type="text/css"/>
</head>


<body class="be-splash-screen">
  <div class="be-wrapper be-login">
    <div class="be-content">
      <div class="main-content container-fluid">
        <div class="splash-container">
          <div class="card card-border-color card-border-color-primary">
            <div class="card-header"><img class="logo-img" src="{{asset('public/assets1/img/logo-xx.png')}}" alt="logo" width="{conf.logoWidth}" height="27"><span class="splash-description">MyDoor Society Admin Register</span></div>
            <div class="card-body">
              <form action="{{url('sadmin/register')}}" method="post">
                {!! csrf_field() !!}
                <div class="login-form">

                  <div class="form-group">
                    <select name="society_id" class="form-control">
                      <option value="" selected disabled>Select Society</option>
                      <?php if(!empty($societies)){
                        foreach($societies as $soc){?>
                          <option value="{{$soc->id}}">{{$soc->name}}</option>
                        <?php }}?>
                      </select>
                      @include('snippets.errors_first', ['param' => 'society_id'])
                    </div>

                    <div class="form-group">
                        <input class="form-control" id="location" name="location" type="text" placeholder="Enter Address" autocomplete="off">

                        @include('snippets.errors_first', ['param' => 'location'])

                    </div>


                    <div class="form-group">
                      <select name="state_id" class="form-control" id="state_id">
                        <option value="" selected disabled>Select State</option>
                        <?php if(!empty($states)){
                          foreach($states as $state){?>
                            <option value="{{$state->id}}">{{$state->name}}</option>
                          <?php }}?>
                        </select>
                        @include('snippets.errors_first', ['param' => 'state_id'])
                      </div>


                      <div class="form-group">
                        <select name="city_id" class="form-control" id="city_id">
                          <option value="" selected disabled>Select City</option>

                        </select>
                        @include('snippets.errors_first', ['param' => 'city_id'])
                      </div>

                        <div class="form-group">
                        <input class="form-control" id="name" name="name" type="text" placeholder="Enter name" autocomplete="off">

                        @include('snippets.errors_first', ['param' => 'name'])

                      </div>


                      <div class="form-group">
                        <input class="form-control" id="email" name="email" type="text" placeholder="Enter Email" autocomplete="off">

                        @include('snippets.errors_first', ['param' => 'email'])

                      </div>

                      <div class="form-group">
                        <input class="form-control" id="phone" name="phone" type="text" placeholder="Enter phone" autocomplete="off">

                        @include('snippets.errors_first', ['param' => 'phone'])

                      </div>


                      <div class="form-group">
                        <input class="form-control" id="password" name="password" type="password" placeholder="Password">
                        @include('snippets.errors_first', ['param' => 'password'])
                      </div>


                      <div class="form-group row login-tools">
                        <div class="col-6 login-remember">
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="check1">
                            <label class="custom-control-label" for="check1">Remember Me</label>
                          </div>
                        </div>

                      </div>
                      <div class="form-group row login-submit">
                        <div class="col-6"><button type="submit" class="btn btn-primary btn-xl">Sign Up</button></div>


                        <div class="col-6"><a href="{{route('sadmin.login')}}">Already Have An Account</a></div>


                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


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
