<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
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
        try {

            $user = Socialite::driver('facebook')->user();
            $isUser = User::where('email', $user->email)->first();

            if ($isUser->fb_id !== $user->id) {
                $isUser->update(['fb_id' => $user->id]);
            }
            $verifyUser = User::where('fb_id', $user->id)->first();
            if ($verifyUser) {
                Auth::login($verifyUser);

                return redirect()
                    ->route('dashboard');
            } else {
                return $this->register($user);
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
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

        return redirect()
            ->route('dashboard');
    }
}
