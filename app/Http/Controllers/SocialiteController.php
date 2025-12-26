<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(24)),
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user);

            if (! $user->member) {
                return redirect()->route('member.setup');
            }

            if ($user->member->status !== 'active') {
                return redirect()->route('member.activation');
            }

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Login Google Gagal: ' . $e->getMessage());
        }
    }
}