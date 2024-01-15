<?php

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('coding/gensequence/{id}', 'API\NumberController@generateSequences');
Route::post('coding/gencodes/{id}', 'API\NumberController@generateCodes');
Route::post('coding/clearcodes/{id}', 'API\NumberController@clearCodes');



Route::post('jpush-notification', 'API\NotificationController@jPushNotification');
Route::post('login', 'API\Auth\LoginController@login');
Route::post('register', 'API\Auth\RegisterController@register');
Route::post('resend-code', 'API\Auth\RegisterController@resendVerifiedCode');
Route::post('push-notification', 'API\NotificationController@pushNotificatoin');

// Phone otp
Route::post('get-otp-code', 'API\Auth\PhoneController@getOtpCode');
Route::post('resend-otp-code', 'API\Auth\PhoneController@resendOtpCode');
Route::post('forget-password/get-otp-code', 'API\Auth\ForgetPasswordController@getOptForForgetPassword');
Route::post('forget-password/verify-otp-code', 'API\Auth\PhoneController@verifyOptCode');
Route::post('phones/get-otp-code', 'API\Auth\PhoneController@getOtpCode');
Route::post('phones/verify-otp-code', 'API\Auth\PhoneController@verifyOptCode');

Route::post('forget-password', 'API\Auth\ForgetPasswordController@forgetPassword');
Route::post('password/reset', 'API\Auth\ForgetPasswordController@resetPassword');

// public home
Route::get('public/home', 'API\HomeController@getPublicHome');
Route::get('public/products', 'API\ProductController@getPublicProductList');
Route::get('public/nearby-shops/{lat}/{lon}', 'API\ShopController@getNearbyShops');
Route::get('products/{product_id}', 'API\ProductController@getProductDetail');
Route::post('products/search', 'API\SearchController@searchAll');
Route::post('public/config', 'API\LoadConfigController@publicLoadConfig');

/**
 * Products
 */
Route::get('products/location1/{id}', 'API\ProductController@getProductsByLocation');

/**
 * locations
 */
Route::get('countries', 'API\LocationsController@getCountry');
Route::get('provinces/{country_id}', 'API\LocationsController@getProvinceList');
Route::get('districts/{province_id}', 'API\LocationsController@getDistrictList');

// Notification no auth
Route::post('notifications/push-all', 'API\NotificationController@pushNotificationToAllUser');

// Public category routing
Route::get('categories', 'API\CategoryController@getCategories');
Route::get('categories/list', 'API\CategoryController@getCategoryList');
Route::get('categories/products/{category_id?}', 'API\CategoryController@show');

// Public brand routing
Route::get('brands', 'API\BrandController@getBrands');
Route::get('brands/list', 'API\BrandController@getBrandList');
Route::get('brands/products/{brand_id?}', 'API\ProductController@getProductsByBrand');

// Added by channa
Route::post('public/search', 'API\SearchController@searchAll');
Route::get('public/search', 'API\SearchController@searchAll');
Route::get('public/filter', 'API\SearchController@filter');

// Search
Route::get('search/typeahead', 'API\SearchController@typeAhead');

// Pages
Route::get('pages/{url?}', 'API\PageController@getPage');


// Route::post('products/create', 'API\ProductController@createProduct');

Route::group(['middleware' => 'auth:api'], function () {
    /**
     * Debug routing: after used comment out.
     */
    Route::get('test/execute', 'API\TestController@execute');
    Route::post('test/viewLogs', 'API\TestController@viewLogs');
    Route::post('test/modifyProductImages', 'API\TestController@modifyProductImages');

    /**
     * Users & security routing
     */
    Route::post('logout', 'API\Auth\LogoutController@logout');
    Route::post('details', 'API\UserController@details');
    Route::get('users', 'API\UserController@index');
    Route::get('users/{id}', 'API\UserController@show');
    Route::post('users/update/{id}', 'API\UserController@updateProfile');

    // Roles
    Route::get('roles', 'API\PermissionController@Permission');
    Route::post('roles/add', 'API\PermissionController@addRole');

    /**
     * Products routing
     */
    Route::get('products', 'API\ProductController@getProductList');
    //Route::post('products/search', 'API\SearchController@searchAll');
    Route::post('products/create', 'API\ProductController@createProduct');
    Route::post('products/update/{id}', 'API\ProductController@updateProduct');
    Route::delete('products/delete/{id}', 'API\ProductController@deleteProduct');
    Route::post('products/store', 'API\ProductController@store');
    Route::get('shop/products', 'API\ShopProductController@index');
    Route::delete('shop/products/{id}', 'API\ShopProductController@delete');


    /**
     * Orders routing
     */
    Route::post('orders/execute-order', 'API\OrderController@executeOrder');
    Route::post('orders/accept-order', 'API\OrderController@acceptOrder');
    Route::post('orders/cancel-order', 'API\OrderController@cancelOrder');
    Route::post('orders/update-order', 'API\OrderController@updateOrderStatus');
    // Buyer Request Order
    Route::get('orders/orders-status', 'API\OrderController@OrderStatus');
    //Veiw order
    Route::get('orders/{orderId}', 'API\OrderController@getOrder');
    Route::get('orders/shop/{shop_id}/{status_id?}', 'API\OrderController@getOrdersByShop');
    // list up orders by user_id
    Route::get('orders/get-my-order-list/{status_id?}', 'API\OrderController@getMyOrderList');
    // list up orders by shop_id -->BTY 20/03/22 to be deleted
    Route::get('orders/get-shop-order-list/{status_id?}', 'API\OrderController@getMyShopOrderList');

    /**
     * Reward points
     */
    Route::get('rewards/points/list', 'API\PointController@getPointList');
    Route::get('rewards/my-points', 'API\PointController@getMyPoints');

    /**
     * Promotions
     */
    Route::get('promotions/list', 'API\PromotionController@getPromotionList');
    Route::get('promotions/verify-code', 'API\PromotionController@verifyCode');

    /**
     * Home routing
     *
     */
    Route::get('home', 'API\HomeController@index');

    /**
     * Shops routing
     */
    Route::get('shops', 'API\ShopController@index');
    Route::get('shops/{id}', 'API\ShopController@show');
    Route::post('open-shop', 'API\ShopController@store');
    Route::post('shops/open-shop', 'API\ShopController@store');
    Route::post('shops/create', 'API\ShopController@store');
    Route::post('shops/update/{id}', 'API\ShopController@update');
    Route::post('shops/delete/{id}', 'API\ShopController@destroy');

    // get list of nearby shops
    Route::get('shops/nearby/{lat}/{lon}', 'API\ShopController@getNearbyShops');
    // find shop by district id (suppliers)
    Route::get('shops/get-suppliers/{district_id}/{membership_level}', 'API\ShopController@getShops_supplier');
    // get list of new opened shop
    Route::get('shops/get-memberships/{supplier_id}/{status_id?}', 'API\ShopController@getMemberships');
    // get??? sambath
    Route::get('shops/supplier/shops', 'API\ShopController@getApplier');
    // For My Products
    Route::get('shops/products/list/{status?}', 'API\ShopController@getShopProductList');
    Route::get('shops/products/{product_id}', 'API\ShopController@getShopProductDetail');
    Route::resource('shop-category', 'API\ShopCategoryController');
    Route::get('shops-lists-category', 'API\ShopCategoryController@getList');

    Route::get('shops/shop-detail-by-user/{user_id}', 'API\ShopController@getShopDetailByUser');

    /**
     * Payment Methods
     */
    Route::post('payment-methods/add', 'API\PaymentMethodController@addPaymentMethod');
    Route::post('payment-methods/add-provider', 'API\PaymentMethodController@addPaymentProvider');
    Route::get('payment-methods/providers', 'API\PaymentMethodController@getProviderList');

    /**
     * Memberships routing
     */
    Route::get('membership', 'API\ShopController@MemberShipList');

    /**
     * Banner
     */
    Route::post('banners/add-banner', 'API\BannerController@addNewBanner');

    /**
     * Load config
     */
    Route::post('loadconfig', 'API\LoadConfigController@loadConfig');

    /**
     *
     */
    Route::get('checkouts', 'API\CheckoutController@getCheckoutList');
    Route::post('getcheckouts', 'API\CheckoutController@getCheckoutList');

    /**
     * Delivery
     */
    Route::get('deliveries', 'API\DeliveryController@getDeliveryList');
    Route::post('deliveries/add', 'API\DeliveryController@addDelivery');
    Route::post('deliveries/add-provider', 'API\DeliveryProviderController@addDeliveryProvider');

    /**
     * Notifications
     */
    Route::get('notifications', 'API\NotificationController@getNotificationList');

    /**
     * Unit
     */
    Route::resource('units', 'API\UnitController');
    Route::resource('city', 'API\CityController');

    //update user fcm token
    Route::post('store-fcm-token', 'API\Auth\LoginController@storeFCMToken');

    // Delivery Address
    Route::resource('delivery-address', 'API\DeliveryAddressController');
});
