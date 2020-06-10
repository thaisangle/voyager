<?php

use App\Http\Controllers\HomeControllers;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/sang','HomeControllers@index');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    // Route::post('login',['uses'=>'HomeControllers@index','as'=>'postlogin']);
});
