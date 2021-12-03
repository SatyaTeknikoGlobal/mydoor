<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Society;
use App\State;
use App\City;



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

        if (auth()->user()){
         return redirect('admin');
     }

     $method = $request->method();

     if($method == 'POST' || $method == 'post'){
        $rules = [];
        $rules['username'] = 'required';
        $rules['password'] = 'required';

        $this->validate($request, $rules);

        $credentials = $request->only('username', 'password');


        $users = Admin::where('username',$request->username)->first();
        if(!empty($users)){
            if($users->status == 0){
                return back()->with('alert-danger', 'Your Account id Inactive, contact the administrator.');
            }if($users->status == 1){
                if($users->is_approve == 0){
                        return back()->with('alert-danger', 'Your Account is Not Approved');
                }else{
                    if(Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])){
                        $request->session()->regenerate();
                        return redirect('admin');
                    }else{
                        // return view('admin/login/index');
                        return back()->with('alert-danger', 'Invalid Username and Password');
                    }
                }
            }
        }
        



    }

    return view('admin/login/index');
}



public function register(Request $request){
     $data = [];

    $data['societies'] = Society::where('status',1)->get();
    $data['states'] = State::where('status',1)->get();
    $data['cities'] = City::where('status',1)->get();



    $method = $request->method();

    if($method == 'POST' || $method == 'post'){
        $rules = [];
        $rules['email'] = 'required|unique:admins';
        $rules['password'] = 'required';
        $rules['society_id'] = 'required|unique:admins';
        $rules['username'] = 'required|unique:admins';
        $rules['phone'] = 'required|unique:admins';
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
        $dbArray['username'] = $request->username;
        $dbArray['status'] = 0;
        $dbArray['is_approve'] = 0;
        $success = Admin::create($dbArray);
        if($success){
            return view('snippets.pendingforvarification',$dbArray);
        }
    }

    return view('admin/register/index',$data);
}














public function logout(Request $request){


    // auth()->user('admin')->logout();
    Auth::logout();

    $request->session()->invalidate();

    return redirect('/admin');
}

/*End of controller */
}