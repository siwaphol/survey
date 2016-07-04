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
        2=>'ก.1',
        3=>'ก.2',
        4=>'ก.3',
        5=>'ข.1',
        6=>'ข.2',
        7=>'ข.3',
        8=>'ข.4',
        9=>'ข.5',
        10=>'ข.6',
        11=>'ข.7',
        12=>'ข.8',
        13=>'ค.1',
        14=>'ค.2',
        15=>'ค.3',
        16=>'ง.1',
        17=>'ง.2',
        18=>'ง.3',
        19=>'ง.4',
        20=>'จ.1',
        21=>'จ.2',
        22=>'จ.3',
        );

    static $subSections = array(
        0=>'ไฟฟ้า',
        1=>'น้ำมันสำเร็จรูป',
        2=>'พลังงานหมุนเวียนดั้งเดิม'
    );
}
