@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

$name = (isset($societyusers_child->name))?$societyusers_child->name:'';
$email = (isset($societyusers_child->email))?$societyusers_child->email:'';
$phone = (isset($societyusers_child->phone))?$societyusers_child->phone:'';
$location = (isset($societyusers_child->location))?$societyusers_child->location:'';
$gender = (isset($societyusers_child->gender))?$societyusers_child->gender:'';
$status = (isset($societyusers_child->status))?$societyusers_child->status:'';
$user_type = (isset($societyusers_child->user_type))?$societyusers_child->user_type:'';

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

                            <div class="form-group">
                               <label for="userName">User type<span class="text-danger">*</span></label>

                               <select name="user_type" class="form-control" id="city_id">
                                  <option value="" selected disabled>Select Type</option>
                                  <option value="Family Member" <?php echo ($user_type == 'Family Member')?'selected':''; ?>>Family Member</option>
                                  <option value="Maid" <?php echo ($user_type == 'Maid')?'selected':''; ?>>Maid</option>
                                  <option value="Chef" <?php echo ($user_type == 'Chef')?'selected':''; ?>>Chef</option>
                                  <option value="Tuition teacher" <?php echo ($user_type == 'Tuition teacher')?'selected':''; ?>>Tuition teacher</option>
                              </select>
                              @include('snippets.errors_first', ['param' => 'user_type'])
                          </div>
                            <div class="form-group">
                                <label for="userName">Name<span class="text-danger">*</span></label>
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
                            <label>Gender</label>
                            <div>
                             Male: <input type="radio" name="gender" value="male" <?php echo ($gender == 'male')?'checked':''; ?>>
                             &nbsp;
                             FeMale: <input type="radio" name="gender" value="female" <?php echo ($gender == 'female')?'checked':''; ?>>

                             @include('snippets.errors_first', ['param' => 'gender'])
                         </div>
                     </div>



                     <div class="form-group">
                        <label>Status</label>
                        <div>
                         Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                         &nbsp;
                         Inactive: <input type="radio" name="status" value="0" <?php echo ($status == '0')?'checked':''; ?> >

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
