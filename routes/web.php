<?php

use App\Http\Controllers\Admin\AppVersionSettingController;
use App\Http\Controllers\Admin\FAQController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{AdminController, ChargesPerMileController, CommissonController, EmergencyController, UserController,PageController, RadiusOfSearchController};
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
    //Driver Module
    Route::get('admin/driver', [UserController::class, 'driver'])->name('admin.driver');
    Route::get('admin/driver_licence/{id}', [UserController::class, 'driver_licence'])->name('admin.driver_licence');
    Route::post('admin/driver_status', [UserController::class, 'driver_status'])->name('admin.driver_status');
    Route::get('admin/driver_insurance/{id}', [UserController::class, 'driver_insurance'])->name('admin.driver_insurance');
    Route::get('admin/driver_fvc/{id}', [UserController::class, 'driver_fvc'])->name('admin.driver_fvc');

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
    Route::get('admin/web-queries', [PageController::class, 'webQueries'])->name('admin.web-queries');
    Route::get('admin/query_user/{id}', [PageController::class, 'query_user'])->name('admin.query_user');
    Route::get('admin/delete-web-query/{query}', [PageController::class, 'deleteWebInquiry'])->name('admin.delete-web-query');
    Route::get('admin/delete-query/{query}', [PageController::class, 'deleteInquiry'])->name('admin.delete-query');
    Route::get('admin/read-query/{id}', [PageController::class, 'readInquiry'])->name('admin.read-query');
    Route::get('admin/web-query-notification', [PageController::class, 'queryNotification'])->name('admin.web-query-notification');

    //Payments Module
    Route::get('admin/payments', [PageController::class, 'payments'])->name('admin.payments');

    //Change Password Module
    Route::get('admin/changePassword', [AdminController::class, 'index'])->name('admin.change_password');
    Route::post('admin/updatePassword', [AdminController::class, 'update'])->name('admin.update_password');

    //Radius of search Module
    Route::get('admin/radius_of_search', [RadiusOfSearchController::class, 'index'])->name('admin.radius_of_search');
    Route::post('admin/update_miles', [RadiusOfSearchController::class, 'update'])->name('admin.update_miles');

    //App Version Settings
    Route::get('admin/app_version_settings', [AppVersionSettingController::class, 'index'])->name('admin.app_version_settings');
    Route::post('admin/update_app_version_settings', [AppVersionSettingController::class, 'update'])->name('admin.update_app_version_settings');

    //Emergency Module
    Route::get('admin/emergency', [EmergencyController::class, 'index'])->name('admin.emergency');
    Route::post('admin/update_emergency', [EmergencyController::class, 'update'])->name('admin.update_emergency');

    //Charges Per Miles Module
    Route::get('admin/charges_per_miles', [ChargesPerMileController::class, 'index'])->name('admin.charges_per_miles');
    Route::post('admin/update_charges_per_miles', [ChargesPerMileController::class, 'update'])->name('admin.update_charges_per_miles');

    //Commission Module
    Route::get('admin/commission', [CommissonController::class, 'index'])->name('admin.commission');
    Route::post('admin/update_commission', [CommissonController::class, 'update'])->name('admin.update_commission');

    // Faq Module
    Route::get('admin/faq_categories', [FAQController::class, 'faqCategories'])->name('admin.faq_categories');
    Route::post('admin/insert_faq_categories', [FAQController::class, 'insertFaqCategories'])->name('admin.insert_faq_categories');
    Route::get('admin/edit_faq_categories{id}', [FAQController::class, 'editFaqCategories'])->name('admin.edit_faq_categories');
    Route::post('admin/update_faq_categories', [FAQController::class, 'updateFaqCategories'])->name('admin.update_faq_categories');
    Route::delete('admin/delete_faq_categories/{id}', [FAQController::class, 'deleteFqueriesaqCategories'])->name('admin.delete_faq_categories');

    Route::get('admin/faq_answers', [FAQController::class, 'faqAnswers'])->name('admin.faq_answers');
    Route::post('admin/insert_faq_answers', [FAQController::class, 'insertFaqAnswers'])->name('admin.insert_faq_answers');
    Route::get('admin/edit_faq_answers/{id}', [FAQController::class, 'editFaqAnswers'])->name('admin.edit_faq_answers');
    Route::post('admin/update_faq_answers', [FAQController::class, 'updateFaqAnswers'])->name('admin.update_faq_answers');
    Route::delete('admin/delete_faq_answers/{id}', [FAQController::class, 'deleteFaqAnswers'])->name('admin.delete_faq_answers');

    Route::get('admin/staff', [FAQController::class, 'getStaff'])->name('admin.staff');
    Route::post('admin/insert_staff', [FAQController::class, 'insertStaff'])->name('admin.insert_staff');
    Route::get('admin/edit_staff/{id}', [FAQController::class, 'editStaff'])->name('admin.edit_staff');
    Route::post('admin/update_staff', [FAQController::class, 'updateStaff'])->name('admin.update_staff');
    Route::delete('admin/delete_staff/{id}', [FAQController::class, 'deleteStaff'])->name('admin.delete_staff');

    Route::get('admin/faq_queries', [FAQController::class, 'getFaqQuries'])->name('admin.faq_queries');
    Route::get('admin/faq_querie_chat/{id}', [FAQController::class, 'faqQuerieChats'])->name('admin.faq_querie_chat');
});
