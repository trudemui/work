<?php

use Illuminate\Database\Seeder;
 
class UserSeeders extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'producer' => 'テスト太郎',
            'login_id' => '1234',
            'password' => bcrypt('password1234'),
            'remember_token' => null,
        ]);
    }
}