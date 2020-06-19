<?php

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
Route::post(
    'stripe/webhook',
    '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook'
);
Route::get('/log-error','CMS\\HomeController@log_error');
Route::group(['prefix'=>'cms','middleware' => ['auth','is_admin','verified']],function(){

	Route::resource('/','CMS\\HomeController');

	Route::resource('/user','CMS\\UserController');
	Route::resource('/config','CMS\\ConfigController');

	Route::resource('/color','CMS\\ColorController');
	Route::resource('/size','CMS\\SizeController');
	Route::resource('/category','CMS\\CategoryController');
	Route::resource('/brand','CMS\\BrandController');
	Route::resource('/product','CMS\\ProductController');


	// Route::get('/dashboard','Backend\\DashboardController@index');
});

Route::get('/', function () {
    return view('welcome');
})->middleware('verified');
Route::get('/home', 'HomeController@index')->name('home');
Auth::routes(['verify' => true]);

// Route::get('/subscribe', 'SubscriptionController@index')->name('subscribe');
// Route::post('/charge', 'SubscriptionController@store')->name('charge');

Route::post(
    'stripe/webhook',
    'WebhookController@handleWebhook'
);