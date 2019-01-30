<?php

use Illuminate\Database\Seeder;

class OAuthClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            'id' => 1,
            'user_id' => null,
            'name' => 'Blockchain voting server',
            'secret' => 'dyCP5s1wW6QGu6z7kg9nRYxNA9tyOcysO01A2dGc',
            'redirect' => 'http://localhost:3000',
            'personal_access_client' => false,
            'password_client' => true,
            'revoked' => false
        ]);
    }
}
