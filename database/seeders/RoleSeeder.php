<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'ADMIN',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'ADMIN_GUDANG',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'STAFF_GUDANG',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'MANAGER',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'KEUANGAN',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'PURCHASING',
            'guard_name' => 'web'
        ]);
    }
}
