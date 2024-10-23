<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedUser;

    public $selectedRoles = [];

    public $selectedPermissions = [];

    public $showModal = false;

    protected $queryString = ['search' => ['except' => '']];

    public function mount()
    {
        if (! auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action.');
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $users = User::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->paginate(10);

        $roles = Role::all();
        $permissions = Permission::all();

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editUser($userId)
    {
        $this->selectedUser = User::findOrFail($userId);
        $this->selectedRoles = $this->selectedUser->roles->pluck('id')->toArray();
        $this->selectedPermissions = $this->selectedUser->permissions->pluck('id')->toArray();
        $this->showModal = true;
    }

    public function updateUserRolesAndPermissions()
    {
        $this->validate([
            'selectedRoles' => 'array',
            'selectedPermissions' => 'array',
        ]);

        $this->selectedUser->syncRoles($this->selectedRoles);
        $this->selectedUser->syncPermissions($this->selectedPermissions);

        $this->showModal = false;
        $this->reset(['selectedUser', 'selectedRoles', 'selectedPermissions']);
        session()->flash('message', 'User roles and permissions updated successfully.');
    }
}
