<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'manage inventory']);
        Permission::create(['name' => 'manage prescriptions']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage organizations']);

        // Create roles and assign permissions
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view dashboard',
            'manage inventory',
            'manage prescriptions',
            'manage users',
        ]);

        $pharmacist = Role::create(['name' => 'pharmacist']);
        $pharmacist->givePermissionTo([
            'view dashboard',
            'manage inventory',
            'manage prescriptions',
        ]);

        $staff = Role::create(['name' => 'ptech']);
        $staff->givePermissionTo([
            'view dashboard',
            'manage inventory',
        ]);
    }
}
