<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(MainTableSeeder::class);
    }
}

class MainTableSeeder extends Seeder {
    public function run()
    {
        DB::table('mains')->insert([
            'id'=>1,
            'recorder_id'=>1
        ]);
    }
}