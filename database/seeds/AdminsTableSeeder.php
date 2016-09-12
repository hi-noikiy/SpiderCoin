<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('admins')->delete();

        \DB::table('admins')->insert([
            0 =>
                [
                    'id'             => 1,
                    'name'           => 'Admin',
                    'email'          => 'admin@dingtou.com',
                    'password'       => bcrypt('123456'),
                    'remember_token' => bcrypt('123456'),
                    'created_at'     => '2016-05-19 09:52:51',
                    'updated_at'     => '2016-06-27 09:06:34',
                ],
            1 =>
                [
                    'id'             => 2,
                    'name'           => 'User2',
                    'email'          => 'user2@dingtou.com',
                    'password'       => bcrypt('123456'),
                    'remember_token' => bcrypt('123456'),
                    'created_at'     => '2016-06-14 17:18:09',
                    'updated_at'     => '2016-06-27 09:07:09',
                ],
        ]);

    }
}
