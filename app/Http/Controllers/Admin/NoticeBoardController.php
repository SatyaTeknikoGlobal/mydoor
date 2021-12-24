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
use App\NoticeBoard;
use App\NoticeBoardDocument;
use Yajra\DataTables\DataTables;
use Storage;
use DB;
use Hash;



Class NoticeBoardController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        // $this->roleId = Auth::guard('admin')->user()->role_id; 

	}



	public function index(Request $request){



        // CustomHelper::isAllowedSection('blockes' , 1 , $type='add');

     if(!(CustomHelper::isAllowedSection('notice_board' , Auth::guard('admin')->user()->role_id , $type='show'))){
         return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.admin.index'));

     }

     $data = [];
       // $blocks = Blocks::paginate(10);
       // $data['blocks'] = $blocks;
     return view('admin.notice_board.index',$data);
 }

 public function get_notice_board(Request $request){

  $role_id = Auth::guard('admin')->user()->role_id;

  $routeName = CustomHelper::getAdminRouteName();
  $datas = NoticeBoard::orderBy('id','desc');
  if($role_id!=0){
    $datas->where('society_id',Auth::guard('admin')->user()->society_id);
}


$datas = $datas->get();

return Datatables::of($datas)


->editColumn('id', function(NoticeBoard $data) {

   return  $data->id;
})
->editColumn('society_id', function(NoticeBoard $data) {

    $society = Society::where('id',$data->society_id)->first();
    return  $society->name;
})

->editColumn('title', function(NoticeBoard $data) {

   return  isset($data->title) ? $data->title :'';
})

->editColumn('status', function(NoticeBoard $data) {
   $sta = '';
   $sta1 ='';
   if($data->status == 1){
    $sta1 = 'selected';
}else{
    $sta = 'selected';
}

$html = "<select id='change_notice_board_status$data->id' onchange='change_notice_board_status($data->id)'>
<option value='1' ".$sta1.">Active</option>
<option value='0' ".$sta.">InActive</option>
</select>";

return  $html;
})

->editColumn('created_at', function(NoticeBoard $data) {
   return  date('d M Y',strtotime($data->created_at));
})

->addColumn('action', function(NoticeBoard $data) {
   $routeName = CustomHelper::getAdminRouteName();

   $BackUrl = $routeName.'/notice_board';
   $html = '';
   if(CustomHelper::isAllowedSection('notice_board' , Auth::guard('admin')->user()->role_id , $type='edit')){
    $html.='<a title="Edit" href="' . route($routeName.'.notice_board.edit',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;';
    $html.='<a title="Edit" href="' . route($routeName.'.notice_board.documents',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-file"> Documents</i></a>&nbsp;&nbsp;&nbsp;';
}   




return $html;
})

->rawColumns(['name', 'status', 'action'])
->toJson();
}




public function add(Request $request){


    $data = [];

    $id = (isset($request->id))?$request->id:0;

    if(!(CustomHelper::isAllowedSection('notice_board' , Auth::guard('admin')->user()->role_id , $type='add'))){
     return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.admin.index'));

 }





 $notices = '';
 if(is_numeric($id) && $id > 0){

    if(!(CustomHelper::isAllowedSection('notice_board' , Auth::guard('admin')->user()->role_id , $type='edit'))){
        return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.admin.index'));
    }




    $notices = NoticeBoard::find($id);
    if(empty($notices)){
        return redirect($this->ADMIN_ROUTE_NAME.'/notice_board');
    }
}

if($request->method() == 'POST' || $request->method() == 'post'){

    if(empty($back_url)){
        $back_url = $this->ADMIN_ROUTE_NAME.'/notice_board';
    }



    $rules = [];

    $rules['title'] = 'required';
    $rules['society_id'] = 'required';
    $this->validate($request, $rules);

    $createdCat = $this->save($request, $id);

    if ($createdCat) {
        $alert_msg = 'Notices has been added successfully.';
        if(is_numeric($id) && $id > 0){
            $alert_msg = 'Notices has been updated successfully.';
        }
        return redirect(url($back_url))->with('alert-success', $alert_msg);
    } else {
        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
    }
}


$page_heading = 'Add Notices';

if(is_numeric($id) && $id > 0){
    $page_heading = 'Update Notices';
}  

$data['page_heading'] = $page_heading;
$data['id'] = $id;
$data['notices'] = $notices;

$data['societies'] = Society::where('status',1)->get();

return view('admin.notice_board.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image']);
    $oldImg = '';

    $notice = new NoticeBoard;

    if(is_numeric($id) && $id > 0){
        $exist = NoticeBoard::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $notice = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $notice->$key = $val;
    }

    $isSaved = $notice->save();

    if($isSaved){
        $this->saveImage($request, $notice, $oldImg);
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


public function documents(Request $request){
    $id = isset($request->id) ? $request->id :0;
    $data = [];


    $method = $request->method();
    if($method == 'post' || $method == 'POST'){
        $rules = [];
        $rules['file'] = 'required';


        $this->validate($request,$rules);
        $files = $request->file('file');
        $path = 'notice_board/';
        $storage = Storage::disk('public');

        if (!empty($files)) {

            foreach($files as $file){
                $uploaded_data = CustomHelper::UploadFile($file, $path, $ext='');
                if($uploaded_data['success']){
                    $dbArray = [];
                    $dbArray['notice_id'] = $request->notice_id;
                    $dbArray['file'] = $uploaded_data['file_name'];
                    $dbArray['type'] = $uploaded_data['extension'];
                    $dbArray['status'] = 1;
                    $success = NoticeBoardDocument::create($dbArray);
                }
            }
        }

    }

    $notice = NoticeBoard::where('id',$id)->first();

    $documents = $notice->getDocuments;

    $data['documents'] = $documents;
    $data['notice'] = $notice;

    return view('admin.notice_board.documents',$data); 

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


public function delete_document(Request $request){
   $doc_id = (isset($request->doc_id))?$request->doc_id:0;
   $path = 'notice_board/';
   $storage = Storage::disk('public');


   $document = NoticeBoardDocument::where('id',$doc_id)->first();
   $oldImg = $document->file;
   if(!empty($oldImg)){
    if($storage->exists($path.$oldImg)){
        $storage->delete($path.$oldImg);
    }
    
}


$is_delete = '';

if(is_numeric($doc_id) && $doc_id > 0){
    $is_delete = NoticeBoardDocument::where('id', $doc_id)->delete();
}

if(!empty($is_delete)){
    return back()->with('alert-success', 'Document has been deleted successfully.');
}
else{
    return back()->with('alert-danger', 'something went wrong, please try again...');
}
}


public function change_notice_board_status(Request $request){
  $not_id = isset($request->not_id) ? $request->not_id :'';
  $status = isset($request->status) ? $request->status :'';

  $notice = NoticeBoard::where('id',$not_id)->first();
  if(!empty($notice)){

     NoticeBoard::where('id',$not_id)->update(['status'=>$status]);
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