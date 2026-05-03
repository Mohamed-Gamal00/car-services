<?php

use App\Http\Controllers\Api\AllBannersController;
use App\Http\Controllers\Api\AllProductsController;
use App\Http\Controllers\Api\CarsController;
use App\Http\Controllers\Api\ProductDetailsController;
use App\Http\Controllers\Api\Profile\PersonalInfoController;
use App\Http\Controllers\Api\Profile\UserAddressesController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\ContactUsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\StaticPagesController;
use App\Http\Controllers\Api\PackageController;

/*
   |--------------------------------------------------------------------------
   | API Routes
   |--------------------------------------------------------------------------
   |
   | Here is where you can register API routes for your application. These
   | routes are loaded by the RouteServiceProvider and all of them will
   | be assigned to the "api" middleware group. Make something great!
   |
   */


Route::middleware(['changeLanguage'])->group(function () {


################################### -- Authentication-- ############################################

    Route::controller(UserAuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('verify-code', 'verifyCode');
        Route::post('forget-password', 'forgetPassword');
        Route::post('logout', 'logout')->middleware('auth:user');
        Route::post('register-token', [UserAuthController::class, 'registerToken'])->middleware('auth:user');
        Route::post('reset-password', [UserAuthController::class, 'resetPassword']);
    });

    #################################### User Profile #######################################
    Route::middleware(['auth:user', 'user_verified'])->group(function () {
        #----------------------------------------------------------------------------------- Personal Info
        Route::post('change-personal-info', [PersonalInfoController::class, 'changePersonalInfo']);
        Route::post('change-profile-image', [PersonalInfoController::class, 'changeProfileImage']);
        Route::post('client-change-password', [PersonalInfoController::class, 'changePassword']);

        Route::get('user-info', [PersonalInfoController::class, 'getUserInfo']);
        Route::get('user-cars', [PersonalInfoController::class, 'getUserCars']);
        Route::get('user-package', [PersonalInfoController::class, 'getUserPackage']);
        Route::post('delete-user-cars/{car}', [PersonalInfoController::class, 'deleteUserCar']);

        #----------------------------------------------------------------------------------- User Addresses
        Route::get('user-addresses', [UserAddressesController::class, 'getAllAddresses']);
        Route::post('delete-address/{id}', [UserAddressesController::class, 'deleteAddress']);

        #-------------------------------------- Checkout --------------------------------------------
//        Route::post('/checkout/{service_id}', [CheckoutController::class, 'usercheckout']);
//        Route::post('/checkout_with_package', [CheckoutController::class, 'checkout_with_package']);
        Route::post('/checkout/{service_id?}', [CheckoutController::class, 'usercheckout']);
        Route::post('/check-coupon', [CheckoutController::class, 'checkCoupon']);
        Route::post('/apply-coupon/{orderId}', [CheckoutController::class, 'applyCoupon']);
        Route::post('/cancel-coupon/{orderId}', [CheckoutController::class, 'cancelCoupon']);
        Route::post('/test-notification', [CheckoutController::class, 'testnotification']);
        Route::get('/available-times', [CheckoutController::class, 'showAvailableTimeSlots']);
        Route::post('/pay-order', [CheckoutController::class, 'payOrder']);

        ################################## subscribe packages #########################################
        Route::post('subscribe', [PackageController::class, 'subscribe']);
        Route::post('renewal-subscribe', [PackageController::class, 'RenewalSubscribe']);


        #-------------------------------------- user-orders --------------------------------------------
        Route::get('/user_show_order', [\App\Http\Controllers\Api\UserOrdersController::class, 'showOrder']);
        Route::get('/user_orders', [\App\Http\Controllers\Api\UserOrdersController::class, 'mainOrders']);
//        Route::post('/user-orders/delete', [\App\Http\Controllers\Api\UserOrdersController::class, 'destroy']);
        /* الطلبات الملغية */
        Route::get('/user-canceled-orders', [\App\Http\Controllers\Api\UserOrdersController::class, 'returns']);
        Route::post('/user-cancel-order/store', [\App\Http\Controllers\Api\UserOrdersController::class, 'cancelOrder']);
        Route::get('/non-rating-order', [\App\Http\Controllers\Api\UserOrdersController::class, 'NonRatingOrder']);
        Route::post('/skip-rating/{ordernumber}', [\App\Http\Controllers\Api\UserOrdersController::class, 'skipRating']);


        #-------------------------------------- notifications --------------------------------------------
        Route::get('/client-notifications', [\App\Http\Controllers\Api\ClientNotificationController::class, 'clientNotification']);
        Route::get('/client-notification/show/{id}', [\App\Http\Controllers\Api\ClientNotificationController::class, 'clientShowNotification']);
        Route::post('/client-delete-notifications/{id}', [\App\Http\Controllers\Api\ClientNotificationController::class, 'clientDeleteNotification']);
        Route::post('/client-notifications/delete-all', [\App\Http\Controllers\Api\ClientNotificationController::class, 'clientdeleteAllNotifications']);

        #-------------------------------------- ratings --------------------------------------------
        Route::post('/make-rating', [\App\Http\Controllers\Api\RatingController::class, 'store']);


//        #######################################  change app lang ####################################
//        Route::post('update-language', [\App\Http\Controllers\Api\UpdateAppLangController::class, 'updateLanguage']);
    });

//        #######################################  change app lang ####################################
//    Route::post('update-language', [\App\Http\Controllers\Api\UpdateAppLangController::class, 'updateLanguage']);
    Route::middleware(['auth:user,captain'])->post('update-language', [\App\Http\Controllers\Api\UpdateAppLangController::class, 'updateLanguage']);

    #######################################  Get All cities ####################################
    Route::get('get-cities', [\App\Http\Controllers\Api\CitiesController::class, 'allCities']);


    #######################################  Get All cars ####################################
    Route::get('get-cars', [CarsController::class, 'allCars']);


    ################################## Get All Top Products(services) #########################################
    Route::get('get-services', [ServicesController::class, 'getServices']);
    Route::get('get-services/{service_id}', [ServicesController::class, 'getService_id']);
    Route::get('get-specific-services-times/{service_id?}', [ServicesController::class, 'getSpecificServiceTimes']);


    ################################## packages #########################################
    Route::get('packages', [PackageController::class, 'packages']);
    Route::get('get-package/{id}', [PackageController::class, 'getPackage_id']);

    ###################################  Get All Banners ########################################
    Route::get('get-header-banners', [AllBannersController::class, 'headerBanners']);

    ####################################  Get Products (services)##########################################
    Route::get('products/{category_id}', AllProductsController::class);

    ##################################### service Details Page ######################################
    Route::get('product-features/{id}', [ProductDetailsController::class, 'productFeatures']);


    ###################################### static pages #############################################
    Route::get('static-pages', [StaticPagesController::class, 'static_pages']);
    Route::get('about', [StaticPagesController::class, 'about_app']);
    Route::get('terms', [StaticPagesController::class, 'terms_and_Conditions']);
    Route::get('common-questions', [StaticPagesController::class, 'common_questions']);

    ###################################### Contact Us #########################################
    Route::post('contact-us', [ContactUsController::class, 'sendMessage']);

    ####################################### Settings #########################################
    Route::get('settings', [SettingsController::class, 'settings']);


###################################### captain routes  ########################################
    Route::post('captain/register', [\App\Http\Controllers\Api\CaptainAuthController::class, 'register']);
    Route::post('captain/login', [\App\Http\Controllers\Api\CaptainAuthController::class, 'login']);
    Route::post('verify-captain', [\App\Http\Controllers\Api\CaptainAuthController::class, 'verifyCode']);
    Route::post('captain-register-token', [\App\Http\Controllers\Api\CaptainAuthController::class, 'registerToken'])->middleware('auth:captain');
    Route::post('captain-logout', [\App\Http\Controllers\Api\CaptainAuthController::class, 'logout'])->middleware('auth:captain');


    Route::middleware('auth:captain')->group(function () {
        #----------------------------------------------------------------------------------- captain Info
        Route::post('change-captain-info', [\App\Http\Controllers\Api\CaptainInfoController::class, 'changePersonalInfo']);
        Route::post('change-password', [\App\Http\Controllers\Api\CaptainInfoController::class, 'changePassword']);
        Route::post('captain-change-profile-image', [\App\Http\Controllers\Api\CaptainInfoController::class, 'changeProfileImage']);
        Route::get('captain-info', [\App\Http\Controllers\Api\CaptainInfoController::class, 'getCaptainInfo']);

        #-------------------------------------- captain-orders --------------------------------------------
        Route::get('/captain_orders', [\App\Http\Controllers\Api\CaptainOrderController::class, 'mainOrders']);
        Route::get('/completed-orders', [\App\Http\Controllers\Api\CaptainOrderController::class, 'CompleteOrders']);
        Route::get('/captain_show_order/{number}', [\App\Http\Controllers\Api\CaptainOrderController::class, 'showOrder']);
        Route::post('/captain_accept_order/{id}', [\App\Http\Controllers\Api\CaptainOrderController::class, 'acceptOrder']);
        Route::post('/captain_complete_order/{id}', [\App\Http\Controllers\Api\CaptainOrderController::class, 'completeOrder']);

        #-------------------------------------- notifications --------------------------------------------
        Route::get('/captain-notifications', [\App\Http\Controllers\Api\NotificationController::class, 'captainNotification']);
        Route::get('/captain-notification/show/{id}', [\App\Http\Controllers\Api\NotificationController::class, 'captainShowNotification']);
        Route::post('/captain-delete-notifications/{id}', [\App\Http\Controllers\Api\NotificationController::class, 'captainDeleteNotification']);
        Route::post('/captain-notifications/delete-all', [\App\Http\Controllers\Api\NotificationController::class, 'captaindeleteAllNotifications']);

        Route::post('/notifyArrival/{id} ', [\App\Http\Controllers\Api\NotificationController::class, 'notifyCustomerArrival']);

    });

});
