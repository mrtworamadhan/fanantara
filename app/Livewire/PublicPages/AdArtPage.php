<?php

namespace App\Livewire\PublicPages;

use Livewire\Component;

class AdArtPage extends Component
{
    public function render()
    {
        return view('livewire.public-pages.adart-page')
            ->layout('components.layouts.public', ['title' => 'AD/ART']);
    }
}
