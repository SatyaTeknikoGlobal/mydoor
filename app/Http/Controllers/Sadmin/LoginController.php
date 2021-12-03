<?php

namespace App\Http\Controllers\Sadmin;

use App\Admin;

use App\State;
use App\Vendor;
use App\City;
use App\Society;
use App\SocietyAdmin;
use DB;




use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller{
    protected $redirectTo = '/';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function index(Request $request){



     if (auth()->guard('sadmin')->user()) return redirect('sadmin');


     $method = $request->method();

     if($method == 'POST' || $method == 'post'){
        $rules = [];
        $rules['email'] = 'required';
        $rules['password'] = 'required';

        $this->validate($request, $rules);



        $exist = SocietyAdmin::where(['email' => $request->email])->first();

        $credentials = $request->only('email', 'password');
        if(!empty($exist)){
            if($exist->is_approve == 1){
        if(Auth::guard('sadmin')->attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $request->session()->regenerate();
            return redirect()->route('sadmin.home');

        }else{
            return view('sadmin/login/index');

        }
    }else{
         return view('snippets.pendingforvarification',$exist);

    }
 }else{
    return view('sadmin/login/index');
    
        
 }



    }

    return view('sadmin/login/index');
}




public function register(Request $request){
    $data = [];

    $data['societies'] = Society::where('status',1)->get();
    $data['states'] = State::where('status',1)->get();
    $data['cities'] = City::where('status',1)->get();



    $method = $request->method();

    if($method == 'POST' || $method == 'post'){
        $rules = [];
        $rules['email'] = 'required|unique:societyadmins';
        $rules['password'] = 'required';
        $rules['society_id'] = 'required';
        $rules['phone'] = 'required|unique:societyadmins';
        $rules['state_id'] = 'required';
        $rules['city_id'] = 'required';
        $rules['location'] = 'required';
        $rules['name'] = 'required';
        $this->validate($request, $rules);
        

        


        $dbArray = [];
        $dbArray['name'] = $request->name;
        $dbArray['phone'] = $request->phone;
        $dbArray['state_id'] = $request->state_id;
        $dbArray['city_id'] = $request->city_id;
        $dbArray['password'] = bcrypt($request->password);
        $dbArray['email'] = $request->email;
        $dbArray['address'] = $request->address;
        $dbArray['society_id'] = $request->society_id;
        $dbArray['address'] = $request->location;
        $dbArray['status'] = 1;
        $dbArray['is_approve'] = 0;

        $success = SocietyAdmin::create($dbArray);


        if($success){
            return view('snippets.pendingforvarification',$dbArray);
        }
        // $credentials = $request->only('email', 'password');

        // if(Auth::guard('sadmin')->attempt(['email' => $request->email, 'password' => $request->password]))
        // {
        //     $request->session()->regenerate();
        //     return redirect()->route('sadmin.home');
        // }
    }






    return view('sadmin/register/index',$data);

}







public function get_city(Request $request){
   $state_id = isset($request->state_id) ? $request->state_id : '';
   $html = '<option value="" selected disabled>Select your City</option>';
   if(!empty($state_id)){
    $cities = City::where('state_id',$state_id)->get();
    if(!empty($cities)){
        foreach($cities as $city){
            $html.='<option value='.$city->id.' >'.$city->name.'</option>';
        }
    }
}


echo $html;
}


public function forgot_password(Request $request){

    $data = [];
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){
        $rules = [];
        $rules['email'] = 'required';


        $this->validate($request, $rules);


    }

    return view('merchant/login/forgot_password',$data);

}


public function get_lat_long(Request $request){
    $address = isset($request->address) ? $request->address :'';
    $apiKey ='AIzaSyBKs8JiHVLxm6mX5xcvV4C2ZPTT7D5mVbU';
    $url = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($address).'&sensor=false&key='.$apiKey;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
    $responseJson = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($responseJson);

    if ($response->status == 'OK') {
        $latitude = $response->results[0]->geometry->location->lat;
        $longitude = $response->results[0]->geometry->location->lng;
        echo json_encode(array('latitude'=>$latitude,'longitude'=>$longitude));
    }

}




public function logout(Request $request){


    // auth()->user('admin')->logout();
    Auth::logout();

    $request->session()->invalidate();

    return redirect('/sadmin');
}

/*End of controller */
}