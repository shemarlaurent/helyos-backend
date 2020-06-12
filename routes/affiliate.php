<?php

Route::group(['namespace' => 'Affiliate'], function() {
    Route::get('/', 'HomeController@index')->name('affiliate.dashboard');

    // Login
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('affiliate.login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('affiliate.logout');

    // Register
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('affiliate.register');
    Route::post('register', 'Auth\RegisterController@register');

    // Passwords
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('affiliate.password.email');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('affiliate.password.request');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('affiliate.password.reset');

    // Must verify email
    Route::get('email/resend','Auth\VerificationController@resend')->name('affiliate.verification.resend');
    Route::get('email/verify','Auth\VerificationController@show')->name('affiliate.verification.notice');
    Route::get('email/verify/{id}','Auth\VerificationController@verify')->name('affiliate.verification.verify');

});