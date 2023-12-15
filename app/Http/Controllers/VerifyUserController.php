<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

/**
 * The VerifyUserController class extends Laravel's base Controller class.
 *  It is responsible for managing the verification and refusal of user accounts.
 */
class VerifyUserController extends Controller
{

    /**
     * The getUnverifiedUsers method retrieves a list of unverified users and renders a view to display them.
     */
    public function getUnverifiedUsers(){
        $unverified = User::nonVerifiedUsers();
        return view('auth.validate', ['unverified' => $unverified]);
    }
    
    /**
     * The verifyUser method verifies a user account based on the provided user ID and updates the user's verification status.
     */
    public function verifyUser(Request $request){
        $request->validate(['id_user_unverified' => ['required']]);
        User::verifyUser($request->id_user_unverified);
        return back();
    }

    /**
     * The refuseUser method refuses or deletes a user account based on the provided user ID.
     */
    public function refuseUser(Request $request){
        $request->validate(['id_user_unverified' => ['required']]);
        User::deleteUser($request->id_user_unverified);
        return back();
    }
}
