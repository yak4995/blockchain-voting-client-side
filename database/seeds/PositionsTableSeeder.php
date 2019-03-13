<?php

use Illuminate\Database\Seeder;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->insert([
            ['id' => 1, 'name' => 'Асистент'],
            ['id' => 2, 'name' => 'Викладач'],
            ['id' => 3, 'name' => 'Доцент'],
            ['id' => 4, 'name' => 'Професор'],
            ['id' => 5, 'name' => 'Зав. кафедри'],
            ['id' => 6, 'name' => 'Декан'],
        ]);
    }
}
