<!DOCTYPE html>
<html lang="en">
<head>
    <title>UBERIZE GATE Admin - Dashboard</title>
    <meta name="description" content="">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Your website">
    <link rel="shortcut icon" href="{{asset('public/assets1/img/logo-xx.png')}}">
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
/*.ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
    border-color: var(--ck-color-base-border);
    height: 300px;
    overflow-y: auto;
}*/
    </style>




    <?php 
$storage = Storage::disk('public');

    //pr($storage);

$path = 'user/';

$imageUrl = asset('public/assets/images/avatars/avatar.png');
$image_name = Auth::guard('admin')->user()->image; 
 if($storage->exists($path.$image_name)){

        $imageUrl =  url('storage/app/public/'.$path.'thumb/'.$image_name);

 }
$roleId = Auth::guard('admin')->user()->role_id; 

?>




<body class="adminbody">

    <div id="main">

       <!-- top bar navigation -->
        <div class="headerbar">

            <!-- LOGO -->
            <div class="headerbar-left">
                <a href="{{url('/admin')}}" class="logo">
                    <img alt="Logo" src="{{asset('public/assets1/img/logo-xx.png')}}" />
                    <span>UBERIZE GATE</span>
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
                                    <small>Hello, <?php echo isset(Auth::guard('admin')->user()->name) ? Auth::guard('admin')->user()->name : 'UBERIZE GATE';?></small>
                                </h5>
                            </div>

                            <!-- item-->
                            <a href="{{route('admin.profile')}}" class="dropdown-item notify-item">
                                <i class="fas fa-user"></i>
                                <span>Profile</span>
                            </a>
                            <?php if(Auth::guard('admin')->user()->role_id == 0){?>
                            <a href="{{route('admin.setting')}}" class="dropdown-item notify-item">
                                <i class="fas fa-gear"></i>
                                <span>Settings</span>
                            </a>
                        <?php }?>
                            <!-- item-->
                            <a href="{{url('admin/logout')}}" class="dropdown-item notify-item">
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



   

@include('admin.common.sidebar')
