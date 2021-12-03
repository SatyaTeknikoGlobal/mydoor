<!DOCTYPE html>
<html lang="en">
<head>
    <title>MyDoor Society Admin - Dashboard</title>
    <meta name="description" content="Dashboard | Fans Studio Admin">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Your website">
    <link rel="shortcut icon" href="{{asset('public/assets/images/favicon.ico')}}">
    <!-- Bootstrap CSS -->
    <link href="{{asset('public/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome CSS -->
    <link href="{{asset('public/assets/font-awesome/css/all.css')}}" rel="stylesheet" type="text/css" />
    <!-- Custom CSS -->
    <link href="{{asset('public/assets/css/style.css')}}" rel="stylesheet" type="text/css" />


     <link href="{{asset('public/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/plugins/chart.js/Chart.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/plugins/datatables/datatables.min.css')}}" />
    <!-- END CSS for this page -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>



</head>
 <style>
        tfoot {
            display: table-header-group;
        }
    </style>





    <?php 
$storage = Storage::disk('public');

    //pr($storage);

$path = 'user/';

$imageUrl = asset('public/assets/images/avatars/avatar.png');
$image_name = Auth::guard('sadmin')->user()->image; 
 if(!empty($image_name) && $storage->exists($path.$image_name) ){

    $imageUrl =  url('public/storage/'.$path.'thumb/'.$image_name);
 }

?>



<body class="adminbody">

    <div id="main">

       <!-- top bar navigation -->
        <div class="headerbar">

            <!-- LOGO -->
            <div class="headerbar-left">
                <a href="{{url('/sadmin')}}" class="logo">
                    <img alt="Logo" src="{{asset('public/assets/images/logo.png')}}" />
                    <span>MyDoor Society Admin</span>
                </a>
            </div>

            <nav class="navbar-custom">

                <ul class="list-inline float-right mb-0">

                    <li class="list-inline-item dropdown notif">
                        <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" aria-haspopup="false" aria-expanded="false">
                            <img src="{{$imageUrl}}" alt="Profile image" class="avatar-rounded">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="text-overflow">
                                    <small>Hello, <?php echo isset(Auth::guard('sadmin')->user()->name) ? Auth::guard('sadmin')->user()->name : 'My Door';?></small>
                                </h5>
                            </div>

                            <!-- item-->
                            <a href="{{route('sadmin.profile')}}" class="dropdown-item notify-item">
                                <i class="fas fa-user"></i>
                                <span>Profile</span>
                            </a>

                            <!-- item-->
                            <a href="{{url('sadmin/logout')}}" class="dropdown-item notify-item">
                                <i class="fas fa-power-off"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>

                </ul>

                <ul class="list-inline menu-left mb-0">
                    <li class="float-left">
                        <button class="button-menu-mobile open-left">
                            <i class="fas fa-bars"></i>
                        </button>
                    </li>
                </ul>

            </nav>

        </div>
        <!-- End Navigation -->



   

@include('sadmin.common.sidebar')
