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

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Margules\bplib\BluePay;
use Symfony\Component\Console\Input\Input;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('pay', 'User\PaymentController@charge');
Route::get('out', 'Abyss\ForumController@forums');

Route::get('order/{order}', 'OrderController@buyAgain');

Route::get('product/{product}', 'Seller\ProductController@show');
