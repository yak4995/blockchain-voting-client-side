<?php

use Illuminate\Database\Seeder;

class FacultiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faculties')->insert([
            ['id' => 1, 'name' => 'ІІТвЕ'],
            ['id' => 2, 'name' => 'МЕіМ'],
            ['id' => 3, 'name' => 'ФЕФ'],
        ]);
    }
}
