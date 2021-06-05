<?php

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

Route::get('/', 'WebController@index');
Route::get('/contact-us', 'WebController@contactUs');
Route::post('/read-notification', 'WebController@readNotification');
Route::post('products/checkProduct', 'ProductController@checkProduct');

Route::post('admin/tools/update-product-price', 'Admin\ToolController@updateProductPrice');
Route::post('admin/tools/create-product-redis', 'Admin\ToolController@createProductRedis');

Route::get('products/{id}/sharedUrl', 'ProductController@sharedUrl');
Route::resource('products', 'ProductController');

Route::post('signup', 'AuthController@signup');

Route::post('login', 'AuthController@login');

Route::resource('admin/orders', 'Admin\OrderController');
Route::resource('admin/products', 'Admin\ProductController');
Route::post('admin/products/upload-image', 'Admin\ProductController@uploadImage');
Route::post('/admin/products/excel/import', 'Admin\ProductController@import');

Route::post('admin/orders/{id}/delivery', 'Admin\OrderController@delivery');
Route::get('/admin/orders/excel/export', 'Admin\OrderController@export');
Route::get('/admin/orders/excel/export-by-shipped', 'Admin\OrderController@exportByShipped');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', 'AuthController@user');
    Route::get('logout', 'AuthController@logout');
    Route::resource('carts', 'CartController');
    Route::resource('cart-item', 'CartItemController');
    Route::post('carts/checkout', 'CartController@checkout');
});
