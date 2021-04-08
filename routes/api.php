<?php

use App\Http\Middleware\PaystackRequestIsValid;
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

Route::post('/referral_reward', 'ReferralRewardController@index');
Route::post('/send_verification_email', 'SendVerificationUrlController@sendEmailVerification');
Route::post('/account_activate_reward', 'ReferralRewardController@activateAccount');
Route::post('/save_referral', 'ReferralRewardController@save_referral');
Route::post('/sms/send_notification', 'SendSMSController@index');
Route::post('/sms/send_phone_verification', 'SendPhoneVerification@generateToken');
Route::post('/sms/resend_phone_verification', 'SendPhoneVerification@resendToken');
Route::post('/sms/verify_token', 'SendPhoneVerification@verifyToken');
Route::post('/update_user_data', 'UpdateUserData@index');
Route::post('/monnify-transactions', 'MonnifyController@index');
Route::post('/paystack', 'PaystackController@index')->middleware(PaystackRequestIsValid::class);

