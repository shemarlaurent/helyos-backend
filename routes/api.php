<?php

use Illuminate\Http\Request;
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

Route::prefix('v1')->group(function() {
    Route::prefix('admin')->middleware('auth.assign:admin')->group(function () {
        Route::post('/login', 'Admin\AuthController@login');

        Route::prefix('details')->group(function() {
            Route::get('/', 'Admin\HomeController@index');
        });

        Route::get('sellers', 'Admin\HomeController@sellers');
        Route::get('affiliates', 'Admin\HomeController@affiliates');

        // invitation links
        Route::prefix('invitation')->middleware('jwt.verify:admin')->group(function() {
            Route::get('all', 'InvitationController@getInvitations');
            Route::post('seller', 'Admin\InviteSellerController@inviteSeller');
            Route::post('affiliate', 'Admin\InviteSellerController@inviteAffiliate');
        });

        Route::prefix('priced-products')->group(function() {
            Route::get('details', 'Admin\PriceProductController@getDetails');
            Route::get('products', 'Admin\PriceProductController@getProducts');
            Route::post('add', 'Admin\PriceProductController@addProducts');
        });

        /*
         *  Pryo routes
         */
        Route::prefix('pryo')->group(function() {
            Route::post('create', 'Admin\PryoController@create');
            Route::get('active', 'Admin\PryoController@active');
            Route::get('details', 'Admin\PryoController@details');
        });

        /*
         * Admin Settings routes
         */
        Route::prefix('settings')->group(function() {
            Route::get('banner', 'BannerController@banners');
            Route::post('banner', 'BannerController@addBanners');
        });
    });

    /*
     * Seller Routes
     */
    Route::prefix('seller')->middleware('auth.assign:seller')->group(function () {
        Route::post('/login', 'Seller\AuthController@login');
        Route::post('/register', 'Seller\AuthController@register');


        /*
         * Seller Store routes
         */
        Route::prefix('store')->middleware('jwt.verify')->group(function() {
            Route::get('details', 'Seller\StoreController@index');
            Route::post('/product/create', 'Seller\ProductController@store');
            Route::post('/product/createCsv', 'Seller\ProductController@fromCsv');
            Route::get('/products/all', 'Seller\ProductController@index');
            Route::get('/products/{product}', 'Seller\ProductController@show');
        });

        /*
         * Seller Orders routes
         */
        Route::prefix('orders')->middleware('jwt.verify')->group(function() {
                Route::get('all', 'Seller\OrderController@index');
            Route::get('{order}', 'Seller\OrderController@store');
        });

    });

    /*
     * Affiliate Routes
     */
    Route::prefix('affiliate')->middleware('auth.assign:affiliate')->group(function() {
        Route::post('login', 'Affiliate\AuthController@login');
        Route::post('register', 'Affiliate\AuthController@register');

        Route::get('details', 'Affiliate\HomeController@home');
    });

    /*
     * Abyss Routes
     */
    Route::prefix('abyss')->middleware('auth.assign:abyss_user')->group(function () {
        Route::post('register', 'Abyss\AuthController@register');
        Route::post('login', 'Abyss\AuthController@login');
        Route::post('update', 'Abyss\AccountController@update');

        /*
         * Abyss Forum Routes
         */
        Route::prefix('forums')->group(function() {
            Route::post('create', 'Admin\AbyssForumController@create');
            Route::get('all', 'Abyss\ForumController@forums');
            Route::get('details', 'Admin\AbyssController@details');
            Route::get('user-forums', 'Abyss\AbyssUserForumController@getUserForums');
            Route::post('join', 'Abyss\AbyssUserForumController@joinForum');

            Route::prefix('{forum}')->group(function() {
                Route::get('/', 'Abyss\ForumController@forum');
                Route::get('messages', 'Abyss\AbyssForumMessagesController@getMessages');
                Route::post('send', 'Abyss\AbyssForumMessagesController@sendMessage');
                Route::post('claim-price', 'Abyss\ForumController@claimPrice');
            });
        });
    });

    Route::middleware('auth.assign:api')->group(function() {
        Route::prefix('auth')->group(function () {
            Route::post('register', 'User\AuthController@register');
            Route::post('login', 'User\AuthController@login');
            Route::post('update', 'User\AuthController@update');
        });

        Route::prefix('account')->group(function() {
            Route::get('terminate', 'User\AccountController@terminateSubscription');
            Route::get('revive', 'User\AccountController@reviveSubscription');

            Route::prefix('orders')->group(function() {
                Route::get('/', 'User\OrderController@index');
            });

            Route::prefix('likes')->group(function() {
                Route::get('/', 'User\LikeController@index');
            });

        });
    });

    Route::prefix('resources')->group(function () {
        Route::get('/brands', 'BrandController@index');
        Route::get('/categories', 'CategoryController@index');
    });

    Route::prefix('store')->group(function () {
        Route::prefix('products')->group(function () {
            Route::get('all', 'ProductController@index');
            Route::get('featured', 'ProductController@featured');
            Route::get('{product}', 'ProductController@show');
            Route::get('{product/like}', 'User\ProductLikeController@toggleLike');
            Route::get('/related/{product}', 'Seller\ProductController@related');
        });
    });

    Route::prefix('pyp')->group(function () {
        Route::get('details', 'PickYourPriceController@sales');
    });


    Route::prefix('payment')->group(function() {
        Route::post('checkout', 'Store\CheckoutController@checkout');
    });

    Route::get('search/{query}', 'SearchController@search');
    Route::get('search-list-products', 'SearchController@searchList');
    Route::post('search-filters', 'SearchController@searchWithFilters');
    Route::get('search-params', 'SearchController@filterParams');

    Route::post('like', 'LikeController@like');
    Route::post('dislike', 'LikeController@dislike');
    Route::get('likes', 'LikeController@likes');

    Route::prefix('associates')->group(function () {
        Route::get('details', 'HomeController@details');
    });

    Route::prefix('card')->group(function () {
        Route::post('add', 'HomeController@addCard');
    });

    Route::prefix('plazma')->group(function() {
        Route::post('shoe-check', 'PlazmaController@shoeCheck');
        Route::post('shoe-name', 'PlazmaController@shoeName');
        Route::post('authentic', 'PlazmaController@authentic');
        Route::post('sole-check', 'PlazmaController@soleCheck');
    });

    Route::prefix('delphi')->group(function() {
        Route::post('forecast', 'DelphiController@forecast');
    });

    Route::post('insta-search', 'InstaController@shoeName');
});

