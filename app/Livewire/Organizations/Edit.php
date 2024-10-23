<?php

namespace App\Livewire\Organizations;

use App\Models\Organization;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Edit extends Component
{
    public $organizationId;

    public $name;

    public $address;

    public $phone;

    public $email;

    protected $rules = [
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email',
    ];

    public function mount(Organization $organization)
    {
        $this->organizationId = $organization->id;
        $this->name = $organization->name;
        $this->address = $organization->address;
        $this->phone = $organization->phone;
        $this->email = $organization->email;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.organizations.edit');
    }

    public function save()
    {
        $this->validate();

        $organization = Organization::findOrFail($this->organizationId);
        $organization->name = $this->name;
        $organization->address = $this->address;
        $organization->phone = $this->phone;
        $organization->email = $this->email;
        $organization->save();

        session()->flash('message', 'Organization updated successfully.');

        return redirect()->route('organizations.index');
    }
}
