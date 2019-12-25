<?php

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

Route::post('/send_verification_email', 'SendVerificationUrlController@index');
Route::post('/referral_reward', 'ReferralRewardController@index');
Route::post('/save_referral', 'ReferralRewardController@save_referral');
