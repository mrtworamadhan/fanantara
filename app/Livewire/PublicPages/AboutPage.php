<?php

namespace App\Livewire\PublicPages;

use Livewire\Component;

class AboutPage extends Component
{
    public function render()
    {
        return view('livewire.public-pages.about-page')
            ->layout('components.layouts.public', ['title' => 'Tentang Kami']);
    }
}
