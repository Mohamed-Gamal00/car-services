<?php
//
//use App\Http\Controllers\Front\AboutUsController;
//use App\Http\Controllers\Front\BrandsController;
//use App\Http\Controllers\Front\BulkOrderController;
//use App\Http\Controllers\Front\CartController;
//use App\Http\Controllers\Front\CategoriesController;
//use App\Http\Controllers\Front\CheckDiscountCode;
//use App\Http\Controllers\Front\CheckoutController;
//use App\Http\Controllers\Front\CheckoutLoginController;
//use App\Http\Controllers\Front\CarsController;
//use App\Http\Controllers\Front\CommentsController;
//use App\Http\Controllers\Front\ContactUsController;
//use App\Http\Controllers\Front\CurrencyConverterController;
//use App\Http\Controllers\Front\GuestOrdersController;
//use App\Http\Controllers\Front\HomeController;
//use App\Http\Controllers\Front\LatestProductController;
//use App\Http\Controllers\Front\MainPageController;
//use App\Http\Controllers\Front\OfferProductController;
//use App\Http\Controllers\Front\ProductDetailsController;
//use App\Http\Controllers\Front\ProductsController;
//use App\Http\Controllers\Front\RepresentativeOrderController;
//use App\Http\Controllers\Front\ReturnProductsController;
//use App\Http\Controllers\Front\ShippingController;
//use App\Http\Controllers\Front\SpecialProductsController;
//use App\Http\Controllers\Front\StaticPageController;
//use App\Http\Controllers\Front\ServicesController;
//use App\Http\Controllers\Front\UserOrdersController;
//use App\Http\Controllers\Front\UserProfileController;
//use App\Http\Controllers\Front\WishListController;
//
//use App\Http\Controllers\LanguageController;
//use App\Http\Controllers\Test\ShippingCompaniesTestEnvController;
use Illuminate\Support\Facades\Route;

//
///*
//|--------------------------------------------------------------------------
//| Web Routes
//|--------------------------------------------------------------------------
//|
//| Here is where you can register web routes for your application. These
//| routes are loaded by the RouteServiceProvider and all of them will
//| be assigned to the "web" middleware group. Make something great!
//|
//*/
//
//Route::get('UPS', [ShippingCompaniesTestEnvController::class, 'UPS']);
//Route::get('auth', [ShippingCompaniesTestEnvController::class, 'OAuth']);
//
////----------------------------------------------------------------------Change Lang
//Route::get('/toggle-lang/{lang}', [LanguageController::class, 'changeLanguage'])->name('change.language');
//
////----------------------------------------------------------------------Main Page
//Route::get('/', [MainPageController::class, 'index'])->name('main_page');
//Route::post('/update-default-currency', [MainPageController::class, 'updateDefaultCurrency'])->name('update.default.currency');
//
////----------------------------------------------------------------------Add Email for News in Footer
//Route::post('/add_email', [MainPageController::class, 'addEmailForNews'])->name('users_news');
//
////----------------------------------------------------------------------ContactUs
//Route::get('/contact_us', [ContactUsController::class, 'index'])->name('contact_us');
//Route::post('/contact_us/store', [ContactUsController::class, 'store'])->name('contact_us.store');
//
//// bulk orders
//Route::get('/bulk_orders', [BulkOrderController::class, 'index'])->name('front.bulk_orders');
//Route::post('/bulk_orders/store', [BulkOrderController::class, 'storeorder'])->name('bulk_order.storeorder');
//
//// representative orders
//Route::get('/representative_orders', [RepresentativeOrderController::class, 'index'])->name('representative_orders');
//Route::post('/representative_orders/store', [RepresentativeOrderController::class, 'storeorder'])->name('representative_order.storeorder');
//
////----------------------------------------------------------------------Currency Converter
//Route::post('currency', [CurrencyConverterController::class, 'store'])->name('currency.converter');
//
////----------------------------------------------------------------------Special Products
//Route::get('/special-products', [SpecialProductsController::class, 'index'])->name('special_products');
//
////----------------------------------------------------------------------top-selling Products
//Route::get('/top-products', [ServicesController::class, 'index'])->name('top_products');
//
////----------------------------------------------------------------------latest Products
//Route::get('/latest-products', [LatestProductController::class, 'index'])->name('latest_products');
//
////----------------------------------------------------------------------latest Products
//Route::get('/offer-products', [OfferProductController::class, 'index'])->name('offer_products');
//
///* home page */
//Route::get('/', [HomeController::class, 'index'])->name('front.home');
//Route::get('/all-products', [HomeController::class, 'allProducts'])->name('search.products');
//Route::post('/get_product_search', [HomeController::class, 'searchProduct'])->name('get.product.search');
//
///*  كل منتجات الاقسام */
//Route::get('/products', [ProductsController::class, 'index'])->name('front.products');
//
///* الاقسام */
//Route::get('/categories/{slug}', [CategoriesController::class, 'index'])->name('front.categories');
//Route::get('/all_categories', [CategoriesController::class, 'viewAllCategories'])->name('all_categories');
//
///* البراندات */
//Route::get('/all_brands', [BrandsController::class, 'index'])->name('front.all_brands');
//
///* تفاصيل المنتج / صفحة المنتج*/
//Route::get('/product/{slug}', [ProductDetailsController::class, 'index'])->name('product.details');
//
//
//// static_pages
//Route::get('/about_us', [AboutUsController::class, 'index'])->name('aboutus');
//Route::get('/privacy_policy', [StaticPageController::class, 'privacy_policy'])->name('privacy_policy');
//Route::get('/questions', [StaticPageController::class, 'questions'])->name('questions');
//Route::get('/shipping_policy', [StaticPageController::class, 'shipping_policy'])->name('shipping_policy');
//Route::get('/terms_conditions', [StaticPageController::class, 'terms_conditions'])->name('terms_conditions');
//
//
//// cities route
//Route::get('/cities/{countryId}', [CarsController::class, 'getCitiesByCountry']);
//
//// Cart route
//Route::post('/cart/add-and-go-to-cart', [CartController::class, 'storeAndGoToCart'])->name('cart.addAndGoToCart');
//Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
//Route::get('/cart/total', [CartController::class, 'getTotalPrice'])->name('cart.total');
//Route::get('/cart/total-quantity', [CartController::class, 'getTotalPrice'])->name('cart.totalQuantity');
//
//Route::resource('/cart', CartController::class);
//Route::post('/updateTotalPrice', [CartController::class, 'getTotalQuantity'])->name('cart.updatedTotal');
//
//Route::get('/get-shipping-cost/{cityId}', [ShippingController::class, 'getShippingCost']);
//Route::get('/get-total-with-shipping', [ShippingController::class, 'getTotalWithShipping']);
//
///* CheckDiscountCode */
//Route::post('/check_discount', CheckDiscountCode::class)->name('check_discount_code');
//
///* mustLoginFirst */
//Route::post('/checkout-login', [CheckoutLoginController::class, 'login'])->name('checkout.login');
//
////checkout order routes
//Route::get('/checkout', [CheckoutController::class, 'create'])->name('order.index');
//Route::post('/checkout/create', [CheckoutController::class, 'store'])->name('checkout');
//
//
////-------------------------------------------------------- guest Orders routes
//Route::get('/guest-orders/{number}', [GuestOrdersController::class, 'orders'])->name('guest.orders');
//Route::get('/guest-main-orders', [GuestOrdersController::class, 'mainOrders'])->name('guest.main.orders');
//Route::post('/guest-return-orders/store', [GuestOrdersController::class, 'store'])->name('guest.return_products.store');
//Route::get('/guest-return-orders', [GuestOrdersController::class, 'guestReturnProductsindex'])->name('guest.return_products');
//Route::delete('/guest-return-orders', [GuestOrdersController::class, 'guestOrderDelete'])->name('guest.order_delete');
///* new */
//Route::get('/guest-wishlist', [GuestOrdersController::class, 'guestWishList'])->name('guest.wishlist');
//
///* add products to wishlist */
//Route::post('/wishlist/{id}', [WishListController::class, 'addProductToWishList'])->name('add.wishlist');
//
//
//// user Auth routes
//Route::middleware('auth:web')->group(function () {
//    Route::get('/user-profile', [UserProfileController::class, 'index'])->name('user.profile');
//    Route::post('/wishlist2/{id}', [WishListController::class, 'wishListInProductDetails'])->name('wishlist_product_details');
//    /* المفضلة */
//    Route::get('/user_wishlist', [UserProfileController::class, 'userWishList'])->name('user.wishlist');
//    Route::get('/user_info', [UserProfileController::class, 'userInfo'])->name('user.info');
//    Route::get('/user_addresses', [UserProfileController::class, 'userAddresses'])->name('user.addresses');
//    Route::get('/user_addresses/create', [UserProfileController::class, 'userAddressesCreate'])->name('create.address');
//    Route::post('/user_addresses/store', [UserProfileController::class, 'userAddressesStore'])->name('store.address');
//    Route::get('/user_addresses/{addressId}/edit', [UserProfileController::class, 'userAddressesEdit'])->name('edit.address');
//    Route::put('/user_addresses/{addressId}/update', [UserProfileController::class, 'userAddressesUpdate'])->name('update.address');
//    Route::delete('/user_addresses/{addressId}/delete', [UserProfileController::class, 'userAddressesDestroy'])->name('delete.address');
//    /*new*/
//    Route::post('/user_addresses/{address}/set_main', [UserProfileController::class, 'setMainAddress'])->name('user.addresses.set_main');
//
//
//    Route::get('/user_change_password', [UserProfileController::class, 'changePasswordView'])->name('user_password');
//    Route::put('/user_update_info/update', [UserProfileController::class, 'updatePassword'])->name('update_password');
//    Route::put('/user_normal_info/update', [UserProfileController::class, 'updateUserInfo'])->name('update_user_info');
//
//
//    /* مشاهدة الطلب */
//    Route::get('/user_orders/{number}', [UserOrdersController::class, 'showOrder'])->name('user.orders');
//    /* الطلبات */
//    Route::get('/user_main_orders', [UserOrdersController::class, 'mainOrders'])->name('user.main.orders');
//    /* حذف طلب */
//    Route::delete('/user_orders/delete', [UserOrdersController::class, 'destroy'])->name('order.delete');
//
//
//    /* المرتجعات */
//    Route::get('/user_return_orders', [ReturnProductsController::class, 'index'])->name('user.return_products');
//    Route::post('/user_return_orders/store', [ReturnProductsController::class, 'store'])->name('user.return_products.store');
//
//
//    #################################################--Comments Routes
//    Route::post('/comments', [CommentsController::class, 'store'])->name('comments.store');
//});

/*دفع طلب الخدمة*/
Route::get('/payment-page/{order_number}/{method}', [\App\Http\Controllers\Api\PaymentController::class, 'index'])->name('user.payment');
Route::get('/payment-page/{number}/payment/callback', [\App\Http\Controllers\Api\PaymentController::class, 'callback'])->name('payment.callback');

/*دفع الباقة*/
Route::get('/payment-package/{package_id}/{method}', [\App\Http\Controllers\Api\PaymentController::class, 'package_payment_index'])->name('user.payment_package');
Route::get('/payment-package/{package_id}/payment/callback', [\App\Http\Controllers\Api\PaymentController::class, 'package_callback'])->name('payment.package_callback');

/*تجديد الباقة*/
Route::get('/payment-renewal-subscribe/{package_id}/{method}', [\App\Http\Controllers\Api\PaymentController::class, 'renewal_package_payment_index'])->name('user.renewal_payment_package');
Route::get('/payment-renewal-subscribe/{package_id}/payment/callback', [\App\Http\Controllers\Api\PaymentController::class, 'renewal_package_callback'])->name('payment.renewal_package_callback');


require __DIR__ . '/dashboard_cleaned.php';
