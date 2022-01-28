@include('merchant.common.header')
<?php
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$MERCHANT_ROUTE_NAME = CustomHelper::getMerchantRouteName();

?>

<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Redeem Coupon</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Redeem Coupon</a></li>
              <li class="breadcrumb-item active" aria-current="page">Redeem Coupon</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <a href="{{ route($MERCHANT_ROUTE_NAME.'.coupon.coupon_redeemption')}}" class="btn btn-sm btn-neutral">Back</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="card mb-4">
    <!-- Card header -->
    <div class="card-header">
      <h3 class="mb-0">Redeem Coupon</h3>
    </div>
     @include('snippets.errors')
      @include('snippets.flash')
    <!-- Card body -->
    <div class="card-body">
      <!-- Form groups used in grid -->
      <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-md-6">

          <label class="form-control-label" for="">Coupon Code</label><br>
          <div class="input-group">
           
            <input type="text" class="form-control" placeholder="Enter Coupon Code" name="coupon_code" id="coupon_code" value="{{ old('coupon_code') }}" >
          </div>

          @include('snippets.errors_first', ['param' => 'coupon_code'])

        </div>

         <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label" for="">Card No</label>
                      <input type="text" class="form-control " id="" value="{{ old('card_no') }}" name="card_no">
                      @include('snippets.errors_first', ['param' => 'card_no'])
                    </div>
                  </div>

      </div>





        <button class="btn btn-primary" type="submit">Submit form</button>

      </form>
    </div>
  </div>
</div>









@include('merchant.common.footer')
