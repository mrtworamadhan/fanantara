<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;

class ForgotPassword extends Component
{
    public $email;

    #[Layout('components.layouts.auth')]
    public function sendResetLink()
    {
        $this->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', 'Link reset password telah dikirim ke email Anda.');
            $this->reset('email');
        } else {
            $this->addError('email', 'Email tidak ditemukan.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
