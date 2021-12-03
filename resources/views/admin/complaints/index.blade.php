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
            <h1 class="main-title float-left">Complaints</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Complaints</li>
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
            <h3>Filters</h3>

          </div>


          <div class="card-body d-flex">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
              <label>Select Society</label>
              <select class="form-control select2" id="society" >
                <option value="0" selected="" disabled >Select Society</option>
                <?php if(!empty($societies)){
                  foreach($societies as $soc){
                    ?>
                    <option value="{{$soc->id}}">{{$soc->name}}</option>
                  <?php }}?>

                </select>
              </div>


              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                <label>Select Blocks</label>
                <select class="form-control select2" id="blocks" >
                  <option value="0" selected="" disabled >Select Blocks</option>


                </select>

              </div>



            </div>

            <div class="card-body d-flex">

              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                <label>Select Flat No</label>
                <select class="form-control select2" id="flats" >
                  <option value="0" selected="" disabled >Select Flat No</option>


                </select>
              </div>


              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                <label>Select Compaint Status</label>
                <select class="form-control select2" id="status" >
                  <option value="0" selected="" disabled >Select Compaint Status</option>
                  <option value="pending">Pending</option>
                  <option value="processing">Processing</option>
                  <option value="completed">Completed</option>

                </select>

              </div>



            </div>













          </div>
          <!-- end card-->
        </div>
      </div>






      <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="card mb-3">
            <div class="card-header">
              <h3>Complaints List</h3>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover display" style="width:100%">
                  <thead>
                    <tr>
                     <th scope="col">#ID</th>
                     <th scope="col">Society Name</th>
                     <th scope="col">Block</th>
                     <th scope="col">Flat No</th>
                     <th scope="col">Category</th>
                     <th scope="col">Image</th>
                     <th scope="col">Details</th>
                     <th scope="col">Status</th>
                     <th scope="col">Date Created</th>
                     <th scope="col">Remarks</th>
                   </tr>
                 </thead>
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

<script>
  var i = 1;

  var table = $('#dataTable').DataTable({
   ordering: false,
   processing: true,
   serverSide: true,

    ajax: {
          url: '{{ route($routeName.'.complaints.get_complaints') }}',
          data: function (d) {
                d.society_id = $('#society').val(),
                d.block_id = $('#blocks').val(),
                d.flat_id = $('#flats').val(),
                d.status = $('#status').val(),
                d.search = $('input[type="search"]').val()
            }
      },



   columns: [
   { data: 'id', name: 'id' },
   { data: 'society_id', name: 'society_id' ,searchable: false, orderable: false},
   { data: "block_id",name: 'block_id'},
   { data: "flat_id",name: 'flat_id'},
   { data: "category",name: 'category'},
   { data: "image",name: 'image'},
   { data: "text",name: 'text'},
   { data: "status",name: 'status'},
   { data: 'created_at', name: 'created_at' },
   { data: 'remarks', name: 'remarks' },
   // { data: 'action', searchable: false, orderable: false }

   ],








   // "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {


   //    if (aData['core_status'] == "pending") {
   //      $('td', nRow).css('background-color', '#ff5d48');
   //    } else  if (aData['core_status'] == "processing") {
   //      $('td', nRow).css('background-color', '#f1b53d');
   //    }
   //    else  if (aData['core_status'] == "completed") {
   //      $('td', nRow).css('background-color', '#1bb99a');
   //    }
   //  }



 });



 $('#society').change(function(){
        table.draw();
    });
 $('#blocks').change(function(){
        table.draw();
    });
 $('#flats').change(function(){
        table.draw();
    });
 $('#status').change(function(){
        table.draw();
    });


</script>

<script type="text/javascript">
  function get_textarea_value(id){
    var comment = $('#comment'+id).val();

    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.complaints.remark_submit') }}",
      type: "POST",
      data: {comment:comment,id:id},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      success: function(resp){
        if(resp.success){
                        //alert(resp.message);
                      }
                      else{
                        //alert(resp.message);
                        
                      }

                    }
                  });



  }



  function change_complaint_status(com_id){
    var status = $('#change_complaint_status'+com_id).val();

    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.complaints.change_complaint_status') }}",
      type: "POST",
      data: {com_id:com_id, status:status},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        if(resp.success){
          alert(resp.message);
        }else{
          alert(resp.message);

        }
      }
    });

  }

</script>

<script type="text/javascript">
  $('#society').change(function() {
    var society_id = $(this).val();
      var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.get_blocks') }}",
      type: "POST",
      data: {society_id:society_id},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        $('#blocks').html(resp);
      }
    });



  });


  $('#blocks').change(function() {
    var block_id = $(this).val();
      var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.get_flats') }}",
      type: "POST",
      data: {block_id:block_id},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        $('#flats').html(resp);
      }
    });



  });



</script>