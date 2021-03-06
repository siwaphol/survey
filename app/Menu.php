<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    const TYPE_LINK = 'link';
    const TYPE_TOGGLE = 'toggle';
    const TYPE_HEADING = 'heading';

    protected $casts = ['tool_report'=>'array'];

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

    public function submenu()
    {
        return $this->hasMany('App\Menu','parent_id','id');
    }
}
