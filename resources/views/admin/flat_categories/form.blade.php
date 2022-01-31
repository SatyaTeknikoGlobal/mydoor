@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$flats_id = (isset($flats->id))?$flats->id:'';
$society_id = (isset($flats->society_id))?$flats->society_id:'';
$name = (isset($flats->name))?$flats->name:'';

$details = (isset($flats->details))?$flats->details:'';
$square_feet = (isset($flats->square_feet))?$flats->square_feet:'';
$maintainance_fee = (isset($flats->maintainance_fee))?$flats->maintainance_fee:'';
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
                                <label for="userName"> Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" />

                                @include('snippets.errors_first', ['param' => 'name'])
                            </div>


                                <div class="form-group">
                                <label for="userName"> Details<span class="text-danger">*</span></label>
                           
                                <textarea name="details" id="description" class="form-control">{{ old('details', $details) }}</textarea>

                                @include('snippets.errors_first', ['param' => 'details'])
                            </div>


                               <div class="form-group">
                                <label for="userName"> Square Feet<span class="text-danger">*</span></label>
                                <input type="text" name="square_feet" value="{{ old('square_feet', $square_feet) }}" id="square_feet" class="form-control"  maxlength="255" />

                                @include('snippets.errors_first', ['param' => 'square_feet'])
                            </div>


                               <div class="form-group">
                                <label for="userName">Maintainance Cost<span class="text-danger">*</span></label>
                                <input type="text" name="maintainance_fee" value="{{ old('maintainance_fee', $maintainance_fee) }}" id="maintainance_fee" class="form-control"  maxlength="255" />

                                @include('snippets.errors_first', ['param' => 'maintainance_fee'])
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