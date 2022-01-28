@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$flats_id = (isset($flats->id))?$flats->id:'';
$society_id = (isset($flats->society_id))?$flats->society_id:'';
$block_id = (isset($flats->block_id))?$flats->block_id:'';
$flat_no = (isset($flats->flat_no))?$flats->flat_no:'';
$status = (isset($flats->status))?$flats->status:'';


$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

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

                            <input type="hidden" name="id" value="{{$flats_id}}">

                             <div class="form-group">
                                <label for="userName">Society Name<span class="text-danger">*</span></label>
                               <select name="society_id" id="society_id" class="form-control select2">
                                   <option value="" selected disabled>Select Society</option>
                                   <?php if(!empty($societies)){
                                    foreach($societies as $soci){
                                    ?>
                                    <option value="{{$soci->id}}" <?php if($soci->id == $society_id) echo "selected"?>>{{$soci->name}}</option>
                                <?php }}?>

                               </select>

                                @include('snippets.errors_first', ['param' => 'society_id'])
                            </div>


                            <div class="form-group">
                                <label for="userName">Block Name<span class="text-danger">*</span></label>
                               <select name="block_id" id="block_id" class="form-control select2">
                                   <option value="" selected disabled>Select Block</option>
                                   <?php if(!empty($blocks)){
                                    foreach($blocks as $block){
                                    ?>
                                    <option value="{{$block->id}}" <?php if($block->id == $block_id) echo "selected"?>>{{$block->name}}</option>
                                <?php }}?>

                               </select>

                                @include('snippets.errors_first', ['param' => 'block_id'])
                            </div>








                            <div class="form-group">
                                <label for="userName">Flat Name<span class="text-danger">*</span></label>
                                <input type="text" name="flat_no" value="{{ old('flat_no', $flat_no) }}" id="flat_no" class="form-control"  maxlength="255" />

                                @include('snippets.errors_first', ['param' => 'flat_no'])
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
<script>
    CKEDITOR.replace( 'description' );
</script>
<script type="text/javascript">
    $('#society_id').change(function() {
    var society_id = $(this).val();
    var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.flats.get_blocks_from_society') }}",
                type: "POST",
                data: {society_id:society_id},
                dataType:"HTML",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    $('#block_id').html(resp);
                }
            });

   });
</script>