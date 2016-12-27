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

Route::group(['prefix'=>config('cludge.url_namespace')], function () {
    //dashboad
    Route::get('/', 'CludgeController@dashboard');

    // AUTHENTICATION
    $this->get('login', 'Auth\LoginController@showLoginForm')->name(config('cludge.url_namespace').'.login');
    $this->post('login', 'Auth\LoginController@login');
    $this->post('logout', 'Auth\LoginController@logout')->name(config('cludge.url_namespace').'.logout');
    // Registration Routes...
    $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name(config('cludge.url_namespace').'.register');
    $this->post('register', 'Auth\RegisterController@register');
    // Password Reset Routes...
    $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
    $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
    $this->post('password/reset', 'Auth\ResetPasswordController@reset');
});

Route::any('{slug?}', 'CludgeController@page')->where('slug', '^(?!_).+')->name(config('cludge.url_namespace').'.page');
