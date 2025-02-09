<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'name'=>'admin',
                'email'=>'admin@admin.com',
                'password'=>Hash::make(123456)
            ],
            [
                'name'=>'user',
                'email'=>'user@user.com',
                'password'=>Hash::make(123456)
            ],
        ];

        foreach($data as $item)
        {
            User::firstOrCreate($item);
        }
    }
}
