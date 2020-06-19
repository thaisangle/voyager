<?php

use Illuminate\Http\Request;

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

Route::get('cron', 'API\ConfigController@cron_config');

Route::group(['prefix' => 'v1'], function () {
    Route::get('email/verify/{id}', 'API\VerificationController@verify')->name('verificationapi.verify');
    Route::post('email/resend', 'API\VerificationController@resend')->name('verificationapi.resend');
    Route::post('login', 'API\AuthController@login');

    Route::get('get-token-instagram', 'API\AuthController@getAccessToken');
    // Route::get('login-instagram', 'API\AuthController@login_instagram');

    Route::post('send-notify', 'API\AuthController@sendNotify');
    Route::post('register', 'API\AuthController@register');
    Route::post('register_via_social', 'API\AuthController@register_via_social');

    Route::resource('category','API\CategoryController');
    Route::resource('size','API\SizeController');
    Route::resource('color','API\ColorController');
    Route::resource('brand','API\BrandController');
    Route::get('detail','API\ConfigController@list_detail');
	
    // Route::resource('product','API\ProductController');
    // Route::delete('product/delete_image/{id}','API\ProductController@delete_image');
    // Route::post('like/{id}','API\ProductController@Like');
    // Route::post('skip/{id}','API\ProductController@Skip');
    
    Route::group(['middleware' => 'auth:api'], function () {
	    Route::get('get_user_via_token', 'API\AuthController@getUserViaToken');
        Route::post('logout-user', 'API\AuthController@logout');
        Route::post('edit-profile', 'API\AuthController@editProfile');
        Route::post('delete-account','API\AuthController@delete_account');
        Route::post('setting-filter','API\AuthController@setting_filter');
        Route::post('add-card','API\AuthController@add_card');
        Route::post('delete-card','API\AuthController@delete_card');
        Route::post('list-card','API\AuthController@list_card');
        Route::post('select-active-card','API\AuthController@select_active_card');
        Route::post('buy-premium','API\AuthController@buy_premium');
        Route::post('cancel-premium','API\AuthController@cancel_premium');
        
        Route::post('check-like','API\MatchProductController@check_like_my_product');
        Route::post('like/{id}','API\MatchProductController@Like');
        Route::post('skip','API\MatchProductController@skip');
        Route::post('dislike/{id}','API\MatchProductController@dislike');
        Route::post('unmatch','API\MatchProductController@unmatch');
        Route::post('seen-match','API\MatchProductController@seen');
        Route::get('check-seen-match','API\MatchProductController@check_seen_match');

        Route::get('my-product','API\ProductController@my_product');
        Route::post('product','API\ProductController@store');
        Route::post('product/{id}','API\ProductController@update');
        Route::post('product-list','API\ProductController@index');
        Route::post('product-search','API\ProductController@search');
        Route::get('product/{id}','API\ProductController@show');
        Route::post('product/delete-old-product/{id}','API\ProductController@delete_old_product');
        
        Route::delete('product/delete_image/{id}','API\ProductController@delete_image');
        Route::delete('product/{id}','API\ProductController@destroy');
        Route::post('product/upload_image/{id}','API\ProductController@upload_image');
        Route::post('product/update_image/{id}','API\ProductController@update_image');

        Route::post('confirm-swap-product','API\MatchProductController@confirm_swap_product');

        Route::post('registration-buy','API\BuyProductController@registration_buy');

        Route::post('sell','API\BuyProductController@sell');
        Route::post('search-display-all-message','API\BuyProductController@search_match_product_and_sell_product');
        Route::post('list-buy-product','API\BuyProductController@list_buy_product');
        Route::post('cancel-request-buy/{id}','API\BuyProductController@destroy');

        Route::resource('rate','API\RateController');
        Route::post('rate-of-product','API\RateController@rate_of_product');
        Route::post('rate-app','API\RateController@rate_app');
    });

    Route::post('product-no-user','API\ProductController@index_no_user');

    Route::get('cronjob','API\ConfigController@cron_config');

    Route::post('test-upload', 'API\ConfigController@test_upload');

});