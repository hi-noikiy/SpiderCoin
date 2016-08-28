<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('users')->delete();

        \DB::table('users')->insert([
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
                    'name'           => 'User',
                    'email'          => 'user@dingtou.com',
                    'password'       => bcrypt('123456'),
                    'remember_token' => bcrypt('123456'),
                    'created_at'     => '2016-06-14 17:18:09',
                    'updated_at'     => '2016-06-27 09:07:09',
                ],
        ]);


    }
}
