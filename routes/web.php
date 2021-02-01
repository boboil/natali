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

Route::get('/', 'App\Http\Controllers\HomeController@home')->name('home');



/*Category*/
Route::get('/category', 'App\Http\Controllers\CategoryController@categories')->name('categories');
Route::get('/category/{slug}', 'App\Http\Controllers\CategoryController@category')->name('category');
Route::get('/ajax/{slug}', 'App\Http\Controllers\CategoryController@ajax')->name('product.ajax');
/*Category end*/

/*Pages*/
Route::get('/page/{slug}', 'App\Http\Controllers\PageController@page')->name('page');
/*Pages end*/


/*Product*/
Route::get('/product/{slug}', 'App\Http\Controllers\ProductController@product')->name('product');
/*Product end*/

/*Cart*/
//Route::post('/cart-show', 'CartController@show')->name('cart.show');
Route::post('/cart-add', 'App\Http\Controllers\CartController@add')->name('cart.add');
Route::post('/cart-update', 'App\Http\Controllers\CartController@update')->name('cart.update');
Route::post('/cart-remove', 'App\Http\Controllers\CartController@remove')->name('cart.remove');
Route::get('/shopping-cart', 'App\Http\Controllers\CartController@shopping')->name('shopping.cart');
Route::get('/checkout', 'App\Http\Controllers\CartController@checkout')->name('checkout');
/*Route::get('/order', 'App\Http\Controllers\CartController@order')->name('order');*/
//Route::post('/cart-clear', 'CartController@clear')->name('cart.clear');
/*Cart end*/


/*Checkout*/
Route::get('/order','App\Http\Controllers\OrderController@order');
Route::post('/order','App\Http\Controllers\OrderController@ordersend');
/*Checkout end*/

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('amo', 'App\Http\Controllers\AmoController@auth')->name('auth');
Route::any('amo-add-pr', 'App\Http\Controllers\AmoController@addProduct')->name('addProduct');
Route::get('amo-status', 'App\Http\Controllers\AmoController@status')->name('status');
