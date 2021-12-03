@extends('admin/layout')



@section('manage_content')



active



@endsection



@section('contents')



active



@endsection



@section('content')



<div class="container-fluid">



  <!-- Breadcrumb-->



  <div class="row pt-2 pb-2">



    <div class="col-sm-9">



      <h4 class="page-title">Manage Contents</h4>



      <ol class="breadcrumb">



        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>



        <li class="breadcrumb-item active"><a href="javaScript:void();">Contents</a></li>



        <li class="breadcrumb-item"><a href="javaScript:void();"><?=$topic->board_name;?></a></li>

        <li class="breadcrumb-item"><a href="javaScript:void();"><?=$topic->title;?></a></li>

        <li class="breadcrumb-item"><a href="javaScript:void();"><?=$topic->chapter_name;?></a></li>

        <li class="breadcrumb-item"><a href="javaScript:void();"><?=$topic->name;?></a></li>



    </ol>



</div>



<div class="col-sm-3">



 <div class="btn-group float-sm-right">



    <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  url('/new/content') }}">Back</a>



</div>



</div>



</div>



<!-- End Breadcrumb-->



<div class="row">



    <div class="col-lg-12">



      <div class="card">



        <div class="card-header"><i class="fa fa-clipboard"></i> Video's List </div>



        <div class="card-body">



          <div class="table-responsive">



              <table id="default-datatable" class="table table-bordered">



                <thead>



                    <tr>



                        <th>#</th>





                        <th>Topic</th>

                        

                        <th>Is Paid</th>



                        <th>Is Free</th>



                        <th>Video</th>



                        <th>Action</th>



                    </tr>



                </thead>



                <tbody>



                    @php



                    $i=1



                    @endphp



                    @foreach($video as $row)



                    <tr>

                        <td>{{$row->id}}</td>



                        <td>{{$row->title}}</td>

                        <td>

                          {{$row->is_paid}}

                      </td>

                      <td>

                          {{$row->is_free}}

                      </td>

                      <td>

                          <?php if($row->hls_type == 'local' && $row->type == 'video'){?>
                            <video src="{{url('/public/content/video/'.$row->hls.'.mp4')}}" controls="" height="100" width="200"></video>
                        <?php }if($row->hls_type == 'youtube' && $row->type == 'video'){?>
                         <iframe id="ytplayer" type="text/html" width="200" height="100"
                         src="https://www.youtube.com/embed/{{$row->hls}}"
                         frameborder="0"></iframe>



                         
                     <?php }?>

                 </td>

                 <td>
                   
                   <a href="{{ route('content.edit', $row->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                   <form action="{{ route('content.destroy',$row->id) }}" method="POST" id="delete_record">

                      @csrf

                      @method('DELETE')

                      <button href="javascript:void(0);" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('You Want to Delete this?')"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>

                  </form>

              </td>

          </tr>



          @endforeach



      </tbody>



  </table>



</div>



</div>



</div>



</div>



</div><!-- End Row-->
<?php 
$pre_id = isset($pre_id) ? $pre_id : 0;
?>
<form method="post" action="{{route('note_delete')}}">
   {{ csrf_field() }}
   <input type="hidden" name="pre_id" value="{{$pre_id}}">
   <div class="row">



    <div class="col-lg-12">



      <div class="card">

        

         <div style="display: flex;">
            <div class="card-header"><i class="fa fa-clipboard"></i> Notes List </div>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are You want to Delete')" style="float: right;">Delete</button >
        </div>

        <div class="card-body">



          <div class="table-responsive">



              <table id="defaulttable" class="table table-bordered">



                <thead>



                    <tr>


                        <th></th>
                        <th>#</th>





                        <th>Topic</th>

                        

                        <th>Is Paid</th>



                        <th>Is Free</th>



                        <th>Notes</th>



                        <th>Action</th>



                    </tr>



                </thead>



                <tbody>



                    @php



                    $i=1



                    @endphp



                    @foreach($notes as $row)



                    <tr>


                     <td><input type="checkbox" name="note_delete[]" value="{{$row->id}}"></td>
                 </form>

                 <td>{{$i++}}</td>



                 <td>{{$row->title}}</td>

                 <td>

                  {{$row->is_paid}}

              </td>

              <td>

                  {{$row->is_free}}

              </td>

              <td>



                 @if(isset($row->hls))


                 <a href="{{url('/public/content/notes/'.$row->hls)}}" target="_blank">View Pdf</a>

                 @endif

                 

             </td>

             <td>

               <form action="{{ route('content.destroy',$row->id) }}" method="POST" id="delete_record">

                  @csrf

                  @method('DELETE')

                  <button href="javascript:void(0);" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('You Want to Delete this?')"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>

              </form>

          </td>

      </tr>



      @endforeach



  </tbody>



</table>



</div>



</div>



</div>



</div>



</div><!-- End Row-->


<!--start overlay-->



<div class="overlay toggle-menu"></div>



<!--end overlay-->



</div>



<!-- End container-fluid-->



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>



@if ($message = Session::get('success'))



<script>



    Swal.fire({



        icon: 'success',



        title: '{{ $message }}',



        showConfirmButton: false,



        timer: 2500



    });



</script>



@endif





<script>



   $(document).ready(function() {



     var table = $('#defaulttable').DataTable( {



        lengthChange: true,



        buttons: [ 'copy', 'excel', 'pdf', 'print', 'colvis' ]



    } );



     



     table.buttons().container()



     .appendTo( '#default-datatable_wrapper .col-md-6:eq(0)' );



     



 } );









</script>



@endsection