<?php

use Illuminate\Database\Seeder;

class SettingGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting_group')->insert([
            'name_en'=> \App\Parameter::ELECTRIC_POWER,
            'name_th'=> 'กำลังไฟฟ้าโดยเฉลี่ย'
        ]);
        DB::table('setting_group')->insert([
            'name_en'=> \App\Parameter::TIME_UNIT,
            'name_th'=> 'หน่วยเวลา'
        ]);
        DB::table('setting_group')->insert([
            'name_en'=> \App\Parameter::POPULATION,
            'name_th'=> 'จำนวนครัวเรือน'
        ]);
        DB::table('setting_group')->insert([
            'name_en'=> \App\Parameter::OIL_PRICE,
            'name_th'=> 'ราคาน้ำมัน'
        ]);
        DB::table('setting_group')->insert([
            'name_en'=> \App\Parameter::KTOE_UNIT,
            'name_th'=> 'แปลงหน่วย ktoe'
        ]);
    }
}
