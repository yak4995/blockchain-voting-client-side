<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Кривохижа Ю.',
                'email' => 'yak4995@gmail.com',
                'password' => bcrypt('yuro1995'),
                'is_admin' => true
            ],
            [
                'id' => 2,
                'name' => 'Бережний А.',
                'email' => 'berejant@ukr.net',
                'password' => bcrypt('yuro1995'),
                'is_admin' => false
            ],
        ]);
    }
}
