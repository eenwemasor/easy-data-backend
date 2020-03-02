<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function getUserById(string $id)
    {
        $user = User::findOrFail($id);
        return $user;
    }


    /**
     *
     */
//    gets admin user
    function getAdmin()
    {
        return $admin = User::where("accessibility", "admin")->first();
    }

}
