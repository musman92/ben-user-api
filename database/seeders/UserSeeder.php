<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $u = new User();
        $u->name = 'admin';
        $u->username = 'admin';
        $u->email = 'admin@ben.io';
        $u->password = Hash::make('123456');
        $u->registered_at = date('Y-m-d H:i:s');
        $u->role = 'admin';
        $u->verified = 1;
        $u->save();
    }
}
