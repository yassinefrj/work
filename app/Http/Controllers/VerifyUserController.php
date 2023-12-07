<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class VerifyUserController extends Controller
{

    public function getUnverifiedUsers(){
        $unverified = User::nonVerifiedUsers();
        return view('auth.validate', ['unverified' => $unverified]);
    }

    public function verifyUser(Request $request){
        $request->validate(['id_user_unverified' => ['required']]);
        User::verifyUser($request->id_user_unverified);
        return back();
    }

    public function refuseUser(Request $request){
        $request->validate(['id_user_unverified' => ['required']]);
        User::deleteUser($request->id_user_unverified);
        return back();
    }
}
