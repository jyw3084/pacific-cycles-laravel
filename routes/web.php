<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestEmailController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AJAXController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\BankController;
use App\Http\Livewire;
use Illuminate\Support\Facades\App;
use Gloudemans\Shoppingcart\Facades\Cart;

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

Route::get('set-locale/{var}', function ($var) {
    App::setLocale($var);
    session()->put('locale', $var);

    Cart::destroy();
    return redirect()->back();
})->middleware('checkLocale')->name('locale.setting');


Route::get('email', [TestEmailController::class, 'test_email']);
Route::post('send-mail', [TestEmailController::class, 'sendMail']);

//back-end routes
//if(auth()->user()):

//endif;

    Route::get('login', Livewire\Back\Login::class)->name('login');
    Route::post('login', [AJAXController::class, 'login']);
    Route::group(['prefix' => 'event'], function () {
        Route::post('signup', [AJAXController::class, 'signup']);
        Route::post('pay', [AJAXController::class, 'event_pay']);
        Route::post('return', [AJAXController::class, 'return']);
    });
    Route::post('contact', [AJAXController::class, 'contact']);
    Route::post('apply', [AJAXController::class, 'apply']);
    Route::get('logout', [AJAXController::class, 'logout']);
    Route::post('extend/pay', [AJAXController::class, 'warranty_extension']);
    Route::get('signup-with-phone', Livewire\Back\SignUpWithPhone::class)->name('signup.with.phone');
    Route::get('signup-with-email', Livewire\Back\SignUpWithEmail::class)->name('signup.with.email');
    Route::get('forget-password', Livewire\Back\ForgetPassword::class)->name('forgetpassword');
    Route::get('birthday', [AJAXController::class, 'birthday']);


Route::group(['middleware' => 'auth'], function(){
    Route::get('my-bike', Livewire\Back\Mybikes::class)->name('mybike'); // it's temporary link up SIGN IN button
    Route::get('bike-details/{ProductNo}', Livewire\Back\BikeDetails::class)->name('bike.details');
    Route::get('extend/{ProductNo}', Livewire\Back\Extend::class)->name('extend');
    Route::get('extend-credit-fail/{ProductNo}', Livewire\Back\ExtendCreditFail::class)->name('extend.credit.fail');
    Route::get('my-account', Livewire\Back\MyAccount::class)->name('myaccount');
    Route::get('my-account-edit', Livewire\Back\MyAccountEdit::class)->name('myaccount.edit');
    Route::get('change-pwd', Livewire\Back\PasswordChange::class)->name('password.change');
    Route::get('register-bike-with-qr', Livewire\Back\RegisterBikeWithQr::class)->name('register.bike.with.qr');
    Route::get('my-account-without-email', Livewire\Back\MyAccountWithoutEmail::class)->name('myaccount.without.email');
    Route::get('my-account-without-phone', Livewire\Back\MyAccountWithoutPhone::class)->name('myaccount.without.phone');
    Route::get('register-bike-without-qr', Livewire\Back\RegisterBikeWithoutQr::class)->name('register.bike.without.qr');
    Route::get('transfer/{ProductNo}', Livewire\Back\Transfer::class)->name('transfer');
    Route::get('order-details/{number}', Livewire\Back\OrderDetails::class)->name('myorderdetails');

    Route::get('/my-coupons', Livewire\Back\MyCoupons::class)->name('mycoupons');
    Route::get('/my-orders', Livewire\Back\MyOrders::class)->name('myorder');
    Route::get('/my-credits', Livewire\Back\MyCredits::class)->name('mycredits');
});


//front-end
Route::group(['prefix' => 'store'], function () {
    Route::get('/', Livewire\Frontend\Store::class);
    Route::get('/category/{category_id}', [\App\Http\Controllers\Shop\ShopController::class, 'category']);
    Route::get('products/{product_code}', Livewire\Frontend\ProductDetails::class);
    Route::get('package/{id}', Livewire\Frontend\PackageDetails::class);
});
Route::group(['prefix' => 'shopping'], function () {
    Route::get('cart', Livewire\Frontend\ShippingProcess::class);
    Route::get('order-complete/{number}', Livewire\Frontend\OrderResponse::class);
    Route::get('failed-payment', Livewire\Frontend\Failedpayment::class)->name('failedpayment');
    Route::post('pay', [BankController::class, 'pay']);
    Route::post('callback', [BankController::class, 'callback']);
});
Route::group(['prefix' => 'order'], function () {
    Route::post('pay', [AJAXController::class, 'order_pay']);
    Route::post('pay/{id}', [OrderController::class, 'pay']);
    Route::post('return', [OrderController::class, 'return']);
});
Route::group(['prefix' => 'about'], function () {
    Route::get('/', Livewire\Frontend\Abouts::class);
    Route::get('section-zero', Livewire\Frontend\SectionZero::class);
});
Route::group(['prefix' => 'bikes'], function () {
    Route::get('folding', Livewire\Frontend\BikesFolding::class);
    Route::get('folding/birdy', Livewire\Frontend\BikesFoldingBirdy::class);
    Route::get('folding/reach', Livewire\Frontend\BikesFoldingReach::class);
    Route::get('folding/if', Livewire\Frontend\BikesFoldingIf::class);
    Route::get('folding/carryme', Livewire\Frontend\BikesFoldingCarryme::class);
    Route::get('ebike', Livewire\Frontend\BikesEbike::class);
    Route::get('ebike/moove', Livewire\Frontend\BikesEbikeMoove::class);
    Route::get('ebike/ebirdy', Livewire\Frontend\BikesEbikeEbirdy::class);
    Route::get('supportive', Livewire\Frontend\BikesSupportive::class);
    Route::get('supportive/micah', Livewire\Frontend\BikesSupportiveMicah::class);
    Route::get('supportive/handy', Livewire\Frontend\BikesSupportiveHandy::class);
    Route::get('supportive/2rider', Livewire\Frontend\BikesSupportive2rider::class);
    Route::get('supportive/hase', Livewire\Frontend\BikesSupportiveHase::class);
    Route::get('supportive/racerunner', Livewire\Frontend\BikesSupportiveRacerunner::class);
});
Route::group(['prefix' => 'dealer'], function () {
    Route::get('/', Livewire\Frontend\Dealer::class);
    Route::get('apply', Livewire\Frontend\DealerApply::class);
});
Route::group(['prefix' => 'news-events'], function () {
    Route::get('/', Livewire\Frontend\NewsEvents::class);
    Route::get('news/{id}', Livewire\Frontend\NewsDetails::class);
    Route::get('event/{id}', Livewire\Frontend\EventDetail::class);
    Route::get('event/{id}/register', Livewire\Frontend\EventRegister::class)->name('event-register');
    Route::get('event/{id}/register/failed', Livewire\Frontend\EventRegisterFail::class);
});
Route::group(['prefix' => 'support'], function () {
    Route::get('/', Livewire\Frontend\Support::class);
    Route::get('faq', Livewire\Frontend\Faq::class);
    Route::get('warranty', Livewire\Frontend\WarrantyPolicy::class);
});
Route::get('contact', Livewire\Frontend\Contact::class);
Route::get('/', Livewire\Frontend\Home::class);
Route::get('/home', Livewire\Front\Home::class);

Route::namespace('App\Http\Controllers\Auth')->prefix('auth')->group(function () {
    Route::prefix('google')->group(function () {
        Route::get('/', 'SocialitesGoogleController@google');
        Route::any('callback', 'SocialitesGoogleController@callback');
    });
    Route::prefix('facebook')->group(function () {
        Route::get('/', 'SocialitesFacebookController@facebook');
        Route::any('callback', 'SocialitesFacebookController@callback');
    });
});


//sync
Route::group(['prefix' => 'sync'], function () {
    Route::get('area', [SyncController::class, 'area']);
    Route::get('category', [SyncController::class, 'category']);
    Route::get('model', [SyncController::class, 'model']);
    Route::get('prodcutByModel', [SyncController::class, 'prodcutByModel']);
    Route::get('store', [SyncController::class, 'store']);
    Route::get('bikes', [SyncController::class, 'MyBikeGetList']);
});

