@include('merchant.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$routeName = CustomHelper::getAdminRouteName();

$old_name = (request()->has('name'))?request()->name:'';
$storage = Storage::disk('public');
$path = 'subscription/';

?>

<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Subscription</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Subscription</a></li>
              <li class="breadcrumb-item active" aria-current="page">Subscription List</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">

        </div>
      </div>
      <!-- Card stats -->
      <div class="row">

        <?php if(!empty($subscriptions)){
          foreach($subscriptions as $sub){

            ?>



            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">{{$sub->name}}</h5>
                      <span class="h2 font-weight-bold mb-0">₹{{$sub->amount}}</span>
                    </div>
                    <a id="buy_now" onclick="buy_now({{$sub->id}})" title="Click Here to Buy">
                      <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                          <i class="fa fa-shopping-cart"></i>
                        </div>
                      </div>
                    </a>


                  </div>

                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-nowrap mr-2">Duration: {{$sub->duration}} Days</span><br>
                    <span class="text-nowrap mr-2">Category:  <?php if(!empty($categories) && count($categories) > 0){
                      foreach($categories as $cat){
                        if($cat->id == $sub->cat_id){
                          echo $cat->name;
                        }
                      }}
                    ?></span>

                    <span class="text-nowrap mr-2">SubCategory:  <?php if(!empty($subcategories) && count($subcategories) > 0){
                      foreach($subcategories as $sub_cat){
                        if($sub_cat->id == $sub->sub_cat_id){
                          echo $sub_cat->name;
                        }
                      }}
                    ?></span>





                    <span class="text-nowrap"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#infomodal{{$sub->id}}"><i class="fa fa-info-circle"></i></button></span>





                  </p>
                </div>
              </div>
            </div>





            <div class="modal fade" id="infomodal{{$sub->id}}" tabindex="-1" role="dialog" aria-labelledby="#infomodal{{$sub->id}}" aria-hidden="true">
              <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content text-center">
                  <div class="modal-header bg-danger text-white d-flex justify-content-center">
                    <h5 class="modal-title" id="#infomodal{{$sub->id}}">{{$sub->name}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                   <h1>Description</h1>
                   <p>{{$sub->description}}</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger ripple-surface" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>




            <form id="buy_subscription{{$sub->id}}" method="post" action="{{route('merchant.subscription.buy_subscription')}}">
              {{ csrf_field() }}
              <input type="hidden" id="vendor_id{{$sub->id}}" name="vendor_id" value="{{Auth::guard('merchant')->user()->id}}">
              <input type="hidden" id="subscription{{$sub->id}}" name="subscription_id" value="{{$sub->id}}">
              <input type="hidden" id="amount{{$sub->id}}" name="amount" value="{{$sub->amount}}">
              <input type="hidden" id="duration{{$sub->id}}" name="subscription_duration" value="{{$sub->duration}}">
              <input type="hidden" id="cat_id{{$sub->id}}" name="cat_id" value="{{$sub->cat_id}}">
              <input type="hidden" id="sub_cat_id{{$sub->id}}" name="sub_cat_id" value="{{$sub->sub_cat_id}}">


              <input type="hidden" id="state_id{{$sub->id}}" name="state_id" value="{{Auth::guard('merchant')->user()->state_id}}">


              <input type="hidden" id="city_id{{$sub->id}}" name="city_id" value="{{Auth::guard('merchant')->user()->city_id}}">

              <input type="hidden" id="txn_no{{$sub->id}}" name="txn_no" value="">
            </form>


          <?php }}?>  
        </div>
      </div>
    </div>
  </div>



  @include('merchant.common.footer')

  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

  <script type="text/javascript">

    var sub_id = '';



    function buy_now(id){

     sub_id = id;

     var total = $('#amount'+id).val();
     var logoUrl = "https://royalitsolution.in/public/storage/settings/150221060218-oie_MGGl65pBRn5g-removebg-preview.png";
     var currSel = $(this);
     var options = {
      key: "rzp_test_iXlRuzzNkp5EaF",
      amount: total * 100,
      id: "<?php echo rand(0000,9999)?>",
      name: 'ADSSOLUTION',
      image: logoUrl,
      "prefill": {
        "name": '{{Auth::guard('merchant')->user()->business_name}}',
        "email": "{{Auth::guard('merchant')->user()->email}}",
        "contact": "{{Auth::guard('merchant')->user()->phone}}"
      },
      handler: demoSuccessHandler
    }
    window.r = new Razorpay(options);
    var succ = r.open();
    if(succ){
      return false;
    }
  }

  function padStart(str) {
    return ('0' + str).slice(-2)
  }

  function demoSuccessHandler(transaction) {
        // You can write success code here. If you want to store some data in database.
        $("#paymentDetail").removeAttr('style');
        $('#paymentID').text(transaction.razorpay_payment_id);
        var paymentDate = new Date();
        $('#paymentDate').text(
          padStart(paymentDate.getDate()) + '.' + padStart(paymentDate.getMonth() + 1) + '.' + paymentDate.getFullYear() + ' ' + padStart(paymentDate.getHours()) + ':' + padStart(paymentDate.getMinutes())
          );

        var transaction_id = transaction.razorpay_payment_id;
        $('#txn_no'+sub_id).val(transaction_id);
        $('#buy_subscription'+sub_id).submit();

        
      }

    </script>