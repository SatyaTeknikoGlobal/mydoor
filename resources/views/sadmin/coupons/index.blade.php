@include('merchant.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$MERCHANT_ROUTE_NAME = CustomHelper::getMerchantRouteName();
$routeName = CustomHelper::getMerchantRouteName();

$old_name = (request()->has('name'))?request()->name:'';
$storage = Storage::disk('public');
$path = 'coupon/';

?>



<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Coupons</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Coupons</a></li>
              <li class="breadcrumb-item active" aria-current="page">Coupons List</li>
          </ol>
      </nav>
  </div>
  <div class="col-lg-6 col-5 text-right">
      <?php if(!empty($merchant_subscription) && count($merchant_subscription) > 0){?>

      <a href="{{ route($MERCHANT_ROUTE_NAME.'.coupon.add')}}" class="btn btn-sm btn-neutral">New</a>
    <?php }?>
      <a href="#" class="btn btn-sm btn-neutral">Filters</a>
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
          <h3 class="mb-0">Coupons List</h3>

      </div>

      @include('snippets.errors')
      @include('snippets.flash')
      <div class="table-responsive py-4">
          <table class="table table-flush" id="datatable-basic">
            <thead class="thead-light">
              <tr>
                 <th class="">Sl No</th>

                 <th class="">Name</th>
                 <th class="">Coupon Code</th>
                 <th class="">Expiry Date</th>

                 <th class="">Image</th>
                 <th class="">Merchant Name</th>
                 <th class="">Status</th>
                 <th class="">Action</th>
             </tr>
         </thead>

         <tbody>

            <?php if(!empty($coupons) && $coupons->count() > 0){
                $i = 1;
                foreach ($coupons as $coup){


                    ?>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$coup->name}}</td>
                        <td>{{$coup->coupan_code}}</td>
                        <td>{{$coup->expary_date}}</td>
                        <td><?php if(!empty($coup->image)){?>
                           <img src="{{ $coup->image }}" style="width:50px;">
                         <?php }?>


                        </td>
                        <td>
                          <?php if(!empty($vendors)){

                            foreach($vendors as $ven){
                              if($ven->id == $coup->merchant_id){
                                echo $ven->business_name;
                              }


                            }}?>


                        </td>
                        <td><?php  echo ($coup->status==1)?'Active':'Inactive';  ?></td>
                        <td>
                            <a href="{{route($routeName.'.coupon.edit', ['id'=>$coup->id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>
                        </td>
                        
                    </tr>

                <?php }}?>

            </tbody>
        </table>
    </div>
</div>

</div>
</div>



@include('merchant.common.footer')
