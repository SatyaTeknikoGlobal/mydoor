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
use App\Flats;
use App\FlatCategory;
use Yajra\DataTables\DataTables;
use Storage;
use DB;
use Hash;



Class FlatController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        // $this->roleId = Auth::guard('admin')->user()->role_id; 

	}



	public function index(Request $request){



        // CustomHelper::isAllowedSection('blockes' , 1 , $type='add');

     if(!(CustomHelper::isAllowedSection('blockes' , Auth::guard('admin')->user()->role_id , $type='show'))){
         return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.blockes.index'));

     }
     $data = [];

     return view('admin.flats.index',$data);
 }

 public function get_flats(Request $request){

  $role_id = Auth::guard('admin')->user()->role_id;

  $routeName = CustomHelper::getAdminRouteName();
  $datas = Flats::orderBy('id','desc');
  if($role_id!=0){
    $datas->where('added_by',Auth::guard('admin')->user()->id);
}


$datas = $datas->get();

return Datatables::of($datas)


->editColumn('id', function(Flats $data) {

   return  $data->id;
})

->editColumn('name', function(Flats $data) {
   return  $data->flat_no;
})

->editColumn('society_id', function(Flats $data) {

    $society = Society::where('id',$data->society_id)->first();
    return  $society->name;
})

->editColumn('block_id', function(Flats $data) {

    $block = Blocks::where('id',$data->block_id)->first();
    return  $block->name ??'';
})

->editColumn('added_by', function(Flats $data) {

    $user = Admin::where('id',$data->added_by)->first();
    return  isset($user->name) ? $user->name :'';
})

->editColumn('status', function(Flats $data) {
   $sta = '';
   $sta1 ='';
   if($data->status == 1){
    $sta1 = 'selected';
}else{
    $sta = 'selected';
}

$html = "<select id='change_flat_status$data->id' onchange='change_flat_status($data->id)'>
<option value='1' ".$sta1.">Active</option>
<option value='0' ".$sta.">InActive</option>
</select>";





return  $html;
})

->editColumn('created_at', function(Flats $data) {
   return  date('d M Y',strtotime($data->created_at));
})

->addColumn('action', function(Flats $data) {
   $routeName = CustomHelper::getAdminRouteName();

   $BackUrl = $routeName.'/flats';
   $html = '';
   if(CustomHelper::isAllowedSection('flats' , Auth::guard('admin')->user()->role_id , $type='edit')){
    $html.='<a title="Edit" href="' . route($routeName.'.flats.edit',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;';
}   




return $html;
})

->rawColumns(['name', 'status', 'action'])
->toJson();
}




public function add(Request $request){


    $data = [];

    $id = (isset($request->id))?$request->id:0;

    if(!(CustomHelper::isAllowedSection('flats' , Auth::guard('admin')->user()->role_id , $type='add'))){
     return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.flats.index'));

 }





 $flats = '';
 if(is_numeric($id) && $id > 0){

    if(!(CustomHelper::isAllowedSection('flats' , Auth::guard('admin')->user()->role_id , $type='edit'))){
        return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.flats.index'));
    }




    $flats = Flats::find($id);
    if(empty($flats)){
        return redirect($this->ADMIN_ROUTE_NAME.'/flats');
    }
}

if($request->method() == 'POST' || $request->method() == 'post'){

    if(empty($back_url)){
        $back_url = $this->ADMIN_ROUTE_NAME.'/flats';
    }

    $name = (isset($request->name))?$request->name:'';


    $rules = [];

    $rules['flat_no'] = 'required';
    $rules['society_id'] = 'required';
    $rules['block_id'] = 'required';




    $this->validate($request, $rules);

    $createdCat = $this->save($request, $id);

    if ($createdCat) {
        $alert_msg = 'Flats has been added successfully.';
        if(is_numeric($id) && $id > 0){
            $alert_msg = 'Flats has been updated successfully.';
        }
        return redirect(url($back_url))->with('alert-success', $alert_msg);
    } else {
        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
    }
}


$page_heading = 'Add Flat';

if(isset($flats->flat_no)){
    $flats_flat_no = $flats->flat_no;
    $page_heading = 'Update Flat - '.$flats_flat_no;
}  

$data['page_heading'] = $page_heading;
$data['id'] = $id;
$data['flats'] = $flats;

$data['societies'] = Society::where('status',1)->get();

$role_id = Auth::guard('admin')->user()->role_id;
$admin_society_id = Auth::guard('admin')->user()->society_id;
if($role_id == 0){
if(is_numeric($id) && $id > 0){

    $data['blocks'] = Blocks::where('society_id',$flats->society_id)->where('status',1)->get();
}
}
else{
    $data['blocks'] = Blocks::where('society_id',$admin_society_id)->where('status',1)->get();
    
}


return view('admin.flats.form', $data);

}


public function get_blocks_from_society(Request $request){
    $society_id = isset($request->society_id) ? $request->society_id :0;
    $html = '<option value="" selected disabled>Select Block</option>';
    if($society_id != 0){
        $blocks = Blocks::where('society_id',$society_id)->where('status',1)->get();
        if(!empty($blocks)){
            foreach($blocks as $block){
                $html.='<option value='.$block->id.'>'.$block->name.'</option>';
            }
        }

    }
    echo $html;
}
public function get_flat_cat_from_society(Request $request){
    $society_id = isset($request->society_id) ? $request->society_id :0;
    $html = '<option value="" selected disabled>Select Flat Category</option>';
    if($society_id != 0){
        $blocks = FlatCategory::where('society_id',$society_id)->where('status',1)->get();
        if(!empty($blocks)){
            foreach($blocks as $block){
                $html.='<option value='.$block->id.'>'.$block->name.'</option>';
            }
        }

    }
    echo $html;
}


public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image']);

        //prd($request->toArray());

    if($id == 0){

        $data['added_by'] = Auth::guard('admin')->user()->id; 

    }



    $oldImg = '';

    $flats = new Flats;

    if(is_numeric($id) && $id > 0){
        $exist = Flats::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $flats = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $flats->$key = $val;
    }

    $isSaved = $flats->save();

    if($isSaved){
        $this->saveImage($request, $flats, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $blockes, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'blockes/';
        $thumb_path = 'blockes/thumb/';
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
        $is_delete = Flats::where('id', $id)->update(['is_delete'=>1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Flats has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_flat_status(Request $request){
  $flat_id = isset($request->flat_id) ? $request->flat_id :'';
  $status = isset($request->status) ? $request->status :'';

  $flats = Flats::where('id',$flat_id)->first();
  if(!empty($flats)){

     Flats::where('id',$flat_id)->update(['status'=>$status]);
     $response['success'] = true;
     $response['message'] = 'Status updated';


     return response()->json($response);
 }else{
     $response['success'] = false;
     $response['message'] = 'No Flat FOund';
     return response()->json($response);
 }


}


}