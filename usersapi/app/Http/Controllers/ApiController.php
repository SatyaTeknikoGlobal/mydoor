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
use App\Banner;
use App\AppVersion;
use App\UserLogin;
use App\UserOtp;
use App\Visitor;
use App\State;
use App\City;
use App\Society;
use App\Blocks;
use App\Flats;
use App\Services;
use App\ServiceUsers;
use App\UserDailyHelp;
use App\UserDocument;
use App\UserVehicle;
use App\Complaints;
use App\Billing;
use App\Chats;
use App\Payment;
use App\BookingRequest;

use Razorpay\Api\Api;


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

// public function fcmNotification($device_id, $sendData)
// {
//         #API access key from Google API's Console
//     if (!defined('API_ACCESS_KEY')){

//         // define('API_ACCESS_KEY', 'AAAATmZU4nA:APA91bGClTtsQEYtexrS3tdYGTca7Q2UhwWGHplyx7vjXoE2RgMihRt1oc2z-SepjOIDXDVkGmps4X1jKa-YPzUpyYKe6RUWl-isZ2_S8o_Npyh18FFltQKIgeFEQexhKQl07gHQTdEm');


//         define('API_ACCESS_KEY', 'AAAA-ub9LE8:APA91bFxQB0OiVLwiAhK0YtrnVdAObaX5HG8nRxe-n88lrgK0Cqn-6cxmr9xsrfcSmW2beyq8mtyrbOqPzWEYGmhqFYC7ggl4e1ec-AeKE66MRFBvKvR0HGqY6ftSXRID89LOuBb64yd');

//     }

//     $fields = array(
//         'to'    => $device_id,
//         'data'  => $sendData,
//         'notification'  => $sendData
//     );


//     $headers = array
//     (
//         'Authorization: key=' . API_ACCESS_KEY,
//         'Content-Type: application/json'
//     );
//         #Send Reponse To FireBase Server    
//     $ch = curl_init();
//     curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
//     curl_setopt( $ch,CURLOPT_POST, true );
//     curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
//     curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
//     curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, true );
//     curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
//     $result = curl_exec($ch);
//         //$data = json_decode($result);
//     if($result === false)
//         die('Curl failed ' . curl_error());

//     curl_close($ch);

//     //prd($result);
//     return json_decode($result);
// }







    public function send_test_notification(Request $request){



        $deviceToken = 'dzHn8qdTQROVj2H4KpX5aZ:APA91bE6NHu2jstkNRx49H7gcBBKrWgb1Gbr_r-oc-PxKW6IU_GzD9ZP0o26lpKFmPqbnq6Ewl3jGYVq6dq_uSmCCF_L96Xl_apzOs4nrD7cPaEOsdBjdnTGJhTE7Ig7R4X6z4Xj9S5y';
        $sendData = array(
            'body' => 'Test',
            'title' => 'My Door Notification',
            'sound' => 'Default',
        );
        $result = $this->fcmNotification($deviceToken,$sendData);

        print_r($result);

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
                'result' => false,
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
            'status' =>$status,
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
                'result' => false,

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
                'result' => false,
                'message' => 'OTP Not Varified',
            ],200);

        }



    }

// public function social_login(Request $request){
//  $validator =  Validator::make($request->all(), [
//     'username' => 'required',
//     'deviceID' => 'required',
//     'deviceToken' => 'required',
//     'deviceType' => 'required',
// ]);
//  $user = null;
//  $status = 'new';
//  if ($validator->fails()) {
//     return response()->json([
//         'result' => false,
//         'token' => null,
//         'status' =>$status,
//         'message' => json_encode($validator->errors()),
//         'user'=>$user
//     ],400);
// }

// $token = null;
// $user = User::orWhere('email',$request->only('username'))->first();
// if(!empty($user)){
//     $token = JWTAuth::fromUser($user);
//     $status = 'old';


//     $deviceID = $request->input("deviceID");
//     $deviceToken = $request->input("deviceToken");
//     $deviceType = $request->input("deviceType");

//     $device_info = UserLogin::where(['user_id'=>$user->id])->first();
//     if (!empty($device_info)){
//         $device_info->deviceToken = $deviceToken;
//         $device_info->deviceType = $deviceType;
//         $device_info->save();
//         unset($user->id);
//         $user->image = asset('public/images/'.$user->image);
//         return response()->json([
//             'result' => true,
//             'token' => $token,
//             'message' => 'Successful Login',
//             'status' =>$status,
//             'user' => $user
//         ],200);
//     }
//     UserLogin::create([
//         "user_id"=>$user->id,
//         "ip_address"=>$request->ip(),
//         "deviceID"=>$deviceID,
//         "deviceToken"=>$deviceToken,
//         "deviceType"=>$deviceType,
//     ]);
//     unset($user->id);







//     return response()->json([
//         'result' => true,
//         'token' => $token,
//         'message' => 'Successful Login',
//         'status' =>$status,
//         'user' => $user
//     ],200);
// }else{
//     return response()->json([
//         'result' => true,
//         'token' => $token,
//         'message' => 'New User',
//         'status' =>$status,
//         'user' => $user
//     ],200);
// }
// }

    public function login(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'mobile' => 'required',
            'otp'=>'required',
            'deviceID' => 'required',
            'deviceToken' => 'required',
            'deviceType' => 'required',
        ]);
        $user = null;
        $status = 'new';
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'token' => null,
                'status' =>$status,
                'message' => json_encode($validator->errors()),
                'user'=>$user
            ],400);
        }
        $time = date("Y-m-d H:i:s",strtotime('-15 minutes'));
        $verify_otp  = UserOtp::where(['mobile'=>$request->only('mobile'),'otp'=>$request['otp']])->first();
        if (empty($verify_otp)) {
            return response()->json([
                'result' => false,
                'token' => null,
                'status' =>$status,
                'message' => 'Invalid Otp.',
                'user'=>$user
            ],200);
        }
        $credentials = $request->only('mobile');
        // $user = User::where('phone',$request->only('mobile'))->where('status',1)->where('is_approve',1)->first();
        $user = User::where('phone',$request->only('mobile'))->first();

        $flats = [];
        $blocks = [];
        $socities = [];
        if(!empty($user->flat_no)){

            $flats = Flats::where('id',$user->flat_no)->first();
            $user->flat_no = $flats->flat_no;
        }
        if(!empty($user->block_id)){

         $blocks = Blocks::where('id',$user->block_id)->first();
            $user->block_name = $blocks->name;

     }
     if(!empty($user->society_id)){

         $socities = Society::where('id',$user->society_id)->first();
            $user->society_name = $socities->name;

     }

      $flats = Flats::where('id',$user->flat_no)->first();
    $user->flat_name = $flats->flat_no ?? '';

    $blocks = Blocks::where('id',$user->block_id)->first();
    $user->block_name = $blocks->name ?? '';

    $socities = Society::where('id',$user->society_id)->first();
    $user->society_name = $socities->name ?? '';


    // $city = City::where('id',$user->city_id)->first();
    // $user->city_id = $city->name ?? '';


        //$userexist = User::where('phone',$request->only('mobile'))->first();

     if(!empty($user)){
        $status = 'old';
    }
    try {
        if (!empty($user)) {
            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json([
                    'result' => false,
                    'token' => "",
                    'status' =>$status,
                    'message' => 'invalid_credentials',
                    'user' => ""], 400);
            }
        }
        else{
            return response()->json([
                'result' => true,
                'status' =>$status,
                'message' => 'User Not Approved',
                'token' => "",
                'user' => $user], 200);
        }
    }

    catch (JWTException $e) {
        return response()->json([
            'result' => false,
            'token' => "",
            'status' =>$status,
            'message' => 'could_not_create_token',
            'user' => ""], 500);
    }
    $deviceID = $request->input("deviceID");
    $deviceToken = $request->input("deviceToken");
    $deviceType = $request->input("deviceType");

    $device_info = UserLogin::where(['user_id'=>$user->id])->first();
    if (!empty($device_info)){
        $device_info->deviceToken = $deviceToken;
        $device_info->deviceType = $deviceType;
        $device_info->save();
        unset($user->id);
        $user->photo = asset('public/uploads/images/'.$user->photo);
        return response()->json([
            'result' => true,
            'token' => $token,
            'message' => 'Successful Login dfasdf',
            'status' =>$status,
            'user' => $user
        ],200);
    }

    UserLogin::create([
        "user_id"=>$user->id,
        "ip_address"=>$request->ip(),
        "deviceID"=>$deviceID,
        "deviceToken"=>$deviceToken,
        "deviceType"=>$deviceType,
    ]);
    unset($user->id);
    if(!empty($user->photo)){
        $user->photo =  asset('public/uploads/images/'.$user->photo);
    }



    return response()->json([
        'result' => true,
        'token' => $token,
        'message' => 'Successful Login',
        'status' =>$status,
        'user' => $user
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
            'result' => false,
            'message' => json_encode($validator->errors())
        ],400);
    }

    try {
        JWTAuth::invalidate($request->token);
        $user_login = UserLogin::where(['deviceID' => $request->input("deviceID")])->delete();
        return response()->json([
            'result' => true,
            'message' => 'User logged out successfully'
        ],200);
    } catch (JWTException $exception) {
        return response()->json([
            'result' => false,
            'message' => 'Sorry, the user cannot be logged out'
        ], 500);
    }
}

public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
        'phone' => 'required|unique:societyusers,phone',
        'email'=>'required|unique:societyusers',
        'gender'=>'required',
        'referral_code'=>'',
        'deviceID' => 'required',
        'deviceToken' => 'required',
        'deviceType' => 'required',
    ]);
    if ($validator->fails()) {

        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),
            'token'=>null,
            'user'=>null
        ],400);
    }

    if(!empty($request->referral_code)){
        $exist = User::where('referral_code',$request->referral_code)->first();
    }



    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->is_approve = 0;
    $user->status = 0;
    $user->gender =isset($request->gender) ? $request->gender :'';
    $user->referral_code = $this->generateReferalCode(8);


    if(!empty($exist)){
        $user->referral_userID = $exist->id;
    }
    $user->photo= 'user.png';
    $user->save();

    $credentials = $request->only('phone');
    $user = User::where('phone',$credentials)->first();
    $user->photo= $this->url.'assets/images/users/'.$user->photo;
    try {
        if (!empty($user)) {
            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json([
                    'result' => false,
                    'token' => null,
                    'message' => 'invalid_credentials',
                    'user' => null], 400);
            }
        } else {
            return response()->json([
                'result' => false,
                'token' => null,
                'message' => 'invalid_credentials',
                'user' => null], 400);
        }

    } catch (JWTException $e) {
        return response()->json([
            'result' => false,
            'token' => null,
            'message' => 'could_not_create_token',
            'user' => null], 500);
    }
    $deviceID = $request->input("deviceID");
    $deviceToken = $request->input("deviceToken");
    $deviceType = $request->input("deviceType");
    $device_info = UserLogin::where(['user_id'=>$user->id])->first();
    UserLogin::create([
        "user_id"=>$user->id,
        "ip_address"=>$request->ip(),
        "deviceID"=>$deviceID,
        "deviceToken"=>$deviceToken,
        "deviceType"=>$deviceType,
    ]);
    unset($user->id);
    if($user->photo!=='' && $user->photo!=null){
        $user->photo =  asset('public/images/'.$user->photo);
    }

    return response()->json([
        'result' => true,
        'token' => $token,
        'message' => 'Successful Login',
        'user' => $user
    ],200);
}


public  function generateReferalCode($length = 8) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}





public function update_profile(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $user = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),
            'user' =>$user,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
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

    if(!empty($request->location)){
        $dbArray['location'] = $request->location;
    }
    if(!empty($request->society_id)){
        $dbArray['society_id'] = $request->society_id;
    }
    if(!empty($request->block_id)){
        $dbArray['block_id'] = $request->block_id;
    }
    if(!empty($request->flat_no)){
        $dbArray['flat_no'] = $request->flat_no;
    }
    if(!empty($request->user_type)){
        $dbArray['user_type'] = $request->user_type;
    }

    if(!empty($request->state_id)){
        $dbArray['state_id'] = $request->state_id;
    }
    if(!empty($request->city_id)){
        $dbArray['city_id'] = $request->city_id;
    }
    if(!empty($request->no_of_dose)){
        $dbArray['no_of_dose'] = $request->no_of_dose;
    }


    if($request->hasFile('document')){
        $file = $request->file('document');

        $destinationPath = public_path("/uploads/images/documents");

        $side1 = $request->file('document');

        $side_name1 = $user->id.'_user_document'.time().'.'.$side1->getClientOriginalExtension();

        $side1->move($destinationPath, $side_name1);

        $dbArray['document'] = $side_name1;
    }


    if($request->hasFile('photo')){
        $file = $request->file('photo');

        $destinationPath = public_path("/uploads/images");

        $side = $request->file('photo');

        $side_name = $user->id.'_user_profile'.time().'.'.$side->getClientOriginalExtension();

        $side->move($destinationPath, $side_name);

        $dbArray['photo'] = $side_name;
    }


    User::where('id',$user->id)->update($dbArray);
    $user = User::where('id',$user->id)->first();

    if(!empty($user) && !empty($user->photo)){
        $photo= $this->url.'usersapi/public/uploads/images/'.$user->photo;
    }else{
        $photo= $this->url.'usersapi/public/uploads/images/man.png';
    }

    $user->photo = $photo;

    if(!empty($user) && !empty($user->document)){
        $user->document= $this->url.'usersapi/public/uploads/images/documents/'.$user->document;
    }

    return response()->json([
        'result' => true,
        'message' => 'Profile Updated successfully',
        'user'=>$user,
        'token'=>$request->token,
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
            'result' => false,
            'message' => json_encode($validator->errors()),
            'user' =>$user,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
            'user' =>$user,
        ],401);
    }

    $flats = Flats::where('id',$user->flat_no)->first();
    $user->flat_name = $flats->flat_no ?? '';

    $blocks = Blocks::where('id',$user->block_id)->first();
    $user->block_name = $blocks->name ?? '';

    $socities = Society::where('id',$user->society_id)->first();
    $user->society_name = $socities->name ?? '';

    if(!empty($user) && !empty($user->photo)){
        $user->photo= $this->url.'usersapi/public/uploads/images/'.$user->photo;
    }else{
        $user->photo= $this->url.'usersapi/public/uploads/images/user.png';
    }

    return response()->json([
        'result' => true,
        'message' => 'User Profile',
        'user'=>$user,
        'token'=>$request->token,
    ],200);

}

public function visitorsEntry(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'key' => '',
    ]);
    $upcoming_visitors = null;
    $past_visitors = null;
    $user = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),
            'upcoming_visitors' =>$upcoming_visitors,
            'past_visitors' =>$past_visitors,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
            'upcoming_visitors' =>$upcoming_visitors,
            'past_visitors' =>$past_visitors,
        ],401);
    }
    $time = date('H:i');

    if($request->key == 'add'){
        $validator =  Validator::make($request->all(), [
            'visitor_name' => 'required',
            'reason' => 'required',
            'phone' => 'required',
            'date' => 'required',
            'time' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors()),
                'upcoming_visitors' =>$upcoming_visitors,
                'past_visitors' =>$past_visitors,
            ],400);
        }


//$otp = rand(111111,99999);
        $otp = 123456;

        $mobile = $request->phone;

        $message =$otp." is your authentication Code to register.";

//$this->send_message($mobile,$message);

        $dbArray = [];
        $dbArray['visitor_name'] = $request->visitor_name;
        $dbArray['reason'] = $request->reason;
        $dbArray['user_id'] = $user->id;
        $dbArray['phone'] = $request->phone;
        $dbArray['date'] = date('Y-m-d',strtotime($request->date));
        $dbArray['time'] = $request->time;
        $dbArray['otp'] = $otp;
        $dbArray['society_id'] = $user->society_id;

        Visitor::create($dbArray);

    }

    $upcoming_visitors = Visitor::where('user_id',$user->id)->where('date','>=',date('Y-m-d'))->where('time','>',$time)->get();

    $past_visitors = Visitor::where('user_id',$user->id)->where('date','<',date('Y-m-d'))->get();


    return response()->json([
        'result' => true,
        'message' => 'Visitors List',
        'upcoming_visitors' =>$upcoming_visitors,
        'past_visitors' =>$past_visitors,
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
            'result' => false,
            'message' => json_encode($validator->errors()),
            'list' =>$list,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
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
            'result' => false,
            'message' => json_encode($validator->errors()),
            'societies' =>$list,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
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
            'result' => false,
            'message' => json_encode($validator->errors()),
            'pages' =>$pages,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
            'pages' =>$pages,
        ],401);
    }

// if($request->type == 'contactus'){
//     $pages = DB::table('setting')->first()->contactus;
// }


    if($request->type == 'about'){
        $pages = DB::table('setting')->first()->about;
    }

    if($request->type == 'privacy'){
        $pages = DB::table('setting')->first()->privacy;
    }

    if($request->type == 'terms'){
        $pages = DB::table('setting')->first()->terms;
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
        'society_id' => 'required',

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
    $blocks = Blocks::select('id','name','society_id')->where('society_id',$request->society_id)->get();
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
            'result' => false,
            'message' => json_encode($validator->errors()),
            'notifications' =>$notifications,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
            'notifications' =>$notifications,
        ],401);
    }
    $notifications = DB::table('notifications')->where('user_id',$user->id)->paginate(10);

    return response()->json([
        'result' => true,
        'message' => 'Notification List',
        'notifications'=>$notifications,
    ],200);

}


public function notification_details(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'notification_id'=>'required',
    ]);
    $notifications = null;
    $user = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),
            'notifications' =>$notifications,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
            'notifications' =>$notifications,
        ],401);
    }
    $notifications = DB::table('notifications')->where('id',$request->notification_id)->first();
    $documents = [];
    if(!empty($notifications)){
        $notifications_documents = DB::table('notification_documents')->where('not_id',$notifications->id)->get();
        if(!empty($notifications_documents)){
            foreach($notifications_documents as $doc){

                $documents[] = array(
                    'type'=>$doc->type,
                    'file_name'=>$this->url.'public/storage/notification_documents/'.$doc->file_name,
                );




            }
        }
    }

    $notifications->documents = $documents;


    DB::table('notifications')->where('id',$request->notification_id)->update(['is_read'=>1]);

    return response()->json([
        'result' => true,
        'message' => 'Notification List',
        'notifications'=>$notifications,
    ],200);

}





public function get_question(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'question_id' => 'required',

    ]);
    $question = array();
    $user = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),
            'question' =>$question,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
            'question' =>$question,
        ],401);
    }

    $question = EventQuestion::where('id',$request->question_id)->first();

    return response()->json([
        'result' => true,
        'message' => 'Question Details',
        'question'=>$question,
    ],200);

}

public function submit_answer(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'question_id' => 'required',
        'option_id' => 'required',
        'event_id' => 'required',
        'time' => '',

    ]);
    $question = array();
    $user = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),
            'question' =>$question,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
            'question' =>$question,
        ],401);
    }

    $exist = EventQuestionAnswer::where('user_id',$user->id)->where('question_id',$request->question_id)->where('event_id',$request->event_id)->first();
    if(!empty($exist)){
        EventQuestionAnswer::create([
            'user_id'=>$user->id,
            'question_id'=>$request->question_id,
            'option_id'=>$request->option_id,
            'event_id'=>$request->event_id,
            'time'=>$request->time,

        ]);

        return response()->json([
            'result' => true,
            'message' => 'Answer SUbmitted Succesfully',
        ],200);
    }else{
        return response()->json([
            'result' => false,
            'message' => 'Answer Already SUbmitted ',
        ],200);
    }



}




public function gallery_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',


    ]);
    $galleries = array();
    $user = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),
            'galleries' =>$galleries,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
            'galleries' =>$galleries,
        ],401);
    }

    $galleries = Gallery::where('status',1)->get();

    if(!empty($galleries)){
        foreach($galleries as $gallery){
            $gallery->image = $this->url.'/public/storage/galleries/'.$gallery->image;
        }
    }


    return response()->json([
        'result' => true,
        'message' => 'Gallery List',
        'galleries' =>$galleries,
    ],200);
}



public function subscription_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',


    ]);
    $subscriptions = array();
    $user = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),
            'subscriptions' =>$subscriptions,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
            'subscriptions' =>$subscriptions,
        ],401);
    }

    $subscriptions = Subscription::where('status',1)->get();


    return response()->json([
        'result' => true,
        'message' => 'subscriptions List',
        'subscriptions' =>$subscriptions,
    ],200);
}



public function WalletHistory(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',


    ]);
    $wallets = array();
    $user = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),
            'wallets' =>$wallets,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
            'wallets' =>$wallets,
        ],401);
    }

    $wallets = WalletHistory::where('user_id',$user->id)->orderby('id','desc')->paginate(10);
    if(!empty($wallets)){
        foreach($wallets as $wallet){
            $created_at = $wallet->created_at;
            $wallet->date = date('Y-m-d',strtotime($created_at));
            $wallet->time = date('h:i A',strtotime($created_at));
        }
    }

    return response()->json([
        'result' => true,
        'message' => 'Wallet History',
        'wallets' =>$wallets,
    ],200);
}


public function get_wallet(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',


    ]);
    $wallets = array();
    $user = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),
            'wallets' =>$wallets,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
            'wallets' =>$wallets,
        ],401);
    }

    $wallets = WalletHistory::where('user_id',$user->id)->latest()->take(3)->get();

    if(!empty($wallets)){
        foreach($wallets as $wallet){
            $created_at = $wallet->created_at;
            $wallet->date = date('Y-m-d',strtotime($created_at));
            $wallet->time = date('h:i A',strtotime($created_at));
        }
    }


    return response()->json([
        'result' => true,
        'message' => 'Wallet History',
        'wallet' =>$user->wallet,

        'wallets_history' =>$wallets,
    ],200);
}

public function influencer_details(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'influencer_id' => 'required',


    ]);
    $influencers = array();
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

    $influencers = Influencers::where('id',$request->influencer_id)->first();




    if(!empty($influencers)){

        $galleries = InfluencersGallery::select('image')->where('influencer_id',$request->influencer_id)->get();
        if(!empty($galleries)){
            foreach($galleries as $gallery){
                $gallery->image = $this->url.'public/storage/influencer_gallery/'.$gallery->image;
            }
        }
        $influencers->image = $this->url.'public/storage/influencer/'.$influencers->image;
        $influencers->gallery= $galleries;
        $details = Events::where('influencers_id',$request->influencer_id)->where('event_date','>',date('Y-m-d'))->latest()->first();

        $influencers->gallery= $galleries;
        $influencers->event_date= '';
        $influencers->start_time= '';
        $influencers->end_time= '';
        $influencers->about= '';



        if(!empty($details)){

            $details->start_time = date('h:i A', strtotime($details->start_time));
            $details->end_time = date('h:i A', strtotime($details->end_time));

            $start = strtotime($details->start_time);
            $end =strtotime($details->end_time);
            $elapsed = $end - $start;
                //$details->duration = date('H:i',$elapsed).' Hours';

            $influencers->event_date= $details->event_date;
            $influencers->start_time= date('h:i A', strtotime($details->start_time));
            $influencers->end_time= date('h:i A', strtotime($details->end_time));

            $influencers->about= $influencers->description;


        }



    }


    return response()->json([
        'result' => true,
        'message' => 'Wallet History',
        'influencers' =>$influencers,
    ],200);
}

public function influencer_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $influencers = array();
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

    $influencers = Influencers::where('status',1)->orderby('id','desc')->paginate(10);
    if(!empty($influencers)){
        foreach($influencers as $influe){
            if(!empty($influe->image)){
                $influe->image = $this->url.'public/storage/influencer/'.$influe->image;
            }
        }
    }

    return response()->json([
        'result' => true,
        'message' => 'Wallet History',
        'influencers' =>$influencers,
    ],200);




}

public function live_influencer_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $influencers = array();
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

    $today = date('Y-m-d');
    $time = date('H:i');

    $events = Events::where('event_date',$today)->where('start_time','<=',$time)->where('end_time','>=',$time)->latest()->paginate(10);
    if(!empty($events)){
        foreach($events as $event){
            $influencer_id = $event->influencers_id;

            $influencer = Influencers::where('id',$influencer_id)->first();
            if(!empty($influencer)){
                $influencer->image = $this->url.'public/storage/influencer/'.$influencer->image;
                $influencer->event_date = $event->event_date;
                $influencer->event_id = $event->id;



                $is_subscription = 'N';

                $event_sub = DB::table('event_subscription')->where('user_id',$user->id)->where('event_id',$event->id)->first();
                if(!empty($event_sub)){
                    $is_subscription = 'Y';
                }
                $user_sub = DB::table('user_subscriptions')->where('user_id',$user->id)->where('end_date','>=',date('Y-m-d'))->first();
                if(!empty($user_sub)){
                    $is_subscription = 'Y';
                }


                $influencer->is_subscription = $is_subscription;


                $influencers[] = $influencer;
            }




        }
    }


    return response()->json([
        'result' => true,
        'message' => 'Live Influencers List',
        'influencers' =>$influencers,
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


public function winers_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'eventId' => 'required',
        'question_id' => 'required',
    ]);
    $user = null;
    $winers_list = array();
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

    $answers = DB::table('event_question_answers')->where('event_id',$request->eventId)->where('question_id',$request->question_id)->get();


    $userIds = [];

    if(!empty($answers)){
        foreach($answers as $ans){
            $question = EventQuestion::where('id',$ans->question_id)->first();
            if(!empty($question)){
                if($question->right_option == $ans->option_id){
                    if(!in_array($ans->user_id,$userIds)){
                        $userIds[] = $ans->user_id;

                    }
                }
            }
        }
    }



    $users = User::select('photo','id')->whereIn('id',$userIds)->get();
    if(!empty($users)){
        foreach($users as $user){

            if(!empty($user->photo)){
                $user->photo = $this->url.'api/public/uploads/images/'.$user->photo;
            }else{
                $user->photo = $this->url.'api/public/uploads/images/man.png';
            }
        }
    }


    return response()->json([
        'result' => true,
        'message' => ' SuccessFully',
        'winers_list' => $users,
    ],200);




}



public function my_participation(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $user = null;
    $my_participation = array();
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
    $eventIds = [];
    $event_users = DB::table('event_users')->where('user_id',$user->id)->get();
    if(!empty($event_users)){
        foreach($event_users as $event){
            $eventIds[]  = $event->event_id;
        }
    }
    $events = null;


    if(!empty($eventIds)){

        $events = Events::whereIn('id',$eventIds)->paginate(10);
        if(!empty($events)){
            foreach($events as $event){
                $event_date = $event->event_date;
                $event->image = $this->url.'public/storage/events/'.$event->image;

                $event->event_day = date('D',strtotime($event_date));
                $event->event_month = date('F',strtotime($event_date));
                $event->event_date = date('j',strtotime($event_date));

                $event->start_time = date('h:i A',strtotime($event->start_time));
                $event->end_time = date('h:i A',strtotime($event->end_time));


                $influencer = Influencers::where('id',$event->influencers_id)->first();
                $event->influencer = $influencer;

            }
        }
    }

    return response()->json([
        'result' => true,
        'message' => ' SuccessFully',
        'my_participation' => $events,
    ],200);


}


public function question_winner_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'eventId' => 'required',
        'question_id' => 'required',
    ]);
    $user = null;
    $winers_list = array();
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

    $answer = 0;
    $answers = DB::table('event_question_answers')->where('event_id',$request->eventId)->where('question_id',$request->question_id)->get();


    $questions = EventQuestion::where('id',$request->question_id)->first();

    $user_answers = DB::table('event_question_answers')->where('event_id',$request->eventId)->where('question_id',$request->question_id)->where('user_id',$user->id)->first();
    if(!empty($user_answers)){

        if($user_answers->option_id == $questions->right_option){
            $answer = 1;
        }else{
            $answer = 2;
        }

    }


    $userIds = [];

    if(!empty($answers)){
        foreach($answers as $ans){
            $question = EventQuestion::where('id',$ans->question_id)->first();
            if(!empty($question)){
                if($question->right_option == $ans->option_id){
                    if(!in_array($ans->user_id,$userIds)){
                        $userIds[] = $ans->user_id;

                    }
                }
            }
        }
    }
    $users = User::select('photo','id','name','email')->whereIn('id',$userIds)->get();
    if(!empty($users)){
        foreach($users as $user){

            if(!empty($user->photo)){
                $user->photo = $this->url.'api/public/uploads/images/'.$user->photo;
            }else{
                $user->photo = $this->url.'api/public/uploads/images/man.png';
            }
        }
    }






    return response()->json([
        'result' => true,
        'message' => ' SuccessFully',

        'winers_list' => $users,
        'questions' => $questions,
        'answer' => $answer,

    ],200);


}

public function join_live(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'eventId' => 'required',
    ]);
    $user = null;
    $winers_list = array();
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



    $is_subscription = 'N';

    $event_sub = DB::table('event_subscription')->where('user_id',$user->id)->where('event_id',$request->eventId)->first();
    if(!empty($event_sub)){
        $is_subscription = 'Y';
    }
    $user_sub = DB::table('user_subscriptions')->where('user_id',$user->id)->where('end_date','>=',date('Y-m-d'))->first();
    if(!empty($user_sub)){
        $is_subscription = 'Y';
    }


    if($is_subscription == 'Y'){
        $dbArray = [];
        $dbArray['user_id'] = $user->id;
        $dbArray['event_id'] = $request->eventId;


        $check_exist = DB::table('event_users')->where('user_id',$user->id)->where('event_id',$request->eventId)->first();


        if(empty($check_exist)){
            DB::table('event_users')->insert($dbArray);
        }else{
            return response()->json([
                'result' => true,
                'message' => 'You Already Joined',

            ],200);

        }

        return response()->json([
            'result' => true,
            'message' => ' SuccessFully',

        ],200);

    }else{
        return response()->json([
            'result' => true,
            'message' => 'Please take subscription',

        ],200);
    }






}


public function removeUserDocument(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'document_id' => 'required',
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

$success = UserDocument::where('id',$request->document_id)->delete();
return response()->json([
    'result' => true,
    'message' => 'Deleted SuccessFully',

],200);
}


public function uploadUserDocument(Request $request){
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


    $dbArray = [];

    if($request->hasFile('file')){
        $file = $request->file('file');

        $destinationPath = public_path("/uploads/userdocument");

        $side = $request->file('file');

        $side_name = $user->id.'_user_document'.time().'.'.$side->getClientOriginalExtension();

        $side->move($destinationPath, $side_name);
        $dbArray['type'] = $side->getClientOriginalExtension();
        $dbArray['file'] = $side_name;
    }

    $dbArray['title'] = $request->title;
    $dbArray['user_id'] = $user->id;
    $dbArray['parent'] = $user->parent;
    $dbArray['description'] = $request->description;
    $dbArray['status'] = 1;

    $success = UserDocument::create($dbArray);
    if($success){
        return response()->json([
            'result' => true,
            'message' => 'Upload SuccessFully',

        ],200);
    }else{
        return response()->json([
            'result' => false,
            'message' => 'Something Went Wrong',

        ],200);
    }
}


public function get_user_document(Request $request){
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

    $files = UserDocument::where('user_id',$user->id)->get();
    if(!empty($files)){
        foreach($files as $file){
            $file->file = $this->url.'/usersapi/public/uploads/userdocument/'.$file->file;
        }
    }




    return response()->json([
        'result' => true,
        'message' => 'Succesfully',
        'files' =>$files,

    ],200);


}



public function add_family_member(Request $request){
    $validator = [];
    if($request->type == 'add'){
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'name' =>'required',
            'phone' => 'required|unique:societyusers,phone',
            'email'=>'required|unique:societyusers',
            'type' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors()),

            ],400);
        }
    }

    if($request->type == 'edit'){
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'name' =>'required',
            'phone' => 'required',
            'email'=>'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors()),

            ],400);
        }
    }


    $user = null;

    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => false,
            'message' => '',
        ],401);
    }


    $society_user = User::where('id',$user->id)->first();

    if($request->type == 'add'){
        if(!empty($society_user)){
            $dbArray = [];
            $dbArray['name'] = $request->name ?? '';
            $dbArray['email'] = $request->email ?? '';
            $dbArray['phone'] = $request->phone ?? '';
            $dbArray['society_id'] = $society_user->society_id;
            $dbArray['block_id'] = $society_user->block_id;
            $dbArray['flat_no'] = $society_user->flat_no;
            $dbArray['is_approve'] = $society_user->is_approve;
            $dbArray['status'] = $society_user->status;
            $dbArray['location'] = $society_user->location;
            $dbArray['parent'] = $society_user->id;
            $dbArray['user_type'] = 'family_user';
            if($request->hasFile('photo')){
                $file = $request->file('photo');

                $destinationPath = public_path("/uploads/images");

                $side = $request->file('photo');

                $side_name = $user->id.'_user_profile'.time().'.'.$side->getClientOriginalExtension();

                $side->move($destinationPath, $side_name);

                $dbArray['photo'] = $side_name;
            }

            User::create($dbArray);
        }
    }elseif($request->type == 'edit'){
        if(!empty($request->user_id)){
            $dbArray = [];
            $dbArray['name'] = $request->name ?? '';
            $dbArray['email'] = $request->email ?? '';
            $dbArray['phone'] = $request->phone ?? '';
            $dbArray['society_id'] = $society_user->society_id;
            $dbArray['block_id'] = $society_user->block_id;
            $dbArray['flat_no'] = $society_user->flat_no;
            $dbArray['is_approve'] = $society_user->is_approve;
            $dbArray['status'] = $society_user->status;
            $dbArray['location'] = $society_user->location;
            $dbArray['parent'] = $society_user->id;
            $dbArray['user_type'] = 'family_user';
            if($request->hasFile('photo')){
                $file = $request->file('photo');

                $destinationPath = public_path("/uploads/images");

                $side = $request->file('photo');

                $side_name = $user->id.'_user_profile'.time().'.'.$side->getClientOriginalExtension();

                $side->move($destinationPath, $side_name);

                $dbArray['photo'] = $side_name;
            }



            User::where('id',$request->user_id)->update($dbArray);

        }

    }




    else{
        $society_user = User::select('id','name','phone','photo')->where('id',$user->id)->orWhere('parent',$user->id)->paginate(10);
        if(!empty($society_user)){
            foreach($society_user as $use){

                $photo = asset('public/uploads/images/user.png');

                if(!empty($use->photo)){
                    $photo = asset('public/uploads/images/'.$use->photo);
                }
                $use->photo = $photo;
            }
        }
    }



    return response()->json([
        'result' => true,
        'message' => 'Succesfully',
        'users'=>$society_user,

    ],200);





}



public function service_category(Request $request){
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

    $categories = [];
//print_r($user);

    $categories = Services::select('id','name')->where('status',1)->where('is_delete',0)->get();
    if(!empty($categories)){
        foreach($categories as $cat){
            $count = ServiceUsers::where('society_id',$user->society_id)->where('service_id',$cat->id)->count();
            $cat->count = $count;
        }
    }

    return response()->json([
        'result' => true,
        'message' => 'Succesfully',
        'categories' =>$categories,

    ],200);


}

public function service_user_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'cat_id' => 'required',
    ]);
    $user = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),

        ],400);
    }
    $users = JWTAuth::parseToken()->authenticate();
    if (empty($users)){
        return response()->json([
            'result' => false,
            'message' => '',
        ],401);
    }

    $service_user = [];

    $service_user = ServiceUsers::where('society_id',$users->society_id)->where('service_id',$request->cat_id)->paginate(15);
    if(!empty($service_user)){
        foreach($service_user as $user){
            $image = $this->url.'usersapi/public/uploads/images/user.png';
            $rating = 0.0;
            $no_of_house = 1;
            if(!empty($user->image)){
                $image = $this->url.'/storage/app/public/service_user/'.$user->image;;
            }
            $user->image = $image;
            $details = null;

            $user->no_of_house = $no_of_house;



            $user->service_name = Services::where('id',$user->service_id)->first()->name;

            $user->is_added = 0;

            $exist = [];

            $exist = UserDailyHelp::where('service_id',$user->service_id)->where('service_user_id',$user->id)->where('flat_id',$users->flat_no)->first();

            if(!empty($exist)){
                $user->is_added = 1;

            }


            $user->rating = $rating;

                ////////Details//////////////////

                // $details['name'] = $user->name ?? '';
                // $details['image'] = $image;
                // $details['phone'] = $user->phone ?? '';
            $flats = null;
            $flat = [];

            $daily_help = UserDailyHelp::where('service_user_id',$user->id)->get();


            if(!empty($daily_help)){
                foreach($daily_help as $dh){
                    $flat_details = Flats::select('id','flat_no')->where('id',$dh->flat_id)->first();
                    if(!empty($flat_details)){
                        $flatuser = User::where('flat_no',$flat_details->id)->first();
                        $flats['user_name'] = $flatuser->name;
                        $flats['phone'] = $flatuser->phone;


                        $flats['flat_no'] = $flat_details->flat_no;

                        array_push($flat, $flats);
                    }


                }
            }







            $details['house_count'] = count($daily_help);


            $details['flat'] = $flat;




            $user->details = $details;

        }
    }

    return response()->json([
        'result' => true,
        'message' => 'Succesfully',
        'service_user' =>$service_user,

    ],200);


}




public function userVehicle(Request $request){
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

    $vehicle_list = [];

    if($request->key == 'add'){
        $validator =  Validator::make($request->all(), [
            'car_name' => 'required',
            'vehicle_no' => 'required',
            'vehicle_type'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors()),

            ],400);
        }
        $image = '';
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $destinationPath = public_path("/uploads/uservehicle");
            $side = $request->file('photo');
            $side_name = $user->id.'_user_vehicle'.time().'.'.$side->getClientOriginalExtension();
            $side->move($destinationPath, $side_name);
            $image = $side_name;
        }
        $dbArray = [];
        $dbArray['flat_id'] = $user->flat_no;
        $dbArray['car_name'] = $request->car_name;
        $dbArray['vehicle_no'] = $request->vehicle_no;
        $dbArray['type'] = $request->vehicle_type;
        $dbArray['status'] = 1;
        $dbArray['image'] = $image;

        UserVehicle::create($dbArray);

        return response()->json([
            'result' => true,
            'message' => 'Succesfully Added',

        ],200);
    }else{
        $vehicle_list = UserVehicle::where('flat_id',$user->flat_no)->get();
        if(!empty($vehicle_list)){
            foreach($vehicle_list as $vel){
                if(!empty($vel->image)){
                    $vel->image = $this->url.'/usersapi/public/uploads/uservehicle/'.$vel->image;
                }else{
                    $vel->image = $this->url.'/usersapi/public/uploads/uservehicle/car.jpeg';
                }

            }
        }

        return response()->json([
            'result' => true,
            'message' => 'Succesfully',
            'vehicle_list' =>$vehicle_list,

        ],200);
    }








}




public function assign_daily_help(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'service_userId' => 'required',
        'service_id' => 'required',
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

    $dbArray = [];

    $dbArray['service_id'] = $request->service_id;
    $dbArray['service_user_id'] = $request->service_userId;
    $dbArray['flat_id'] = $user->flat_no;
    $dbArray['status'] = 1;

    UserDailyHelp::insert($dbArray);

    return response()->json([
        'result' => true,
        'message' => 'Succesfully Assigned',

    ],200);



}

public function visitors_list(Request $request){
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
    $visitors = [];


    $visitors = Visitor::where('user_id',$user->id)->latest()->paginate(10);

    return response()->json([
        'result' => true,
        'message' => 'Succesfully ',
        'users' => $visitors,

    ],200);



}



public function daily_help_list(Request $request){
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

    $daily_users = [];

    $daily_users = UserDailyHelp::where('flat_id',$user->flat_no)->paginate(10);
    if(!empty($daily_users)){
        foreach($daily_users as $user){
            $service_user = ServiceUsers::where('id',$user->service_user_id)->first();
            $user->name = $service_user->name ?? '';
            $image = $this->url.'usersapi/public/uploads/images/user.png';
            if(!empty($service_user->image)){
                $image = $this->url.'/storage/app/public/service_user/'.$service_user->image;
            }


            $service = Services::where('id',$user->service_id)->first();

            $user->photo = $image;
            $user->phone = $service_user->phone ?? '';
            $user->service_name = $service->name ?? '';

        }
    }



    return response()->json([
        'result' => true,
        'message' => 'Succesfully Assigned',
        'users' => $daily_users,

    ],200);


}



public function complain_type_list(Request $request){
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


    $complain_type_list = [];

    $complaintArr = config('custom.complain_type');
    if(!empty($complaintArr)){
        foreach ($complaintArr as $key => $value) {
            $dbArray = [];
            $dbArray['name'] = $key;
            $dbArray['icon'] = $value;

            $complain_type_list[] = $dbArray;
        }
    }

    return response()->json([
        'result' => true,
        'message' => 'Succesfully Assigned',
        'complain_type_list' => $complain_type_list,

    ],200);




}


public function compaint_list_data(Request $request)
{   
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

         // $complain_type_list = [];

$complaints = Complaints::where('flat_id', $user->flat_no)->paginate(15);

return response()->json([
    'result' => true,
    'message' => 'Succesfully',
    'complaints' => $complaints,

],200);
}

public function complaint_submit_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'complaint_type' => 'required',
        'complaint_category' => 'required',
        'text' => 'required',
        'image' => '',
        'type'=>'required',
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
    $complain_list = [];

    if($request->type == 'save'){
        $dbArray = [];
        $dbArray['society_id'] = $user->society_id;
        $dbArray['block_id'] = $user->block_id;
        $dbArray['flat_id'] = $user->flat_no;
        $dbArray['category'] = $request->complaint_category;
        $dbArray['type'] = $request->complaint_type;
        $dbArray['text'] = $request->text;
        $dbArray['status'] = 'pending';


        if($request->hasFile('image')){
            $file = $request->file('image');

            $destinationPath = public_path("/uploads/images/complaints/");

            $side = $request->file('image');

            $side_name = $user->id.'_user_complaint'.time().'.'.$side->getClientOriginalExtension();

            $side->move($destinationPath, $side_name);

            $dbArray['image'] = $side_name;
        }


        Complaints::create($dbArray);

        return response()->json([
            'result' => true,
            'message' => 'Succesfully Submitted',

        ],200);

    }
    if($request->type == 'list'){

        $complain_list = Complaints::where('flat_id',$user->flat_no)->get();



        return response()->json([
            'result' => true,
            'message' => 'Succesfully ',
            'complain_list' => $complain_list,

        ],200);
    }








}







public function resident_list(Request $request){
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
    $resident_list = [];
    $search = isset($request->search) ? $request->search :'';

    $flats = Flats::where('block_id',$user->block_id)->where('id','!=',$user->flat_no);

    if(!empty($search)){
            //$flats->orWhere('flat_no', 'like', '%' . $search . '%');
    }

    $flats = $flats->get();

    if(!empty($flats)){
        foreach($flats as $flat){

            $user = User::where('flat_no',$flat->id)->where('user_type','owner')->where('is_approve',1)->where('status',1);

            if(!empty($search)){
                $user->where('name', 'like', '%' . $search . '%');
                    // $user->orWhereHas('flats', function ($query) use ($search) {
                    //     $query->where('flat_no', 'like', '%'.$search.'%');
                    // });
            }


            $user = $user->first();




            if(!empty($user)){

                $dbArray = [];
                $dbArray['name'] = $user->name;
                $dbArray['flat_no'] = $flat->flat_no;

                $resident_list[] = $dbArray;
            }


        }
    }


    return response()->json([
        'result' => true,
        'message' => 'Succesfully',
        'resident_list'=>$resident_list,
    ],200);




}


public function approve_visitor(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'requestId' => 'required',
        'status' => 'required',
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
    $resident_list = [];

    $requests = DB::table('guard_visitors')->where('id',$request->requestId)->first();
    if(!empty($requests)){
        if($user->flat_no == $requests->flat_id){
            if($requests->approved_by != '' || $requests->approved_by != NULL){
                return response()->json([
                    'result' => false,
                    'message' => 'Already Approved ',
                ],200);
            }else{
                DB::table('guard_visitors')->where('id',$request->requestId)->update(['is_approve'=>$request->status,'approved_by'=>$user->id]);
                return response()->json([
                    'result' => true,
                    'message' => 'Succesfully',
                ],200);
            }



        }else{
            return response()->json([
                'result' => false,
                'message' => 'Flat No Not Matched',
            ],200);
        }
    }else{
        return response()->json([
            'result' => false,
            'message' => 'Request not Found',
        ],200);
    }


}






public function all_visitors_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $chats = array();
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
    $visitors = [];

    

    $visitors = DB::table('guard_visitors')->where('flat_id',$user->flat_no)->latest()->paginate(10);

    if(!empty($visitors)){
        foreach($visitors as $vis){
            $vis->date = date("d M Y",strtotime($vis->created_at));

            if(!empty($vis->image)){
                $vis->image = $this->url.'guardapi/public/uploads/images/visitors/'.$vis->image;
            }
        }
    }


    return response()->json([
        'result' => 'success',
        'message' => 'Visitors List',
        'all_visitors' =>$visitors,
    ],200);

}

public function send_notification($title, $body, $deviceToken,$type){
    $sendData = array(
        'body' => !empty($body) ? $body : '',
        'title' => !empty($title) ? $title : '',
        'type' => !empty($type) ? $type : '',
        'sound' => 'Default',
        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
    );


        // print_r($sendData);
        // die;

    return $this->fcmNotification($deviceToken,$sendData);
}

public function fcmNotification($device_id, $sendData)
{
        #API access key from Google API's Console
    if (!defined('API_ACCESS_KEY')){
        define('API_ACCESS_KEY', 'AAAAr9vxAJ8:APA91bGeBTtXibJvRjHvmRPLAjqlXYKy3VwlXOjUM2mf1Cj3fqDVUWgBI8uU5jNW7SfGOhQTdg8f5elizeZ5TCc1qfegQ73HoQ-XVwyLcLY5Bw5C2JrkwPJHfL2Yiz2K6k5txiAdl3Fs');
    }

    $fields = array
    (
        'to'    => $device_id,
        'data'  => $sendData,
            //'notification'  => $sendData,

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
    return $result;
         //print_r($result);
}



public function chat_with_guard(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'text' => 'required',
    ]);
    $chats = array();
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


    $dbArray = [];

    $dbArray['user_id'] = $user->id;
    $dbArray['society_id'] = $user->society_id;
    $dbArray['text'] = $request->text;

    Chats::create($dbArray);

    $society_id = $user->society_id;
    $guardId = [];
    $guards = DB::table('guards')->where('society_id',$society_id)->get();
    if(!empty($guards)){
        foreach($guards as $guard){
            $logins = DB::table('guard_logins')->where('user_id',$guard->id)->get();

            $dbArray1 = [];
            $dbArray1['guard_id'] = $guard->id;
            $dbArray1['text'] = $request->text;
            $dbArray1['is_send'] = 1;
            $dbArray1['title'] = 'Message From User';

            DB::table('notifications')->insert($dbArray1);
            $user_id = (string)$user->id;
            if(!empty($logins)){
                foreach($logins as $login){
                    $deviceToken = $login->deviceToken;
                    $title = 'Message From Users';
                    $body = ['id'=>$user_id,'name'=>$user->name,'phone'=>$user->phone];
                       // $deviceToken = 'flwiiO5rSyqTRl6JARIcrd:APA91bFOWeEhSB_vJB-vTa0Os18CRmkvPcJOl1BsWBEsSZW_VeKTJOGSLU2b1l4-drBD44_fbQoT-2VaVaAzq0Z9czln_038QUEjrvUlQLAhLtJbjS2nEWv_mFlICelPTK0GKSFP30rQ';
                    $type = 'redirect_message';

                    $success = $this->send_notification($title, $body, $deviceToken,$type);




                }
            }
        }
    }




    return response()->json([
        'result' => true,
        'message' => 'Message Send Succesfully',

    ],200);


}

public function emergency(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $chats = array();
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

    $flats = Flats::where('id',$user->flat_no)->first();
    $user->flat_no = $flats->flat_no;
    $user->title = $request->title;


    $guards = DB::table('guards')->where('society_id',$user->society_id)->get();
    if(!empty($guards)){
        foreach($guards as $guard){
            $logins = DB::table('guard_logins')->where('user_id',$guard->id)->get();
            $dbArray1 = [];
            $dbArray1['guard_id'] = $guard->id;
            $dbArray1['text'] = $request->text ?? '';
            $dbArray1['is_send'] = 1;
            $dbArray1['type'] = 'emergency';
            $dbArray1['title'] = $request->title ?? 'Emergency From User';



            DB::table('notifications')->insert($dbArray1);





            if(!empty($logins)){
                foreach($logins as $login){
                    $deviceToken = $login->deviceToken;
                    $title = 'Message From Users';
                    $body = ['id'=>(string)$user->id,'name'=>$user->name,'phone'=>$user->phone,'flat_no'=>$flats->flat_no,'title'=>$request->title];
                        //$deviceToken = 'flwiiO5rSyqTRl6JARIcrd:APA91bFOWeEhSB_vJB-vTa0Os18CRmkvPcJOl1BsWBEsSZW_VeKTJOGSLU2b1l4-drBD44_fbQoT-2VaVaAzq0Z9czln_038QUEjrvUlQLAhLtJbjS2nEWv_mFlICelPTK0GKSFP30rQ';
                    $type = 'emergency';
                    $success = $this->send_notification($title, $body, $deviceToken,$type);

                }
            }
        }
    }



    return response()->json([
        'result' => true,
        'message' => 'Message Send Succesfully',
        'user_details'=>$user,
    ],200);


}





public function year_list(Request $request){
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




$monthArr = config('custom.monthArr');
$monthArray = [];
if(!empty($monthArr)){
    foreach ($monthArr as $key => $value) {
        $dbArray = [];
        $dbArray['key'] = $key;
        $dbArray['value'] = $value;

        $monthArray[] = $dbArray;
    }
}

$year = [];

for ($i=date('Y')-10; $i < date('Y')+10; $i++) { 
    $year[] = $i;
}



return response()->json([
    'result' => 'success',
    'message' => 'Succesfully',
    'years' => $year,

        //'months' => $monthArray,
],200);


}


public function notice_boards(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $user = null;
    $notice_list = [];
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


    $notice_list = DB::table('notice_boards')->where('society_id',$user->society_id)->latest()->paginate(10);
    if(!empty($notice_list)){
        foreach($notice_list as $list){
            $documents = DB::table('notice_board_documents')->where('notice_id',$list->id)->get();
            if(!empty($documents)){
                foreach($documents as $document){
                    //$document->file = $this->url.'/'.$document->file;
                    $document->file = $this->url.'/storage/app/public/notice_board/'.$document->file;
                }
            }
            $list->documents = $documents;
        }
    }


    return response()->json([
        'result' => 'success',
        'message' => 'Succesfully',
        'notice_list' => $notice_list,
    ],200);


}

public function billing_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'year' => 'required',
    ]);
    $chats = array();
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
    $paid_bill = [];
    $unpaid_bill = [];

    $paid_bill = Billing::where('flat_id',$user->flat_no)->where('status','paid')->where('year',$request->year)->get();

    if(!empty($paid_bill)){
        foreach($paid_bill as $paid){
            $paid->month = date("F", mktime(0, 0, 0, $paid->month, 10));

        }
    }




    $unpaid_bill = Billing::where('flat_id',$user->flat_no)->where('status','pending')->where('year',$request->year)->get();

    if(!empty($unpaid_bill)){
        foreach($unpaid_bill as $unpaid){
            $unpaid->month = date("F", mktime(0, 0, 0, $unpaid->month, 10));
        }
    }


    return response()->json([
        'result' => 'success',
        'message' => 'Succesfully',
        'year' => $request->year,
        'unpaid_bill'=>$unpaid_bill,
        'paid_bill'=>$paid_bill,
    ],200);





}

public function create_payment(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'unpaidIds' => 'required',
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

    $api_key = 'rzp_test_PrWbblwHjSxp2p';
    $api_secret = 'ecOIpaWurUy28fjgj3VxPWOw';
    $api = new Api($api_key, $api_secret);

    $amount = 0;

    $unpaidIds = explode(",",$request->unpaidIds);
    if(!empty($unpaidIds)){
        foreach ($unpaidIds as $key => $value) {
            $bill = Billing::where('id',$value)->first();
            if(!empty($bill)){
                if($bill->status == 'pending'){
                    $amount+=$bill->cost;
                }
            }
        }   
    }

    if($amount == 0){
       return response()->json([
        'result' => false,
        'message' => 'No pending Bills Found',
    ],400);
   }

   $paymentArr = [];
   $paymentArr['currency'] = "INR"; 
   $paymentArr['amount'] = $amount * 100; 
   $order = $api->order->create($paymentArr);
   $orderId = $order['id'];
   $user_payment = new Payment;

   $user_payment->name =  $user->name ?? '';
   $user_payment->amount = $amount;
   $user_payment->unpaidIds = $request->unpaidIds;
   $user_payment->payment_id = $orderId;
   $user_payment->save();

   return response()->json([
    'result' => true,
    'message' => 'Succesfully',
    'payment_details' => $user_payment,
    'callback_url' => url('api/check_payment'),
],200);
}


public function check_payment(Request $request){
    $data = $request->all();
    $user = Payment::where('payment_id', $data['razorpay_order_id'])->first();
    $user->payment_done = true;
    $user->razorpay_id = $data['razorpay_payment_id'];
    $api = new Api('rzp_test_PrWbblwHjSxp2p', 'ecOIpaWurUy28fjgj3VxPWOw');
    try{
        $attributes = array(
           'razorpay_signature' => $data['razorpay_signature'],
           'razorpay_payment_id' => $data['razorpay_payment_id'],
           'razorpay_order_id' => $data['razorpay_order_id']
       );
        $order = $api->utility->verifyPaymentSignature($attributes);
        $success = true;
    }catch(SignatureVerificationError $e){

        $succes = false;
    }


    if($success){
        $user->save();
        
        ///////Update paid Status

        $payments = Payment::where('payment_id', $data['razorpay_order_id'])->first();
        if(!empty($payments)){
            $unpaidIds = $payments->unpaidIds;
            if(!empty($unpaidIds)){
                $unpaidIds = explode(",", $unpaidIds);
                if(!empty($unpaidIds)){
                    foreach ($unpaidIds as $key => $value) {
                     Billing::where('id',$value)->update([
                        'txn_no'=>$data['razorpay_payment_id'],
                        'status'=>'paid',
                        'paid_by'=>$payments->name ?? '',

                    ]);
                 }
             }
         }
     }



 }else{


 }

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


public function chat_list(Request $request){
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

$chats = [];

$chat_list = DB::table('chats')->where('user_id',$user->id)->latest()->get();

return response()->json([
    'result' => true,
    'message' => 'Succesfully',
    'chat_list' => $chat_list,
],200); 
}


public function booking_request(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'key' => '',
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
    if($request->key == 'add'){
       $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'type' => 'required',
        'description' => 'required',
        'start' => 'required',
        'end' => 'required',

    ]);
       if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),
        ],400);
    }

    $dbArray = [];
    $dbArray['type'] = $request->type;
    $dbArray['user_id'] = $user->id;
    $dbArray['description'] = $request->description;
    $dbArray['start'] = $request->start;
    $dbArray['end'] = $request->end;
    if($request->hasFile('file')){
        $file = $request->file('file');
        $destinationPath = public_path("/uploads/booking_request");
        $side = $request->file('file');
        $side_name = $user->id.'_user_booking_request'.time().'.'.$side->getClientOriginalExtension();
        $side->move($destinationPath, $side_name);
        $dbArray['file'] = $side_name;
        $dbArray['extension'] = $side->getClientOriginalExtension();
    }


    BookingRequest::insert($dbArray);



}




$booking_list = BookingRequest::where('user_id',$user->id)->latest()->paginate(10);
if(!empty($booking_list)){
    foreach($booking_list as $book){
        if(!empty($book->file)){
           $book->file = $this->url.'/usersapi/public/uploads/booking_request/'.$book->file; 
       }
   }
}



return response()->json([
    'result' => true,
    'message' => 'Succesfully',
    'booking_list' => $booking_list,
],200); 
}


public function banners(Request $request)
{
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

   // $banners = [];

    $banners = Banner::where('status',1)->get(); 

    foreach($banners as $bann)
    {
        $bann->image = $this->url.'/public/storage/banner/'.$bann->image;
    }    

    if(empty($banners))
    {
        return response()->json([
            'result' => false,
            'message' => 'Banner Not Available',

        ], 400);

    }

    return response()->json([
        'result' => true,
        'banners' => $banners,

    ], 200);


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





}
