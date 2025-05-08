<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'System administrator with full access'
            ],
            [
                'name' => 'Landlord',
                'slug' => 'landlord',
                'description' => 'Property owner who manages their properties'
            ],
            [
                'name' => 'Tenant',
                'slug' => 'tenant',
                'description' => 'Property tenant who rents units'
            ],
            [
                'name' => 'Agent',
                'slug' => 'agent',
                'description' => 'Property agent who manages properties on behalf of landlords'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
