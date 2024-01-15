<?php

use Illuminate\Http\Request;

//Auth::routes();
//Route::namespace('App\Admin\Http\Controllers')->group(function () {
/*
Route::get('/', function () {
    return 'test';
});
*/
/*
Route::get('login', function() {
    return view('admin::auth.login');
    //return 'test';
})->name('admin.login');
*/

Route::get('login', 'Http\Controllers\Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('login', 'Http\Controllers\Auth\AdminLoginController@login')->name('admin.login.submit');
Route::post('logout', 'Http\Controllers\Auth\AdminLoginController@logout')->name('admin.logout');
//});
/*
Route::namespace('Http\Controllers')->middleware('auth:admin')->group(function () {
    Route::get('/', 'HomeController@index')->name('admin.home');
});
*/

Route::namespace('Http\Controllers')->middleware(['auth:admin'])->group(function () {
    Route::get('/', 'HomeController@index')->name('admin.dashboard');

    Route::post('user/{id}', 'UserController@destroy');
    Route::resource('users', 'UserController');
    Route::post('admins/{id}', 'AdminController@destroy');
    Route::resource('admins', 'AdminController');

    Route::resource('codes', 'CodeController');
    Route::resource('code-data', 'CodeVerificationController');
    Route::resource('code-settings', 'CodeSettingController');
    
    Route::get('codes-data/updatefixaa', 'CodeController@updateCodes');
    
    Route::get('code-data/download/{id}', 'CodeController@downloadIndex')->name('codes.download');
    Route::get('code-data/download_data/{fid}', 'CodeController@downloadCodeData')->name('codes.downloaddata');
    Route::get('code-caldiff', 'CodeController@calDiff')->name('caldiff');

    Route::resource('products', 'ProductController');
    Route::get('product-datatables', 'ProductController@datatables')->name('product.datatables');
    Route::get('product/subunit/{id}', 'ProductController@getSubUnits')->name('product.getSubUnit');
    Route::post('product-status', 'ProductController@changeProductStatus')->name('product.status');
    Route::get('product-get-total', 'ProductController@getTotalProductBy')->name('product.get_total');
    Route::post('product-remove', 'ProductController@removeById')->name('product.remove_by_id');

    Route::post('categories/{id}', 'CategoryController@destroy');
    Route::resource('categories', 'CategoryController');
    Route::get('categories-datatables', 'CategoryController@datatables')->name('category.datatables');
    Route::post('category-status', 'CategoryController@updateStatus')->name('category.status');

    Route::post('units/{id}', 'UnitController@destroy');
    Route::resource('units', 'UnitController');

    Route::post('banners/{id}', 'BannerController@destroy');
    Route::resource('banners', 'BannerController');
    Route::get('banners-datatables', 'BannerController@datatables')->name('banners.datatables');
    route::post('banner-status', 'BannerController@updateStatus')->name('banners.status');

    Route::post('shops/{id}', 'ShopController@destroy');
    Route::resource('shops', 'ShopController');
    // 08/01/2021
    Route::get('shops/get-supplier/{id}', 'ShopController@getSupplier')->name('shops.get_supplier');

    Route::post('orders/{id}', 'OrderController@destroy');
    Route::resource('orders', 'OrderController');
    Route::get('notifications/orders/{id}', 'OrderController@show');

    Route::post('memberships/{id}', 'MembershipController@destroy');
    Route::resource('memberships', 'MembershipController');

    Route::post('city/{id}', 'CityController@destroy');
    Route::resource('cities', 'CityController');

    Route::post('district/{id}', 'DistrictController@destroy');
    Route::resource('districts', 'DistrictController');
    Route::get('get-district/{id}', 'DistrictController@getDistrict')->name('get.district');

    Route::post('payments/{id}', 'PaymentController@destroy');
    Route::resource('payments', 'PaymentController');

    Route::post('paymentMethods/{id}', 'PaymentMethodController@destroy');
    Route::resource('paymentMethods', 'PaymentMethodController');

    Route::post('productPrices/{id}', 'ProductPriceController@destroy');
    Route::resource('productPrices', 'ProductPriceController');
    Route::get('productPrices/unit/{id}', 'ProductPriceController@getUnit')->name('productPrice.units');

    Route::post('deliveryProviders/{id}', 'DeliveryProviderController@destroy');
    Route::resource('deliveryProviders', 'DeliveryProviderController');

    Route::post('deliveries/{id}', 'DeliveryController@destroy');
    Route::resource('deliveries', 'DeliveryController');

    Route::resource('brands', 'BrandController');
    Route::get('brands-datatables', 'BrandController@datatables')->name('brands.datatables');
    Route::post('brands-status', 'BrandController@updateStatus')->name('brands.status');

    // Route::post('shops', 'ShopController@store')->name('shops.store');

    Route::post('productPrices/search', 'ProductPriceController@search')->name('productPrices.search');

    Route::post('orders/search', 'OrderController@search')->name('orders.search');

    Route::post('remove-item', 'OrderItemController@destroy')->name('order.removeitem');

    Route::resource('shop-category', 'ShopCategoryController');

    Route::get('promotion-membership', 'PromotionController@getMemberShipDiscount')->name('promotion.membership');
    Route::resource('promotion', 'PromotionController');
    Route::resource('promotion-type', 'PromotionTypeController');
    Route::get('promotion-generate-code', 'PromotionController@generateCode')->name('promotion.generate-code');

    // Redeem code
    Route::resource('/redeem', 'RedeemController');

    Route::resource('settings', 'SettingController');
    Route::post('setting/product-point', 'SettingController@rateExchangeRedeemPoint')->name('setting.product-point');

    //19/03/2021
    // Route::post('order/update-order-status', 'OrderController@updateOrderStatus')->name('order.update_status_order');

    Route::resource('point', 'PointController');

    // Payment Provider
    Route::resource('payment-provider', 'PaymentProviderController');

    // Payment account
    Route::resource('payment-accounts', 'PaymentAccountController');
});
