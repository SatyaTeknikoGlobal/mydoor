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
use App\Roles;
use App\Services;
use App\ServiceUser;
use App\SocietyDocument;
use Yajra\DataTables\DataTables;
use Storage;
use DB;
use Hash;



Class ServiceUserController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

	}



	public function index(Request $request){
     if(!(CustomHelper::isAllowedSection('service_user' , Auth::guard('admin')->user()->role_id , $type='show'))){
         return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.service_user.index'));

     }


     $data =[];
     return view('admin.service_user.index',$data);
 }

 public function get_service_users(Request $request){
   // prd($request->toArray());
  $role_id = Auth::guard('admin')->user()->role_id;

  $routeName = CustomHelper::getAdminRouteName();



  $datas = ServiceUser::where('is_delete',0)->orderBy('id','desc');

  $datas = $datas->get();



  return Datatables::of($datas)


  ->editColumn('id', function(ServiceUser $data) {

   return  $data->id;
})

  ->editColumn('society_id', function(ServiceUser $data) {

    $society = Society::where('id',$data->society_id)->first();
    return  $society->name ?? '';
})

  ->editColumn('service_id', function(ServiceUser $data) {

    $service = Services::where('id',$data->service_id)->first();
    return  $service->name ?? '';
})


  ->editColumn('phone', function(ServiceUser $data) {
   return  $data->phone;
})
  ->editColumn('image', function(ServiceUser $data) {
    $html ='';
    $image = isset($data->image) ? $data->image :'';
    $storage = Storage::disk('public');
    $path = 'service_user/';
     if(!empty($image)){
        if($storage->exists($path.$image)){
            $html.="<a href='/storage/app/public/$path/thumb/$image' target='_blank'><img src='/storage/app/public/$path/thumb/$image' style='width:70px;'></a>";

        }}


    return  $html;


})

  ->editColumn('id_proof', function(ServiceUser $data) {

     $html ='';
    $id_proof = isset($data->id_proof) ? $data->id_proof :'';
    $storage = Storage::disk('public');
    $path = 'service_user/';
     if(!empty($id_proof)){
        if($storage->exists($path.$id_proof)){
            $html.="<a href='/storage/app/public/$path/thumb/$id_proof' target='_blank'><img src='/storage/app/public/$path/thumb/$id_proof' style='width:70px;'></a>";

        }}


    return  $html;


})
  ->editColumn('status', function(ServiceUser $data) {
   $sta = '';
   $sta1 ='';
   if($data->status == 1){
    $sta1 = 'selected';
}else{
    $sta = 'selected';
}

$html = "<select id='change_status$data->id' onchange='change_status($data->id)'>
<option value='1' ".$sta1.">Active</option>
<option value='0' ".$sta.">InActive</option>
</select>";





return  $html;
})

  ->editColumn('created_at', function(ServiceUser $data) {
   return  date('d M Y',strtotime($data->created_at));
})

  ->addColumn('action', function(ServiceUser $data) {
   $routeName = CustomHelper::getAdminRouteName();

   $BackUrl = $routeName.'/service_user';
   $html = '';


   if(CustomHelper::isAllowedSection('service_user' , Auth::guard('admin')->user()->role_id , $type='edit')){
    $html.='<a title="Edit" href="' . route($routeName.'.service_user.edit',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;';
}   

if(CustomHelper::isAllowedSection('service_user' , Auth::guard('admin')->user()->role_id , $type='delete')){
            // $html.='<a title="Edit" href="' . route($routeName.'.service_user.delete',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-trash">Delete</i></a>&nbsp;&nbsp;&nbsp;';
}  



return $html;
})

  ->rawColumns(['name', 'status','image', 'action','id_proof'])
  ->toJson();
}




public function add(Request $request){
    $data = [];
    $id = (isset($request->id))?$request->id:0;
    if(!(CustomHelper::isAllowedSection('service_user' , Auth::guard('admin')->user()->role_id , $type='add'))){
     return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.service_user.index'));

 }


 $service_user = '';
 if(is_numeric($id) && $id > 0){

    if(!(CustomHelper::isAllowedSection('service_user' , Auth::guard('admin')->user()->role_id , $type='edit'))){
        return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.service_user.index'));
    }

    $service_user = ServiceUser::find($id);
    if(empty($service_user)){
        return redirect($this->ADMIN_ROUTE_NAME.'/service_user');
    }
}

if($request->method() == 'POST' || $request->method() == 'post'){

    if(empty($back_url)){
        $back_url = $this->ADMIN_ROUTE_NAME.'/service_user';
    }

    $name = (isset($request->name))?$request->name:'';


    $rules = [];
   
       $rules['name'] = 'required';
       $rules['phone'] = 'required';
       $rules['email'] = 'required';
       $rules['society_id'] = 'required';
       $rules['service_id'] = 'required';

       



  $this->validate($request, $rules);

  $createdCat = $this->save($request, $id);

  if ($createdCat) {
    $alert_msg = 'Service User has been added successfully.';
    if(is_numeric($id) && $id > 0){
        $alert_msg = 'Service User has been updated successfully.';
    }
    return redirect(url($back_url))->with('alert-success', $alert_msg);
} else {
    return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
}
}


$page_heading = 'Add Service User';

if(isset($service_user->name)){
    $service_user_name = $service_user->name;
    $page_heading = 'Update Service User - '.$service_user_name;
}  

$data['page_heading'] = $page_heading;
$data['id'] = $id;
$data['service_user'] = $service_user;

$data['societies'] = Society::where('status',1)->get();

$data['services'] = Services::where('status',1)->get();



return view('admin.service_user.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image','password','id_proof']);

        //prd($request->toArray());

    // if($id == 0){
    //     $data['added_by'] = Auth::guard('admin')->user()->id; 
    // }

    // if(!empty($request->password)){
    //     $data['password'] = bcrypt($request->password);
    // }

    $oldImg = '';
    $oldImg2 = '';


    $users = new ServiceUser;

    if(is_numeric($id) && $id > 0){
        $exist = ServiceUser::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $users = $exist;

            $oldImg = $exist->image;
            $oldImg2 = $exist->id_proof;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $users->$key = $val;
    }

    $isSaved = $users->save();

    if($isSaved){
        $this->saveImage($request, $users, $oldImg,$oldImg2);
    }

    return $isSaved;
}


private function saveImage($request, $users, $oldImg='',$oldImg2 = ''){

    $file = $request->file('image');
    $file2 = $request->file('id_proof');


    if ($file) {
        $path = 'service_user/';
        $thumb_path = 'service_user/thumb/';
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
            $users->image = $image;
            $users->save();         
        }

    }

    if($file2){
         $path = 'service_user/';
        $thumb_path = 'service_user/thumb/';
        $storage = Storage::disk('public');
            //prd($storage);
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadImage($file2, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

           // prd($uploaded_data);
        if($uploaded_data['success']){

            if(!empty($oldImg2)){
                if($storage->exists($path.$oldImg2)){
                    $storage->delete($path.$oldImg2);
                }
                if($storage->exists($thumb_path.$oldImg2)){
                    $storage->delete($thumb_path.$oldImg2);
                }
            }
            $image = $uploaded_data['file_name'];
            $users->id_proof = $image;
            $users->save();         
        }

    }

  //if(!empty($uploaded_data)){   
            //return $uploaded_data;
//}  

    return true;

}




public function delete(Request $request){

        //prd($request->toArray());

    $id = (isset($request->id))?$request->id:0;

    $is_delete = '';

    if(is_numeric($id) && $id > 0){
        $is_delete = SocietyUser::where('id', $id)->update(['is_delete',1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'SocietyUser has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_status(Request $request){
  $user_id = isset($request->user_id) ? $request->user_id :'';
  $status = isset($request->status) ? $request->status :'';

  $users = ServiceUser::where('id',$user_id)->first();
  if(!empty($users)){

     ServiceUser::where('id',$user_id)->update(['status'=>$status]);
     $response['success'] = true;
     $response['message'] = 'Status updated';


     return response()->json($response);
 }else{
     $response['success'] = false;
     $response['message'] = 'Not  Found';
     return response()->json($response);  
 }

}







}