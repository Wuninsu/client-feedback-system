<?php

namespace App\Livewire\Guest;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class Home extends Component
{
    public function render()
    {
        return view('livewire.guest.home');
    }
}
