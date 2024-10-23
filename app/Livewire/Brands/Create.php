<?php

namespace App\Livewire\Brands;

use App\Models\Brand;
use Livewire\Component;

class Create extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|min:2|max:255|unique:brands,name',
    ];

    public function save()
    {
        $this->validate();

        $brand = Brand::create([
            'name' => $this->name,
        ]);

        $this->reset('name');
        $this->dispatch('brandCreated', ['brandId' => $brand->id])->to('medications.create');
    }

    public function render()
    {
        return view('livewire.brands.create');
    }
}
