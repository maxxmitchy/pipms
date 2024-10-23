<?php

namespace App\Livewire\Organizations;

use App\Models\Organization;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public $name;

    public $address;

    public $phone;

    public $email;

    protected $rules = [
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|unique:organizations,email',
    ];

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.organizations.create');
    }

    public function save()
    {
        $this->validate();

        Organization::create([
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
        ]);

        session()->flash('message', 'Organization created successfully.');

        return redirect()->route('organizations.index');
    }
}
