<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'staff'];

        $permissions = [
            'view-client', 'create-client', 'update-client', 'delete-client', 'import-client',
            'view-invoice', 'create-invoice', 'update-invoice', 'delete-invoice', 'import-invoice',
            'view-state-of-account', 'export-state-of-account'
        ];

        foreach($roles as $role){
            Role::firstOrCreate(['name' => $role]);
        };

        foreach($permissions as $permission){
            Permission::firstOrCreate(['name' => $permission]);
        };

        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->permissions()->sync(Permission::pluck('id'));

        $staffPerm = Permission::whereIn('name', [
            'view-client', 'create-client', 'update-client',
            'view-invoice', 'create-invoice', 'update-invoice',
            'view-state-of-account', 'export-state-of-account'
        ])->pluck('id');

        $staffRole = Role::where('name', 'staff')->first();
        $staffRole->permissions()->sync($staffPerm);

        // Assign admin role to 1st user
        $user = User::first();
        if($user){
            $user->roles()->sync([$adminRole->id]);
        }

    }
}
