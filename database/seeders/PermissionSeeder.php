<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $allRoles = Role::all()->keyBy('id');

        $permissions = [
            'all-access' => [Role::ROLE_ADMIN],
            'product-manage' => [Role::ROLE_VENDOR],
            'order-manage' => [Role::ROLE_CUSTOMER],

            
        ];

        foreach ($permissions as $key => $roles) {
            $permission = Permission::create(['name' => $key]);
            foreach ($roles as $role) {
                $allRoles[$role]->permissions()->attach($permission->id);
            }
        }
    }
}
