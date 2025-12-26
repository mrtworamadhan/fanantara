<?php

namespace App\Livewire\PublicPages;

use Livewire\Component;

class ContactPage extends Component
{
    public function render()
    {
        return view('livewire.public-pages.contact-page')
            ->layout('components.layouts.public', ['title' => 'Hubungi Kami']);
    }
}
