<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class MicrosoftController extends Controller
{

    public function redirect() {
        return Socialite::driver('microsoft')->redirect();
    }
 
    public function callback() {
        $ms_user = Socialite::driver('microsoft')->user();
    
        # TODO: generalize this
        $user = User::firstWhere("email", $ms_user->getEmail());
        if(!$user) {
            $user = User::create([
                'email' => $ms_user->getEmail(),
                'name' => $ms_user->getName(),
                'password' => base64_encode(random_bytes(8)),
            ]);
        }
    
        $user->microsoft_id = $ms_user->getId();
        $user->save();
          
        Auth::login($user);
    
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
