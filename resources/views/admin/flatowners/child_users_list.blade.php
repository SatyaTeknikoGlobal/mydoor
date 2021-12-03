@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/thumb/';
$roleId = Auth::guard('admin')->user()->role_id; 



?>

<div class="content-page">

  <!-- Start content -->
  <div class="content">

    <div class="container-fluid">

      <div class="row">
        <div class="col-xl-12">
          <div class="breadcrumb-holder">
            <h1 class="main-title float-left">Child Users</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Child Users</li>
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
              <h3>Child Users List</h3>
              @if(CustomHelper::isAllowedSection('flatowners' , $roleId , $type='add'))
              <span class="pull-right">
                <a href="{{ route($routeName.'.flatowners.child_user_add', ['user_id'=>$user_id,'back_url'=>$BackUrl]) }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus" aria-hidden="true"></i> Add Child Users</a>
              </span>
              @endif
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover display" style="width:100%">
                  <thead>
                    <tr>
                     <th scope="col">#ID</th>
                     <th scope="col">Name</th>
                     <th scope="col">email</th>
                     <th scope="col">gender</th>
                     <th scope="col">phone</th>
                     <th scope="col">user_type</th>
                     <th scope="col">Status</th>
                     <th scope="col">Date Created</th>
                     <th scope="col">Action</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php if (!empty($societyusers_child)) {?>
                    <?php foreach ($societyusers_child as $key): ?>
                      <tr>
                        <td><?php echo $key->id?></td>
                        <td><?php echo $key->name?></td>
                        <td><?php echo $key->email?></td>
                        <td><?php echo $key->gender?></td>
                        <td><?php echo $key->phone?></td>
                        <td><?php echo $key->user_type?></td>
                        <td><select id='change_flatowner_status$data->id' onchange='change_flatowner_status($data->id)'>
                          <option value='1' <?php echo ($key->status ==1) ? "selected":""?>>Active</option>
                          <option value='0' <?php echo ($key->status ==0) ? "selected":""?>>InActive</option>
                        </select></td>
                        <td><?php echo $key->created_at?></td>
                        <td><a title="Edit" href="{{ route($routeName.'.flatowners.child_user_edit', ['user_id'=>$user_id,'id'=>$key->id,'back_url'=>$BackUrl]) }}"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;'</td>

                      </tr>
                    <?php endforeach ?>
                  <?php }?>

                </tbody>
              </table>
            </div>
            <!-- end table-responsive-->

          </div>
          <!-- end card-body-->

        </div>
        <!-- end card-->

      </div>

    </div>
    <!-- end row-->

  </div>
  <!-- END container-fluid -->

</div>
<!-- END content -->

</div>
<!-- END content-page -->



@include('admin.common.footer')

