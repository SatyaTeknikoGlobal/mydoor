<?php
namespace App\Http\Controllers\Sadmin;

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
use App\Vendor;
use App\State;
use App\City;

use Storage;
use DB;
use Hash;





Class HomeController extends Controller
{

	public function index(Request $request){
		$data = [];
        return view('sadmin.home.index',$data);
    }

    public function profile(Request $request){

       
      $data = [];
      $method = $request->method();
      $user = Auth::guard('merchant')->user();


      if($method == 'post' || $method == 'POST'){
        //  $request->validate([
        //     'name' => 'required',
        //     'email' => 'required',
        //    'state_id'=>'required',
        //    'city_id'=>'required',
        //     'phone' => 'required',
          
        // ]);





         $business_name = isset($request->business_name) ? $request->business_name : '';
         $email = isset($request->email) ? $request->email : '';
         $state_id = isset($request->state_id) ? $request->state_id : '';
         $city_id = isset($request->city_id) ? $request->city_id : '';
         $phone = isset($request->phone) ? $request->phone : '';
       


         $dbArray = [];
         $dbArray['business_name'] = $business_name; 
         $dbArray['email'] = $email; 
         $dbArray['state_id'] = $state_id; 
         $dbArray['city_id'] = $city_id; 
         $dbArray['phone'] = $phone; 




         $result = Vendor::where('id',$user->id)->update($dbArray);
         if($result){

           if($request->hasFile('file')) {
            $file = $request->file('file');
            $image_result = $this->saveImage($file,$user->id,'file');
            if($image_result['success'] == false){     
                session()->flash('alert-danger', 'Image could not be added');
            }
        }


        return back()->with('alert-success','Profile Updated Successfully');
    }else{
        return back()->with('alert-danger','Something Went Wrong');

    }
}

$data['breadcum'] = 'Update Profile';
$data['title'] = 'Update Profile';


 $user = Vendor::where('id',$user->id)->first();

$data['states'] = State::get();
$data['cities'] = City::get();


$data['user'] = $user;
return view('merchant.profile.index',$data);
}




private function saveImage($file, $id,$type){
        // prd($file); 
        //echo $type; die;

    $result['org_name'] = '';
    $result['file_name'] = '';

    if ($file) 
    {
        $path = 'user/';
        $thumb_path = 'user/thumb/';
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
        if($uploaded_data['success']){
            $new_image = $uploaded_data['file_name'];

            if(is_numeric($id) && $id > 0){
                $user = Vendor::find($id);

                if(!empty($user) && $user->id > 0){

                    $storage = Storage::disk('public');

                    if($type == 'file'){
                        $old_image = $user->image;
                        $user->image = url('/public/storage/user/'.$new_image);
                    }

                    $isUpdated = $user->save();

                    if($isUpdated){

                        if(!empty($old_image) && $storage->exists($path.$old_image)){
                            $storage->delete($path.$old_image);
                        }

                        if(!empty($old_image) && $storage->exists($thumb_path.$old_image)){
                            $storage->delete($thumb_path.$old_image);
                        }
                    }
                }


            }
        }

        if(!empty($uploaded_data))
        {   
            return $uploaded_data;
        }
    }
}

public function get_city(Request $request){
    $state_id = isset($request->state_id) ? $request->state_id :0;
    $html = '<option value="" selected disabled>Select City</option>';
    if($state_id !=0){
        $cities = City::where('state_id',$state_id)->get();
        if(!empty($cities)){
            foreach($cities as $city){
                $html.='<option value='.$city->id.'>'.$city->name.'</option>';
            }
        }
    } 
    echo $html;
}
public function change_password(Request $request){
    //prd($request->toArray());
    $data = [];
    $password = isset($request->password) ?  $request->password:'';
    $new_password = isset($request->new_password) ?  $request->new_password:'';
    $method = $request->method();

        //prd($method);
    $auth_user = Auth::guard('merchant')->user();
    $admin_id = $auth_user->id;
    if($method == 'POST' || $method =="post"){
        $post_data = $request->all();
        $rules = [];

        $rules['old_password'] = 'required|min:6|max:20';
        $rules['new_password'] = 'required|min:6|max:20';
        $rules['confirm_password'] = 'required|min:6|max:20|same:new_password';

        $validator = Validator::make($post_data, $rules);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        else{
                //prd($request->all());

            $old_password = $post_data['old_password'];

            $user = Vendor::where(['id'=>$admin_id])->first();

            $existing_password = (isset($user->password))?$user->password:'';

            $hash_chack = Hash::check($old_password, $user->password);

            if($hash_chack){
                $update_data['password']=bcrypt(trim($post_data['new_password']));

                $is_updated = Vendor::where('id', $admin_id)->update($update_data);

                $message = [];

                if($is_updated){

                    $message['alert-success'] = "Password updated successfully.";
                }
                else{
                    $message['alert-danger'] = "something went wrong, please try again later...";
                }

                return back()->with($message);


            }
            else{
                $validator = Validator::make($post_data, []);
                $validator->after(function ($validator) {
                    $validator->errors()->add('old_password', 'Invalid Password!');
                });
                    //prd($validator->errors());
                return back()->withErrors($validator)->withInput();
            }
        }
    }



}

// public function profile(Request $request){
//     $data = [];


//     return view('admin.home.profile',$data);
// }






















}