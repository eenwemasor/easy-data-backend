<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UpdateUserData extends Controller
{
    function index(Request $request){
        $user_id = $request->id;
        $new_balance = $request->newBalance;
        $wallet_type = $request->walletType;

        $user = User::where('id', $user_id)->first();

        if ($wallet_type == "bonus_wallet"){
            $user->bonus_wallet = $new_balance;
            $user->save();

            return response()->json(['status' => 'success', 'message'=>"Bonus Wallet Updated Successfully"]);
        }else{
            $user->wallet = $new_balance;
            $user->save();

            return response()->json(['status' => 'success', 'message'=>"Wallet Updated Successfully"]);
        }
    }
}
