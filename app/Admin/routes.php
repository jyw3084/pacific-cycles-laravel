<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    Route::resource('translations', 'TranslationController');
    Route::resource('currencies', 'CurrencyController');
    Route::resource('news_categories', 'NewsCategoryController');
    Route::resource('news', 'NewsController');
    Route::resource('event_categories', 'EventCategoryController');
    Route::resource('events', 'EventController');
    Route::resource('signups', 'EventSignUpController');
    Route::resource('manage-customers', 'UserController');
    Route::resource('contact-form', 'ContactFormController');
    Route::resource('about', 'AboutController');
    Route::resource('faq', 'FaqController');
    Route::resource('contact', 'ContactController');
    Route::resource('manuals', 'ManualFilesController');
    Route::resource('product_features', 'FeaturesController');
    Route::resource('cms-header', 'HeaderController');
    Route::resource('cms-footer', 'FooterController');
    Route::resource('bikes', 'BikeController');
    Route::resource('pages', 'BikeController');
    Route::resource('warranty-policy', 'WarrantyPolicyController');
    Route::group(['prefix' => 'dealers'], function () {
        Route::resource('store', 'StoreController');
        Route::resource('form', 'DealerFormController');
    });
    Route::group(['prefix' => 'e-commerce'], function () {
        Route::resource('discounts', 'DiscountController');
        Route::resource('orders', 'OrderController');
        Route::resource('shipment', 'ShipmentController');
    });
    Route::resource('shipping_fee', 'ShippingFeeController');
    Route::resource('free', 'FreeShippingController');
    Route::group(['prefix' => 'product'], function () {
        Route::resource('packages', 'PackagesConrtoller');
        Route::resource('products', 'ProductsController');
        Route::resource('categories', 'CategoryController');
        Route::resource('models', 'BikeModelController');
        Route::resource('accessories', 'AccessoriesController');
        Route::resource('warranty-extension', 'WarrantyExtensionController');
    });
    
    Route::resource('dealer', 'DealerController');
    Route::resource('email-management', 'EmailManagementControler');
    Route::resource('promos', 'PromosController');
    Route::resource('vouchers', 'VoucherController');
    Route::resource('bonus', 'BonusController');
    Route::resource('setting', 'SettingController');
    Route::resource('home', 'IndexController');
    Route::resource('banner', 'BannerController');
    Route::resource('head', 'HeadController');
    Route::post('api/upload', 'ApiController@uploadImg')->name('uploadImg');
    Route::get('api/bike_model', 'ApiController@bike_model');
    Route::get('api/product', 'ApiController@product');
    Route::get('api/bikes', 'ApiController@bikes');
    Route::get('api/package_products', 'ApiController@package_products');

});
