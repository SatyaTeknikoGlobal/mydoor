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
          <h6 class="h2 text-white d-inline-block mb-0">Coupons Redeemption</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Coupons Redeemption</a></li>
              <li class="breadcrumb-item active" aria-current="page">Coupons Redeemption List</li>
          </ol>
      </nav>
  </div>
  <div class="col-lg-6 col-5 text-right">
     

      <a href="{{ route($MERCHANT_ROUTE_NAME.'.coupon.addredeem')}}" class="btn btn-sm btn-neutral">New</a>
      
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
          <h3 class="mb-0">Coupons Redeemption List</h3>

      </div>

      @include('snippets.errors')
      @include('snippets.flash')
      <div class="table-responsive py-4">
          <table class="table table-flush" id="datatable-basic">
            <thead class="thead-light">
              <tr>
                 <th class="">Sl No</th>

                 <th class="">User Name</th>
                 <th class="">User Card No</th>
                 <th class="">Coupon Code</th>
                 <th class="">Claim Date</th>
               
             </tr>
         </thead>

         <tbody>

            <?php if(!empty($redeems) && $redeems->count() > 0){
                $i = 1;
                foreach ($redeems as $coup){
                    ?>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$coup->user_id}}</td>
                        <td>{{$coup->card_no}}</td>

                        <td>{{$coup->coupon_code}}</td>
                        <td>{{$coup->redeem_date}}</td>
                    </tr>

                <?php }}?>

            </tbody>
        </table>
    </div>
</div>

</div>
</div>



@include('merchant.common.footer')
