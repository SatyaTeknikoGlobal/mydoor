<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Auth;
use Validator;
use App\User;
use App\Admin;
use App\Blocks;
use App\Society;
use App\Complaints;
use App\SocietyUsers;
use App\Flats;
use Yajra\DataTables\DataTables;
use Storage;
use DB;
use Hash;



Class ComplainController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        // $this->roleId = Auth::guard('admin')->user()->role_id; 

	}



	public function index(Request $request){



        // CustomHelper::isAllowedSection('blockes' , 1 , $type='add');

       if(!(CustomHelper::isAllowedSection('complaints' , Auth::guard('admin')->user()->role_id , $type='show'))){
           return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.complaints.index'));

       }


       // $blocks = Blocks::paginate(10);
       // $data['blocks'] = $blocks;
       $data = [];


       $societies = Society::where('status',1)->where('is_delete',0)->get();
       $data['societies'] = $societies;




       return view('admin.complaints.index',$data);
   }



   public function get_complaints(Request $request){

      $role_id = Auth::guard('admin')->user()->role_id;

      $society_id = isset($request->society_id) ? $request->society_id :0;
      $block_id = isset($request->block_id) ? $request->block_id :0;
      $flat_id = isset($request->flat_id) ? $request->flat_id :0;
      $status = isset($request->status) ? $request->status :'';


      $routeName = CustomHelper::getAdminRouteName();
      $datas = Complaints::orderBy('id','desc');
      if($role_id!=0 && Auth::guard('admin')->user()->society_id!=0){
        $datas->where('society_id',Auth::guard('admin')->user()->society_id);
        }

        if(!empty($society_id) && $society_id != 0){

            $datas->where('society_id',$society_id);
        }

         if(!empty($block_id) && $block_id != 0){

            $datas->where('block_id',$block_id);
        }

         if(!empty($flat_id) && $flat_id != 0){

            $datas->where('flat_id',$flat_id);
        }

         if(!empty($status) && $status != ''){

            $datas->where('status',$status);
        }



    $datas = $datas->get();

    return Datatables::of($datas)


    ->editColumn('id', function(Complaints $data) {

     return  $data->id;
 })

    ->editColumn('name', function(Complaints $data) {
     return  $data->name;
 })


    ->editColumn('society_id', function(Complaints $data) {

        $society = Society::where('id',$data->society_id)->first();
        return  $society->name ?? '';
    })

    ->editColumn('block_id', function(Complaints $data) {

      $block = Blocks::where('id',$data->society_id)->first();
      return  $block->name ?? '';
  })

    ->editColumn('flat_id', function(Complaints $data) {
       $flat = Flats::where('id',$data->society_id)->first();

       $user = SocietyUsers::where('flat_no',$flat->id)->where('user_type','owner')->where('status',1)->first();

       return  $flat->flat_no.' (<a href="tel:'.$user->phone.'">'.$user->phone .')';
   })


    ->editColumn('category', function(Complaints $data) {
     return  $data->category ?? '';
 })
    ->editColumn('image', function(Complaints $data) {
     $html ='';
     $image = isset($data->image) ? $data->image :'';


     if(!empty($image)){

        $html.="<a href='/usersapi/public/uploads/images/complaints/$image' target='_blank'><img src='/usersapi/public/uploads/images/complaints/$image' style='width:70px;'></a>";

    }


    return  $html;
})

    ->editColumn('text', function(Complaints $data) {

       $text = mb_strlen(strip_tags($data->text),'utf-8') > 50000 ? mb_substr(strip_tags($data->text),0,50000,'utf-8').'...' : strip_tags($data->text);
       $society = Society::where('id',$data->society_id)->first();
       $block = Blocks::where('id',$data->society_id)->first();
       $flat = Flats::where('id',$data->society_id)->first();

       return '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal'.$data->id.'">Click Here
       </button>

       <div class="modal fade" id="exampleModal'.$data->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
       <div class="modal-content">
       <div class="modal-header">
       <h5 class="modal-title" id="exampleModalLabel">Details</h5>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
       <span aria-hidden="true">&times;</span>
       </button>
       </div>
       <div class="modal-body">
       <p><b>Society Name :</b>'.$society->name.'</p>
       <p><b>Block Name :</b>'.$block->name.'</p>
       <p><b>Flat No :</b>'.$flat->flat_no.'</p>
       <p><b>Description :</b> '.$text.'</p>

       </div>
       <div class="modal-footer">
       <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
       </div>
       </div>
       </div>
       </div>







       ';
   })




    ->editColumn('status', function(Complaints $data) {

        $html = '';

    // if($data->status == 'pending'){
    //     $html = '<button class="btn btn-danger">Pending</button>';
    // }
    // if($data->status == 'processing'){
    //     $html = '<button class="btn btn-warning">Processing</button>';
    // }

    // if($data->status == 'completed'){
    //     $html = '<button class="btn btn-success">Completed</button>';
    // }

        $selected = '';
        $selected1 = '';
        $selected2 = '';
        if($data->status == 'pending'){
            $selected = 'selected';
        } if($data->status == 'processing'){
            $selected1 = 'selected';
        } if($data->status == 'completed'){
            $selected2 = 'selected';
        }


        $html = "<select class='form-control' id='change_complaint_status$data->id' onchange='change_complaint_status($data->id)'>";
        $html.='<option value="" selected disabled>Select Status</option>';

        $html.='<option value="pending" '.$selected.'>Pending</option>';
        $html.='<option value="processing" '.$selected1.'>Processing</option>';
        $html.='<option value="completed" '.$selected2.'>Completed</option>';



        $html.="</select>";






        return  $html;
    })

    ->editColumn('created_at', function(Complaints $data) {
     return  date('d M Y',strtotime($data->created_at));
 })

    ->editColumn('remarks', function(Complaints $data) {
     return  '<textarea placeholder="Enter Remarks" rows="4" cols="50" class="form-control" id="comment'.$data->id.'" onkeyup="get_textarea_value('.$data->id.');">'.$data->comments.'</textarea>';
 })

    ->editColumn('core_status', function(Complaints $data) {
     return  $data->status;
 })

// ->addColumn('action', function(Complaints $data) {
//    $routeName = CustomHelper::getAdminRouteName();

//    $BackUrl = $routeName.'/complaints';
//    $html = '';
//    if(CustomHelper::isAllowedSection('complaints' , Auth::guard('admin')->user()->role_id , $type='edit')){
//     $html.='<a title="Edit" href="' . route($routeName.'.complaints.edit',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;';
// }   




// return $html;
// })

    ->rawColumns(['name', 'status', 'action','image','remarks','text','flat_id'])
    ->toJson();
}




public function add(Request $request){


    $data = [];

    $id = (isset($request->id))?$request->id:0;

    if(!(CustomHelper::isAllowedSection('blockes' , Auth::guard('admin')->user()->role_id , $type='add'))){
       return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.blockes.index'));

   }





   $blockes = '';
   if(is_numeric($id) && $id > 0){

    if(!(CustomHelper::isAllowedSection('blockes' , Auth::guard('admin')->user()->role_id , $type='edit'))){
        return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.blockes.index'));
    }




    $blockes = Blocks::find($id);
    if(empty($blockes)){
        return redirect($this->ADMIN_ROUTE_NAME.'/blockes');
    }
}

if($request->method() == 'POST' || $request->method() == 'post'){

    if(empty($back_url)){
        $back_url = $this->ADMIN_ROUTE_NAME.'/blockes';
    }

    $name = (isset($request->name))?$request->name:'';


    $rules = [];

    $rules['name'] = 'required';
    $rules['society_id'] = 'required';




    $this->validate($request, $rules);

    $createdCat = $this->save($request, $id);

    if ($createdCat) {
        $alert_msg = 'Blockes has been added successfully.';
        if(is_numeric($id) && $id > 0){
            $alert_msg = 'Blockes has been updated successfully.';
        }
        return redirect(url($back_url))->with('alert-success', $alert_msg);
    } else {
        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
    }
}


$page_heading = 'Add Block';

if(isset($blockes->title)){
    $blockes_name = $blockes->title;
    $page_heading = 'Update Blocke - '.$blockes_name;
}  

$data['page_heading'] = $page_heading;
$data['id'] = $id;
$data['blockes'] = $blockes;

$data['societies'] = Society::where('status',1)->get();

return view('admin.complaints.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image']);

        //prd($request->toArray());

    if($id == 0){

        $data['added_by'] = Auth::guard('admin')->user()->id; 

    }



    $oldImg = '';

    $blockes = new Blocks;

    if(is_numeric($id) && $id > 0){
        $exist = Blocks::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $blockes = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $blockes->$key = $val;
    }

    $isSaved = $blockes->save();

    if($isSaved){
        $this->saveImage($request, $blockes, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $blockes, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'complaints/';
        $thumb_path = 'complaints/thumb/';
        $storage = Storage::disk('public');
            //prd($storage);
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

           // prd($uploaded_data);
        if($uploaded_data['success']){

            if(!empty($oldImg)){
                if($storage->exists($path.$oldImg)){
                    $storage->delete($path.$oldImg);
                }
                if($storage->exists($thumb_path.$oldImg)){
                    $storage->delete($thumb_path.$oldImg);
                }
            }
            $image = $uploaded_data['file_name'];
            $blockes->image = $image;
            $blockes->save();         
        }

        if(!empty($uploaded_data)){   
            return $uploaded_data;
        }  

    }

}




public function delete(Request $request){

        //prd($request->toArray());

    $id = (isset($request->id))?$request->id:0;

    $is_delete = '';

    if(is_numeric($id) && $id > 0){
        $is_delete = Blocks::where('id', $id)->delete();
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Blockes has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_complaint_status(Request $request){
  $com_id = isset($request->com_id) ? $request->com_id :'';
  $status = isset($request->status) ? $request->status :'';

  $complaints = Complaints::where('id',$com_id)->first();
  if(!empty($complaints)){

   Complaints::where('id',$com_id)->update(['status'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'No Block FOund';
   return response()->json($response);
}


}






public function remark_submit(Request $request){
  $comment = isset($request->comment) ? $request->comment :'';
  $id = isset($request->id) ? $request->id :'';

  $complaints = Complaints::where('id',$id)->where('status','!=','completed')->first();
  if(!empty($complaints)){
   Complaints::where('id',$id)->update(['comments'=>$comment]);
   $response['success'] = true;
   $response['message'] = 'Updated Successfully';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Complaints Already Completed';
   return response()->json($response);
}
}


}