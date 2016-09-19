<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $electricPowerGroupId = DB::table('setting_groups')->where('name_en', \App\Parameter::ELECTRIC_POWER)->first()->id;
        DB::table('settings')->insert([
            'name_en'=>' ',
            'name_th'=>'หลอดไฟ (ในบ้าน) หลอดไส้',
            'code'=>'A1',
            'value'=>0.06,
            'unit_of_measure'=>'กิโลวัตต์',
            'group_id'=>$electricPowerGroupId,
        ]);
    }
}
