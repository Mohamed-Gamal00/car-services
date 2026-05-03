<?php

use App\Http\Controllers\Dashboard\ContactUsController;
use App\Http\Controllers\Dashboard\DiscountCodeController;
use App\Http\Controllers\Dashboard\NotificationsController;
use App\Http\Controllers\Dashboard\OrderStatusController;
use App\Http\Controllers\Dashboard\AdminsController;
use App\Http\Controllers\Dashboard\ChoiceController;
use App\Http\Controllers\Dashboard\CityController;
use App\Http\Controllers\Dashboard\ClientsController;
use App\Http\Controllers\Dashboard\CommonQuestionController;
use App\Http\Controllers\Dashboard\CountriesController;
use App\Http\Controllers\Dashboard\DesignsController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\PageController;
use App\Http\Controllers\Dashboard\ReportsController;
use App\Http\Controllers\Dashboard\RulesController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\ProfileSettingsController;
use App\Http\Controllers\Dashboard\PushNotificationController;
use App\Http\Controllers\Dashboard\PackageController;
use App\Http\Controllers\Dashboard\CaptainController;
use App\Http\Controllers\Dashboard\ServicesController;
use App\Http\Controllers\Dashboard\CarsController;
use App\Http\Controllers\Dashboard\PaymentController;
use App\Http\Controllers\Dashboard\ProductsController;
use Illuminate\Support\Facades\Route;

//=============================================== Dashboard Routes
Route::get('/', function () {
    return redirect()->route('admin.login');
});

//----------------------------------------------/ Admin Authentication
Route::get('admin/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])
    ->middleware('guest:admin')
    ->name('admin.login');

Route::post('admin/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])
    ->middleware('guest:admin')
    ->name('admin.login.submit');

Route::post('admin/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])
    ->middleware('admin')
    ->name('admin.logout');

//=============================================== Protected Admin Routes
Route::group(['prefix' => 'dashboard', 'middleware' => 'admin'], function () {

    //-----------------------------------------------------------------------------/ Dashboard Home
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    //-----------------------------------------------------------------------------/ Notifications
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');

    //-----------------------------------------------------------------------------/ Admin Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/{id}/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/profile/edit', [ProfileSettingsController::class, 'index'])->name('profile.settings.index');
    Route::put('/profile/update', [ProfileSettingsController::class, 'changePassword'])->name('profile.settings.update');

    //-----------------------------------------------------------------------------/ Services Management
    Route::get('/services/trash', [ServicesController::class, 'trash'])->name('services.trash');
    Route::put('/services/{id}/restore', [ServicesController::class, 'restore'])->name('services.restore');
    Route::delete('/services/{id}/force-delete', [ServicesController::class, 'forceDelete'])->name('services.force-delete');
    Route::resource('/services', ServicesController::class);


    //-----------------------------------------------------------------------------/ Packages Management
    Route::resource('/packages', PackageController::class);

    //-----------------------------------------------------------------------------/ Additional Services (Choices)
    Route::resource('/choices', ChoiceController::class)->names([
        'index' => 'main_choices.index',
        'create' => 'main_choices.create',
        'store' => 'main_choices.store',
        'show' => 'main_choices.show',
        'edit' => 'main_choices.edit',
        'update' => 'main_choices.update',
        'destroy' => 'main_choices.destroy',
    ]);

    //-----------------------------------------------------------------------------/ Orders Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/update', [OrderController::class, 'update'])->name('orders.update');
    Route::put('/orders/{id}/assignCaptain', [OrderController::class, 'assignCaptain'])->name('orders.assignCaptain');
    Route::delete('/orders/{id}/delete', [OrderController::class, 'destroy'])->name('orders.destroy');

    //-----------------------------------------------------------------------------/ Order Statuses
    Route::get('/order_status/arranging', [OrderStatusController::class, 'orderArrangement'])->name('order_status.arranging');
    Route::put('/order_status/arranging/update', [OrderStatusController::class, 'orderArrangementUpdate'])->name('order_status.arranging_update');
    Route::resource('/order_status', OrderStatusController::class);

    //-----------------------------------------------------------------------------/ Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

    //-----------------------------------------------------------------------------/ Customers Management
    Route::put('/clients/{id}/update_pass', [ClientsController::class, 'updatePassword'])->name('client.update_password');
    Route::resource('/clients', ClientsController::class);

    //-----------------------------------------------------------------------------/ Captains Management
    Route::put('/captains/{id}/update_pass', [CaptainController::class, 'updatePassword'])->name('captain.update_password');
    Route::get('/captains/{id}/rating', [CaptainController::class, 'rating'])->name('captain.rating');
    Route::resource('/captains', CaptainController::class);

    //-----------------------------------------------------------------------------/ Designs/Banners Management
    Route::resource('/designs', DesignsController::class);

    //-----------------------------------------------------------------------------/ Discount Codes
    Route::resource('/discount_code', DiscountCodeController::class);
    Route::get('/api/search-services', [DiscountCodeController::class, 'searchServices'])->name('search.services');

    //-----------------------------------------------------------------------------/ Admins Management
    Route::put('/admins/{id}/update_password', [AdminsController::class, 'ChangePassword'])->name('admins.update_password');
    Route::resource('/admins', AdminsController::class);

    //-----------------------------------------------------------------------------/ Admin Groups/Rules Management
    Route::resource('/rules', RulesController::class);

    //-----------------------------------------------------------------------------/ Cars (Brands & Models)
    Route::resource('/cars', CarsController::class);

    //-----------------------------------------------------------------------------/ Countries & Cities
    Route::get('/countries/{countryId}/cities', [CountriesController::class, 'getCitiesByCountry']);
    Route::resource('/countries', CountriesController::class);
    Route::resource('/cities', CityController::class);

    //-----------------------------------------------------------------------------/ Contact Us Messages
    Route::get('/contact_us_view', [ContactUsController::class, 'index'])->name('contact_us.index');
    Route::get('/contact_us_view/{id}/show', [ContactUsController::class, 'show'])->name('contact_us.watch');

    //-----------------------------------------------------------------------------/ Static Pages
    Route::resource('/pages', PageController::class);
    Route::resource('/common_questions', CommonQuestionController::class);

    //-----------------------------------------------------------------------------/ System Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/{id}/update', [SettingsController::class, 'update'])->name('settings.update');

    //-----------------------------------------------------------------------------/ Push Notifications
    Route::get('/push_notification', [PushNotificationController::class, 'create'])->name('notification.Dashboard.create');
    Route::post('/send-notification', [PushNotificationController::class, 'store'])->name('notification.Dashboard.store');

    //-----------------------------------------------------------------------------/ Reports & Analytics
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/clients/export', [ReportsController::class, 'exportClients'])->name('clients.export');
    Route::get('/captains/export', [ReportsController::class, 'exportCaptains'])->name('captains.export');
    Route::get('/orders/export', [ReportsController::class, 'exportOrders'])->name('orders.export');
    Route::get('/coupons/export', [ReportsController::class, 'exportCoupons'])->name('coupons.export');
    Route::get('/payments/export', [ReportsController::class, 'exportPayments'])->name('payments.export');
});
