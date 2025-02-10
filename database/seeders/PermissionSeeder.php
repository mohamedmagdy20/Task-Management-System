<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'name'=>'view-any Role',
                'guard_name'=>'web'
            ],
            [
                'name'=>'view Role',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create Role',
                'guard_name'=>'web'
            ],
            [
                'name'=>'update Role',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete Role',
                'guard_name'=>'web'
            ],
            [
                'name'=>'restore Role',
                'guard_name'=>'web'
            ],
            [
                'name'=>'force-delete Role',
                'guard_name'=>'web'
            ],
            [
                'name'=>'replicate Role',
                'guard_name'=>'web'
            ],
            [
                'name'=>'reorder Role',
                'guard_name'=>'web'
            ],


            [
                'name'=>'view-any Permission',
                'guard_name'=>'web'
            ],
            [
                'name'=>'view Permission',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create Permission',
                'guard_name'=>'web'
            ],
            [
                'name'=>'update Permission',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete Permission',
                'guard_name'=>'web'
            ],
            [
                'name'=>'restore Permission',
                'guard_name'=>'web'
            ],
            [
                'name'=>'force-delete Permission',
                'guard_name'=>'web'
            ],
            [
                'name'=>'replicate Permission',
                'guard_name'=>'web'
            ],
            [
                'name'=>'reorder Permission',
                'guard_name'=>'web'
            ],
            [
                'name'=>'view-any User',
                'guard_name'=>'web'
            ],
            [
                'name'=>'view User',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create User',
                'guard_name'=>'web'
            ],
            [
                'name'=>'update User',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete User',
                'guard_name'=>'web'
            ],
            [
                'name'=>'restore User',
                'guard_name'=>'web'
            ],
            [
                'name'=>'force-delete User',
                'guard_name'=>'web'
            ],
            [
                'name'=>'replicate User',
                'guard_name'=>'web'
            ],
            [
                'name'=>'reorder User',
                'guard_name'=>'web'
            ],

            [
                'name'=>'view-any Task',
                'guard_name'=>'web'
            ],
            [
                'name'=>'view Task',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create Task',
                'guard_name'=>'web'
            ],
            [
                'name'=>'update Task',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete Task',
                'guard_name'=>'web'
            ],
            [
                'name'=>'restore Task',
                'guard_name'=>'web'
            ],
            [
                'name'=>'force-delete Task',
                'guard_name'=>'web'
            ],
        ];

        foreach($data as $record)
        {
            Permission::firstOrCreate($record);
        }


        // Attach All Permission to Admin 
        $allPermission = Permission::all();
        $adminRole = Role::firstOrCreate([
            'name'=>'Admin',
            'guard_name'=>'web'
        ]);
        $adminRole->syncPermissions($allPermission);
    }
}
