<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{UserController,PageController};
use App\Http\Controllers\{SiteController};

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
Route::get('/', [SiteController::class, 'index'])->name('/');
Route::get('privacy_policy', [SiteController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('term_and_condition', [SiteController::class, 'term_and_condition'])->name('term_and_condition');


Auth::routes();
Route::get('verifyEmail/{id}/{code}', [PageController::class, 'verifyEmail'])->name('verifyEmail');
// Route::post('verifyEmail', 'AuthController@')->name('verifyEmail');

Route::get('/admin/home', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.home');
Route::group(['namespace' => 'App\Http\Controllers\Admin', 'middleware' => ['auth','is_admin']], function () {
    Route::get('admin/test', [UserController::class, 'index'])->name('admin.test');
    //Driver Module
    Route::get('admin/driver', [UserController::class, 'driver'])->name('admin.driver');
    Route::get('admin/driver_licence/{id}', [UserController::class, 'driver_licence'])->name('admin.driver_licence');
    Route::post('admin/driver_status', [UserController::class, 'driver_status'])->name('admin.driver_status');
    Route::get('admin/driver_insurance/{id}', [UserController::class, 'driver_insurance'])->name('admin.driver_insurance');

    //Rider Module
    Route::get('admin/rider', [UserController::class, 'rider'])->name('admin.rider');
    Route::get('admin/rider_children/{id}', [UserController::class, 'rider_children'])->name('admin.rider_children');
    Route::post('admin/rider_status', [UserController::class, 'driver_status'])->name('admin.rider_status');

    //Comunity Group Module
    Route::get('admin/community_group', [PageController::class, 'community_group'])->name('admin.community_group');
    Route::post('admin/update_community_group', [PageController::class, 'update_community_group'])->name('admin.update_community_group');

    //Help Module
    Route::get('admin/help', [PageController::class, 'help'])->name('admin.help');
    Route::post('admin/update_help', [PageController::class, 'update_help'])->name('admin.update_help');

    //termsCondition Module
    Route::get('admin/termsCondition', [PageController::class, 'termsCondition'])->name('admin.termsCondition');
    Route::post('admin/update_termsCondition', [PageController::class, 'update_termsCondition'])->name('admin.update_termsCondition');

    //privacyAndPolicy Module
    Route::get('admin/privacyAndPolicy', [PageController::class, 'privacyAndPolicy'])->name('admin.privacyAndPolicy');
    Route::post('admin/update_privacyAndPolicy', [PageController::class, 'update_privacyAndPolicy'])->name('admin.update_privacyAndPolicy');

    //Queries Module
    Route::get('admin/queries', [PageController::class, 'queries'])->name('admin.queries');
    Route::get('admin/query_user/{id}', [PageController::class, 'query_user'])->name('admin.query_user');

    //Payments Module
    Route::get('admin/payments', [PageController::class, 'payments'])->name('admin.payments');
});
