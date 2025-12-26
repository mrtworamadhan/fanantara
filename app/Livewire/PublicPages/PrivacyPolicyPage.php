<?php

namespace App\Livewire\PublicPages;

use Livewire\Component;

class PrivacyPolicyPage extends Component
{
    public function render()
    {
        return view('livewire.public-pages.privacy-policy-page')
            ->layout('components.layouts.public', ['title' => 'Privacy Policy']);
    }
}
