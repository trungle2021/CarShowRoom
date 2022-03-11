<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\ChangeLanguageController;
use App\Http\Controllers\User\UserOrderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\EmployeeAccountController;
use App\Http\Controllers\EmployeeInfoController;
use App\Http\Controllers\ModelInfoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\CarInfoController;
use App\Http\Controllers\User\CostEstimateController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShowroomController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SocialController;
use App\Models\employeeInfo;
use App\Models\showroom;
use App\Models\modelInfo;

use Database\Seeders\CarInfo;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

Route::get('/', function () {

    return redirect()->route('user.home');
});



Route::get('/lang/{locale?}', [ChangeLanguageController::class, 'switch']);

Auth::routes();
Route::get('/redirect/google', [SocialController::class, 'redirectGoogle']);
Route::get('/callback/google', [SocialController::class, 'callbackGoogle']);

Route::prefix('user')->name('user.')->group(function () {
    // Guest - User UnAuthenticated
    Route::middleware(['guest:web', 'PreventBackHistory', 'Localization'])->group(function () {
        Route::get('/fetchInfo_Layout', [UserController::class, 'fetchInfo_Layout'])->name('fetchInfo_Layout');
        Route::get('/home', [UserController::class, 'home'])->name('home'); //guest homepage
        //Authenticate Page
        Route::get('/login', [UserController::class, 'loadLayoutLogin'])->name('login');
        Route::get('/register', [UserController::class, 'loadLayoutRegister'])->name('register');
        Route::post('/create', [UserController::class, 'create'])->name('create');
        Route::post('/authenticate', [UserController::class, 'authenticate'])->name('authenticate');
        //Order Page
        Route::get('/order/{id?}', [UserOrderController::class, 'GuestOrder'])->name('GuestOrder');
        Route::post('/getModelInfo', [UserOrderController::class, 'getModelInfo'])->name('getModelInfo');
        Route::post('/getShowRoom', [UserOrderController::class, 'getShowRoom'])->name('getShowRoom');
        Route::post('/getShowRoomAddress', [UserOrderController::class, 'getShowRoomAddress'])->name('getShowRoomAddress');
        Route::post('/SubmitOrder', [UserOrderController::class, 'GuestSubmitOrder'])->name('GuestSubmitOrder');
        //Cost Estimation Page
        Route::get('/CostEstimate', [CostEstimateController::class, 'index'])->name('CostEstimate');
        Route::post('/CostEstimate/getModelInfo', [CostEstimateController::class, 'getModelInfo'])->name('getModelInfo');
        Route::post('/CostEstimate/getFees', [CostEstimateController::class, 'getFees'])->name('getFees');
    });

    // Customer - User Authenticated
    Route::middleware(['auth:web', 'PreventBackHistory', 'Localization'])->group(function () {
        //Customer Profile Page

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('auth/settings', [DashboardController::class, 'show'])->name('settings');
            Route::get('auth/fetch-data', [DashboardController::class, 'fetchData']);
            Route::post('auth/editfullname', [DashboardController::class, 'editfullname']);
            Route::post('auth/editaddress', [DashboardController::class, 'editaddress']);
            Route::post('auth/editphone', [DashboardController::class, 'editphone']);
            Route::post('auth/editEmail', [DashboardController::class, 'editEmail']);
            Route::post('auth/editCitizenID', [DashboardController::class, 'editCitizenID']);
            Route::post('auth/editAvatar', [DashboardController::class, 'editAvatar']);
        });
        //Function page for customer
        Route::get('/auth/fetchInfo_Layout_auth', [UserController::class, 'fetchInfo_Layout_auth'])->name('fetchInfo_Layout_auth');
        Route::get('auth/home', [UserController::class, 'home_auth'])->name('home_auth'); //customer homepage
        //Customer Order Page 
        Route::get('auth/order/{id?}', [UserOrderController::class, 'CustomerOrder'])->name('CustomerOrder');
        Route::post('/auth/getModelInfo', [UserOrderController::class, 'getModelInfo'])->name('getModelInfo');
        Route::post('/auth/getShowRoom', [UserOrderController::class, 'getShowRoom'])->name('getShowRoom');
        Route::post('/auth/getShowRoomAddress', [UserOrderController::class, 'getShowRoomAddress'])->name('getShowRoomAddress');
        Route::post('/CustomerSubmitOrder', [UserOrderController::class, 'CustomerSubmitOrder'])->name('CustomerSubmitOrder');
        //Customer Cost Estimation
        Route::get('auth/CostEstimate', [CostEstimateController::class, 'index'])->name('CostEstimate');
        Route::post('auth/CostEstimate/getModelInfo', [CostEstimateController::class, 'getModelInfo'])->name('getModelInfo');
        Route::post('auth/CostEstimate/getFees', [CostEstimateController::class, 'getFees'])->name('getFees');
        //Logout 
        Route::post('auth/logout', [UserController::class, 'logout'])->name('logout');
    });
});
Route::get('/sendmail_ordersuccess/{order_code?}', [MailController::class, 'sendmail_ordersuccess']);
Route::post('/CostEstimate/submit', [CostEstimateController::class, 'CostEstimateSubmit'])->name('CostEstimateSubmit');


// KHANG
// Route::get('admin', function () {
//     return view('admin_home');
// });

// group lai sau
// Route::get('admin/showroom', function () {
//     return view('admin.showroom.order');
// });



Route::get('/test', [ModelInfoController::class, 'compare']);



// 







// Route::get('admin/showroom',[OrderDetailController::class,'show']);
// Route::get('admin/showroom/check',[OrderDetailController::class,'orderedstatus']);
// Route::get('admin/showroom/myorder',[OrderDetailController::class,'myorder']);
// Route::get('admin/showroom/ordercanceled',[OrderDetailController::class,'custcanceledtatus']);
// Route::get('admin/showroom/takeorder/{id}',[OrderDetailController::class,'takeorder']);//nhan dơn
// Route::get('admin/showroom/orderdetail/{order_id}/{model_id}',[OrderDetailController::class,'orderdetail']);
// Route::get('admin/showroom/carmanage',[CarInfoController::class,'showroomcar']);
// Route::get('admin/showroom/carmanagepending',[CarInfoController::class,'showroomcarpending']);
// Route::get('admin/showroom/carmanageshowroom',[CarInfoController::class,'showroomcarreceived']);
// Route::get('admin/showroom/confirmorder/{id}',[OrderDetailController::class,'confirmorder']);//xac nhan thông tin
// Route::get('admin/showroom/confirmdeposited/{id}',[OrderDetailController::class,'confirmdeposited']);//xac nhan thông tin


// Route::get('admin/showroom/sold/{id}',[OrderDetailController::class,'soldorder']);//nhan dơn
// Route::get('admin/showroom/ordercanceled/{id}',[OrderDetailController::class,'ordercanceled']);//huy don
// Route::post('admin/showroom/checkinfo',[OrderDetailController::class,'checkinfo']);//confirminfo
// Route::get('admin/showroom/empcancel/{id}',[OrderDetailController::class,'empcancel']);//huy don khách ko thanh toán


// Route::get('admin/general/employee',[EmployeeInfoController::class,'show']);
// Route::post('admin/general/employee/create',[EmployeeAccountController::class,'create']);
// // Route::post('admin/general/employee/create', 'EmployeeAccountController@create');
// Route::get('admin/general/customer',[DashboardController::class,'showcustlist']);
// Route::get('admin/general/customer/detail/{id}',[DashboardController::class,'detail']);
// Route::get('admin/general/report',[ReportController::class,'report']);
// Route::get('admin/general/empcreate', [ShowroomController::class,'create']);
// Route::post('admin/general/empchangepass/{id}',[EmployeeAccountController::class,'empchangepass']);
// Route::post('admin/general/employee/accountcreate',[EmployeeAccountController::class,'accountcreate']);

//test




// Route::get('admin/warehouse',[CarInfoController::class,'show']);
// Route::get('admin/warehouse/ordering',[OrderDetailController::class,'confirmstatus']);
// Route::get('admin/warehouse/delete', [CarInfoController::class,'carcancel']);
// Route::get('admin/warehouse/stock', [StockController::class,'show']);
// Route::get('admin/warehouse/stockadd/{id}', [StockController::class,'addstock']);
// Route::get('admin/warehouse/car/delete/{id}', [CarInfoController::class,'cardelete']);
// Route::post('admin/warehouse/ordering/create', [CarInfoController::class,'carcreate']);
// Route::get('admin/warehouse/carpending/{id}',[CarInfoController::class,'carpending']);
// Route::get('admin/warehouse/released',[CarInfoController::class,'released']);







// ok
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware(['guest:employee', 'PreventBackHistory'])->group(function () {
        Route::view('/login', 'admin.adminprofile.adminlogin')->name('login');
        Route::post('/authenticate', [EmployeeAccountController::class, 'authenticate'])->name('authenticate');
    });

    Route::middleware(['auth:employee', 'PreventBackHistory'])->group(function () {
        Route::view('/home', 'admin_home')->name('home');

        // profile


        Route::prefix('profile')->name('profile.')->group(function () {

            Route::get('myprofile', [EmployeeInfoController::class, 'fetchData']);
            Route::post('myprofile/editfullname', [EmployeeInfoController::class, 'updatename']);
            Route::post('myprofile/editaddress', [EmployeeInfoController::class, 'updateaddress']);
            Route::post('myprofile/editphone', [EmployeeInfoController::class, 'updatephone']);
            Route::post('myprofile/editpassword', [EmployeeInfoController::class, 'updatepassword']);
            // Route::post('auth/editAvatar', [EmployeeInfoController::class, 'editAvatar']);
        });




        // //showroom
        Route::middleware(['auth:employee', 'PreventBackHistory', 'showroom'])->group(function () {

            Route::get('showroom', [OrderDetailController::class, 'show']);
            Route::get('showroom/check', [OrderDetailController::class, 'orderedstatus']);
            Route::get('showroom/myorder', [OrderDetailController::class, 'myorder']);
            Route::get('showroom/ordercanceled', [OrderDetailController::class, 'custcanceledtatus']);
            Route::get('showroom/takeorder/{id}', [OrderDetailController::class, 'takeorder']); //nhan dơn
            Route::get('showroom/orderdetail/{order_id}/{model_id}', [OrderDetailController::class, 'orderdetail']);
            Route::get('showroom/carmanage', [CarInfoController::class, 'showroomcar']);
            Route::get('showroom/carmanagepending', [CarInfoController::class, 'showroomcarpending']);
            Route::get('showroom/carmanageshowroom', [CarInfoController::class, 'showroomcarreceived']);
            Route::get('showroom/confirmorder/{id}', [OrderDetailController::class, 'confirmorder']); //xac nhan thông tin
            Route::get('showroom/confirmdeposited/{id}', [OrderDetailController::class, 'confirmdeposited']); //xac nhan thông tin
            Route::get('showroom/sold/{id}', [OrderDetailController::class, 'soldorder']); //nhan dơn
            Route::get('showroom/ordercanceled/{id}', [OrderDetailController::class, 'ordercanceled']); //huy don
            Route::post('showroom/checkinfo', [OrderDetailController::class, 'checkinfo']); //confirminfo
            Route::get('showroom/empcancel/{id}', [OrderDetailController::class, 'empcancel']); //huy don khách ko thanh toán
            //general admin

        });
        Route::middleware(['auth:employee', 'PreventBackHistory', 'general'])->group(function () {


            Route::view('/general', 'admin.general.general')->name('general.');
            Route::get('general/employee', [EmployeeInfoController::class, 'show']);
            Route::post('general/employee/create', [EmployeeAccountController::class, 'create']);
            // Route::post('admin/general/employee/create', 'EmployeeAccountController@create');
            Route::get('general/customer', [DashboardController::class, 'showcustlist']);
            Route::get('general/customer/detail/{id}', [DashboardController::class, 'detail']);
            Route::get('general/report', [ReportController::class, 'report']);
            Route::get('general/empcreate', [ShowroomController::class, 'create']);
            Route::post('general/empchangepass/{id}', [EmployeeAccountController::class, 'empchangepass']);
            Route::post('general/employee/accountcreate', [EmployeeAccountController::class, 'accountcreate']);

            //    phat
            Route::get('general/add-model', function () {
                return view('admin.general.add_model', [ModelInfoController::class, 'addModel']);
            });

            Route::get('general/edit-model/{model_id}', function () {
                return view('admin.general.edit_model', [ModelInfoController::class, 'editModel']);
            });
            Route::get('general/deletemodel/{model_id}', function () {
                return view('admin.general.all_model', [ModelInfoController::class, 'deleteModel']);
            });
            Route::get('general/allmodel', function () {
                return view('admin.general.all_model');
                // ,[ModelInfoController::class,'allModel']);
            });
            Route::get('general/save_model', function () {
                return view('admin.general.add_model', [ModelInfoController::class, 'saveModel']);
            });
            Route::get('general/update_model/{model_id}', function () {
                return view('admin.general.all_model', [ModelInfoController::class, 'updateModel']);
            });
            //model
            Route::get('general/addmodel', 'ModelInfoController@addModel');
            Route::get('general/editmodel/{model_id}', 'ModelInfoController@editModel');
            Route::get('general/deletemodel/{model_id}', 'ModelInfoController@deleteModel');
            Route::get('general/allmodel', 'ModelInfoController@allModel');


            Route::post('general/savemodel', 'ModelInfoController@saveModel');
            Route::post('general/updatemodel/{model_id}', 'ModelInfoController@updateModel');
        });
        //warehouse

        Route::middleware(['auth:employee', 'PreventBackHistory', 'warehouse'])->group(function () {
            Route::get('warehouse', [CarInfoController::class, 'show']);
            Route::get('warehouse/ordering', [OrderDetailController::class, 'confirmstatus']);
            Route::get('warehouse/delete', [CarInfoController::class, 'carcancel']);
            Route::get('warehouse/stock', [StockController::class, 'show']);
            Route::get('warehouse/stockadd/{id}', [StockController::class, 'addstock']);
            Route::get('warehouse/car/delete/{id}', [CarInfoController::class, 'cardelete']);
            Route::post('warehouse/ordering/create', [CarInfoController::class, 'carcreate']);
            Route::get('warehouse/carpending/{id}', [CarInfoController::class, 'carpending']);
            Route::get('warehouse/released', [CarInfoController::class, 'released']);
            // tao acc fake lười
            // Route::get('warehouse/taoacc', [DashboardController::class, 'taoacc']);
            //   Route::get('warehouse/taoxe', [DashboardController::class, 'taoxe']);

        });




        Route::post('/logout', [EmployeeAccountController::class, 'logout'])->name('logout');
    });
});

// Route::view('/profile','dashboard.user.profile')->name('profile');
// KHANGEND


// Route::get('admin/general/add-model', function () {
//     return view('admin.general.add_model', [ModelInfoController::class, 'addModel']);
// });

// Route::get('admin/general/edit-model/{model_id}', function () {
//     return view('admin.general.edit_model', [ModelInfoController::class, 'editModel']);
// });
// Route::get('admin/general/deletemodel/{model_id}', function () {
//     return view('admin.general.all_model', [ModelInfoController::class, 'deleteModel']);
// });
// Route::get('admin/general/allmodel', function () {
//     return view('admin.general.all_model');
//     // ,[ModelInfoController::class,'allModel']);
// });
// Route::get('admin/general/save_model', function () {
//     return view('admin.general.add_model', [ModelInfoController::class, 'saveModel']);
// });
// Route::get('admin/general/update_model/{model_id}', function () {
//     return view('admin.general.all_model', [ModelInfoController::class, 'updateModel']);
// });
// //model
// Route::get('admin/general/addmodel', 'ModelInfoController@addModel');
// Route::get('admin/general/editmodel/{model_id}', 'ModelInfoController@editModel');
// Route::get('admin/general/deletemodel/{model_id}', 'ModelInfoController@deleteModel');
// Route::get('admin/general/allmodel', 'ModelInfoController@allModel');

// Route::get('user/modeldetails/{model_id?}', 'ModelInfoController@showModel');
// Route::post('admin/general/savemodel', 'ModelInfoController@saveModel');
// Route::post('admin/general/updatemodel/{model_id}', 'ModelInfoController@updateModel');


Route::get('/compare', 'CompareController@index');

Route::get('/user/compare', function () {
    return view('compare');
});
