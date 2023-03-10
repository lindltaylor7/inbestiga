<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create([
            'name' => 'Admin'
        ]);

        $role2 = Role::create([
            'name' => 'Seller'
        ]);

        $role3 = Role::create([
            'name' => 'AdminAcad'
        ]);

        $role4 = Role::create([
            'name' => 'Acad'
        ]);
    }
}
