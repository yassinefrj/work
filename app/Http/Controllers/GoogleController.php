<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * The GoogleController class extends Laravel's base Controller class.
 * It facilitates the authentication with Google and the handling of Google OAuth callbacks for user login.
 */
class GoogleController extends Controller
{

    /**
     * The redirect() method initiates the Google OAuth process by redirecting the user to the Google authentication page.
     */
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }
 
    /**
     * The callback() method is responsible for handling the callback after a successful Google authentication. 
     * It retrieves user information from Google, checks if the user already exists in the database,
     *  and either logs them in or creates a new user.
     */
    public function callback() {
        $google_user = Socialite::driver('google')->user();


        $user = User::firstWhere("email", $google_user->getEmail());
        if(!$user) {

            $user = User::create([
                'email' => $google_user->getEmail(),
                'name' => $google_user->getName(),
                'avatar_path' => $google_user->getAvatar(),
                'password' => base64_encode(random_bytes(8)),
            ]);
        }
    
        $user->google_id = $google_user->getId();
        $user->save();
          
        Auth::login($user);
    
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
