<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/api',function(){
	return "Test api";
});



Route::post('send_otp', 'ApiController@send_otp');



Route::post('verify_otp', 'ApiController@verify_otp');




Route::match(['get','post'],'check_payment', 'ApiController@check_payment');



Route::post('send_otp_email', 'ApiController@send_otp_email');
Route::post('login', 'ApiController@login');
Route::post('login_password', 'ApiController@loginWithPassword');
Route::post('signup', 'ApiController@register');
Route::get('check_version ', 'ApiController@app_version');
Route::get('country_list ', 'ApiController@country_list');
Route::get('language_list ', 'ApiController@language_list');
Route::get('subcategory_list ', 'ApiController@subcategory_list');
Route::post('checkUsername ', 'ApiController@checkUsername');
Route::post('checkEmail ', 'ApiController@checkEmail');
Route::post('apply_referral_code ', 'ApiController@apply_referral_code');


Route::match(['get','post'],'send_test_notification', 'ApiController@send_test_notification');




Route::post('social_login ', 'ApiController@social_login');




Route::group(['middleware' => 'auth.jwt'], function () {
	Route::match(['get','post'],'logout', 'ApiController@logout');


	Route::match(['get','post'],'profile', 'ApiController@profile');
	Route::match(['get','post'],'update_profile', 'ApiController@update_profile');
	Route::match(['get','post'],'visitors_entry', 'ApiController@visitorsEntry');
	Route::match(['get','post'],'notification_list', 'ApiController@notification_list');

	
	Route::match(['get','post'],'notification_details', 'ApiController@notification_details');

	Route::match(['get','post'],'state_city_list', 'ApiController@state_city_list');

	Route::match(['get','post'],'society_list', 'ApiController@society_list');
	Route::match(['get','post'],'get_blocks_list', 'ApiController@get_blocks_list');
	Route::match(['get','post'],'get_flats_list', 'ApiController@get_flats_list');
	
	Route::match(['get','post'],'uploadUserDocument', 'ApiController@uploadUserDocument');
	Route::match(['get','post'],'removeUserDocument', 'ApiController@removeUserDocument');
	Route::match(['get','post'],'get_user_document', 'ApiController@get_user_document');



	Route::match(['get','post'],'add_family_member', 'ApiController@add_family_member');


	Route::match(['get','post'],'service_category', 'ApiController@service_category');
	Route::match(['get','post'],'service_user_list', 'ApiController@service_user_list');
	Route::match(['get','post'],'sub_category_list', 'ApiController@sub_category_list');


	Route::match(['get','post'],'assign_daily_help', 'ApiController@assign_daily_help');
	Route::match(['get','post'],'visitors_list', 'ApiController@visitors_list');


	Route::match(['get','post'],'daily_help_list', 'ApiController@daily_help_list');

	Route::match(['get','post'],'userVehicle', 'ApiController@userVehicle');


	Route::match(['get','post'],'approve_visitor', 'ApiController@approve_visitor');



	Route::match(['get','post'],'all_visitors_list', 'ApiController@all_visitors_list');
	
	Route::match(['get','post'],'chat_with_guard', 'ApiController@chat_with_guard');



    Route::match(['get','post'],'emergency', 'ApiController@emergency');
    
    Route::match(['get','post'],'notice_boards', 'ApiController@notice_boards');




    Route::match(['get','post'],'billing_list', 'ApiController@billing_list');

    Route::match(['get','post'],'year_list', 'ApiController@year_list');



    Route::match(['get','post'],'emergency_nos', 'ApiController@emergency_nos');



	Route::match(['get','post'],'cmspages', 'ApiController@cmspages');



	Route::match(['get','post'],'complain_type_list', 'ApiController@complain_type_list');

	Route::match(['get','post'], 'compaint_list_data', 'ApiController@compaint_list_data');

	Route::match(['get','post'],'complaint_submit_list', 'ApiController@complaint_submit_list');
	Route::match(['get','post'],'resident_list', 'ApiController@resident_list');




	Route::match(['get','post'],'create_payment', 'ApiController@create_payment');


	Route::match(['get','post'],'chat_list', 'ApiController@chat_list');
	Route::match(['get','post'],'booking_request', 'ApiController@booking_request');

	Route::match(['get','post'], 'banners', 'ApiController@banners');


	





});



