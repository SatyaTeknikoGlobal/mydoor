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
use App\State;
use App\City;
use App\SocietyUsers;
use App\UserVehicle;
use App\Billing;
use App\UserDailyHelp;
use Yajra\DataTables\DataTables;
use Storage;
use DB;
use Hash;



Class FlatOwnerController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){
		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
	}



	public function index(Request $request){



        // CustomHelper::isAllowedSection('blockes' , 1 , $type='add');

       if(!(CustomHelper::isAllowedSection('flatowners' , Auth::guard('admin')->user()->role_id , $type='show'))){
           return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.flatowners.index'));

       }
       $data = [];

       return view('admin.flatowners.index',$data);
   }

   public function get_flatowners(Request $request){

      $role_id = Auth::guard('admin')->user()->role_id;

      $routeName = CustomHelper::getAdminRouteName();
      $datas = SocietyUsers::where('parent',0)->orderBy('id','desc');
      if($role_id!=0){
        $datas->where('added_by',Auth::guard('admin')->user()->id);
    }


    $datas = $datas->get();

    return Datatables::of($datas)


    ->editColumn('id', function(SocietyUsers $data) {

     return  $data->id;
 })

    ->editColumn('name', function(SocietyUsers $data) {

     return  $data->name ??'';
 })

    ->editColumn('phone', function(SocietyUsers $data) {

     return  $data->phone ??'';
 })


    ->editColumn('society_id', function(SocietyUsers $data) {

        $society = Society::where('id',$data->society_id)->first();
        return  $society->name ??'';
    })

    ->editColumn('block_id', function(SocietyUsers $data) {

        $block = Blocks::where('id',$data->block_id)->first();
        return  $block->name ??'';
    })

    ->editColumn('flat_no', function(SocietyUsers $data) {
     $flats = Flats::where('id',$data->flat_no)->first();
     return  $flats->flat_no ??'';
 })


    ->editColumn('added_by', function(SocietyUsers $data) {

        $user = Admin::where('id',$data->added_by)->first();
        return  isset($user->name) ? $user->name :'';
    })
    ->editColumn('is_approve', function(SocietyUsers $data) {
     $sta = '';
     $sta1 ='';
     if($data->is_approve == 1){
        $sta1 = 'selected';
    }else{
        $sta = 'selected';
    }

    $html = "<select id='change_flatowner_approve$data->id' onchange='change_flatowner_approve($data->id)'>
    <option value='1' ".$sta1.">Approved</option>
    <option value='0' ".$sta.">Not Approved</option>
    </select>";





    return  $html;
})

    ->editColumn('status', function(SocietyUsers $data) {
     $sta = '';
     $sta1 ='';
     if($data->status == 1){
        $sta1 = 'selected';
    }else{
        $sta = 'selected';
    }

    $html = "<select id='change_flatowner_status$data->id' onchange='change_flatowner_status($data->id)'>
    <option value='1' ".$sta1.">Active</option>
    <option value='0' ".$sta.">InActive</option>
    </select>";





    return  $html;
})

    ->editColumn('created_at', function(SocietyUsers $data) {
     return  date('d M Y',strtotime($data->created_at));
 })

    ->addColumn('action', function(SocietyUsers $data) {
     $routeName = CustomHelper::getAdminRouteName();

     $BackUrl = $routeName.'/flatowners';
     $html = '';
     if(CustomHelper::isAllowedSection('flatowners' , Auth::guard('admin')->user()->role_id , $type='edit')){
        $html.='<a class="btn btn-primary btn-sm btn-block" title="Edit" href="' . route($routeName.'.flatowners.edit',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a><a title="Edit" class="btn btn-danger btn-sm btn-block" href="' . route($routeName.'.flatowners.family_member',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-user">Details</i></a>';
    }   




    return $html;
})

    ->rawColumns(['name', 'status', 'action','is_approve'])
    ->toJson();
}

public function child_users(Request $request){
   $data = [];

   $id = (isset($request->user_id))?$request->user_id:0;
   if($id==0 || empty($id)){
       return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.flatowners.index'));
   }
   $condicion['parent']=$id;
   $data['societyusers_child'] = SocietyUsers::orderBy('id','desc')->where($condicion)->get();
   $data['user_id'] = $id;
   return view('admin.flatowners.child_users_list',$data);

}
public function child_user_add(Request $request){
    $user_id = (isset($request->user_id))?$request->user_id:'';
    $id = (isset($request->id))?$request->id:'';

    if($request->method() == 'POST' || $request->method() == 'post'){
     if(empty($back_url)){
        $back_url = $this->ADMIN_ROUTE_NAME.'/flatowners/child_user/'.$user_id;
    }
    $rules = [];

    $rules['name'] = 'required';
    $rules['phone'] = 'required';
    $rules['email'] = 'required';
    $rules['location'] = 'required';
    $rules['gender'] = 'required';
    $rules['status'] = 'required';

    $this->validate($request, $rules);
    $SocietyUsers = SocietyUsers::orderBy('id','desc')->where('id',$user_id)->first();
    $save_data = array(
        'name'    =>$request->name,
        'phone'   =>$request->phone,
        'email'   =>$request->email,
        'location'=>$request->location,
        'gender'  =>$request->gender,
        'status'  =>$request->status,
        'user_type'  =>$request->user_type,

        'society_id'  =>$SocietyUsers->society_id,
        'block_id'    =>$SocietyUsers->block_id,
        'flat_no'     =>$SocietyUsers->flat_no,
        'is_approve'  =>$SocietyUsers->is_approve,
        'parent'      =>$SocietyUsers->id,
        'state_id'    =>$SocietyUsers->state_id,
        'city_id'     =>$SocietyUsers->city_id,
    );
    if(is_numeric($id) && $id > 0){
        $isSaved = DB::table('societyusers') ->where('id', $id)->update($save_data);
    }else{
        $isSaved = SocietyUsers::insert($save_data);
    }
    if ($isSaved) {
        $alert_msg = 'User has been added successfully.';
        if(is_numeric($id) && $id > 0){
            $alert_msg = 'User has been updated successfully.';
        }
        return redirect(url($back_url))->with('alert-success', $alert_msg);
    } else {
        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
    }
}

$data = [];
$condicion['id']=$id;
$data['societyusers_child'] = SocietyUsers::orderBy('id','desc')->where($condicion)->first();
$data['id'] = $id;
$data['user_id'] = $user_id;
if(is_numeric($id) && $id > 0){
    $data['page_heading'] = 'Edit Child User';
}else{
 $data['page_heading'] = 'Add Child User';
}
return view('admin.flatowners.child_user_add',$data);
}

// public function child_user_save(Request $request){
//     $data = [];

//     $id = (isset($request->id))?$request->id:0;
//     $user_id = (isset($request->user_id))?$request->user_id:0;

//     if($request->method() == 'POST' || $request->method() == 'post'){

//         if(empty($back_url)){
//             $back_url = $this->ADMIN_ROUTE_NAME.'/flatowners';
//         }

//         $name = (isset($request->name))?$request->name:'';


//         $rules = [];

//         $rules['flat_no'] = 'required';
//         $rules['society_id'] = 'required';
//         $rules['block_id'] = 'required';
//         $rules['phone'] = 'required';
//         $rules['state_id'] = 'required';
//         $rules['city_id'] = 'required';




//         $this->validate($request, $rules);

//         $createdCat = $this->save($request, $id);

//         if ($createdCat) {
//             $alert_msg = 'Flats has been added successfully.';
//             if(is_numeric($id) && $id > 0){
//                 $alert_msg = 'Flats has been updated successfully.';
//             }
//             return redirect(url($back_url))->with('alert-success', $alert_msg);
//         } else {
//             return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
//         }
//     }
// }



public function details(Request $request){

    $data = [];

    $user_id = isset($request->user_id) ? $request->user_id :'';
    $users = SocietyUsers::where('parent',$user_id)->get();

    $data['users'] = $users;

    $societyuser = SocietyUsers::where('id',$user_id)->first();



    $data['societyuser'] = $societyuser;
    $data['user_id'] = $user_id;
    $velhicles = [];

    $daily_helps = UserDailyHelp::where('flat_id',$societyuser->flat_no)->get();


    $data['daily_helps'] = $daily_helps;


    $velhicles = UserVehicle::where('flat_id',$societyuser->flat_no)->get();
    $data['velhicles'] = $velhicles;


     $bills = Billing::where('flat_id',$societyuser->flat_no)->where('user_id',$societyuser->id)->get();
    $data['bills'] = $bills;

    return view('admin.flatowners.family_members',$data);

}

public function add_bill(Request $request){
    if($request->method() == 'POST' || $request->method() == 'post'){
        $BackUrl = 'admin/flatowners';
   
    $rules = [];

    $rules['flat_id'] = 'required';
    $rules['month'] = 'required';
    $rules['user_id'] = 'required';
    $rules['type'] = 'required';
    $rules['year'] = 'required';
    $rules['cost'] = 'required';
    $this->validate($request, $rules);

     $dbArray = [];
    $dbArray['month'] = $request->month;
    $dbArray['year'] = $request->year;
    $dbArray['user_id'] = $request->user_id;
    $dbArray['flat_id'] = $request->flat_id;
    $dbArray['status'] = 'pending';
    $dbArray['type'] = $request->type;
    $dbArray['cost'] = $request->cost;
    Billing::insert($dbArray);
   return redirect()->to(route('admin.flatowners.family_member',$request->user_id.'?back_url='.$BackUrl));
}
}
















public function add(Request $request){
    $data = [];

    $id = (isset($request->id))?$request->id:0;

    if(!(CustomHelper::isAllowedSection('flatowners' , Auth::guard('admin')->user()->role_id , $type='add'))){
       return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.flatowners.index'));

   }





   $flatowners = '';
   if(is_numeric($id) && $id > 0){

    if(!(CustomHelper::isAllowedSection('flatowners' , Auth::guard('admin')->user()->role_id , $type='edit'))){
        return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.flatowners.index'));
    }




    $flatowners = SocietyUsers::find($id);
    if(empty($flatowners)){
        return redirect($this->ADMIN_ROUTE_NAME.'/flatowners');
    }
}

if($request->method() == 'POST' || $request->method() == 'post'){

    if(empty($back_url)){
        $back_url = $this->ADMIN_ROUTE_NAME.'/flatowners';
    }

    $name = (isset($request->name))?$request->name:'';


    $rules = [];

    $rules['flat_no'] = 'required';
    $rules['society_id'] = 'required';
    $rules['block_id'] = 'required';
    $rules['phone'] = 'required';
    $rules['state_id'] = 'required';
    $rules['city_id'] = 'required';




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


$page_heading = 'Add Flat Owner';

if(isset($flatowners->name)){
    $name = $flatowners->name;
    $page_heading = 'Update Flat Owner - '.$name;
}  

$data['page_heading'] = $page_heading;
$data['id'] = $id;
$data['flatowners'] = $flatowners;

$data['societies'] = Society::where('status',1)->get();
$data['states'] = State::where('status',1)->get();

if(is_numeric($id) && $id > 0){
    $data['blocks'] = Blocks::where('society_id',$flatowners->society_id)->where('status',1)->get();
    $data['flats'] = Flats::where('block_id',$flatowners->block_id)->where('status',1)->get();
    $data['cities'] = City::where('state_id',$flatowners->state_id)->where('status',1)->get();

}

return view('admin.flatowners.form', $data);

}


public function get_flats_from_block(Request $request){
    $block_id = isset($request->block_id) ? $request->block_id :0;
    $html = '<option value="" selected disabled>Select Flat No</option>';
    if($block_id != 0){
        $flats = Flats::where('block_id',$block_id)->where('status',1)->get();
        if(!empty($flats)){
            foreach($flats as $flat){
                $html.='<option value='.$flat->id.'>'.$flat->flat_no.'</option>';
            }
        }

    }
    echo $html;
}



public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image']);

        //prd($request->toArray());
    $data['user_type'] = 'owner';
    if($id == 0){

        $data['added_by'] = Auth::guard('admin')->user()->id; 

    }



    $oldImg = '';

    $users = new SocietyUsers;

    if(is_numeric($id) && $id > 0){
        $exist = SocietyUsers::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $users = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $users->$key = $val;
    }

    $isSaved = $users->save();

    if($isSaved){
        $this->saveImage($request, $users, $oldImg);
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
        $is_delete = SocietyUsers::where('id', $id)->update(['is_delete'=>1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'SocietyUsers has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_flatowner_status(Request $request){
  $user_id = isset($request->user_id) ? $request->user_id :'';
  $status = isset($request->status) ? $request->status :'';

  $users = SocietyUsers::where('id',$user_id)->first();
  if(!empty($users)){

   SocietyUsers::where('id',$user_id)->update(['status'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'No Owner FOund';
   return response()->json($response);
}


}


public function update_parking(Request $request){
  $velhicle_id = isset($request->velhicle_id) ? $request->velhicle_id :'';
  $parking_no = isset($request->parking_no) ? $request->parking_no :'';

  $vehicle = UserVehicle::where('id',$velhicle_id)->first();
  if(!empty($vehicle)){

   UserVehicle::where('id',$velhicle_id)->update(['parking_no'=>$parking_no]);
   $response['success'] = true;
   $response['message'] = 'Parking updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Not Found';
   return response()->json($response);
}

}



public function change_vehicle_status(Request $request){
  $velhicle_id = isset($request->velhicle_id) ? $request->velhicle_id :'';
  $status = isset($request->status) ? $request->status :'';

  $users = UserVehicle::where('id',$velhicle_id)->first();
  if(!empty($users)){

   UserVehicle::where('id',$velhicle_id)->update(['status'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'No Owner FOund';
   return response()->json($response);
}


}



public function change_daily_help_status(Request $request){
  $help_id = isset($request->help_id) ? $request->help_id :'';
  $status = isset($request->status) ? $request->status :'';

  $help = UserDailyHelp::where('id',$help_id)->first();
  if(!empty($help)){

   UserDailyHelp::where('id',$help_id)->update(['status'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'No Owner FOund';
   return response()->json($response);
}


}






public function change_flatowner_approve(Request $request){
  $user_id = isset($request->user_id) ? $request->user_id :'';
  $status = isset($request->status) ? $request->status :'';

  $users = SocietyUsers::where('id',$user_id)->first();
  if(!empty($users)){

   SocietyUsers::where('id',$user_id)->update(['is_approve'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'No Owner FOund';
   return response()->json($response);
}

}





}