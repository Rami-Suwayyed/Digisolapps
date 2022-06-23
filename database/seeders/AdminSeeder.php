<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'username' => "admin",
            'email' => 'admin@admin.com',
            'full_name' => "admin admin",
            'password' => Hash::make('admin'),
            'is_super_admin' => 1,
        ]);
    }
}
