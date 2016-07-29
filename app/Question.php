<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_TITLE = 'title';
    const TYPE_NUMBER = 'number';
    const TYPE_TEXT = 'text';

    static $sections = array(
        0=>'',
        1=>'ทั่วไป',
        2=>'ก.1: สภาพภูมิศาสตร์ของครัวเรือน',
        3=>'ก.2: ข้อมูลพื้นฐานของครัวเรือน',
        4=>'ก.3: รายได้และรายจ่ายของครัวเรือน',
        5=>'ข.1 หมวดแสงสว่าง',
        6=>'ข.2 หมวดประกอบอาหาร',
        7=>'ข.3 หมวดข่าวสารบันเทิง',
        8=>'ข.4 หมวดความสะดวกสบาย',
        9=>'ข.5 หมวดเพื่อความอบอุ่น',
        10=>'ข.6 หมวดไล่และล่อแมลง',
        11=>'ข.7 หมวดการเดินทางและคมนาคม',
        12=>'ข.8 หมวดเกษตรกรรม',
        13=>'ค.1 หมวดแหล่งเชื้อเพลิงที่หาเองได้',
        14=>'ค.2 หมวดแหล่งเชื้อเพลิงที่ซื้อ',
        15=>'ค.3',
        16=>'ง.1 การเผาถ่าน',
        17=>'ง.2 การผลิตก๊าซชีวภาพ',
        18=>'ง.3 เครื่องปั่นไฟ',
        19=>'ง.4 การผลิตไฟฟ้าด้วยเซลล์แสงอาทิตย์',
        20=>'จ.1 แนวโน้มการเปลี่ยนการใช้พลังงานในการประกอบอาหาร',
        21=>'จ.2 แนวโน้มการเปลี่ยนการใช้พลังงานในการเดินทางและคมนาคม',
        22=>'จ.3 แนวโน้มการเปลี่ยนการใช้ยานพาหนะในการเดินทาง',
        );

    static $subSections = array(
        0=>'ไฟฟ้า',
        1=>'น้ำมันสำเร็จรูป',
        2=>'พลังงานหมุนเวียนดั้งเดิม'
    );

    public static function updateSectionId()
    {
        $menus = Menu::all();

        foreach ($menus as $menu){
            if (is_null($menu->parent_id)){
                \DB::table('questions')->where('section', $menu->name)
                    ->where('sub_section', 'NULL')
                    ->update(['section_id'=>$menu->id]);
                \DB::table('answers')->where('section', $menu->name)
                    ->where('sub_section', 'NULL')
                    ->update(['section_id'=>$menu->id]);
                continue;
            }

            $pMenu = $menus->where('id', $menu->parent_id)->first();
            \DB::table('questions')->where('section', $pMenu->name)
                ->where('sub_section', $menu->name)
                ->update(['section_id'=>$pMenu->id, 'sub_section_id'=>$menu->id]);
            \DB::table('answers')->where('section', $pMenu->name)
                ->where('sub_section', $menu->name)
                ->update(['section_id'=>$pMenu->id, 'sub_section_id'=>$menu->id]);
        }

        echo 'success';
    }
}
