<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(array('name' => 'admin', 'email' => 'admin@admin.com', 'password' => app('hash')->make('admin12345'), 'is_super_admin' => 1));
    }
}
