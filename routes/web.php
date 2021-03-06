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
use App\Http\Controllers\CheckoutController;
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


Route::prefix('user')->name('user.')->group(function(){
    // Guest - User UnAuthenticated
    Route::middleware(['guest:web','PreventBackHistory','Localization'])->group(function(){        
            Route::get('/fetchInfo_Layout',[UserController::class,'fetchInfo_Layout'])->name('fetchInfo_Layout');
            Route::get('/home',[UserController::class,'home'])->name('home'); //guest homepage
            //Authenticate Page
            Route::get('/login',[UserController::class,'loadLayoutLogin'])->name('login'); 
            Route::get('/register',[UserController::class,'loadLayoutRegister'])->name('register');
            Route::post('/create',[UserController::class,'create'])->name('create');
            Route::post('/authenticate',[UserController::class,'authenticate'])->name('authenticate');
            //Forgot Password
            Route::get('/password/forgot',[UserController::class,'showForgotForm'])->name('forgot.password.form'); 
            Route::post('/password/forgot',[UserController::class,'sendResetLink'])->name('forgot.password.link'); 
            Route::get('/password/reset/{token}',[UserController::class, 'showResetForm'])->name('reset.password.form');
            Route::post('/password/reset',[UserController::class,'resetPassword'])->name('reset.password');
            //Cost Estimation Page
            Route::get('/CostEstimate/{id?}/{matp?}',[CostEstimateController::class,'index'])->name('CostEstimate');
            Route::post('/CostEstimate/getModelInfo',[CostEstimateController::class,'getModelInfo'])->name('getModelInfo');
            Route::post('/CostEstimate/getFees',[CostEstimateController::class,'getFees'])->name('getFees');
            // Route::get('/CostEstimate/OrderCar',[UserOrderController::class,'GuestOrder'])->name('GuestCostEstimateSubmit');
            //Order Page
            Route::get('/order/{id?}/{provinces?}',[UserOrderController::class,'GuestOrder'])->name('GuestOrder');
            Route::post('/getModelInfo',[UserOrderController::class,'getModelInfo'])->name('getModelInfo');
            Route::post('/getShowRoom',[UserOrderController::class,'getShowRoom'])->name('getShowRoom');
            Route::post('/getShowRoomAddress',[UserOrderController::class,'getShowRoomAddress'])->name('getShowRoomAddress');
            Route::post('/SubmitOrder',[UserOrderController::class,'GuestSubmitOrder'])->name('GuestSubmitOrder');
            //car compare
            Route::get('/compare','CompareController@index');
            //car detail
            Route::get('/modeldetails/{model_id?}', 'ModelInfoController@showModel');
     });
    

    // Customer - User Authenticated
    Route::middleware(['auth:web', 'PreventBackHistory', 'Localization'])->group(function () {
        //Customer Profile Page

        
        Route::prefix('profile')->name('profile.')->group(function(){
            Route::get('auth/settings',[DashboardController::class,'show'])->name('settings');
            Route::get('auth/order_history',[DashboardController::class,'show_order_history_page'])->name('order_history');
            Route::get('auth/fetch-data',[DashboardController::class,'fetchData']);
            Route::post('auth/editfullname',[DashboardController::class,'editfullname']); 
            Route::post('auth/editaddress',[DashboardController::class,'editaddress']); 
            Route::post('auth/editphone',[DashboardController::class,'editphone']); 
            Route::post('auth/editEmail',[DashboardController::class,'editEmail']); 
            Route::post('auth/change_password',[DashboardController::class,'change_password']); 
            Route::post('auth/editCitizenID',[DashboardController::class,'editCitizenID']); 
            Route::post('auth/editAvatar',[DashboardController::class,'editAvatar']); 
        });
        //Function page for customer
            Route::get('/auth/fetchInfo_Layout_auth',[UserController::class,'fetchInfo_Layout_auth'])->name('fetchInfo_Layout_auth');
            Route::get('auth/home',[UserController::class,'home_auth'])->name('home_auth'); //customer homepage
            //Customer Cost Estimation
            Route::get('auth/CostEstimate/{id?}/{matp?}',[CostEstimateController::class,'index'])->name('CostEstimate');
            Route::post('auth/CostEstimate/getModelInfo',[CostEstimateController::class,'getModelInfo'])->name('getModelInfo');
            Route::post('auth/CostEstimate/getFees',[CostEstimateController::class,'getFees'])->name('getFees');
           
           
            //Customer Order Page 
            Route::get('auth/order/{id?}/{provinces?}',[UserOrderController::class,'CustomerOrder'])->name('CustomerOrder');
            Route::post('/auth/getModelInfo',[UserOrderController::class,'getModelInfo'])->name('getModelInfo');
            Route::post('/auth/getShowRoom',[UserOrderController::class,'getShowRoom'])->name('getShowRoom');
            Route::post('/auth/getShowRoomAddress',[UserOrderController::class,'getShowRoomAddress'])->name('getShowRoomAddress');
            Route::post('auth/CustomerSubmitOrder',[UserOrderController::class,'CustomerSubmitOrder'])->name('CustomerSubmitOrder');
             //car compare
            Route::get('auth/compare','CompareController@index');
            //car detail
            Route::get('auth/modeldetails/{model_id?}', 'ModelInfoController@showModel');
            //Logout 
            Route::post('auth/logout',[UserController::class,'logout'])->name('logout');
    });
     //Order Tracking
     Route::post('/getOrderCode',[UserOrderController::class,'getOrderCode'])->name('getOrderCode')->middleware('Localization');
     Route::get('/order_tracking',[UserOrderController::class,'order_tracking'])->name('ordertracking')->middleware('Localization');
     Route::post('/cancel_contract',[UserOrderController::class,'cancelContract'])->name('cancelContract')->middleware('Localization');

    //momo
    Route::get('/momo_payment/{order_id}/{deposit}',[CheckoutController::class,'momo_payment'])->name('momo_payment')->middleware('Localization');
    Route::get('/checkout',[CheckoutController::class,'checkout'])->name('checkout')->middleware('Localization');
    Route::get('/transaction_expired',[CheckoutController::class,'transaction_expired'])->name('transaction_expired')->middleware('Localization');;
   
});

Route::post('/sendmail_ordersuccess/{order_code?}',[MailController::class,'sendmail_ordersuccess'])->middleware("Localization");
// Route::get('/deposit_require/{order_code?}',[MailController::class,'sendmail_deposit_require'])->middleware("Localization");
Route::post('/send_cancel_code/{order_code?}',[MailController::class,'send_cancel_code'])->middleware("Localization");


// KHANG
// Route::get('admin', function () {
//     return view('admin_home');
// });

// group lai sau
// Route::get('admin/showroom', function () {
//     return view('admin.showroom.order');
// });

// Route::get('admin/showroom',[OrderDetailController::class,'show']);
// Route::get('admin/showroom/check',[OrderDetailController::class,'orderedstatus']);
// Route::get('admin/showroom/myorder',[OrderDetailController::class,'myorder']);
// Route::get('admin/showroom/ordercanceled',[OrderDetailController::class,'custcanceledtatus']);
// Route::get('admin/showroom/takeorder/{id}',[OrderDetailController::class,'takeorder']);//nhan d??n
// Route::get('admin/showroom/orderdetail/{order_id}/{model_id}',[OrderDetailController::class,'orderdetail']);
// Route::get('admin/showroom/carmanage',[CarInfoController::class,'showroomcar']);
// Route::get('admin/showroom/carmanagepending',[CarInfoController::class,'showroomcarpending']);
// Route::get('admin/showroom/carmanageshowroom',[CarInfoController::class,'showroomcarreceived']);
// Route::get('admin/showroom/confirmorder/{id}',[OrderDetailController::class,'confirmorder']);//xac nhan th??ng tin
// Route::get('admin/showroom/confirmdeposited/{id}',[OrderDetailController::class,'confirmdeposited']);//xac nhan th??ng tin


// Route::get('admin/showroom/sold/{id}',[OrderDetailController::class,'soldorder']);//nhan d??n
// Route::get('admin/showroom/ordercanceled/{id}',[OrderDetailController::class,'ordercanceled']);//huy don
// Route::post('admin/showroom/checkinfo',[OrderDetailController::class,'checkinfo']);//confirminfo
// Route::get('admin/showroom/empcancel/{id}',[OrderDetailController::class,'empcancel']);//huy don kh??ch ko thanh to??n


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
            Route::get('showroom/takeorder/{id}', [OrderDetailController::class, 'takeorder']); //nhan d??n
            Route::get('showroom/orderdetail/{order_id}/{model_id}', [OrderDetailController::class, 'orderdetail']);
            Route::get('showroom/carmanage', [CarInfoController::class, 'showroomcar']);
            Route::get('showroom/carmanagepending', [CarInfoController::class, 'showroomcarpending']);
            Route::get('showroom/carmanageshowroom', [CarInfoController::class, 'showroomcarreceived']);
            Route::get('showroom/confirmorder/{id}', [OrderDetailController::class, 'confirmorder']); //xac nhan th??ng tin
            Route::get('showroom/confirmdeposited/{id}', [OrderDetailController::class, 'confirmdeposited']); //xac nhan th??ng tin
            Route::get('showroom/sold/{id}', [OrderDetailController::class, 'soldorder']); //nhan d??n
            Route::get('showroom/ordercanceled/{id}', [OrderDetailController::class, 'ordercanceled']); //huy don
            Route::post('showroom/checkinfo', [OrderDetailController::class, 'checkinfo']); //confirminfo
            Route::get('showroom/empcancel/{id}', [OrderDetailController::class, 'empcancel']); //huy don kh??ch ko thanh to??n
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
            // tao acc fake l?????i
            // Route::get('warehouse/taoacc', [DashboardController::class, 'taoacc']);
            //   Route::get('warehouse/taoxe', [DashboardController::class, 'taoxe']);

        });




        Route::post('/logout', [EmployeeAccountController::class, 'logout'])->name('logout');
    });
});

// Route::view('/profile','dashboard.user.profile')->name('profile');
// KHANGEND



// Route::get('admin/general/add-model', function () {
//     return view('admin.general.add_model',[ModelInfoController::class,'addModel']);
// });

// Route::get('admin/general/edit-model/{model_id}', function () {
//     return view('admin.general.edit_model',[ModelInfoController::class,'editModel']);
// });
// Route::get('admin/general/deletemodel/{model_id}', function () {
//     return view('admin.general.all_model',[ModelInfoController::class,'deleteModel']);
// });
// Route::get('admin/general/allmodel', function () {
//     return view('admin.general.all_model');
//     // ,[ModelInfoController::class,'allModel']);
// });
// Route::get('admin/general/save_model', function () {
//     return view('admin.general.add_model',[ModelInfoController::class,'saveModel']);
// });Route::get('admin/general/update_model/{model_id}', function () {
//     return view('admin.general.all_model',[ModelInfoController::class,'updateModel']);
// });

//model
// Route::get('admin/general/addmodel', 'ModelInfoController@addModel');
// Route::get('admin/general/editmodel/{model_id}', 'ModelInfoController@editModel');
// Route::get('admin/general/deletemodel/{model_id}', 'ModelInfoController@deleteModel');
// Route::get('admin/general/allmodel', 'ModelInfoController@allModel');


// Route::post('admin/general/savemodel', 'ModelInfoController@saveModel');
// Route::post('admin/general/updatemodel/{model_id}', 'ModelInfoController@updateModel');



Route::get('/AboutUs',function(){
    return(view('AboutUs'));
});
