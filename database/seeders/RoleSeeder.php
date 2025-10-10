<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        Permission::create(['name' => 'archive empresas']);
        Permission::create(['name' => 'unarchive empresas']);

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'Administrator']);
        $admin->givePermissionTo(['archive empresas', 'unarchive empresas']);

        // Updated role name
        Role::firstOrCreate(['name' => 'Teacher']); // Previously "User"

        // New role
        Role::firstOrCreate(['name' => 'Student']);
    }
}