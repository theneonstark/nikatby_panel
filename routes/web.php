<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Service\BbpsController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [UserController::class, 'loginpage'])->middleware('guest')->name('mylogin');
Route::get('login', [UserController::class, 'loginpage'])->middleware('guest');
Route::get('start', [UserController::class, 'signup'])->name('signup');

Route::group(['prefix' => 'auth'], function () {
    Route::post('check', [UserController::class, 'login'])->name('authCheck');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('reset', [UserController::class, 'passwordReset'])->name('authReset');
    Route::post('getotp', [UserController::class, 'getotp'])->name('getotp');
    Route::post('setpin', [UserController::class, 'setpin'])->name('setpin');
    Route::post('onboard', [UserController::class, 'web_onboard'])->name('web_onboard');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [HomeController::class, 'index']);

    Route::group(['prefix' => 'bbps'], function () {
        Route::get('/services', [BbpsController::class, 'services']);
        Route::get('/service/{category}', [BbpsController::class, 'getAllOperator']);
        Route::post('/biller', [BbpsController::class, 'billerId']);
        Route::post('/fetchbill', [BbpsController::class, 'fetchBill']);
        Route::post('/paybill', [BbpsController::class, 'paybill']);
    


    // Route::get('/billerInfo', [BbpsController::class, 'billerInfo']);
    // Route::get('/fetchbill/details', [BbpsController::class, 'fetchBillDetails']);
    
    
    // New API routes for dynamic state/city loading (optional)
    // Route::get('/category/{category}/states', [BbpsController::class, 'getStatesByCategory'])
    //     ->where('category', '.*');
    // Route::get('/category/{category}/state/{state}/cities', [BbpsController::class, 'getCitiesByState'])
    //     ->where(['category' => '.*', 'state' => '.*']);
    // Route::get('/category/{category}/state/{state}/city/{city}/operators', [BbpsController::class, 'getOperatorsByStateAndCity'])
    //     ->where(['category' => '.*', 'state' => '.*', 'city' => '.*']);
    
    // Route::post('/billPayment', [BbpsController::class, 'billPayment']);
    // Route::post('/transactionStatus', [BbpsController::class, 'transactionStatus']);
    // Route::get('/complaintRegistration', [BbpsController::class, 'complaintRegistration']);
    // Route::post('/previousRegisteredComplaint', [BbpsController::class, 'previousRegisteredComplaint']);
    // Route::post('/billValidation', [BbpsController::class, 'billValidation']);
    // Route::get('/successfulTransactionsSms', [BbpsController::class, 'successfulTransactionsSms']);
    // Route::get('/complaintRegistrationSms', [BbpsController::class, 'complaintRegistrationSms']);
    });

    Route::group(['prefix' => 'recharge'], function () {

    });
});


// Route::get('/', function () {
//     return Inertia::render('Auth/login');
// });

// require __DIR__.'/auth.php';
