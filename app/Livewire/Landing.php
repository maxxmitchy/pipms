<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Landing extends Component
{
    #[Layout('layouts.guest')]
    public function render()
    {

        $user = User::find(1);
        $user->assignRole('super-admin');
        $user->assignRole('pharmacist');

        return view('livewire.landing');
    }
}
