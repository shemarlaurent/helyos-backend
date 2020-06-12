<?php

Route::group(['namespace' => 'AbyssUser'], function() {
    Route::get('/', 'HomeController@index')->name('abyssuser.dashboard');

    // Login
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('abyssuser.login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('abyssuser.logout');

    // Register
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('abyssuser.register');
    Route::post('register', 'Auth\RegisterController@register');

    // Passwords
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('abyssuser.password.email');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('abyssuser.password.request');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('abyssuser.password.reset');

    // Must verify email
    Route::get('email/resend','Auth\VerificationController@resend')->name('abyssuser.verification.resend');
    Route::get('email/verify','Auth\VerificationController@show')->name('abyssuser.verification.notice');
    Route::get('email/verify/{id}','Auth\VerificationController@verify')->name('abyssuser.verification.verify');

});