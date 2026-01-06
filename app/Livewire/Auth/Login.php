<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    #[Layout('components.layouts.app')] 

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            $user = Auth::user();

            if ($user->member->status !== 'active') {
                // Contoh notif gagal karena status belum aktif
                $this->dispatch('notify', [
                    'type' => 'warning',
                    'title' => 'Akun Belum Aktif',
                    'message' => 'Silahkan lakukan aktivasi terlebih dahulu.'
                ]);
                Auth::logout();
                return;
            }

            return redirect()->intended('/dashboard');
        }

        // Jika Gagal Login
        $this->dispatch('notify', [
            'type' => 'error',
            'title' => 'Login Gagal',
            'message' => 'Email atau password yang kamu masukkan salah.'
        ]);
    }

    public function render()
    {
        return view('livewire.auth.login'); 
    }
}