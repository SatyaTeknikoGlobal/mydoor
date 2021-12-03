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
use App\Society;
use App\Blocks;
use App\State;
use App\City;
use App\SocietyDocument;
use Yajra\DataTables\DataTables;
use Storage;
use DB;
use Hash;



Class SocietyController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

	}



	public function index(Request $request){
     if(!(CustomHelper::isAllowedSection('society' , Auth::guard('admin')->user()->role_id , $type='show'))){
         return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.society.index'));

     }


     $data =[];
     return view('admin.society.index',$data);
 }

 public function get_society(Request $request){
  $role_id = Auth::guard('admin')->user()->role_id;

  $routeName = CustomHelper::getAdminRouteName();
  $datas = Society::where('is_delete',0)->orderBy('id','desc');
  if($role_id!=0){
    $datas->where('added_by',Auth::guard('admin')->user()->id);
}


$datas = $datas->get();



return Datatables::of($datas)


->editColumn('id', function(Society $data) {

   return  $data->id;
})
->editColumn('name', function(Society $data) {
   return  $data->name;
})
->editColumn('location', function(Society $data) {
   return  $data->location;
})

->editColumn('state', function(Society $data) {
    $state = State::where('id',$data->state_id)->first();
    return  $state->name ?? '';
})

->editColumn('city', function(Society $data) {
    $city = City::where('id',$data->city_id)->first();

    return  $city->name ?? '';
})
->editColumn('status', function(Society $data) {
   $sta = '';
   $sta1 ='';
   if($data->status == 1){
    $sta1 = 'selected';
}else{
    $sta = 'selected';
}

$html = "<select id='change_society_status$data->id' onchange='change_society_status($data->id)'>
<option value='1' ".$sta1.">Active</option>
<option value='0' ".$sta.">InActive</option>
</select>";





return  $html;
})

->editColumn('created_at', function(Society $data) {
   return  date('d M Y',strtotime($data->created_at));
})

->addColumn('action', function(Society $data) {
   $routeName = CustomHelper::getAdminRouteName();

   $BackUrl = $routeName.'/society';
   $html = '<a title="Documents" href="' . route($routeName.'.society.documents',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-file">File</i></a>&nbsp;&nbsp;&nbsp;';


   if(CustomHelper::isAllowedSection('society' , Auth::guard('admin')->user()->role_id , $type='edit')){
    $html.='<a title="Edit" href="' . route($routeName.'.society.edit',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;';
}   

if(CustomHelper::isAllowedSection('society' , Auth::guard('admin')->user()->role_id , $type='delete')){
            // $html.='<a title="Edit" href="' . route($routeName.'.society.delete',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-trash">Delete</i></a>&nbsp;&nbsp;&nbsp;';
}  



return $html;
})

->rawColumns(['name', 'status', 'action'])
->toJson();
}




public function add(Request $request){


    $data = [];

    $id = (isset($request->id))?$request->id:0;

    if(!(CustomHelper::isAllowedSection('society' , Auth::guard('admin')->user()->role_id , $type='add'))){
     return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.society.index'));

 }





 $society = '';
 if(is_numeric($id) && $id > 0){

    if(!(CustomHelper::isAllowedSection('society' , Auth::guard('admin')->user()->role_id , $type='edit'))){
        return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.society.index'));
    }

    $society = Society::find($id);
    if(empty($society)){
        return redirect($this->ADMIN_ROUTE_NAME.'/society');
    }
}

if($request->method() == 'POST' || $request->method() == 'post'){

    if(empty($back_url)){
        $back_url = $this->ADMIN_ROUTE_NAME.'/society';
    }

    $name = (isset($request->name))?$request->name:'';


    $rules = [];

    $rules['name'] = 'required';
    $rules['state_id'] = 'required';
    $rules['city_id'] = 'required';
    $rules['location'] = 'required';




    $this->validate($request, $rules);

    $createdCat = $this->save($request, $id);

    if ($createdCat) {
        $alert_msg = 'Society has been added successfully.';
        if(is_numeric($id) && $id > 0){
            $alert_msg = 'Society has been updated successfully.';
        }
        return redirect(url($back_url))->with('alert-success', $alert_msg);
    } else {
        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
    }
}


$page_heading = 'Add Society';

if(isset($society->title)){
    $society_name = $society->title;
    $page_heading = 'Update Society - '.$society_name;
}  

$data['page_heading'] = $page_heading;
$data['id'] = $id;
$data['society'] = $society;
$data['states'] = State::get();
$cities = [];
if(is_numeric($id) && $id > 0){

    $cities = City::where('state_id',$society->state_id)->get();
}

$data['cities']= $cities;

return view('admin.society.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image']);

        //prd($request->toArray());

    if($id == 0){

        $data['added_by'] = Auth::guard('admin')->user()->id; 

    }



    $oldImg = '';

    $society = new Society;

    if(is_numeric($id) && $id > 0){
        $exist = Society::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $society = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $society->$key = $val;
    }

    $isSaved = $society->save();

    if($isSaved){
        $this->saveImage($request, $society, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $society, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'society/';
        $thumb_path = 'society/thumb/';
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
            $society->image = $image;
            $society->save();         
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
        $is_delete = Society::where('id', $id)->update(['is_delete',1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Society has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_society_status(Request $request){
  $society_id = isset($request->society_id) ? $request->society_id :'';
  $status = isset($request->status) ? $request->status :'';

  $society = Society::where('id',$society_id)->first();
  if(!empty($society)){

     Society::where('id',$society_id)->update(['status'=>$status]);
     $response['success'] = true;
     $response['message'] = 'Status updated';


     return response()->json($response);
 }else{
     $response['success'] = false;
     $response['message'] = 'No Society FOund';
     return response()->json($response);  
 }

}

public function documents(Request $request){

   $society_id = isset($request->id) ? $request->id :0;
   $method = $request->method();

   $data = [];


   if($method == 'post' || $method == 'POST'){

    $rules = [];
    $rules['file'] = 'required';

    $this->validate($request,$rules);

    if($request->hasFile('file')) {

        $image_result = $this->saveImageMultiple($request,$society_id);
        if($image_result){
            return back()->with('alert-success', 'Image uploaded successfully.');

        }
    }


}

$documents = SocietyDocument::where('society_id',$society_id)->get();

$data['documents'] = $documents;

return view('admin.society.documents',$data);

}
private function saveImageMultiple($request,$society_id){

    $files = $request->file('file');
    $path = 'societydocument/';
    $storage = Storage::disk('public');
            //prd($storage);
    $IMG_WIDTH = 768;
    $IMG_HEIGHT = 768;
    $THUMB_WIDTH = 336;
    $THUMB_HEIGHT = 336;
    $dbArray = [];

    if (!empty($files)) {

        foreach($files as $file){
            $uploaded_data = CustomHelper::UploadFile($file, $path, $ext='');
            if($uploaded_data['success']){
                $image = $uploaded_data['file_name'];
                $dbArray['file'] = $image;
                $dbArray['society_id'] = $society_id;
      
                $success = SocietyDocument::create($dbArray);
            }
        }
        return true;
    }else{
        return false;
    }
}



}