<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create permissions if they don't exist
        Permission::firstOrCreate(['name' => 'archive empresas', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'unarchive empresas', 'guard_name' => 'web']);

        // Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'Administrator', 'guard_name' => 'web']);
        $admin->givePermissionTo(['archive empresas', 'unarchive empresas']);

        // Updated role name
        Role::firstOrCreate(['name' => 'Docente']); // Previously "User"

        // New role
        Role::firstOrCreate(['name' => 'Estudiante']);
    }
}
