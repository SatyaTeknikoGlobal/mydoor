<?php

namespace App\Http\Controllers;

use JWTAuth;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Illuminate\support\str;

use App\User;
use App\AppVersion;
use App\GuardLogin;
use App\UserOtp;
use App\Visitor;
use App\State;
use App\City;
use App\Society;
use App\Blocks;
use App\Flats;
use App\Guard;
use App\SocietyUser;
use App\Services;
use App\ServiceDetail;
use App\ServiceUsers;
use App\GuardVisitor;
use App\Chats;
use App\UserVehicle;
use App\GuardBanner;
use App\StaffAttendance;



use Mail;
use Storage;



class ApiController extends Controller
{


 public function __construct()
 {
    $this->user = new User;
    date_default_timezone_set("Asia/Kolkata");  
    $this->url = env('BASE_URL');
}







                    //============================= Fans Studio API ==================================//

public function app_version(){
    $app_version = AppVersion::first();
    return response()->json([
        'result' => true,
        'message' => '',
        'version' => $app_version,
    ],200);
}


public static function sendEmail($viewPath, $viewData, $to, $from, $replyTo, $subject, $params=array()){

    try{

        Mail::send(
            $viewPath,
            $viewData,
            function($message) use ($to, $from, $replyTo, $subject, $params) {
                $attachment = (isset($params['attachment']))?$params['attachment']:'';

                if(!empty($replyTo)){
                    $message->replyTo($replyTo);
                }

                if(!empty($from)){
                    $message->from($from);
                }

                if(!empty($attachment)){
                    $message->attach($attachment);
                }

                $message->to($to);
                $message->subject($subject);

            }
        );
    }
    catch(\Exception $e){
            // Never reached
    }

    if( count(Mail::failures()) > 0 ) {
        return false;
    }       
    else {
        return true;
    }

}






private function send_message($mobile,$message)
{
    $sender = "CITRUS";
    $message = urlencode($message);
    $msg = "sender=".$sender."&route=4&country=91&message=".$message."&mobiles=".$mobile."&authkey=284738AIuEZXRVCDfj5d26feae";

    $ch = curl_init('http://api.msg91.com/api/sendhttp.php?');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
                        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    $result = curl_close($ch);
    return $res;
}


public function send_otp(Request $request)
{
   $validator =  Validator::make($request->all(), [
     'mobile' => 'required',
 ]);

   $status = 'new';

   if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'otp'=> '',
        'message' => json_encode($validator->errors()),

    ],400);
}
// $otp = rand(1111,9999);

$otp = 1234;

$message = $otp." is your authentication Code to register.";
$mobile = $request['mobile'];
$time = date("Y-m-d H:i:s",strtotime('15 minutes'));

if(!empty($request->mobile)){
    // $this->send_message($mobile,$message);
    UserOtp::updateOrcreate([
        'mobile'=>$mobile],[
            'otp'=>$otp,
            'timestamp'=>$time,
        ]);

}
return response()->json([
    'result' => true,
    'message' => 'SMS Sent SuccessFully',
    //'status' =>$status,
    'otp'=>$otp,
],200);
}

public function verify_otp(Request $request){
 $validator =  Validator::make($request->all(), [
     'mobile' => 'required',
     'otp'=>'required',

 ]);

 if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',

        'message' => json_encode($validator->errors()),

    ],400);
}

$mobile = isset($request->mobile) ? $request->mobile :'';
$otp = isset($request->otp) ? $request->otp :'';

if(!empty($mobile)){
    $verify_otp  = UserOtp::where(['mobile'=>$mobile,'otp'=>$otp])->first();
}

if(!empty($verify_otp)){
 return response()->json([
    'result' => true,
    'message' => 'OTP Varified SuccessFully',
],200);

}else{
    return response()->json([
        'result' => 'failure',
        'message' => 'OTP Not Varified',
    ],200);

}



}




public function loginWithPassword(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'email' => 'required',
        'password'=>'required',
        'deviceID' => '',
        'deviceToken' => '',
        'deviceType' => '',
    ]);
    $user = null;
    $status = 'new';
    $type ='';
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'token' => null,
            'status' =>$status,
            'message' => json_encode($validator->errors()),
            'user'=>$user
        ],400);
    }
    $email = $request->email;
    $password = $request->password;



    $user_detail = User::Where('email',$email)->where('status',1)->where('is_approve',1)->first();
    if(!empty($user_detail)){
     $pass_check =  Hash::check($request->password, $user_detail->password);
     if($pass_check){
        $user  = User::Where('email',$email)->first();
    }
}

if (!empty($user)) {
 $status = 'old';
 if($user->photo!=='' && $user->photo!=null){
  $user->photo =  asset('public/images/'.$user->photo);
}
}
try {
    if (!empty($user)) {
        if (!$token = JWTAuth::fromUser($user)) {
            return response()->json([
                'result' => false,
                'token' => null,
                'status' =>$status,
                'type' => $type,
                'message' => 'invalid_credentials',
                'user' => null], 400);
        }
    } 
    else{ 
     return response()->json([
        'result' => false,
        'status' =>$status,
        'message' => 'Invalid credentials',
        'token' => null,
        'type' => $type,

        'user' => $user], 200);
 } 
}

catch (JWTException $e) {
    return response()->json([
        'result' => false,
        'token' => null,
        'status' =>$status,
        'message' => 'could_not_create_token',
        'type' => $type,

        'user' => null], 500);
}
$deviceID = $request->input("deviceID");
$deviceToken = $request->input("deviceToken");
$deviceType = $request->input("deviceType");
$device_info = GuardLogin::where(['user_id'=>$user->id])->first();
if (!empty($device_info)){
    $device_info->deviceToken = $deviceToken;
    $device_info->deviceType = $deviceType;
    $device_info->save();

    return response()->json([
        'result' => true,
        'token' => $token,
        'message' => 'Successful Login',
        'status' =>$status,
        'user' => $user,
        'type' => $type,

    ],200);
}
GuardLogin::create([
    "user_id"=>$user->id,
    "ip_address"=>$request->ip(),
    "deviceID"=>$deviceID,
    "deviceToken"=>$deviceToken,
    "deviceType"=>$deviceType,
]);
unset($user->id);

return response()->json([
    'result' => true,
    'token' => $token,
    'message' => 'Successful Login',
    'status' =>$status,
    'user' => $user,
    'type' => $type,

],200);




}




public function logout(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'deviceID' => 'max:255',
    ]);

    if ($validator->fails()) {

        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors())
        ],400);
    }

    try {
        JWTAuth::invalidate($request->token);
        $user_login = GuardLogin::where(['deviceID' => $request->input("deviceID")])->delete();
        return response()->json([
            'result' => true,
            'message' => 'User logged out successfully'
        ],200);
    } catch (JWTException $exception) {
        return response()->json([
            'result' => 'failure',
            'message' => 'Sorry, the user cannot be logged out'
        ], 500);
    }
}








public function update_profile(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
]);
   $user = null; 
   if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),
        'user' =>$user,
    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
        'user' =>$user,
    ],401);
} 

$dbArray= [];
if(!empty($request->name)){
    $dbArray['name'] = $request->name;
}
if(!empty($request->gender)){
    $dbArray['gender'] = $request->gender;
}
if(!empty($request->location)){
    $dbArray['location'] = $request->location;
}

if(!empty($request->state_id)){
    $dbArray['state_id'] = $request->state_id;
}
if(!empty($request->city_id)){
    $dbArray['city_id'] = $request->city_id;
}


if($request->hasFile('document')){
 $file = $request->file('document');

 $destinationPath = public_path("/uploads/images/documents");

 $side1 = $request->file('document');

 $side_name1 = $user->id.'_guard_document'.time().'.'.$side1->getClientOriginalExtension();

 $side1->move($destinationPath, $side_name1);

 $dbArray['document'] = $side_name1;
}

if($request->hasFile('photo')){
 $file = $request->file('photo');

 $destinationPath = public_path("/uploads/images");

 $side = $request->file('photo');

 $side_name = $user->id.'_guard_profile'.time().'.'.$side->getClientOriginalExtension();

 $side->move($destinationPath, $side_name);

 $dbArray['photo'] = $side_name;
}


User::where('id',$user->id)->update($dbArray);
$user = User::where('id',$user->id)->first();

if(!empty($user) && !empty($user->photo)){
    $user->photo= $this->url.'guardapi/public/uploads/images/'.$user->photo;
}else{
   $user->photo= $this->url.'guardapi/public/uploads/images/man.png';
}

if(!empty($user) && !empty($user->document)){
    $user->document= $this->url.'guardapi/public/uploads/images/documents/'.$user->document;
}

return response()->json([
    'result' => true,
    'message' => 'Profile Updated successfully',
    'user'=>$user,
],200); 

}

public function profile(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $user = array();
    $user = null; 
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'user' =>$user,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'user' =>$user,
        ],401);
    } 


    if(!empty($user) && !empty($user->photo)){
        $user->photo= $this->url.'guardapi/public/uploads/images/'.$user->photo;
    }else{
       $user->photo= $this->url.'guardapi/public/uploads/images/user.png';
   }

   return response()->json([
    'result' => true,
    'message' => 'User Profile',
    'user'=>$user,
],200);  

}








public function state_city_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        
    ]);
    $list = null;
    $user = null; 
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'list' =>$list,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'list' =>$list,
        ],401);
    } 

    $states = State::where('status',1)->get();
    if(!empty($states)){
        foreach($states as $state){
            $cities = City::where('state_id',$state->id)->where('status',1)->get();
            if(!empty($cities)){
                $state->cities = $cities;
            }
        }
    }


    return response()->json([
        'result' => true,
        'message' => 'State City List',
        'list'=>$states,
    ],200);  
}

public function society_list(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
]);
   $list = null;
   $user = null; 
   if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),
        'societies' =>$list,
    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
        'societies' =>$list,
    ],401);
} 




$societies = Society::select('id','name')->where('status',1)->get();
if(!empty($societies)){

}


return response()->json([
    'result' => true,
    'message' => 'Societies List',
    'societies'=>$societies,
],200);

}

public function cmspages(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'type' => 'required',
]);
   $pages = null;
   $user = null; 
   if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),
        'pages' =>$pages,
    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
        'pages' =>$pages,
    ],401);
} 

if($request->type == 'contactus'){
    $pages = DB::table('settings')->first()->contactus;
}


if($request->type == 'aboutus'){
    $pages = DB::table('settings')->first()->about_us;
}

if($request->type == 'privacypolicy'){
    $pages = DB::table('settings')->first()->privacypolicy;
}

if($request->type == 'terms'){
    $pages = DB::table('settings')->first()->terms;
}



return response()->json([
    'result' => true,
    'message' => 'CMS Pages List',
    'pages'=>$pages,
],200);




}















public function get_blocks_list(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',


]);
   $blocks_list = null;
   $user = null; 
   if ($validator->fails()) {
    return response()->json([
        'result' => false,
        'message' => json_encode($validator->errors()),
        'blocks_list' =>$blocks_list,
    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => false,
        'message' => '',
        'blocks_list' =>$blocks_list,
    ],401);
} 
$blocks = Blocks::select('id','name','society_id')->where('society_id',$user->society_id)->get();
if(!empty($blocks)){

}
return response()->json([
    'result' => true,
    'message' => 'Blocks List',
    'blocks_list'=>$blocks,
],200);
}


public function get_flats_list(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'block_id' => 'required',

]);
   $flats_list = null;
   $user = null; 
   if ($validator->fails()) {
    return response()->json([
        'result' => false,
        'message' => json_encode($validator->errors()),
        'flats_list' =>$flats_list,
    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => false,
        'message' => '',
        'flats_list' =>$flats_list,
    ],401);
} 
$flats = Flats::select('id','flat_no','block_id')->where('block_id',$request->block_id)->get();
if(!empty($flats)){
    foreach($flats as $flat){
        $user = SocietyUser::select('name','phone')->where('flat_no',$flat->id)->first();
        $flat->user_name = $user->name ?? '';
        $flat->phone = $user->phone ?? '';
    }


}
return response()->json([
    'result' => true,
    'message' => 'Blocks List',
    'flats_list'=>$flats,
],200);
}


public function notification_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        
    ]);
    $notifications = null;
    $user = null; 
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'notifications' =>$notifications,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'notifications' =>$notifications,
        ],401);
    } 
    $notifications = DB::table('notifications')->where('guard_id',$user->id)->paginate(10);

    return response()->json([
        'result' => true,
        'message' => 'Notification List',
        'notifications'=>$notifications,
    ],200);  

}












public function chats(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'eventId' => 'required',
]);
   $chats = array();
   $user = null; 
   if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);
} 

$chats = EventChat::where('event_id',$request->eventId)->paginate(15);
if(!empty($chats)){
    foreach($chats as $chat){
        $chat->name = '';
        $chat->image = '';

        if($chat->user_id == 0){
            $chat->name = 'Admin';
            $chat->image = $this->url.'api/public/uploads/images/user.png';
        }

        if($chat->user_id !=0){
            $user = User::where('id',$chat->user_id)->first();
            $chat->name = $user->name;
            if(!empty($user->photo)){
                $chat->image = $this->url.'api/public/uploads/images/'.$user->photo;
            }else{
                $chat->image = $this->url.'api/public/uploads/images/man.png';
            }

        }


    }
}

return response()->json([
    'result' => true,
    'message' => 'Live Chats List',
    'chats' =>$chats,
],200);


}




public function chatSubmit(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'eventId' => 'required',
    'text' => 'required',
]);
   $user = null; 
   if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);
} 

$chats = EventChat::where('event_id',$request->eventId)->paginate(15);
$dbArray = [];

$dbArray['user_id'] = $user->id;
$dbArray['event_id'] =$request->eventId; 
$dbArray['text'] =$request->text; 

EventChat::create($dbArray);



return response()->json([
    'result' => true,
    'message' => 'Submitted SuccessFully',
],200);


}



public function residence_details(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'block_id' => 'required',
]);
 $flats = null;
 $user = null; 
 if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),
        'flats' =>$flats,
    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
        'flats' =>$flats,
    ],401);
} 


$society_id = $user->society_id;

$societyusers = SocietyUser::select('id','name','flat_no')->where('status',1)->where('is_approve',1)->first();

if(!empty($societyusers)){
    $flat = Flats::where('id',$societyusers->flat_no)->where('is_delete',0)->where('status',1)->first();
    if(!empty($flat)){
        $societyusers->flat_no = $flat->flat_no;
    }

}


return response()->json([
    'result' => true,
    'message' => 'Flats List',
    'flats'=>$societyusers,
],200);

}

public function service_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);

    $services = null;
    $user = null; 
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'services' =>$services,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'services' =>$services,
        ],401);
    } 

    $society_id = $user->society_id;
    $services = Services::where('society_id',$society_id)->where('status',1)->get();
    if(!empty($services)){
        foreach($services as $ser){
           if(!empty($ser->image)){
            $ser->image = $this->url.'storage/app/public/services/'.$ser->image;
        }
        $service_user = ServiceUsers::where('service_id',$ser->id)->get();
        $ser->no_of_user = count($service_user);
        $details = ServiceDetail::select(\DB::raw("MIN(price) AS price"))->where('service_id',$ser->id)->first();
        $start_from = 0;
        if(!empty($details)){
            $start_from = isset($details->price) ? $details->price :0;
        }
        $ser->start_from = $start_from;


    }
}

return response()->json([
    'result' => true,
    'message' => 'Services List',
    'services'=>$services,
],200);
}

public function service_details(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'service_id' => 'required',
    ]);

    $services = null;
    $user = null; 
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'services' =>$services,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'services' =>$services,
        ],401);
    } 
    $details =[];
    $society_id = $user->society_id;
    $services = Services::where('id',$request->service_id)->where('society_id',$society_id)->where('status',1)->first();
    if(!empty($services)){
        $details = ServiceDetail::where('service_id',$services->id)->where('society_id',$society_id)->where('status',1)->get();
        if(!empty($details)){
            foreach($details as $det){
                if(!empty($det->image)){
                 $det->image = $this->url.'storage/app/public/servicedetails/'.$det->image;
             }

         }
     }


 }



 return response()->json([
    'result' => true,
    'message' => 'Services Details List',
    'services'=>$details,
],200);
}



public function get_guards(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
]);

   $user = null; 
   if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),
    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);
} 
$success = false;
$guards = Guard::where('society_id',$user->society_id)->where('status',1)->get();

return response()->json([
    'result' => true,
    'message' => 'Guards List',
    'guards' => $guards,
],200); 

}


public function verified_entry(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'otp' => 'required',
    'type'=>'',
]);

 $user = null; 
 if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),
    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);
} 
$success = false;
$exist = Visitor::where('otp',$request->otp)->first();

if($request->type == 'verify'){
    if(!empty($exist)){
        // Visitor::where('id',$exist->id)->update(array('entry_at'=>date('Y-m-d H:i:s'),'is_verified'=>1,'otp'=>null));
        return response()->json([
            'result' => true,
            'message' => 'Verified SuccessFully',
            'user' => $exist,
        ],200); 
    }else{
        return response()->json([
            'result' => false,
            'message' => 'No visitor Found',
            'user' => null,
        ],200); 
    }
}
else{
    if(!empty($exist)){
      return response()->json([
        'result' => true,
        'message' => 'Get SuccessFully',
        'user' => $exist,
    ],200);
  }else{
      return response()->json([
        'result' => false,
        'message' => 'Visitor Not FOund',
        'user' => null,
    ],200);
  }


}



}





public function visitors_list(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
]);

 $user = null; 
 if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),
    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);
} 

$visitors_list = [];

$visitors = Visitor::where('society_id',$user->society_id)->orderBy('id','desc');

if(!empty($request->search)){

    $visitors->where('visitor_name', 'like', '%' . $request->search . '%');
    $visitors->orWhere('phone', 'like', '%' . $request->search . '%');

}

$visitors = $visitors->paginate(10);

return response()->json([
    'result' => true,
    'message' => 'Visitors List',
    'visitors_list' => $visitors,

],200);



}





public function add_multivisitor_entry(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'contactName' => 'required',
    'contactNumber' => 'required',
    'jsonData' => 'required',
]);

   $user = null; 
   if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),
    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);
} 


$photo ='';
if($request->hasFile('user_image')){
 $file = $request->file('user_image');
 $destinationPath = public_path("/uploads/images/visitors/");
 $side = $request->file('user_image');
 $side_name = $user->id.'_guard_visitor'.time().'.'.$side->getClientOriginalExtension();
 $side->move($destinationPath, $side_name);
 $photo = $side_name;
}

$jsondata = json_decode($request->jsonData);

$insertArr = [];


//$title = 'Test';
//$body = ['id'=>3,'name'=>$request->contactName,'phone'=>$request->contactNumber,'image'=>$photo];
////$deviceToken = $login->deviceToken;
//$deviceToken = 'dx6cnmoXT2G_DomJsnNJm7:APA91bGfc1sAPAwlDVM-s_9Gc4sIdX206lyv5igh6k1dZ7h8DFBSrbvvjIxJ9-7WPb-rp9HmDNdy1mHU5TlI7Vv32ha29dgrPq3QUVY9UqRKLqTPtl6hPKBB9QKC6g9sCWe1VtsOS6Ld';
//$type='incomming_request';
//$success = $this->send_notification($title, $body, $deviceToken,$type);
//



if(!empty($jsondata)){

    $dbArray1 = [];
    $dbArray1['name'] = $request->contactName;
    $dbArray1['society_id'] = $user->society_id;

    $dbArray1['phone'] = $request->contactNumber;
    $dbArray1['category'] = $request->category;
    $dbArray1['sub_category'] = $request->sub_category;

    $vis_id = DB::table('guard_visitor_info')->insertGetId($dbArray1);


    foreach ($jsondata as $key) {



        $dbArray = [];
        $dbArray['society_id'] = $user->society_id;
        $dbArray['name'] = $request->contactName;
        $dbArray['phone'] = $request->contactNumber;
        $dbArray['image'] = $photo ?? '';
        $dbArray['entry_at'] = date('Y-m-d H:i:s');
        $dbArray['block_id'] = $key->blockId;
        $dbArray['vis_id'] = $vis_id;
        $flat_no = $key->flat_no;

        $flat = Flats::where('flat_no',$flat_no)->first();
        $dbArray['flat_id'] = $flat->id;
        //$insertArr[] = $dbArray;
        $id = GuardVisitor::insertGetId($dbArray);

        $image = $this->url.'guardapi/public/uploads/images/visitors/'.$photo;
        ////////Send Notification
        $notArr = [];
        $users = SocietyUser::where('flat_no',$flat->id)->get();












        if(!empty($users)){
         foreach($users as $user){
             $user_login = DB::table('user_logins')->where('user_id',$user->id)->get();
             if(!empty($user_login)){
                 foreach($user_login as $login){
                     $title = $request->sub_category ?? 'A Visitor Wait At Gate';
                     $body = ['id'=>$id,'name'=>$request->contactName,'phone'=>$request->contactNumber,'image'=>$image];
                     $deviceToken = $login->deviceToken;
                             //$deviceToken = 'dhIxAONPT3y9ZrC9mfiYXD:APA91bEmU7H_nC3eJzk5BzX6AzZB6ryyhcXgbG52styw64_GWPj-rT87kpBLU3EmJwBT3Trniyyz0KT-EkiLex0F4Ot-6u9oVbABQmgpv0ztc0MdqkatX37swtFhtasqmdBCv_2OrOQT';
                     $type = 'incomming_request';

                     $success = $this->send_notification($title, $body, $deviceToken,$type);
                     if($success){
                         $dbArray = [];
                         $dbArray['user_id'] = $login->user_id;
                         $dbArray['text'] = $title??'';
                         $dbArray['title'] = $title ?? '';
                         DB::table('notifications')->insert($dbArray);

                     }

                 }
             }


         }
     }



 }
}






return response()->json([
    'result' => true,
    'message' => 'Visitors Added SuccessFully',

],200);

}


public function send_notification($title, $body, $deviceToken,$type){
    $sendData = array(
        'body' => !empty($body) ? $body : '',
        'title' => !empty($title) ? $title : '',
        'type' => !empty($type) ? $type : '',
        'sound' => 'Default'
    );


    // print_r($sendData);
    // die;

    return $this->fcmNotification($deviceToken,$sendData);
}

public function fcmNotification($device_id, $sendData)
{
        #API access key from Google API's Console
    if (!defined('API_ACCESS_KEY')){
        define('API_ACCESS_KEY', 'AAAA-ub9LE8:APA91bFxQB0OiVLwiAhK0YtrnVdAObaX5HG8nRxe-n88lrgK0Cqn-6cxmr9xsrfcSmW2beyq8mtyrbOqPzWEYGmhqFYC7ggl4e1ec-AeKE66MRFBvKvR0HGqY6ftSXRID89LOuBb64yd');
    }


    $fields = array
    (
        'to'    => $device_id,
        'data'  => $sendData,
        //'notification'  => $sendData,
        "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
    );

    $headers = array
    (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );
        #Send Reponse To FireBase Server
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch);
    if($result === false){
        die('Curl failed ' . curl_error($ch));
    }

    curl_close($ch);
//    print_r($result);
//    die;

    return $result;
}






public function all_visitors_list(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
]);
   $chats = array();
   $user = null; 
   if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);
} 
$visitors = [];

$visitors = DB::table('guard_visitors')->select('id','name','phone','image','created_at','is_approve','approved_by','entry_at','exit_at')->where('society_id',$user->society_id)->where('is_approve',1)->latest()->groupBy('vis_id')->paginate(10);

if(!empty($visitors)){
    foreach($visitors as $vis){

        $vis->date = date("d M Y",strtotime($vis->created_at));
        $vis->time = date("h:i A",strtotime($vis->created_at));

        $user = SocietyUser::where('id',$vis->approved_by)->first();

        $vis->approved_by = $user->name ??'';

        $vis->exit_status = 1;
        if(empty($vis->exit_at)){
            $vis->exit_status = 0;
        }
        if(!empty($vis->image)){
            $vis->image = $this->url.'guardapi/public/uploads/images/visitors/'.$vis->image;
        }
    }
}


return response()->json([
    'result' => true,
    'message' => 'Visitors List',
    'all_visitors' =>$visitors,
],200);

}


public function exit_visitor(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'visitor_id' => 'required',
    ]);
    $chats = array();
    $user = null; 
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),

        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);
    } 


    $exist = DB::table('guard_visitors')->where('id',$request->visitor_id)->first();
    if(!empty($exist)){
        DB::table('guard_visitors')->where('id',$request->visitor_id)->update(['exit_at'=>date('Y-m-d H:i:s')]);
        return response()->json([
            'result' => true,
            'message' => 'SuccessFully Exit',
        ],200);
    }else{
        return response()->json([
            'result' => false,
            'message' => 'Visitor Not Found',
        ],200);
    }


}



public function get_chats(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $chats = array();
    $user = null; 
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),

        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);
    } 
    $chats = [];
    $chats = Chats::where('society_id',$user->society_id)->latest()->paginate(10);
    if(!empty($chats)){
        foreach($chats as $chat){
            $user = SocietyUser::where('id',$chat->user_id)->first();
            $flats = Flats::where('id',$user->flat_no)->first();
            $chat->name = $user->name ?? '';
            $chat->email = $user->email ?? '';
            $chat->phone = $user->phone ?? '';
            $chat->flat_no = $flats->flat_no ?? '';
            $chat->date = date('d M Y H:i A',strtotime($chat->created_at));

        }
    }



    return response()->json([
        'result' => true,
        'message' => 'Chats List',
        'chats' =>$chats,
    ],200);
}


public function vehicle_list(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
]);
 $vehicles = array();
 $user = null; 
 if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);
} 
$flatids =[];
$flats = Flats::where('society_id',$user->society_id)->get();
if(!empty($flats)){
    foreach($flats as $flat){
        $flatids[] = $flat->id;
    }
}

if(!empty($flatids)){
    $vehicles  = UserVehicle::whereIn('flat_id',$flatids);
    if(!empty($request->search)){
      $vehicles->where('vehicle_no', 'like', '%' . $request->search . '%');
      $vehicles->orWhere('car_name', 'like', '%' . $request->search . '%');
  }


  $vehicles = $vehicles->paginate(10);
  if(!empty($vehicles)){
    foreach($vehicles as $veh){

        $user = SocietyUser::where('flat_no',$veh->flat_id)->first();
        $flats = Flats::where('id',$veh->flat_id)->first();
        $veh->user_name = $user->name ??'';
        $veh->user_phone = $user->phone ??'';
        $veh->flat_no = $flats->flat_no ??'';

    }
}



}


return response()->json([
    'result' => true,
    'message' => 'Chats List',
    'vehicles' =>$vehicles,
],200);


}




public function guard_banners(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $banners = array();
    $user = null; 
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),

        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);
    } 
    $banners =[];


    $banners = GuardBanner::where('status',1)->where('society_id',$user->society_id)->get();
    if(!empty($banners)){
        foreach($banners as $banner){
            $banner->image = $this->url.'/public/storage/guard_banner/'.$banner->image;
        }
    }

    return response()->json([
        'result' => true,
        'message' => 'Banner List',
        'banners' =>$banners,
    ],200);

}

public function emergency_nos(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $user = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),

        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
        ],401);
    }

    $emergency_nos = [];

    $emergency_nos = DB::table('society_information')->where('society_id',$user->society_id)->get();

    return response()->json([
        'result' => true,
        'message' => 'Succesfully',
        'emergency_nos' => $emergency_nos,
    ],200);



}





public function sub_category_list(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
]);
   $user = null;
   if ($validator->fails()) {
    return response()->json([
        'result' => false,
        'message' => json_encode($validator->errors()),

    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => false,
        'message' => '',
    ],401);
}

$sub_category_list = [];

$deliveryArr = config('custom.deliveryArr');
$cabArr = config('custom.cabArr');

$alldeliveryArr = [];
$allcabArr = [];
if(!empty($deliveryArr)){
    foreach ($deliveryArr as $key => $value) {
        $dbArray = [];
        $dbArray['name'] = $key;
        $dbArray['icon'] = $value;
        $dbArray['category_name'] = "Delivery";
        $alldeliveryArr[] = $dbArray;
    }
}

if(!empty($cabArr)){
    foreach ($cabArr as $key => $value) {
        $dbArray1 = [];
        $dbArray1['name'] = $key;
        $dbArray1['icon'] = $value;
        $dbArray1['category_name'] = "Cab";

        $allcabArr[] = $dbArray1;
    }
}

$sub_category_list['deliveryArr'] = $alldeliveryArr;
$sub_category_list['cabArr'] = $allcabArr;


return response()->json([
    'result' => true,
    'message' => 'Succesfully',
    'sub_category_list' => $sub_category_list,
],200);

}


public function staff_list(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
]);
   $user = null;
   if ($validator->fails()) {
    return response()->json([
        'result' => false,
        'message' => json_encode($validator->errors()),

    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => false,
        'message' => '',
    ],401);
}

$staffs = [];
$search = isset($request->search) ? $request->search :'';


$staffs = ServiceUsers::select('id','name','email','phone','image')->where('society_id',$user->society_id)->where('status',1)->where('is_delete',0);
if(!empty($search)){
    $staffs->where('name', 'like', '%' . $search . '%');
    $staffs->orWhere('phone', 'like', '%' . $search . '%');
    $staffs->orWhere('email', 'like', '%' . $search . '%');
}

$staffs = $staffs->paginate(10);
if(!empty($staffs)){
    foreach($staffs as $staff){
        $attend = StaffAttendance::where('staff_id',$staff->id)->where('date',date('Y-m-d'))->first();
        if(empty($attend)){
                $present = 0; ///////IN
            }else{
                // if($attend->in_time !=NULL || $attend->in_time !=''){
                //      $present = 1; ///////OUT 
                //  }else if($attend->out_time !=NULL || $attend->out_time !=''){
                //      $present = 2; ///////Nothing 
                //  }

                if(!empty($attend->in_time) && empty($attend->out_time)){
                    $present = 1;
                }
                if(!empty($attend->out_time) && !empty($attend->in_time)){
                    $present = 2;
                }


             }
             $staff->present = $present;
             if(!empty($staff->image)){
                $staff->image = $this->url.'/public/storage/service_user/'.$staff->image;
            }

            $exists = DB::table('user_daily_help')->select('flat_id')->where('service_user_id',$staff->id)->get();
            if(!empty($exists)){
                foreach($exists as $exist){
                    $users = SocietyUser::where('flat_no',$exist->flat_id)->where('user_type','owner')->first();
                    $flat = Flats::where('id',$exist->flat_id)->first();
                    $exist->flat_no = $flat->flat_no ?? '';
                    $exist->phone_no = $users->phone ?? '';
                }
            }

            $staff->flats = $exists;

        }
    }


    return response()->json([
        'result' => true,
        'message' => 'Succesfully',
        'staffs' => $staffs,
    ],200); 
}


public function in_out_staff(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'staff_id' => 'required',
        'type' => 'required',
    ]);
    $user = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),

        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
        ],401);
    }


    $staff = ServiceUsers::where('id',$request->staff_id)->first();

    // $exist = StaffAttendance::where('staff_id',$staff->id)->where('date',date('Y-m-d'))->first();
    // if(empty($exist)){
    //    $dbArray = [];
    //    $dbArray['staff_id'] = $request->staff_id;
    //    $dbArray['date'] = date('Y-m-d');
    //    $dbArray['in_time'] = date('h:i');
    //    StaffAttendance::insert($dbArray);
    // }

    if($request->type == 'IN'){
        $exist = StaffAttendance::where('staff_id',$staff->id)->where('date',date('Y-m-d'))->first();
        if(empty($exist)){
           $dbArray = [];
           $dbArray['staff_id'] = $request->staff_id;
           $dbArray['date'] = date('Y-m-d');
           $dbArray['in_time'] = date('h:i');
           StaffAttendance::insert($dbArray);

           return response()->json([
            'result' => true,
            'message' => 'CheckIn Succesfully',
        ],200); 

       }else{
         return response()->json([
            'result' => false,
            'message' => 'Already CheckIn',
        ],200); 
     }
 }
 if($request->type == 'OUT'){
    $exist = StaffAttendance::where('staff_id',$staff->id)->where('date',date('Y-m-d'))->where('in_time','!=',NULL)->first();
    if(!empty($exist)){
        if($exist->out_time == '' || $exist->out_time == NULL){
           StaffAttendance::where('id',$exist->id)->update(['out_time'=>date('h:i')]);
                return response()->json([
                    'result' => true,
                    'message' => 'CheckOut Succesfully',
                ],200); 
            }else{
                return response()->json([
                    'result' => false,
                    'message' => 'Already CheckOut',
                ],200);  
            }
        
    }else{

       return response()->json([
        'result' => false,
        'message' => 'Please Check In First',
    ],200);
   }   

}









}







}
