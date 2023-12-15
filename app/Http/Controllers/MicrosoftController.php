<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;

/**
 * The MicrosoftController class extends Laravel's base Controller class.
 *  It facilitates the authentication with Microsoft and the handling of Microsoft OAuth callbacks for user login.
 */
class MicrosoftController extends Controller
{

    /**
     * The redirect() method initiates the Microsoft OAuth process by redirecting the user to the Microsoft authentication page.
     */
    public function redirect() {
        return Socialite::driver('microsoft')->redirect();
    }
 
    /**
     * The callback() method is responsible for handling the callback after a successful Microsoft authentication.
     * It retrieves user information from Microsoft, checks if the user already exists in the database, 
     * and either logs them in or creates a new user.
     */
    public function callback() {
        $ms_user = Socialite::driver('microsoft')->user();
    
        // TODO: generalize this
        $user = User::firstWhere("email", $ms_user->getEmail());
        if(!$user) {
            $user = User::create([
                'email' => $ms_user->getEmail(),
                'name' => $ms_user->getName(),
                'avatar_path' => $ms_user->getAvatar(),
                'password' => base64_encode(random_bytes(8)),
            ]);
        }
    
        $user->microsoft_id = $ms_user->getId();
        $user->save();
          
        Auth::login($user);
    
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
