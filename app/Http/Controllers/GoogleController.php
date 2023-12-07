<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{

    public function redirect() {
        return Socialite::driver('google')->redirect();
    }
 
    public function callback() {
        $google_user = Socialite::driver('google')->user();


        $user = User::firstWhere("email", $google_user->getEmail());
        if(!$user) {
            $user = User::create([
                'email' => $google_user->getEmail(),
                'name' => $google_user->getName(),
                'password' => base64_encode(random_bytes(8)),
            ]);
        }
    
        $user->google_id = $google_user->getId();
        $user->save();
          
        Auth::login($user);
    
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
