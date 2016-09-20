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
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หลอดไฟ (ในบ้าน) หลอดไส้',
            'code'=>'A1', 'value'=>0.06, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หลอดไฟ (ในบ้าน) หลอดฟลูออเรสเซนต์ ชนิดกลม',
            'code'=>'A2', 'value'=>0.024, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หลอดไฟ (ในบ้าน) หลอดฟลูออเรสเซนต์ ชนิดตรง ขนาดยาว',
            'code'=>'A3', 'value'=>0.036, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หลอดไฟ (ในบ้าน) หลอดฟลูออเรสเซนต์ ชนิดตรง ขนาดสั้น',
            'code'=>'A4', 'value'=>0.018, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หลอดไฟ (ในบ้าน) หลอดคอมแพคฟลูออเรสเซนต์',
            'code'=>'A5', 'value'=>0.018, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หลอดไฟ (ในบ้าน) หลอดแอลอีดี',
            'code'=>'A6', 'value'=>0.010, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        // หลอดไฟนอกบ้าน
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หลอดไฟ (นอกบ้าน) หลอดไส้',
            'code'=>'A7', 'value'=>0.06, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หลอดไฟ (นอกบ้าน) หลอดฟลูออเรสเซนต์ ชนิดกลม',
            'code'=>'A8', 'value'=>0.024, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หลอดไฟ (นอกบ้าน) หลอดฟลูออเรสเซนต์ ชนิดตรง ขนาดยาว',
            'code'=>'A9', 'value'=>0.036, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หลอดไฟ (นอกบ้าน) หลอดฟลูออเรสเซนต์ ชนิดตรง ขนาดสั้น',
            'code'=>'A10', 'value'=>0.018, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หลอดไฟ (นอกบ้าน) หลอดคอมแพคฟลูออเรสเซนต์',
            'code'=>'A11', 'value'=>0.018, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หลอดไฟ (นอกบ้าน) หลอดแอลอีดี',
            'code'=>'A12', 'value'=>0.010, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        //หมวดประกอบอาหาร
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หม้อหุงข้าวไฟฟ้า แบบธรรมดา 1 ลิตร',
            'code'=>'A13', 'value'=>0.400, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หม้อหุงข้าวไฟฟ้า แบบธรรมดา 1 ลิตร',
            'code'=>'A14', 'value'=>0.400, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
        DB::table('settings')->insert(['name_en'=>' ', 'name_th'=>'หม้อหุงข้าวไฟฟ้า แบบธรรมดา 1 ลิตร',
            'code'=>'A15', 'value'=>0.400, 'unit_of_measure'=>'กิโลวัตต์', 'group_id'=>$electricPowerGroupId,
        ]);
    }
}
