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
        //
        $data = [
            [
                'name'=>'Admin',
                'guard_name'=>'web',
            ],
            [
                'name'=>'User',
                'guard_name'=>'web',
            ],
        ];

        foreach($data as $item)
        {
            Role::firstOrCreate($item);
        }
    }
}
