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


use App\Category;
use App\City;
use App\SubCategory;
use App\Blocks;
use App\SocietyUsers;
use App\Flats;
use App\Billing;
use App\FlatCategory;




use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class HomeController extends Controller
{

	public function index(Request $request){
		$data = [];
        $admin_id = Auth::guard('admin')->user()->role_id;
        $society_id = Auth::guard('admin')->user()->society_id;
        $users = SocietyUsers::select('id')->where('status',1)->where('society_id',Auth::guard('admin')->user()->society_id)->where('is_approve',1)->count();
        $societies = Society::select('id')->where('status',1)->where('id',Auth::guard('admin')->user()->society_id)->where('is_delete',0)->count();
        $complaints = DB::table('complaints')->select('id')->where('society_id',Auth::guard('admin')->user()->society_id)->where('status','!=','completed')->count();
        $jan_revenue = 0;
        $feb_revenue = 0;
        $mar_revenue = 0;
        $apr_revenue = 0;
        $may_revenue = 0;
        $june_revenue = 0;
        $july_revenue = 0;
        $aug_revenue = 0;
        $sep_revenue = 0;
        $oct_revenue = 0;
        $nov_revenue = 0;
        $dec_revenue = 0;

        if($admin_id == 0){
            $users = SocietyUsers::select('id')->where('status',1)->where('is_approve',1)->count();
            $societies = Society::select('id')->where('status',1)->where('is_delete',0)->count();
            $complaints = DB::table('complaints')->select('id')->where('status','!=','completed')->count();
            ///////Count Revenue///////////////
            $jan_revenue = Billing::select('cost')->where('year',date('Y'))->where('month',1)->where('status','paid')->sum('cost');
            $feb_revenue = Billing::select('cost')->where('year',date('Y'))->where('month',2)->where('status','paid')->sum('cost');
            $mar_revenue = Billing::select('cost')->where('year',date('Y'))->where('month',3)->where('status','paid')->sum('cost');
            $apr_revenue = Billing::select('cost')->where('year',date('Y'))->where('month',4)->where('status','paid')->sum('cost');
            $may_revenue = Billing::select('cost')->where('year',date('Y'))->where('month',5)->where('status','paid')->sum('cost');
            $june_revenue = Billing::select('cost')->where('year',date('Y'))->where('month',6)->where('status','paid')->sum('cost');
            $july_revenue = Billing::select('cost')->where('year',date('Y'))->where('month',7)->where('status','paid')->sum('cost');
            $aug_revenue = Billing::select('cost')->where('year',date('Y'))->where('month',8)->where('status','paid')->sum('cost');
            $sep_revenue = Billing::select('cost')->where('year',date('Y'))->where('month',9)->where('status','paid')->sum('cost');
            $oct_revenue = Billing::select('cost')->where('year',date('Y'))->where('month',10)->where('status','paid')->sum('cost');
            $nov_revenue = Billing::select('cost')->where('year',date('Y'))->where('month',11)->where('status','paid')->sum('cost');
            $dec_revenue = Billing::select('cost')->where('year',date('Y'))->where('month',12)->where('status','paid')->sum('cost');



            ////////////Count Users
    $jan_user = SocietyUsers::select('id')->whereYear('created_at',date('Y'))->whereMonth('created_at',1)->count();
    $feb_user = SocietyUsers::select('id')->whereYear('created_at',date('Y'))->whereMonth('created_at',2)->count();
    $mar_user = SocietyUsers::select('id')->whereYear('created_at',date('Y'))->whereMonth('created_at',3)->count();
    $apr_user = SocietyUsers::select('id')->whereYear('created_at',date('Y'))->whereMonth('created_at',4)->count();
    $may_user = SocietyUsers::select('id')->whereYear('created_at',date('Y'))->whereMonth('created_at',5)->count();
    $june_user = SocietyUsers::select('id')->whereYear('created_at',date('Y'))->whereMonth('created_at',6)->count();
    $july_user = SocietyUsers::select('id')->whereYear('created_at',date('Y'))->whereMonth('created_at',7)->count();
    $aug_user = SocietyUsers::select('id')->whereYear('created_at',date('Y'))->whereMonth('created_at',8)->count();
    $sep_user = SocietyUsers::select('id')->whereYear('created_at',date('Y'))->whereMonth('created_at',9)->count();
    $oct_user = SocietyUsers::select('id')->whereYear('created_at',date('Y'))->whereMonth('created_at',10)->count();
    $nov_user = SocietyUsers::select('id')->whereYear('created_at',date('Y'))->whereMonth('created_at',11)->count();
    $dec_user = SocietyUsers::select('id')->whereYear('created_at',date('Y'))->whereMonth('created_at',12)->count();









        }else{

            
            $users = SocietyUsers::select('id')->where('society_id',$society_id)->where('status',1)->where('is_approve',1)->count();
            $societies = Society::select('id')->where('status',1)->where('is_delete',0)->count();
            $complaints = DB::table('complaints')->where('society_id',$society_id)->select('id')->where('status','!=','completed')->count();
            ///////Count Revenue///////////////
            $jan_revenue = Billing::select('cost')->where('society_id',$society_id)->where('year',date('Y'))->where('month',1)->where('status','paid')->sum('cost');
            $feb_revenue = Billing::select('cost')->where('society_id',$society_id)->where('year',date('Y'))->where('month',2)->where('status','paid')->sum('cost');
            $mar_revenue = Billing::select('cost')->where('society_id',$society_id)->where('year',date('Y'))->where('month',3)->where('status','paid')->sum('cost');
            $apr_revenue = Billing::select('cost')->where('society_id',$society_id)->where('year',date('Y'))->where('month',4)->where('status','paid')->sum('cost');
            $may_revenue = Billing::select('cost')->where('society_id',$society_id)->where('year',date('Y'))->where('month',5)->where('status','paid')->sum('cost');
            $june_revenue = Billing::select('cost')->where('society_id',$society_id)->where('year',date('Y'))->where('month',6)->where('status','paid')->sum('cost');
            $july_revenue = Billing::select('cost')->where('society_id',$society_id)->where('year',date('Y'))->where('month',7)->where('status','paid')->sum('cost');
            $aug_revenue = Billing::select('cost')->where('society_id',$society_id)->where('year',date('Y'))->where('month',8)->where('status','paid')->sum('cost');
            $sep_revenue = Billing::select('cost')->where('society_id',$society_id)->where('year',date('Y'))->where('month',9)->where('status','paid')->sum('cost');
            $oct_revenue = Billing::select('cost')->where('society_id',$society_id)->where('year',date('Y'))->where('month',10)->where('status','paid')->sum('cost');
            $nov_revenue = Billing::select('cost')->where('society_id',$society_id)->where('year',date('Y'))->where('month',11)->where('status','paid')->sum('cost');
            $dec_revenue = Billing::select('cost')->where('society_id',$society_id)->where('year',date('Y'))->where('month',12)->where('status','paid')->sum('cost');



            ////////////Count Users
    $jan_user = SocietyUsers::select('id')->where('society_id',$society_id)->whereYear('created_at',date('Y'))->whereMonth('created_at',1)->count();
    $feb_user = SocietyUsers::select('id')->where('society_id',$society_id)->whereYear('created_at',date('Y'))->whereMonth('created_at',2)->count();
    $mar_user = SocietyUsers::select('id')->where('society_id',$society_id)->whereYear('created_at',date('Y'))->whereMonth('created_at',3)->count();
    $apr_user = SocietyUsers::select('id')->where('society_id',$society_id)->whereYear('created_at',date('Y'))->whereMonth('created_at',4)->count();
    $may_user = SocietyUsers::select('id')->where('society_id',$society_id)->whereYear('created_at',date('Y'))->whereMonth('created_at',5)->count();
    $june_user = SocietyUsers::select('id')->where('society_id',$society_id)->whereYear('created_at',date('Y'))->whereMonth('created_at',6)->count();
    $july_user = SocietyUsers::select('id')->where('society_id',$society_id)->whereYear('created_at',date('Y'))->whereMonth('created_at',7)->count();
    $aug_user = SocietyUsers::select('id')->where('society_id',$society_id)->whereYear('created_at',date('Y'))->whereMonth('created_at',8)->count();
    $sep_user = SocietyUsers::select('id')->where('society_id',$society_id)->whereYear('created_at',date('Y'))->whereMonth('created_at',9)->count();
    $oct_user = SocietyUsers::select('id')->where('society_id',$society_id)->whereYear('created_at',date('Y'))->whereMonth('created_at',10)->count();
    $nov_user = SocietyUsers::select('id')->where('society_id',$society_id)->whereYear('created_at',date('Y'))->whereMonth('created_at',11)->count();
    $dec_user = SocietyUsers::select('id')->where('society_id',$society_id)->whereYear('created_at',date('Y'))->whereMonth('created_at',12)->count();


        }
        ///////Revenue////////
        $data['jan_revenue'] = $jan_revenue;
        $data['feb_revenue'] = $feb_revenue;
        $data['mar_revenue'] = $mar_revenue;
        $data['apr_revenue'] = $apr_revenue;
        $data['may_revenue'] = $may_revenue;
        $data['june_revenue'] = $june_revenue;
        $data['july_revenue'] = $july_revenue;
        $data['aug_revenue'] = $aug_revenue;
        $data['sep_revenue'] = $sep_revenue;
        $data['oct_revenue'] = $oct_revenue;
        $data['nov_revenue'] = $nov_revenue;
        $data['dec_revenue'] = $dec_revenue;


        //////////////Users//////////////

        $data['jan_user'] = $jan_user;
        $data['feb_user'] = $feb_user;
        $data['mar_user'] = $mar_user;
        $data['apr_user'] = $apr_user;
        $data['may_user'] = $may_user;
        $data['june_user'] = $june_user;
        $data['july_user'] = $july_user;
        $data['aug_user'] = $aug_user;
        $data['sep_user'] = $sep_user;
        $data['oct_user'] = $oct_user;
        $data['nov_user'] = $nov_user;
        $data['dec_user'] = $dec_user;



        $society_revenue = $jan_revenue + $feb_revenue + $mar_revenue + $apr_revenue+ $may_revenue+$june_revenue+$july_revenue+$aug_revenue+ $sep_revenue + $oct_revenue+ $nov_revenue + $dec_revenue;

        $data['usercount'] = $users;
        $data['societiescount'] = $societies;
        $data['society_revenue'] = $society_revenue;
        $data['complaints'] = $complaints;
        return view('admin.home.index',$data);
    }

    public function profile(Request $request){

      $data = [];
      $method = $request->method();
      $user = Auth::guard('admin')->user();

      if($method == 'post' || $method == 'POST'){
         $request->validate([
           // 'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'username' => 'required',
        ]);

         $name = isset($request->name) ? $request->name : '';
         $email = isset($request->email) ? $request->email : '';
         $address = isset($request->address) ? $request->address : '';
         $phone = isset($request->phone) ? $request->phone : '';
         $username = isset($request->username) ? $request->username : '';

         if(!empty($request->name)){
             $dbArray['name'] = $request->name; 
         }
         if(!empty($request->email)){
             $dbArray['email'] = $request->email; 
         }
         if(!empty($request->phone)){
             $dbArray['phone'] = $request->phone; 
         }
         if(!empty($request->username)){
             $dbArray['username'] = $request->username; 
         }


         $result = Admin::where('id',$user->id)->update($dbArray);
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
$data['user'] = $user;
return view('admin.profile.index',$data);
}


public function get_sub_cat(Request $request){
  $cat_id = isset($request->cat_id) ? $request->cat_id : '';
  $html = '<option value="" selected disabled>Select Sub Category</option>';
  if(!empty($cat_id)){
    $subcategories = SubCategory::where('cat_id',$cat_id)->get();
    if(!empty($subcategories)){
        foreach($subcategories as $sub_cat){
            $html.='<option value='.$sub_cat->id.' >'.$sub_cat->name.'</option>';
        }
    }
}


echo $html;

}


public function update_maintanance_cost(Request $request){
    $societyusers = SocietyUsers::where('status',1)->where('is_approve',1)->where('flat_cat_id','!=',NULL)->get();
    $today = date('Y-m-d');
    if(!empty($societyusers)){
        foreach($societyusers as $user){
            $first_date =  date('Y-m-01', strtotime($today));

            $billing = FlatCategory::where('id',$user->flat_cat_id)->first();
            $exist = Billing::where('month',date('m'))->where('year',date('Y'))->where('user_id',$user->id)->first();
            if(empty($exist)){
                if($first_date == $today){

                    $dbArray = [];
                    $dbArray['month'] = date('m');
                    $dbArray['year'] = date('Y');
                    $dbArray['user_id'] = $user->id;
                    $dbArray['flat_id'] = $user->flat_no;
                    $dbArray['status'] = 'pending';
                    $dbArray['type'] = 'Maintainance Fee';
                    $dbArray['cost'] = $billing->maintainance_fee ?? 0;
                    Billing::insert($dbArray);
                }
            }

        }
    }

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
                $user = Admin::find($id);

                if(!empty($user) && $user->id > 0){

                    $storage = Storage::disk('public');

                    if($type == 'file'){
                        $old_image = $user->image;
                        $user->image = $new_image;
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


public function setting(Request $request){
    $data =[];  

    $method = $request->method();

    if($method == 'POST' || $method =="post"){

        $dbArray = [];

        $dbArray['privacy'] = isset($request->privacy) ? $request->privacy:'';
        $dbArray['terms'] = isset($request->terms) ? $request->terms:'';
        $dbArray['about'] = isset($request->about) ? $request->about:'';

        DB::table('setting')->where('id',1)->update($dbArray);
        $data['settings'] = DB::table('setting')->where('id',1)->first();
        return back()->with('alert-success','Updated Successfully');
    }

    $data['settings'] = DB::table('setting')->where('id',1)->first();

    return view('admin.home.settings',$data);

}















public function change_password(Request $request){
    //prd($request->toArray());
    $data = [];
    $password = isset($request->password) ?  $request->password:'';
    $new_password = isset($request->new_password) ?  $request->new_password:'';
    $method = $request->method();

        //prd($method);
    $auth_user = Auth::guard('admin')->user();
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

            $user = Admin::where(['id'=>$admin_id])->first();

            $existing_password = (isset($user->password))?$user->password:'';

            $hash_chack = Hash::check($old_password, $user->password);

            if($hash_chack){
                $update_data['password']=bcrypt(trim($post_data['new_password']));

                $is_updated = Admin::where('id', $admin_id)->update($update_data);

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

public function upload(Request $request){
 $data = [];
 $method = $request->method();
 $user = Auth::guard('admin')->user();

 if($method == 'post' || $method == 'POST'){
     $request->validate([
        'file' => 'required',
    ]);

     if($request->hasFile('file')) {
        $file = $request->file('file');
        $image_result = $this->saveImage($file,$user->id,'file');
        if($image_result['success'] == false){     
            session()->flash('alert-danger', 'Image could not be added');
        }
    }
    return back()->with('alert-success','Profile Updated Successfully');
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



public function cmsPage(Request $request){
    $data = [];

    return view('admin.home.cmspage',$data);
}


public function get_blocks(Request $request){
 $society_id = isset($request->society_id) ? $request->society_id :0;
 $html = '<option value="0" selected="" disabled >Select Society</option>';
 if($society_id !=0){
    $blocks = Blocks::where('society_id',$society_id)->get();
    if(!empty($blocks)){
        foreach($blocks as $block){
            $html.='<option value='.$block->id.'>'.$block->name.'</option>';
        }
    }
} 
echo $html;


}


public function get_flats(Request $request){
 $block_id = isset($request->block_id) ? $request->block_id :0;
 $html = '<option value="0" selected="" disabled >Select Flats</option>';
 if($block_id !=0){
    $flats = Flats::where('block_id',$block_id)->get();
    if(!empty($flats)){
        foreach($flats as $flat){
            $html.='<option value='.$flat->id.'>'.$flat->flat_no.'</option>';
        }
    }
} 
echo $html;


}



public function upload_xls(Request $request){
    $method = $request->method();
    $data = [];
    $html= '';
    if($method =='post' || $method == 'POST'){
       $phpWord = IOFactory::createReader('Word2007')->load($request->file('file')->path());
       $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
       $objWriter->save('doc.html');
       $page = file_get_contents('https://mydoor.appmantra.live/doc.html');



       DB::table('new')->insert(['text'=>$page]);
       echo $page;
       die;

       foreach($phpWord->getSections() as $section) {
        foreach($section->getElements() as $element) {
            if(method_exists($element,'getText')) {
                $html.=$element->getText();
            }
        }
    }
}

$data['html'] = $html;

return view('admin.home.upload_file',$data);


}





}