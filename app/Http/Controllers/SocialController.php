<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function loginWithFacebook()
    {
        $user = Socialite::driver('facebook')->user();
        $isUser = User::where('email', $user->email)->first();

        if ($isUser == null) {
            return $this->register($user);
        }

        if ($isUser->fb_id == null) {
            $isUser->update(['fb_id' => $user->id]);
        }

        $loginUser = User::where('fb_id', $user->id)->first();
        Auth::login($loginUser);

        return $this->redirect();
    }

    private function register($user)
    {
        $password = $user->id . $user->email;
        $createUser = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'fb_id' => $user->id,
            'password' => Hash::make($password),
        ]);
        Auth::login($createUser);

        return $this->redirect();
    }

    private function redirect()
    {
        return redirect()
            ->route('dashboard');
    }
}
