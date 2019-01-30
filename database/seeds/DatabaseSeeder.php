<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            FacultiesTableSeeder::class,
            DepartmentsTableSeeder::class,
            PositionsTableSeeder::class,
            UsersTableSeeder::class,
            OAuthClientsTableSeeder::class,
        ]);
    }
}
