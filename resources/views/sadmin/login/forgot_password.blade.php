    
<!DOCTYPE html>

<html class="loading" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">
  <title>Merchant Forgot Password</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{asset('public/assets/img/ico/favicon.ico')}}">
  <link rel="shortcut icon" type="image/png" href="{{asset('public/assets/img/ico/favicon-32.png')}}">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-touch-fullscreen" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <link rel="icon" href="{{asset('public/assets/img/brand/favicon.png')}}" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="{{asset('public/assets/vendor/nucleo/css/nucleo.css')}}" type="text/css">
  <link rel="stylesheet" href="{{asset('public/assets/vendor/%40fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css">
  <!-- Argon CSS -->
  <link rel="stylesheet" href="{{asset('public/assets/css/argon.min23cd.css?v=1.2.1')}}" type="text/css">
</head>

<body class="bg-default">
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <!-- Navbar -->
  <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
    <div class="container d-none">
      <a class="navbar-brand" href="../dashboards/dashboard.html">
        <img src="{{asset('public/assets/img/brand/white.png')}}">
      </a>

      <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
        <div class="navbar-collapse-header">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="../dashboards/dashboard.html">
                <img src="{{asset('public/assets/img/brand/blue.png')}}">
              </a>
            </div>

          </div>
        </div>

      </div>
    </div>
  </nav>
  <!-- Main content -->
  <div class="main-content">
    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">

      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary border-0 mb-0">

            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-muted mb-4">
                <h4>Merchant Forgot Password</h4>
              </div>
              <form action="{{url('merchant/login')}}" method="post">
                {!! csrf_field() !!}
                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>

                    <input type="text" class="form-control" name="email" placeholder="email">
                  </div>
                  @if ($errors->has('email'))
                  <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                  @endif
                </div>
               
               
                <div class="text-center">
                  <button type="submit" class="btn btn-primary my-4">Sign in</button>
                </div>
              </form>
            </div>
          </div>


          <div class="row mt-3">
             <div class="col-6">
              <a href="{{route('merchant.login')}}" class="text-light"><small>Already Have Account</small></a>
            </div>
            <div class="col-6 text-right">
              <a href="{{route('merchant.register')}}" class="text-light"><small>Create new account</small></a>
            </div>
          </div>



        </div>
      </div>
    </div>
  </div>

  <!-- Core -->
  <script src="{{asset('public/assets/vendor/jquery/dist/jquery.min.js')}}"></script>
  <script src="{{asset('public/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('public/assets/vendor/js-cookie/js.cookie.js')}}"></script>
  <script src="{{asset('public/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js')}}"></script>
  <script src="{{asset('public/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js')}}"></script>
  <!-- Argon JS -->
  <script src="{{asset('public/assets/js/argon.min23cd.js?v=1.2.1')}}"></script>
  <!-- Demo JS - remove this in your project -->
  <script src="{{asset('public/assets/js/demo.min.js')}}"></script>
</body>




























