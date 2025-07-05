<?php

use App\Http\Controllers\Dashboard\ContactUsController;
use App\Http\Controllers\Dashboard\DiscountCodeController;
use App\Http\Controllers\Dashboard\NotificationsController;
use App\Http\Controllers\Dashboard\OrderStatusController;
use App\Http\Controllers\Dashboard\ProductAvailabilityController;
use App\Http\Controllers\Dashboard\AdminsController;
use App\Http\Controllers\Dashboard\AdvertisementController;
use App\Http\Controllers\Dashboard\BulkOrderController;
use App\Http\Controllers\Dashboard\ChoiceController;
use App\Http\Controllers\Dashboard\CityController;
use App\Http\Controllers\Dashboard\ClientsController;
use App\Http\Controllers\Dashboard\ColorController;
use App\Http\Controllers\Dashboard\CommonQuestionController;
use App\Http\Controllers\Dashboard\CompaniesController;
use App\Http\Controllers\Dashboard\CountriesController;
use App\Http\Controllers\Dashboard\CurrencyController;
use App\Http\Controllers\Dashboard\DesignsController;
use App\Http\Controllers\Dashboard\HeaderBanerController;
use App\Http\Controllers\Dashboard\HeaderTextController;
use App\Http\Controllers\Dashboard\MainCategoriesController;
use App\Http\Controllers\Dashboard\MainCategoriesSettingsController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\PageController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProductSettingsController;
use App\Http\Controllers\Dashboard\ProductsFeatures;
use App\Http\Controllers\Dashboard\ReportsController;
use App\Http\Controllers\Dashboard\ReturnOrderController;
use App\Http\Controllers\Dashboard\RulesController;
use App\Http\Controllers\Dashboard\SendNewsToUsersController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\ShippingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\ProfileSettingsController;
use App\Http\Controllers\Dashboard\RepresentativesOrderController;
use App\Http\Controllers\Dashboard\Shipping\ShippingCompanyController;
use App\Http\Controllers\Dashboard\StoreFatuerController;
use App\Http\Controllers\Dashboard\PushNotificationController;
use App\Http\Controllers\Dashboard\PackageController;

//use App\Livewire\Categories;
//use App\Livewire\MainCategorySettings;
use Illuminate\Support\Facades\Route;

//=============================================== Dashboard Routes
Route::get('/', function () {
    return view('welcome'); // Show the admin index page
});
Route::group(['middleware' => 'admin'], function () {

    //-----------------------------------------------------------------------------/ Main Page
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard.index');

    //-----------------------------------------------------------------------------/ Notifications Page
    Route::get('/dashboard/notifications', [NotificationsController::class, 'index'])->name('notifications.index');

    //-----------------------------------------------------------------------------/Profile Settings
    Route::get('/dashboard/profile/edit', [ProfileSettingsController::class, 'index'])->name('profile.settings.index');
    Route::put('/dashboard/profile/update', [ProfileSettingsController::class, 'changePassword'])->name('profile.settings.update');

    //-----------------------------------------------------------------------------/ContactUs
    Route::get('/dashboard/contact_us_view', [ContactUsController::class, 'index'])->name('contact_us.index');
    Route::get('/dashboard/contact_us_view/{id}/show', [ContactUsController::class, 'show'])->name('contact_us.watch');

    //-----------------------------------------------------------------------------/Profile Info
    Route::get('/dashboard/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/dashboard/profile/{id}/update', [ProfileController::class, 'update'])->name('profile.update');

    //----------------------------------------------/ Users News Routes
    Route::get('/dashboard/send_news', [SendNewsToUsersController::class, 'create'])->name('user_news.create');
    Route::post('/dashboard/send_news_mail', [SendNewsToUsersController::class, 'sendNewsMail'])->name('user_news.send');

    // ------------------------------------------------------/ Website Settings
    Route::get('/dashboard/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/dashboard/settings/{id}/update', [SettingsController::class, 'update'])->name('settings.update');

    //-----------------------------------------------------------------------------/colors
    Route::resource('dashboard/colors', ColorController::class);

    //-----------------------------------------------------------------------------/Designs
    Route::resource('/dashboard/designs', DesignsController::class);

    //-----------------------------------------------------------------------------/ShippingCompany
    // Route::get('/dashboard/shipping_companies', [ShippingCompanyController::class, 'index'])->name('shipping.index');

    Route::resource('/dashboard/shipping_companies', ShippingCompanyController::class);
    Route::get('/get-cities/{countryId}', [ShippingCompanyController::class, 'getCities']);

    //-----------------------------------------------------------------------------/softDelete Products
    Route::get('/dashboard/products/trash', [productsController::class, 'trash'])->name('products.trash');
    Route::put('/dashboard/products/{id}/restore', [productsController::class, 'restore'])->name('products.restore');
    Route::delete('/dashboard/products/{id}/force-delete', [productsController::class, 'forceDelete'])->name('products.force-delete');

    //----------------------------------------------/Main Categories
    Route::resource('/dashboard/main_categories', MainCategoriesController::class);

    //----------------------------------------------/Choices
    Route::resource('/dashboard/main_choices', ChoiceController::class);

    //----------------------------------------------/Filters Settings
    Route::get('/dashboard/sub_filters/{id}/view', [MainCategoriesSettingsController::class, 'subFilterView'])->name('sub_filters.index');
    Route::post('/dashboard/sub_filters/store', [MainCategoriesSettingsController::class, 'subFilterStore'])->name('sub_filters.store');
    Route::delete('/dashboard/sub_filters/{id}/delete', [MainCategoriesSettingsController::class, 'subFilterDestroy'])->name('sub_filters.delete');
    Route::get('/dashboard/sub_filters/{id}/edit', [MainCategoriesSettingsController::class, 'subFilterEdit'])->name('sub_filters.edit');
    Route::put('/dashboard/sub_filters/{id}/update', [MainCategoriesSettingsController::class, 'subFilterUpdate'])->name('sub_filters.update');

    Route::resource('/dashboard/filters', MainCategoriesSettingsController::class);

    //----------------------------------------------/Products routes
    // Route::post('/dashboard/header_banner/delete', [HeaderBanerController::class, 'frontHeaderRemoveImage'])->name('headerImage.remove');

    Route::post('/product_images/delete', [ProductsController::class, 'imageDelete'])->name('image.delete');

    Route::get('/sub_category/{categoryId}', [ProductsController::class, 'subCategory'])->name('sub_category');
    Route::post('/search', [ProductsController::class, 'search'])->name('search');
    Route::get('/dashboard/products/out_of_stock', [ProductsController::class, 'outOfStock'])->name('out_of_stock');
    Route::get('/fetch-choices', [ProductsController::class, 'fetchChoices'])->name('fetch.choices');

    Route::resource('/dashboard/products', ProductsController::class);
    Route::resource('/dashboard/packages', PackageController::class);

    //----------------------------------------------/Products Settings routes
    Route::get('/dashboard/products_settings/{id}/filters', [ProductSettingsController::class, 'productFilters'])->name('products.filters');
    Route::put('/dashboard/products_settings/{id}/update', [ProductSettingsController::class, 'productFiltersUpdate'])->name('products.filters.update');
    Route::delete('/dashboard/products_settings/destroy_all', [ProductSettingsController::class, 'destroyAll'])->name('destroy.all');

    Route::resource('/dashboard/products_settings', ProductSettingsController::class);

    //----------------------------------------------/Companies routes
    Route::resource('/dashboard/companies', CompaniesController::class);

    //----------------------------------------------/Companies routes
    Route::resource('/dashboard/store_featuers', StoreFatuerController::class);

    //----------------------------------------------/Admins routes
    Route::put('/dashboard/admins/{id}/update_password', [AdminsController::class, 'ChangePassword'])->name('admins.update_password');
    Route::resource('/dashboard/admins', AdminsController::class);

    //----------------------------------------------/Clients routes
    Route::put('/dashboard/clients/{id}/update_pass', [ClientsController::class, 'updatePassword'])->name('client.update_password');
    Route::resource('/dashboard/clients', ClientsController::class);


    //----------------------------------------------/captains routes
    Route::put('/dashboard/captains/{id}/update_pass', [\App\Http\Controllers\Dashboard\CaptainController::class, 'updatePassword'])->name('captain.update_password');
    Route::get('/dashboard/captain/rating/{id}', [\App\Http\Controllers\Dashboard\CaptainController::class, 'rating'])->name('captain.rating');
    Route::resource('/dashboard/captains', \App\Http\Controllers\Dashboard\CaptainController::class);


    //----------------------------------------------/Admins Rules routes
    Route::resource('/dashboard/rules', RulesController::class);

    //----------------------------------------------/Header Text
    Route::resource('/dashboard/header_text', HeaderTextController::class);
    //----------------------------------------------/Header Banner
    // Route::resource('/dashboard/header_banner', HeaderBanerController::class);
    Route::get('/dashboard/header_banner', [HeaderBanerController::class, 'index'])->name('header_banner.index');
    Route::post('/dashboard/header_banner/delete', [HeaderBanerController::class, 'frontHeaderRemoveImage'])->name('headerImage.remove');
    Route::post('/dashboard/header_banner/store', [HeaderBanerController::class, 'frontHeaderStoreAndUpdate'])->name('header_banner.sotreAndUpdate');
    //----------------------------------------------/Animated Advertisements
    Route::resource('advertisements', AdvertisementController::class);

    //----------------------------------------------/static pages
    Route::resource('pages', PageController::class);

    Route::resource('dashboard/common_questions', CommonQuestionController::class);

    //----------------------------------------------/Representatives Orders

    Route::resource('representatives_orders', RepresentativesOrderController::class);

    //----------------------------------------------/bulk orders
    Route::resource('bulk_orders', BulkOrderController::class);

    //----------------------------------------------/payments
    Route::get('/payments', [\App\Http\Controllers\Dashboard\PaymentController::class, 'index'])->name('payments.index');

    //----------------------------------------------/Countries and Cities
    Route::get('/dashboard/countries/{countryId}/cities', [CountriesController::class, 'getCitiesByCountry']);
    Route::resource('/dashboard/countries', CountriesController::class);
    Route::resource('/dashboard/cities', CityController::class);
    Route::resource('/dashboard/cars', \App\Http\Controllers\Dashboard\CarsController::class);

    //----------------------------------------------/Currencies
    Route::get('/dashboard/currencies/default_currency', [CurrencyController::class, 'setDefaultCurrency'])->name('default_currency');
    Route::put('/dashboard/currencies/change_default_currency', [CurrencyController::class, 'updateDefaultCurrency'])->name('change_default_currency');
    Route::resource('/dashboard/currencies', CurrencyController::class);

    //----------------------------------------------/ Orders Routes
    Route::get('/dashboard/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/dashboard/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::delete('/dashboard/orders/{id}/delete', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::put('/dashboard/orders/{id}/update', [OrderController::class, 'update'])->name('orders.update');
    Route::put('/dashboard/orders/{id}/assignCaptain', [OrderController::class, 'assignCaptain'])->name('orders.assignCaptain');

    //send notification test

    Route::post('/save-token', [OrderController::class, 'saveToken'])->name('save-token');
    Route::post('/send-notification', [OrderController::class, 'sendNotification'])->name('send.notification');
    //----------------------------------------------/ Return Orders Routes
    Route::get('/dashboard/return_orders', [ReturnOrderController::class, 'index'])->name('return_orders.index');
    Route::get('/dashboard/return_orders/{id}', [ReturnOrderController::class, 'show'])->name('return_orders.show');
    Route::delete('/dashboard/return_orders/{id}/delete', [ReturnOrderController::class, 'destroy'])->name('return_orders.destroy');
    Route::put('/dashboard/return_orders/{id}/update', [ReturnOrderController::class, 'update'])->name('return_orders.update');

    //----------------------------------------------/ product_availability Route
    Route::resource('/dashboard/product_availability', ProductAvailabilityController::class);

    //----------------------------------------------/DiscountCode routes
    Route::resource('/dashboard/discount_code', DiscountCodeController::class);
    Route::get('/api/search-products', [DiscountCodeController::class, 'searchProducts'])->name('search.products');

    //----------------------------------------------/ Orders Routes
    Route::get('/dashboard/shipping_types', [ShippingController::class, 'index'])->name('shipping.index');
    Route::get('/dashboard/shipping_types/edit/{id}', [ShippingController::class, 'edit'])->name('shipping.edit');
    Route::put('/dashboard/shipping_types/{id}/update', [ShippingController::class, 'update'])->name('shipping_data.update');
    Route::get('/dashboard/get-cities', [ShippingController::class, 'getCities'])->name('get-cities');
    Route::get('/dashboard/order_status/arranging', [OrderStatusController::class, 'orderArrangement'])->name('order_status.arranging');
    Route::put('/dashboard/order_status/arranging/update', [OrderStatusController::class, 'orderArrangementUpdate'])->name('order_status.arranging_update');
    Route::resource('/dashboard/order_status', OrderStatusController::class);

    //----------------------------------------------/ Reports Routes
    Route::get('/dashboard/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/clients/export', [ReportsController::class, 'export'])->name('clients.export');
    Route::get('/coupons/export', [ReportsController::class, 'export'])->name('coupons.export');
    Route::get('/orders/export', [ReportsController::class, 'export'])->name('orders.export');

//    Route::post('/dashboard/searchReport', [ReportsController::class, 'searchReport'])->name('search_report');

//    Route::get('/dashboard/reports', [ReportsController::class, 'index'])->name('reports.index');
//    Route::post('/dashboard/reports', [ReportsController::class, 'filter'])->name('reports.filter');


    Route::get('/dashboard/push_notification', [PushNotificationController::class, 'create'])->name('notification.Dashboard.create');
    Route::post('/dashboard/send-notification', [PushNotificationController::class, 'store'])->name('notification.Dashboard.store');

});

//----------------------------------------------/Admin login
Route::view('admin/login', 'admin.auth.login')->middleware('guest:admin')->name('admin.login');
