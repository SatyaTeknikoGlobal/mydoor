@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$user_id = (isset($users->id))?$users->id:'';
$image = (isset($users->image))?$users->image:'';
$name = (isset($users->name))?$users->name:'';
$email = (isset($users->email))?$users->email:'';
$phone = (isset($users->phone))?$users->phone:'';
$dob = (isset($users->dob))?$users->dob:'';
$status = (isset($users->status))?$users->status:'';
$location = (isset($users->location))?$users->location:'';
$priority = (isset($users->priority))?$users->priority:'';
$gender = (isset($users->gender))?$users->gender:'';


$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'users/';

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

                            <input type="hidden" name="id" value="{{$user_id}}">


                            <div class="form-group">
                                <label for="userName">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" />

                                @include('snippets.errors_first', ['param' => 'name'])
                            </div>



                            <div class="form-group">
                                <label for="emailAddress">Email<span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $email) }}" id="email" class="form-control"  maxlength="255" />
                                @include('snippets.errors_first', ['param' => 'email'])

                            </div>


                             <div class="form-group">
                                <label for="emailAddress">Phone<span class="text-danger">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', $phone) }}" id="phone" class="form-control"  maxlength="255" />
                                @include('snippets.errors_first', ['param' => 'phone'])

                            </div>



                               <div class="form-group">
                                <label for="emailAddress">Password<span class="text-danger">*</span></label>
                                <input type="password" name="password" value="" id="password" class="form-control"  maxlength="255" />
                                @include('snippets.errors_first', ['param' => 'password'])

                            </div>






                             <div class="form-group">
                                <label for="emailAddress">DOB<span class="text-danger">*</span></label>
                                <input type="date" name="dob" value="{{ old('dob', $dob) }}" id="dob" class="form-control"  maxlength="255" />
                                @include('snippets.errors_first', ['param' => 'dob'])

                            </div>









                             <div class="form-group">
                                <label for="emailAddress">Gender<span class="text-danger">*</span></label>
                                     Male: <input type="radio" name="gender" value="male" <?php echo ($gender == 'male')?'checked':''; ?> checked>
                           &nbsp;
                           Female: <input type="radio" name="gender" value="female"  <?php echo ($gender == 'female')?'checked':''; ?> >

                                @include('snippets.errors_first', ['param' => 'gender'])

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
                    <a type="reset" href="{{ route('admin.influencers.index') }}" class="btn btn-secondary m-l-5">
                        Cancel
                    </a>
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


@include('admin.common.footer')
<script>
    CKEDITOR.replace( 'description' );
</script>