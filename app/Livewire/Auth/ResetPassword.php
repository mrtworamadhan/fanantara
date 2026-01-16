<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

class ResetPassword extends Component
{
    public $token, $email, $password, $password_confirmation;

    #[Layout('components.layouts.auth')]
    public function mount($token)
    {
        $this->token = $token;
        // Mengambil email dari query string (bawaan link Laravel)
        $this->email = request()->query('email');
    }

    public function resetPassword()
    {
        $this->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

                $user->notify(new \App\Notifications\MemberNotification([
                    'title'   => 'Password Berhasil Diubah',
                    'message' => 'Password akun Fanantara Anda telah berhasil diperbarui pada ' . now()->format('d M Y H:i') . '. Jika ini bukan Anda, segera hubungi admin.',
                    'type'    => 'success',
                    'url'     => route('member.profile'), 
                ]));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', 'Password berhasil diubah.');
            return redirect()->route('login');
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}