<?php
namespace App\Http\Controllers;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CustomHelper;
use Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/





// Route::get('/', function () {
//     return view('welcome');
// });

//Route::any('/', 'HomeController@index');
///////////////////////////////////SADMIN/////////////////////////////////////////

// $SADMIN_ROUTE_NAME = CustomHelper::getSadminRouteName();

Route::get('phpartisan', function(){
    $cmd = request('cmd');
    if(!empty($cmd)){
        $exitCode = Artisan::call("$cmd");
    }
});


// Route::match(['get', 'post'], 'sadmin/login', 'Sadmin\LoginController@index')->name('sadmin.login');


Route::match(['get', 'post'], 'get_city', 'Admin\HomeController@get_city')->name('get_city');



// Route::match(['get', 'post'], 'sadmin/forgot_password', 'Sadmin\LoginController@forgot_password')->name('merchant.forgot_password');



// Route::match(['get', 'post'], 'sadmin/register', 'Sadmin\LoginController@register')->name('sadmin.register');



// // echo $SADMIN_ROUTE_NAME;

// // SADMIN
// Route::group(['namespace' => 'Sadmin', 'prefix' => $SADMIN_ROUTE_NAME, 'as' => $SADMIN_ROUTE_NAME.'.', 'middleware' => ['authsocietyadmin']], function() {

//     Route::get('/logout', 'LoginController@logout')->name('logout');

//     Route::match(['get','post'],'/profile', 'HomeController@profile')->name('profile');

//     Route::match(['get','post'],'/change-password', 'HomeController@change_password')->name('change_password');

//     Route::get('/', 'HomeController@index')->name('home');
//     Route::match(['get','post'],'/settings', 'HomeController@cmsPage')->name('cms_pages');






// ////blockes
//     Route::group(['prefix' => 'blockes', 'as' => 'blockes' , 'middleware' => ['allowedsocietymodule:blockes'] ], function() {

//         Route::get('/', 'BlockController@index')->name('.index');
//         Route::match(['get', 'post'], 'add', 'BlockController@add')->name('.add');

//         Route::match(['get', 'post'], 'get_blocks', 'BlockController@get_blocks')->name('.get_blocks');
//         Route::match(['get', 'post'], 'change_block_status', 'BlockController@change_block_status')->name('.change_block_status');

//         Route::match(['get', 'post'], 'edit/{id}', 'BlockController@add')->name('.edit');
//         Route::post('ajax_delete_image', 'BlockController@ajax_delete_image')->name('.ajax_delete_image');
//         Route::match(['get','post'],'delete/{id}', 'BlockController@delete')->name('.delete');
        

//     });




// ////flats
//     Route::group(['prefix' => 'flats', 'as' => 'flats' , 'middleware' => ['allowedsocietymodule:flats'] ], function() {

//         Route::get('/', 'FlatController@index')->name('.index');
//         Route::match(['get', 'post'], 'add', 'FlatController@add')->name('.add');
        
//         Route::match(['get', 'post'], 'get_blocks', 'FlatController@get_blocks')->name('.get_blocks');
//         Route::match(['get', 'post'], 'change_block_status', 'FlatController@change_block_status')->name('.change_block_status');

//         Route::match(['get', 'post'], 'edit/{id}', 'FlatController@add')->name('.edit');
//         Route::post('ajax_delete_image', 'FlatController@ajax_delete_image')->name('.ajax_delete_image');
//         Route::match(['get','post'],'delete/{id}', 'FlatController@delete')->name('.delete');
        

//     });








// });






////////////////////////////////////////ADMIN//////////////////////////////////////////

Route::match(['get', 'post'], '/user-logout', 'Auth\LoginController@logout');


$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


/////Login
Route::match(['get', 'post'], 'admin/login', 'Admin\LoginController@index');

/////Register


Route::match(['get', 'post'], 'admin/register', 'Admin\LoginController@register')->name('admin.register');






Route::match(['get', 'post'], 'update_maintanance_cost', 'Admin\HomeController@update_maintanance_cost')->name('admin.update_maintanance_cost');








// Admin
Route::group(['namespace' => 'Admin', 'prefix' => $ADMIN_ROUTE_NAME, 'as' => $ADMIN_ROUTE_NAME.'.', 'middleware' => ['authadmin']], function() {

    Route::get('/logout', 'LoginController@logout')->name('logout');

    Route::match(['get','post'],'/profile', 'HomeController@profile')->name('profile');
    
    Route::match(['get','post'],'/setting', 'HomeController@setting')->name('setting');


    Route::match(['get','post'],'/upload_xls', 'HomeController@upload_xls')->name('upload_xls');
    
    
    Route::get('fullcalender', 'FullCalenderController@index');
    Route::post('fullcalenderAjax','FullCalenderController@ajax');







    Route::match(['get','post'],'/get_blocks', 'HomeController@get_blocks')->name('get_blocks');
    Route::match(['get','post'],'/get_flats', 'HomeController@get_flats')->name('get_flats');


    

    Route::match(['get','post'],'/upload', 'HomeController@upload')->name('upload');

    Route::match(['get','post'],'/change-password', 'HomeController@change_password')->name('change_password');

    Route::get('/', 'HomeController@index')->name('home');





    Route::match(['get','post'],'get_sub_cat', 'HomeController@get_sub_cat')->name('get_sub_cat');



    // Route::group(['prefix' => 'settings', 'as' => 'settings', 'middleware' => ['allowedmodule:settings'] ], function() {

    //     Route::match(['get', 'post'], '/{setting_id?}', 'SettingsController@index')->name('.index');
    //     //Route::any('/{setting_id}', 'SettingsController@index')->name('.index');
    // });


    


    // roles
    Route::group(['prefix' => 'roles', 'as' => 'roles' , 'middleware' => ['allowedmodule:roles'] ], function() {

        Route::get('/', 'RoleController@index')->name('.index');

        Route::match(['get', 'post'], 'add', 'RoleController@add')->name('.add');

        Route::match(['get', 'post'], 'get_roles', 'RoleController@get_roles')->name('.get_roles');

        Route::match(['get', 'post'], 'change_role_status', 'RoleController@change_role_status')->name('.change_role_status');
        Route::match(['get', 'post'], 'edit/{id}', 'RoleController@add')->name('.edit');

        Route::post('ajax_delete_image', 'RoleController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'RoleController@delete')->name('.delete');
    });



    // permission
    Route::group(['prefix' => 'permission', 'as' => 'permission' , 'middleware' => ['allowedmodule:permission'] ], function() {

        Route::match(['get','post'],'/', 'PermissionController@index')->name('.index');

        Route::match(['get', 'post'], 'add', 'PermissionController@add')->name('.add');
        Route::match(['get', 'post'], 'edit/{id}', 'PermissionController@add')->name('.edit');

        Route::post('ajax_delete_image', 'PermissionController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'PermissionController@delete')->name('.delete');
    });



    Route::group(['prefix' => 'states', 'as' => 'states' , 'middleware' => ['allowedmodule:states'] ], function() {

        Route::get('/', 'StateController@index')->name('.index');

        Route::match(['get', 'post'], '/save/{id?}', 'StateController@save')->name('.save');
    });  





    Route::group(['prefix' => 'cities', 'as' => 'cities' , 'middleware' => ['allowedmodule:cities'] ], function() {

        Route::get('/', 'CityController@index')->name('.index');
        Route::match(['get', 'post'], '/save/{id?}', 'CityController@save')->name('.save');
    });




////blockes
    Route::group(['prefix' => 'blockes', 'as' => 'blockes' , 'middleware' => ['allowedmodule:blockes'] ], function() {

        Route::get('/', 'BlockController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'BlockController@add')->name('.add');

        Route::match(['get', 'post'], 'get_blocks', 'BlockController@get_blocks')->name('.get_blocks');
        Route::match(['get', 'post'], 'change_block_status', 'BlockController@change_block_status')->name('.change_block_status');

        Route::match(['get', 'post'], 'edit/{id}', 'BlockController@add')->name('.edit');
        Route::post('ajax_delete_image', 'BlockController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'BlockController@delete')->name('.delete');
        

    });


/////// banners


     Route::group(['prefix' => 'banners', 'as' => 'banners' , 'middleware' => ['allowedmodule:banners'] ], function() {

        Route::get('/', 'BannerController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'BannerController@add')->name('.add');

        Route::match(['get', 'post'], 'get_banners', 'BannerController@get_banners')->name('.get_banners');
         Route::match(['get', 'post'], 'change_banner_status', 'BannerController@change_banner_status')->name('.change_banner_status');

         Route::match(['get', 'post'], 'edit/{id}', 'BannerController@add')->name('.edit');
        // Route::post('ajax_delete_image', 'BlockController@ajax_delete_image')->name('.ajax_delete_image');
        // Route::match(['get','post'],'delete/{id}', 'BlockController@delete')->name('.delete');
        

    });

/////// NOTICE BOARD


       Route::group(['prefix' => 'notice_board', 'as' => 'notice_board' , 'middleware' => ['allowedmodule:notice_board'] ], function() {

        Route::get('/', 'NoticeBoardController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'NoticeBoardController@add')->name('.add');

        Route::match(['get', 'post'], 'get_notice_board', 'NoticeBoardController@get_notice_board')->name('.get_notice_board');
        Route::match(['get', 'post'], 'change_notice_board_status', 'NoticeBoardController@change_notice_board_status')->name('.change_notice_board_status');

        Route::match(['get', 'post'], 'edit/{id}', 'NoticeBoardController@add')->name('.edit');

        Route::match(['get', 'post'], 'documents/{id}', 'NoticeBoardController@documents')->name('.documents');

        Route::post('ajax_delete_image', 'NoticeBoardController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'NoticeBoardController@delete')->name('.delete');
        Route::match(['get','post'],'delete_document/{doc_id}', 'NoticeBoardController@delete_document')->name('.delete_document');
        

    });




////blockes
    Route::group(['prefix' => 'flats', 'as' => 'flats' , 'middleware' => ['allowedmodule:flats'] ], function() {

        Route::get('/', 'FlatController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'FlatController@add')->name('.add');

        Route::match(['get', 'post'], 'get_flats', 'FlatController@get_flats')->name('.get_flats');
        Route::match(['get', 'post'], 'change_flat_status', 'FlatController@change_flat_status')->name('.change_flat_status');

        Route::match(['get', 'post'], 'edit/{id}', 'FlatController@add')->name('.edit');
        Route::post('ajax_delete_image', 'FlatController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'FlatController@delete')->name('.delete');
        Route::match(['get','post'],'get_blocks_from_society', 'FlatController@get_blocks_from_society')->name('.get_blocks_from_society');
        Route::match(['get','post'],'get_flat_cat_from_society', 'FlatController@get_flat_cat_from_society')->name('.get_flat_cat_from_society');
        

    });



////Guards
    Route::group(['prefix' => 'guards', 'as' => 'guards' , 'middleware' => ['allowedmodule:guards'] ], function() {

        Route::get('/', 'GuardController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'GuardController@add')->name('.add');

        Route::match(['get', 'post'], 'get_guards', 'GuardController@get_guards')->name('.get_guards');
        Route::match(['get', 'post'], 'change_guards_status', 'GuardController@change_guards_status')->name('.change_guards_status');
        Route::match(['get', 'post'], 'change_guards_approve', 'GuardController@change_guards_approve')->name('.change_guards_approve');

        Route::match(['get', 'post'], 'edit/{id}', 'GuardController@add')->name('.edit');
        Route::match(['get','post'],'delete/{id}', 'GuardController@delete')->name('.delete');
       

    });





////blockes
    Route::group(['prefix' => 'flat_categories', 'as' => 'flat_categories' , 'middleware' => ['allowedmodule:flat_categories'] ], function() {

        Route::get('/', 'FlatCategoryController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'FlatCategoryController@add')->name('.add');
        Route::match(['get', 'post'], 'edit/{id}', 'FlatCategoryController@add')->name('.edit');
        Route::post('ajax_delete_image', 'FlatCategoryController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'FlatCategoryController@delete')->name('.delete');

        

    });






  ////notifications
    Route::group(['prefix' => 'notifications', 'as' => 'notifications' , 'middleware' => ['allowedmodule:notifications'] ], function() {
        Route::match(['get','post'],'/', 'NotificationController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'NotificationController@add')->name('.add');
        Route::match(['get', 'post'], 'get_transactions', 'NotificationController@get_transactions')->name('.get_transactions');
        Route::match(['get', 'post'], 'edit/{id}', 'NotificationController@add')->name('.edit');
        Route::post('ajax_delete_image', 'NotificationController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'NotificationController@delete')->name('.delete');

    });





  ////Complaints
    Route::group(['prefix' => 'complaints', 'as' => 'complaints' , 'middleware' => ['allowedmodule:complaints'] ], function() {
        Route::match(['get','post'],'/', 'ComplainController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'ComplainController@add')->name('.add');
        Route::match(['get', 'post'], 'get_complaints', 'ComplainController@get_complaints')->name('.get_complaints');
        Route::match(['get', 'post'], 'edit/{id}', 'ComplainController@add')->name('.edit');
        Route::post('ajax_delete_image', 'ComplainController@ajax_delete_image')->name('.ajax_delete_image');
        Route::post('remark_submit', 'ComplainController@remark_submit')->name('.remark_submit');
        Route::match(['get','post'],'delete/{id}', 'ComplainController@delete')->name('.delete');

        Route::match(['get','post'],'change_complaint_status', 'ComplainController@change_complaint_status')->name('.change_complaint_status');

    });







    ////society
    Route::group(['prefix' => 'society', 'as' => 'society' , 'middleware' => ['allowedmodule:society'] ], function() {

        Route::get('/', 'SocietyController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'SocietyController@add')->name('.add');

        Route::match(['get', 'post'], 'get_society', 'SocietyController@get_society')->name('.get_society');
        Route::match(['get', 'post'], 'change_society_status', 'SocietyController@change_society_status')->name('.change_society_status');

        Route::match(['get', 'post'], 'edit/{id}', 'SocietyController@add')->name('.edit');
        Route::post('ajax_delete_image', 'SocietyController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'SocietyController@delete')->name('.delete');


        Route::match(['get','post'],'documents/{id}', 'SocietyController@documents')->name('.documents');
        Route::match(['get','post'],'info/{id}', 'SocietyController@info')->name('.info');
        Route::match(['get','post'],'delete_info/{id}', 'SocietyController@delete_info')->name('.delete_info');
    });

     ////society
    Route::group(['prefix' => 'admins', 'as' => 'admins' , 'middleware' => ['allowedmodule:admins'] ], function() {

        Route::get('/', 'AdminController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'AdminController@add')->name('.add');

        Route::match(['get', 'post'], 'get_admins', 'AdminController@get_admins')->name('.get_admins');
        Route::match(['get', 'post'], 'change_admins_status', 'AdminController@change_admins_status')->name('.change_admins_status');
        Route::match(['get', 'post'], 'change_admins_approve', 'AdminController@change_admins_approve')->name('.change_admins_approve');

        Route::match(['get', 'post'], 'edit/{id}', 'AdminController@add')->name('.edit');
        Route::post('ajax_delete_image', 'AdminController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'AdminController@delete')->name('.delete');
        Route::match(['get','post'],'change_admins_role', 'AdminController@change_admins_role')->name('.change_admins_role');
        
    });



    ////Flat Owners
    Route::group(['prefix' => 'flatowners', 'as' => 'flatowners' , 'middleware' => ['allowedmodule:flatowners'] ], function() {

        Route::get('/', 'FlatOwnerController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'FlatOwnerController@add')->name('.add');

        Route::match(['get', 'post'], 'get_flatowners', 'FlatOwnerController@get_flatowners')->name('.get_flatowners');
        Route::match(['get', 'post'], 'change_flatowner_status', 'FlatOwnerController@change_flatowner_status')->name('.change_flatowner_status');

        Route::match(['get', 'post'], 'add_bill', 'FlatOwnerController@add_bill')->name('.add_bill');
        
        
        Route::match(['get', 'post'], 'change_vehicle_status', 'FlatOwnerController@change_vehicle_status')->name('.change_vehicle_status');
        Route::match(['get', 'post'], 'change_daily_help_status', 'FlatOwnerController@change_daily_help_status')->name('.change_daily_help_status');

        Route::match(['get', 'post'], 'change_flatowner_approve', 'FlatOwnerController@change_flatowner_approve')->name('.change_flatowner_approve');

        Route::match(['get', 'post'], 'edit/{id}', 'FlatOwnerController@add')->name('.edit');
        Route::post('ajax_delete_image', 'FlatOwnerController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'FlatOwnerController@delete')->name('.delete');
       
        Route::match(['get','post'],'get_flats_from_block', 'FlatOwnerController@get_flats_from_block')->name('.get_flats_from_block');

        
        Route::match(['get', 'post'], 'details/{user_id}', 'FlatOwnerController@details')->name('.family_member');
        
        //Route::match(['get', 'post'], 'add-family-members/{user_id}', 'FlatOwnerController@add_family_member')->name('.add_family_member');


        Route::match(['get', 'post'], 'child_user/{user_id}', 'FlatOwnerController@child_users')->name('.child_user');
        Route::match(['get', 'post'], 'child_user_add/{user_id}', 'FlatOwnerController@child_user_add')->name('.child_user_add');
        Route::match(['get', 'post'], 'child_user_edit/{user_id}/{id}', 'FlatOwnerController@child_user_add')->name('.child_user_edit');


        Route::match(['get', 'post'], 'update_parking', 'FlatOwnerController@update_parking')->name('.update_parking');
        

    });














    ////Services Users
    Route::group(['prefix' => 'service_user', 'as' => 'service_user' , 'middleware' => ['allowedmodule:service_user'] ], function() {

        Route::get('/', 'ServiceUserController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'ServiceUserController@add')->name('.add');

        Route::match(['get', 'post'], 'get_service_users', 'ServiceUserController@get_service_users')->name('.get_service_users');
        Route::match(['get', 'post'], 'change_status', 'ServiceUserController@change_status')->name('.change_status');

        //Route::match(['get', 'post'], 'change_flatowner_approve', 'ServiceUserController@change_flatowner_approve')->name('.change_flatowner_approve');

        Route::match(['get', 'post'], 'edit/{id}', 'ServiceUserController@add')->name('.edit');
        Route::post('ajax_delete_image', 'ServiceUserController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'ServiceUserController@delete')->name('.delete');
       
        Route::match(['get','post'],'get_flats_from_block', 'ServiceUserController@get_flats_from_block')->name('.get_flats_from_block');
    });









    ////Services
    Route::group(['prefix' => 'services', 'as' => 'services' , 'middleware' => ['allowedmodule:services'] ], function() {

        Route::get('/', 'ServicesController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'ServicesController@add')->name('.add');

        Route::match(['get', 'post'], 'get_services', 'ServicesController@get_services')->name('.get_services');
        Route::match(['get', 'post'], 'change_services_status', 'ServicesController@change_services_status')->name('.change_services_status');

        Route::match(['get', 'post'], 'change_flatowner_approve', 'ServicesController@change_flatowner_approve')->name('.change_flatowner_approve');

        Route::match(['get', 'post'], 'edit/{id}', 'ServicesController@add')->name('.edit');
        Route::post('ajax_delete_image', 'ServicesController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'ServicesController@delete')->name('.delete');
       
        Route::match(['get','post'],'get_flats_from_block', 'ServicesController@get_flats_from_block')->name('.get_flats_from_block');
    });













    ////Service Details
    Route::group(['prefix' => 'servicedetails', 'as' => 'servicedetails' , 'middleware' => ['allowedmodule:servicedetails'] ], function() {

        Route::get('/', 'ServiceDetailController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'ServiceDetailController@add')->name('.add');

        Route::match(['get', 'post'], 'get_servicedetails', 'ServiceDetailController@get_servicedetails')->name('.get_servicedetails');
        Route::match(['get', 'post'], 'change_services_detail_status', 'ServiceDetailController@change_services_detail_status')->name('.change_services_detail_status');

        Route::match(['get', 'post'], 'change_flatowner_approve', 'ServiceDetailController@change_flatowner_approve')->name('.change_flatowner_approve');

        Route::match(['get', 'post'], 'edit/{id}', 'ServiceDetailController@add')->name('.edit');
        Route::post('ajax_delete_image', 'ServiceDetailController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'ServiceDetailController@delete')->name('.delete');
       
        Route::match(['get','post'],'get_flats_from_block', 'ServiceDetailController@get_flats_from_block')->name('.get_flats_from_block');
    });



    
});



Route::get('/', 'HomeController@index')->name('home');

