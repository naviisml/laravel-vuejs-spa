<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

// Authentication Routes...
Route::get('/me', 'User\UserController@current');

Route::get('/email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('/email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

Route::post('/logout', 'Auth\LoginController@logout');

// Guest Routes...
Route::post('/login', 'Auth\LoginController@login');
Route::post('/register', 'Auth\RegisterController@register');

// OAuth Routes...
Route::get('/oauth/{driver}', 'Auth\OAuthController@redirect');
Route::post('/oauth/{driver}', 'Auth\OAuthController@redirect');
Route::get('/oauth/{driver}/callback', 'Auth\OAuthController@handleCallback')->name('oauth.callback');

// OpenID Routes...
Route::post('/openid/{driver}', 'Auth\OpenIDController@redirect')->name('openid');
Route::get('/openid/{driver}/handle', 'Auth\OpenIDController@handleCallback')->name('openid.handle');
