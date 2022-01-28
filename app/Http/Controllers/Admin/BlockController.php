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
use Yajra\DataTables\DataTables;
use Storage;
use DB;
use Hash;



Class BlockController extends Controller
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


       $blocks = Blocks::paginate(10);
       $data['blocks'] = $blocks;
       return view('admin.blocks.index',$data);
   }

   public function get_blocks(Request $request){

      $role_id = Auth::guard('admin')->user()->role_id;

      $routeName = CustomHelper::getAdminRouteName();
      $datas = Blocks::orderBy('id','desc');
      if($role_id!=0){
        $datas->where('added_by',Auth::guard('admin')->user()->id);
      }


      $datas = $datas->get();

      return Datatables::of($datas)


      ->editColumn('id', function(Blocks $data) {

         return  $data->id;
     })

      ->editColumn('name', function(Blocks $data) {
         return  $data->name;
     })


      ->editColumn('society_id', function(Blocks $data) {

        $society = Society::where('id',$data->society_id)->first();
         return  $society->name;
     })

    ->editColumn('added_by', function(Blocks $data) {

            $user = Admin::where('id',$data->added_by)->first();
         return  isset($user->name) ? $user->name :'';
     })

      ->editColumn('status', function(Blocks $data) {
         $sta = '';
         $sta1 ='';
         if($data->status == 1){
            $sta1 = 'selected';
        }else{
            $sta = 'selected';
        }

        $html = "<select id='change_block_status$data->id' onchange='change_block_status($data->id)'>
        <option value='1' ".$sta1.">Active</option>
        <option value='0' ".$sta.">InActive</option>
        </select>";





        return  $html;
    })

      ->editColumn('created_at', function(Blocks $data) {
         return  date('d M Y',strtotime($data->created_at));
     })

      ->addColumn('action', function(Blocks $data) {
         $routeName = CustomHelper::getAdminRouteName();

         $BackUrl = $routeName.'/blockes';
         $html = '';
         if(CustomHelper::isAllowedSection('blockes' , Auth::guard('admin')->user()->role_id , $type='edit')){
            $html.='<a title="Edit" href="' . route($routeName.'.blockes.edit',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;';
        }   




        return $html;
    })

      ->rawColumns(['name', 'status', 'action'])
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

return view('admin.blocks.form', $data);

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
        $is_delete = Blocks::where('id', $id)->delete();
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Blockes has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_block_status(Request $request){
  $block_id = isset($request->block_id) ? $request->block_id :'';
  $status = isset($request->status) ? $request->status :'';

  $blocks = Blocks::where('id',$block_id)->first();
  if(!empty($blocks)){

   Blocks::where('id',$block_id)->update(['status'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'No Block FOund';
   return response()->json($response);
}


}


}