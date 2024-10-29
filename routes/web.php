<?php

use App\Livewire\Inventory\Create as InventoryCreate;
use App\Livewire\Inventory\Edit as InventoryEdit;
use App\Livewire\Inventory\Index as InventoryIndex;
use App\Livewire\Landing;
use App\Livewire\Medications\Create as MedicationsCreate;
use App\Livewire\Medications\Edit as MedicationsEdit;
use App\Livewire\Medications\Expiring;
use App\Livewire\Medications\Index as MedicationsIndex;
use App\Livewire\Organizations\Create as OrganizationsCreate;
use App\Livewire\Organizations\Edit as OrganizationsEdit;
use App\Livewire\Organizations\Index as OrganizationsIndex;
use App\Livewire\Prescriptions\Create as PrescriptionsCreate;
use App\Livewire\Prescriptions\Edit as PrescriptionsEdit;
use App\Livewire\Prescriptions\Index as PrescriptionsIndex;
use App\Livewire\Transfers\TransferList;
use App\Livewire\UserManagement;
use Illuminate\Support\Facades\Route;

Route::get('/', Landing::class)->name('landing');

Route::middleware(['auth'])->group(function () {
    Route::get('/medications', MedicationsIndex::class)->name('medications.index');
    Route::get('/medications/create', MedicationsCreate::class)->name('medications.create');
    Route::get('/medications/{medication}/edit', MedicationsEdit::class)->name('medications.edit');
    Route::get('/medications/expiring', Expiring::class)->name('medications.expiring');

    Route::get('/prescriptions', PrescriptionsIndex::class)->name('prescriptions.index');
    Route::get('/prescriptions/create', PrescriptionsCreate::class)->name('prescriptions.create');
    Route::get('/prescriptions/{prescription}/edit', PrescriptionsEdit::class)->name('prescriptions.edit');

    Route::get('/organizations/{organization}/inventory', InventoryIndex::class)->name('inventory.index');
    Route::get('/inventory/create', InventoryCreate::class)->name('inventory.create');
    Route::get('/inventory/{inventory}/edit', InventoryEdit::class)->name('inventory.edit');

    Route::get('/organizations', OrganizationsIndex::class)->name('organizations.index');
    Route::get('/organizations/create', OrganizationsCreate::class)->name('organizations.create');
    Route::get('/organizations/{organization}/edit', OrganizationsEdit::class)->name('organizations.edit');

    Route::get('/transfers', TransferList::class)->name('transfers.index');
});

Route::get('/user-management', UserManagement::class)->middleware(['auth'])->name('usermgt');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
