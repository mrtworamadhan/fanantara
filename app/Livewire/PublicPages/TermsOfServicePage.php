<?php

namespace App\Livewire\PublicPages;

use Livewire\Component;

class TermsOfServicePage extends Component
{
    public function render()
    {
        return view('livewire.public-pages.terms-of-service-page')
            ->layout('components.layouts.public', ['title' => 'Terms of Service']);
    }
}
