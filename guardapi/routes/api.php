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






Route::post('send_otp', 'ApiController@send_otp');



Route::post('verify_otp', 'ApiController@verify_otp');


Route::post('login', 'ApiController@login');
Route::post('login_password', 'ApiController@loginWithPassword');
Route::get('check_version ', 'ApiController@app_version');







Route::group(['middleware' => 'auth.jwt'], function () {
	Route::match(['get','post'],'logout', 'ApiController@logout');


	Route::match(['get','post'],'profile', 'ApiController@profile');
	Route::match(['get','post'],'update_profile', 'ApiController@update_profile');


	
	Route::match(['get','post'],'visitors_entry', 'ApiController@visitorsEntry');
	Route::match(['get','post'],'notification_list', 'ApiController@notification_list');

	Route::match(['get','post'],'state_city_list', 'ApiController@state_city_list');

	Route::match(['get','post'],'society_list', 'ApiController@society_list');
	Route::match(['get','post'],'get_blocks_list', 'ApiController@get_blocks_list');
	Route::match(['get','post'],'get_flats_list', 'ApiController@get_flats_list');

	Route::match(['get','post'],'residence_details', 'ApiController@residence_details');



	Route::match(['get','post'],'all_visitors_list', 'ApiController@all_visitors_list');


	Route::match(['get','post'],'get_chats', 'ApiController@get_chats');





	Route::match(['get','post'],'service_list', 'ApiController@service_list');
	Route::match(['get','post'],'service_details', 'ApiController@service_details');
	Route::match(['get','post'],'verified_entry', 'ApiController@verified_entry');
	Route::match(['get','post'],'visitors_list', 'ApiController@visitors_list');
	Route::match(['get','post'],'add_multivisitor_entry', 'ApiController@add_multivisitor_entry');



	Route::match(['get','post'],'vehicle_list', 'ApiController@vehicle_list');






	Route::match(['get','post'],'cmspages', 'ApiController@cmspages');








});



