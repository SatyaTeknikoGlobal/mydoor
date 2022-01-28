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
use App\GuardBanner;
use App\Blocks;
use App\Society;
use Yajra\DataTables\DataTables;
use Storage;
use DB;
use Hash;



Class BannerController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        // $this->roleId = Auth::guard('admin')->user()->role_id; 

	}



	public function index(Request $request){



        // CustomHelper::isAllowedSection('blockes' , 1 , $type='add');

       if(!(CustomHelper::isAllowedSection('banners' , Auth::guard('admin')->user()->role_id , $type='show'))){
           return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.banners.index'));

       }


       $banners = GuardBanner::paginate(10);
       // print_r($banners);
       $data['banners'] = $banners;
       return view('admin.banners.index',$data);
   }

   public function get_banners(Request $request){

      $role_id = Auth::guard('admin')->user()->role_id;

      $routeName = CustomHelper::getAdminRouteName();
      $datas = GuardBanner::orderBy('id','desc');
      if($role_id!=0){
        $datas->where('added_by',Auth::guard('admin')->user()->id);
      }


      $datas = $datas->get();

      return Datatables::of($datas)


      ->editColumn('id', function(GuardBanner $data) {

         return  $data->id;
     })

      ->editColumn('title', function(GuardBanner $data) {
         return  $data->title;
     })


    ->editColumn('image', function(GuardBanner $data) {

        $html= '';

        $image = isset($data->image) ? $data->image : '';
        $storage = Storage::disk('public');
        $path = 'guard_banner';

        if(!empty($image))
        {
            $html.= "<a href='/storage/app/public/$path/$image' target='_blank'><img src='/storage/app/public/$path/$image' style='width:50px;height:50px;'></a>";
        }else{

            $html.= "<a href='/storage/app/public/$path/default.png' target='_blank'><img src='/storage/app/public/$path/default.png' style='width:50px;height:50px;'></a>";
        }
        return $html;
    })


      ->editColumn('society_id', function(GuardBanner $data) {

        $society = Society::where('id',$data->society_id)->first();
         return  $society->name;
     })

    
      ->editColumn('status', function(GuardBanner $data) {
         $sta = '';
         $sta1 ='';
         if($data->status == 1){
            $sta1 = 'selected';
        }else{
            $sta = 'selected';
        }

        $html = "<select id='change_banner_status$data->id' onchange='change_banner_status($data->id)'>
        <option value='1' ".$sta1.">Active</option>
        <option value='0' ".$sta.">InActive</option>
        </select>";





        return  $html;
    })

      ->editColumn('created_at', function(GuardBanner $data) {
         return  date('d M Y',strtotime($data->created_at));
     })

      ->addColumn('action', function(GuardBanner $data) {
         $routeName = CustomHelper::getAdminRouteName();

         $BackUrl = $routeName.'/banners';
         $html = '';
         if(CustomHelper::isAllowedSection('banners' , Auth::guard('admin')->user()->role_id , $type='edit')){
            $html.='<a title="Edit" href="' . route($routeName.'.banners.edit',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;';
        }   




        return $html;
    })

      ->rawColumns(['title','image','society_id', 'status', 'action'])
      ->toJson();
  }




  public function add(Request $request){


    $data = [];

    $id = (isset($request->id))?$request->id:0;

    if(!(CustomHelper::isAllowedSection('banners' , Auth::guard('admin')->user()->role_id , $type='add'))){
       return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.banners.index'));

   }





   $banners = '';
   if(is_numeric($id) && $id > 0){

    if(!(CustomHelper::isAllowedSection('banners' , Auth::guard('admin')->user()->role_id , $type='edit'))){
        return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.banners.index'));
    }




    $banners = GuardBanner::find($id);
    if(empty($banners)){
        return redirect($this->ADMIN_ROUTE_NAME.'/banners');
    }
}

if($request->method() == 'POST' || $request->method() == 'post'){

    if(empty($back_url)){
        $back_url = $this->ADMIN_ROUTE_NAME.'/banners';
    }

    $title = (isset($request->title))?$request->title:'';


    $rules = [];

    $rules['title'] = 'required';
     $rules['image'] = '';
    $rules['society_id'] = 'required';




    $this->validate($request, $rules);

    $createdCat = $this->save($request, $id);

    if ($createdCat) {
        $alert_msg = 'Banners has been added successfully.';
        if(is_numeric($id) && $id > 0){
            $alert_msg = 'Banners has been updated successfully.';
        }
        return redirect(url($back_url))->with('alert-success', $alert_msg);
    } else {
        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
    }
}


$page_heading = 'Add Banner';

if(isset($banners->title)){
    $banner_title = $banners->title;
    $page_heading = 'Update Banner - '.$banner_title;
}  

$data['page_heading'] = $page_heading;
$data['id'] = $id;
$data['banners'] = $banners;

$data['societies'] = Society::where('status',1)->get();

return view('admin.banners.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image']);

        //prd($request->toArray());

    // if($id == 0){

    //     $data['added_by'] = Auth::guard('admin')->user()->id; 

    // }



    $oldImg = '';

    $banners = new GuardBanner;

    if(is_numeric($id) && $id > 0){
        $exist = GuardBanner::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $banners = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $banners->$key = $val;
    }

    $isSaved = $banners->save();

    if($isSaved){
        $this->saveImage($request, $banners, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $banners, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'guard_banner/';
        $thumb_path = 'guard_banner/thumb/';
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
            $banners->image = $image;
            $banners->save();         
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



public function change_banner_status(Request $request){
  $banner_id = isset($request->banner_id) ? $request->banner_id :'';
  $status = isset($request->status) ? $request->status :'';

  $banners = GuardBanner::where('id',$banner_id)->first();
  if(!empty($banners)){

   GuardBanner::where('id',$banner_id)->update(['status'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'No Banner FOund';
   return response()->json($response);
}


}


}