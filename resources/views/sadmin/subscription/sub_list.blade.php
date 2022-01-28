@include('merchant.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$old_name = (request()->has('name'))?request()->name:'';


?>



<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">My Subscription List</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">My Subscription</a></li>
              <li class="breadcrumb-item active" aria-current="page">My Subscription List</li>
          </ol>
      </nav>
  </div>
</div>
</div>
</div>
</div>




<!-- Page content -->
<div class="container-fluid mt--6">
  <!-- Table -->
  <div class="row">
    <div class="col">
      <div class="card">
        <!-- Card header -->
        <div class="card-header">
          <h3 class="mb-0">My Subscription List</h3>
      </div>
      <div class="table-responsive py-4">
          <table class="table table-flush" id="datatable-basic">
            <thead class="thead-light">
              <tr>
              <th class="">Sl No</th>
                 <th class="">Category Name</th>
                 <th class="">Subscription Name</th>
                 <th class="">Subscription Amount</th>
                 <th class="">Start Date</th>
                 <th class="">End Date</th>
                 <th class="">Duration(In Days)</th>
                 <th class="">Status</th>
           </tr>
       </thead>

       <tbody>

        <?php if(!empty($merchant_subscription) && $merchant_subscription->count() > 0){
                $i = 1;
                foreach ($merchant_subscription as $sub){
                    ?>
                    <tr>
                        <td>{{$i++}}</td>
                         <td>
                            <?php 

                          if(!empty($categories)){
                            foreach($categories as $cat){
                              if($cat->id == $sub->cat_id){
                                echo $cat->name;
                              }

                            }}
                            ?>

                        </td>

                         <td>
                            <?php 

                          if(!empty($subscriptions)){
                            foreach($subscriptions as $subs){
                              if($subs->id == $sub->subscription_id){
                                echo $subs->name;
                              }

                            }}
                            ?>

                        </td>

                        <td>{{$sub->amount}}</td>

                        <td>{{$sub->start_date}}</td>

                        <td>{{$sub->end_date}}</td>
                        <td>{{$sub->subscription_duration}}</td>



                       
                       
                        <td><?php  echo ($sub->status==1)?'Active':'Inactive';  ?></td>
                     
                    </tr>

                <?php }}?>

        </tbody>
    </table>
</div>
</div>

</div>
</div>



@include('merchant.common.footer')

