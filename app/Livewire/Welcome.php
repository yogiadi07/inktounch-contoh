<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class Welcome extends Component
{
    public function render()
    {
        return view('livewire.welcome');
    }
}
