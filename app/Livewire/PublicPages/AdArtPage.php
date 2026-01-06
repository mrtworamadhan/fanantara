<?php

namespace App\Livewire\PublicPages;

use App\Models\SiteSetting;
use Livewire\Component;

class AdArtPage extends Component
{
    public function render()
    {
        $adartFile = SiteSetting::get('adart_file', 'documents/adart.pdf');
        
        return view('livewire.public-pages.adart-page', [
            'adartFile' => $adartFile
        ])
            ->layout('components.layouts.public', ['title' => 'AD/ART']);
    }
}
