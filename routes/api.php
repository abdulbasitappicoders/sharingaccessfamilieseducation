<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\
{
    UserPaymentMethodController,
    AuthController,
    PageController,
    NotificationController,
    ProfileController,
    ChatController,
    ContactUsController,
    ReviewController,
    PaymentWithCardBankController,
    SavedLocationController,
    RideController,
    PaymentController,
    UserCardController,
    GeneralController,
    FaqController,
};


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
Route::post('PusherEndpoint/{id}',[UserCardController::class, 'PusherEndpoint'])->name('PusherEndpoint');

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    Route::post('register',[AuthController::class, 'register'])->name('register');
    Route::post('login',[AuthController::class, 'login'])->name('login');
    Route::post('socialLogin',[AuthController::class, 'socialLogin'])->name('socialLogin');
    Route::post('sendForgotPasswordEmail', 'AuthController@sendForgotPasswordEmail')->name('sendForgotPasswordEmail');
    Route::post('verifyForgotPin', 'AuthController@verifyForgotPin');
    Route::post('resetPassword', 'AuthController@resetPassword');
    Route::get('connectReAuth/{account_no}', 'AuthController@connectReAuth');
    Route::get('connectReturn/{id}', 'AuthController@connectReturn');
    Route::get('getEmergencyNumber', 'GeneralController@getEmergencyNumber');
    Route::post('checkAppVersion', 'GeneralController@checkAppVersion');
    Route::get('faqs', [FaqController::class, 'index'])->name('faqs');
});

Route::group(['namespace' => 'App\Http\Controllers\Api', 'middleware' => 'auth:api'], function () {
    Route::get('logout',[ProfileController::class, 'logout'])->name('logout');
    Route::post('updateProfile',[ProfileController::class, 'updateProfile'])->name('updateProfile');
    Route::get('makeUserAuthenticate',[ProfileController::class, 'makeUserAuthenticate'])->name('makeUserAuthenticate');
    Route::post('updateLatLong',[ProfileController::class, 'updateLatLong'])->name('updateLatLong');
    Route::get('onlineOffline',[ProfileController::class, 'onlineOffline'])->name('onlineOffline');
    Route::post('updateIsBoard',[ProfileController::class, 'updateIsBoard'])->name('updateIsBoard');
    Route::post('getUser',[ProfileController::class, 'getUser'])->name('getUser');
    Route::post('childDelete',[ProfileController::class, 'childDelete'])->name('childDelete');
    Route::get('deleteUser',[ProfileController::class, 'deleteUser'])->name('deleteUser');
    Route::post('updatePassword', [ProfileController::class, 'updatePassword'])->name('updatePassword');

    //Chat Module
    Route::post('chatLists',[ChatController::class, 'chatLists'])->name('chatLists');
    Route::post('sendMessage',[ChatController::class, 'sendMessage'])->name('sendMessage');
    Route::post('chatMessages',[ChatController::class, 'chatMessages'])->name('chatMessages');
    Route::post('deleteMessage',[ChatController::class, 'destroy'])->name('deleteMessage');
    Route::post('createChatList',[ChatController::class, 'createChatList'])->name('createChatList');

    Route::post('createSupportChatList',[ChatController::class, 'createSupportChatList'])->name('createSupportChatList');
    Route::get('supportChatList',[ChatController::class, 'supportChatList'])->name('supportChatList');
    Route::post('sendSupportMessage',[ChatController::class, 'sendSupportMessage'])->name('sendSupportMessage');
    Route::post('supportMessages',[ChatController::class, 'supportMessages'])->name('supportMessages');
    Route::post('deleteSupportMessage',[ChatController::class, 'deleteSupportMessage'])->name('deleteSupportMessage');
    Route::post('endSupportChat',[ChatController::class, 'endSupportChat'])->name('endSupportChat');

    //Contact Us Module
    Route::post('contactUs',[ContactUsController::class, 'store'])->name('contactUs');

    //Review Module
    Route::post('review',[ReviewController::class, 'review'])->name('review');
    Route::get('getRatingsAndReviews',[ReviewController::class, 'getRatingsAndReviews'])->name('getRatingsAndReviews');

    //Notifications Module
    Route::get('getNotifications',[NotificationController::class, 'index'])->name('getNotifications');

    // Pages Module
    Route::get('termPrivacyHelp',[PageController::class, 'index'])->name('termPrivacyHelp');

    //Payment Method Module
    Route::post('storeCard',[UserPaymentMethodController::class, 'storeCard'])->name('storeCard');
    Route::post('storeBank',[UserPaymentMethodController::class, 'storeBank'])->name('storeBank');
    Route::get('getPaymentMethods',[UserPaymentMethodController::class, 'index'])->name('getPaymentMethods');
    Route::post('deleteCard',[UserPaymentMethodController::class, 'deleteCard'])->name('deleteCard');

    //Payment Module
    Route::get('getPaymentHistory',[PaymentController::class, 'getPaymentHistory'])->name('getPaymentHistory');
    Route::get('test',[UserCardController::class, 'test'])->name('test');

    //Payment With Card OR Bank module
    Route::get('getPaymentWithCardBank',[PaymentWithCardBankController::class, 'index'])->name('getPaymentWithCardBank');

    //Ride Modules

    //Driver Ride Noman front end
    Route::post('acceptRide',[RideController::class, 'acceptRide'])->name('acceptRide');
    Route::post('startRide',[RideController::class, 'startRide'])->name('startRide');
    Route::post('dropRide',[RideController::class, 'dropRide'])->name('dropRide');
    Route::post('rideComplete',[RideController::class, 'rideComplete'])->name('rideComplete');
    Route::get('latestRide',[RideController::class, 'latestRide'])->name('latestRide');
    Route::get('driverRequestedRides',[RideController::class, 'driverRequestedRides'])->name('driverRequestedRides');
    Route::get('lastRide',[RideController::class, 'lastRide'])->name('lastRide');

    //Rider Ride Ahsan front end
    Route::post('bookRide',[RideController::class, 'store'])->name('bookRide');
    Route::post('calculateDistance',[RideController::class, 'calculateDistance'])->name('calculateDistance');
    Route::post('confirmRide',[RideController::class, 'confirmRide'])->name('confirmRide');
    Route::post('cancelRide',[RideController::class, 'cancelRide'])->name('cancelRide');

    //General Ride
    Route::get('scheduleRides',[RideController::class, 'scheduleRides'])->name('scheduleRides');
    Route::get('webScheduleRides',[RideController::class, 'webScheduleRides'])->name('webScheduleRides');
    Route::get('pastRides',[RideController::class, 'pastRides'])->name('pastRides');
    Route::get('canceledRides',[RideController::class, 'canceledRides'])->name('canceledRides');
    Route::get('totalRides',[RideController::class, 'totalRides'])->name('totalRides');
    Route::post('availableDrivers',[RideController::class, 'availableDrivers'])->name('availableDrivers');

    //Saved Locations Modules
    Route::get('getSavedLocations',[SavedLocationController::class, 'index'])->name('getSavedLocations');
    Route::post('saveLocation',[SavedLocationController::class, 'store'])->name('saveLocation');
});
