<?php

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->truncate();

        DB::table('menus')->insert(['name'=>'ข้อมูลผู้ถูกสัมภาษณ์', 'parent_id'=>null, 'order'=>1, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'ก.1: สภาพภูมิศาสตร์ของครัวเรือน', 'parent_id'=>null, 'order'=>2, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'ก.2: ข้อมูลพื้นฐานของครัวเรือน', 'parent_id'=>null, 'order'=>3, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'ก.3: รายได้และรายจ่ายของครัวเรือน', 'parent_id'=>null, 'order'=>4, 'type'=>\App\Menu::TYPE_LINK ]);

        DB::table('menus')->insert(['name'=>'ข.1 หมวดแสงสว่าง', 'parent_id'=>null, 'order'=>5, 'type'=>\App\Menu::TYPE_TOGGLE ]);
        $parentId = DB::table('menus')->orderBy('id','DESC')->select('id')->first()->id;
        DB::table('menus')->insert(['name'=>'ไฟฟ้า', 'parent_id'=>$parentId, 'order'=>1, 'type'=>\App\Menu::TYPE_LINK ]);

        DB::table('menus')->insert(['name'=>'ข.2 หมวดประกอบอาหาร', 'parent_id'=>null, 'order'=>6, 'type'=>\App\Menu::TYPE_TOGGLE ]);
        $parentId = DB::table('menus')->orderBy('id','DESC')->select('id')->first()->id;
        DB::table('menus')->insert(['name'=>'ไฟฟ้า', 'parent_id'=>$parentId, 'order'=>1, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'น้ำมันสำเร็จรูป', 'parent_id'=>$parentId, 'order'=>2, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'พลังงานหมุนเวียนดั้งเดิม', 'parent_id'=>$parentId, 'order'=>3, 'type'=>\App\Menu::TYPE_LINK ]);

        DB::table('menus')->insert(['name'=>'ข.3 หมวดข่าวสารบันเทิง', 'parent_id'=>null, 'order'=>7, 'type'=>\App\Menu::TYPE_TOGGLE ]);
        $parentId = DB::table('menus')->orderBy('id','DESC')->select('id')->first()->id;
        DB::table('menus')->insert(['name'=>'ไฟฟ้า', 'parent_id'=>$parentId, 'order'=>1, 'type'=>\App\Menu::TYPE_LINK ]);

        DB::table('menus')->insert(['name'=>'ข.4 หมวดความสะดวกสบาย', 'parent_id'=>null, 'order'=>8, 'type'=>\App\Menu::TYPE_TOGGLE ]);
        $parentId = DB::table('menus')->orderBy('id','DESC')->select('id')->first()->id;
        DB::table('menus')->insert(['name'=>'ไฟฟ้า', 'parent_id'=>$parentId, 'order'=>1, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'น้ำมันสำเร็จรูป', 'parent_id'=>$parentId, 'order'=>2, 'type'=>\App\Menu::TYPE_LINK ]);

        DB::table('menus')->insert(['name'=>'ข.5 หมวดเพื่อความอบอุ่น', 'parent_id'=>null, 'order'=>9, 'type'=>\App\Menu::TYPE_TOGGLE ]);
        $parentId = DB::table('menus')->orderBy('id','DESC')->select('id')->first()->id;
        DB::table('menus')->insert(['name'=>'พลังงานหมุนเวียนดั้งเดิม', 'parent_id'=>$parentId, 'order'=>1, 'type'=>\App\Menu::TYPE_LINK ]);

        DB::table('menus')->insert(['name'=>'ข.6 หมวดไล่และล่อแมลง', 'parent_id'=>null, 'order'=>10, 'type'=>\App\Menu::TYPE_TOGGLE ]);
        $parentId = DB::table('menus')->orderBy('id','DESC')->select('id')->first()->id;
        DB::table('menus')->insert(['name'=>'ไฟฟ้า', 'parent_id'=>$parentId, 'order'=>1, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'พลังงานหมุนเวียนดั้งเดิม', 'parent_id'=>$parentId, 'order'=>2, 'type'=>\App\Menu::TYPE_LINK ]);

        DB::table('menus')->insert(['name'=>'ข.7 หมวดการเดินทางและคมนาคม', 'parent_id'=>null, 'order'=>11, 'type'=>\App\Menu::TYPE_TOGGLE ]);
        $parentId = DB::table('menus')->orderBy('id','DESC')->select('id')->first()->id;
        DB::table('menus')->insert(['name'=>'น้ำมันสำเร็จรูป', 'parent_id'=>$parentId, 'order'=>1, 'type'=>\App\Menu::TYPE_LINK ]);

        DB::table('menus')->insert(['name'=>'ข.8 หมวดเกษตรกรรม', 'parent_id'=>null, 'order'=>12, 'type'=>\App\Menu::TYPE_TOGGLE ]);
        $parentId = DB::table('menus')->orderBy('id','DESC')->select('id')->first()->id;
        DB::table('menus')->insert(['name'=>'น้ำมันสำเร็จรูป', 'parent_id'=>$parentId, 'order'=>1, 'type'=>\App\Menu::TYPE_LINK ]);

        DB::table('menus')->insert(['name'=>'ค.1 หมวดแหล่งเชื้อเพลิงที่หาเองได้', 'parent_id'=>null, 'order'=>13, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'ค.2 หมวดแหล่งเชื้อเพลิงที่ซื้อ', 'parent_id'=>null, 'order'=>14, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'ง.1 การเผาถ่าน', 'parent_id'=>null, 'order'=>15, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'ง.2 การผลิตก๊าซชีวภาพ', 'parent_id'=>null, 'order'=>16, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'ง.3 เครื่องปั่นไฟ', 'parent_id'=>null, 'order'=>17, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'ง.4 การผลิตไฟฟ้าด้วยเซลล์แสงอาทิตย์', 'parent_id'=>null, 'order'=>18, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'จ.1 แนวโน้มการเปลี่ยนการใช้พลังงานในการประกอบอาหาร', 'parent_id'=>null, 'order'=>19, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'จ.2 แนวโน้มการเปลี่ยนการใช้พลังงานในการเดินทางและคมนาคม', 'parent_id'=>null, 'order'=>20, 'type'=>\App\Menu::TYPE_LINK ]);
        DB::table('menus')->insert(['name'=>'จ.3 แนวโน้มการเปลี่ยนการใช้ยานพาหนะในการเดินทาง', 'parent_id'=>null, 'order'=>21, 'type'=>\App\Menu::TYPE_LINK ]);
    }
}
