<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            ['id' => 1, 'name' => 'ІСвЕ', 'faculty_id' => 1],
            ['id' => 2, 'name' => 'ІМ', 'faculty_id' => 1],
            ['id' => 3, 'name' => 'КБ', 'faculty_id' => 1],
            ['id' => 4, 'name' => 'КММ', 'faculty_id' => 2],
            ['id' => 5, 'name' => 'ДУ', 'faculty_id' => 3],
            ['id' => 6, 'name' => 'ОіА', 'faculty_id' => 3],
        ]);
    }
}
