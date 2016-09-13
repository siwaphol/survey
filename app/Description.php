<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    protected $fillable = ['title', 'unique_key'];
    public $timestamps = false;

    public static $table3Eletric = [
        //พัดลม
        'พัดลม ตั้งโต๊ะ 14 นิ้ว',
        'พัดลม ตั้งโต๊ะ 16 นิ้ว',
        'พัดลม ตั้งโต๊ะ 18 นิ้ว',
        'พัดลม ตั้งพื้น 14 นิ้ว',
        'พัดลม ตั้งพื้น 16 นิ้ว',
        'พัดลม ตั้งพื้น 18 นิ้ว',
        'พัดลม ติดผนัง 14 นิ้ว',
        'พัดลม ติดผนัง 16 นิ้ว',
        'พัดลม ติดผนัง 18 นิ้ว',
        'พัดลม ติดเพดาน 16 นิ้ว',
        'พัดลม ติดเพดาน 56 นิ้ว',
        'พัดลม อุตสาหกรรม 18 นิ้ว',
        'พัดลม อุตสาหกรรม 22 นิ้ว',
        'พัดลม อุตสาหกรรม 25 นิ้ว',
        'พัดลม ทาวเวอร์',
        'พัดลม ไอเย็น',
        // พัดลมดูดอากาศ
    'พัดลมดูดอากาศ ติดผนัง 8 นิ้ว',
    'พัดลมดูดอากาศ ติดผนัง 10 นิ้ว',
    'พัดลมดูดอากาศ ติดผนัง 12 นิ้ว',
        // เครื่องฟอกอากาศ
    'เครื่องฟอกอากาศ',
        // เครื่องทำน้ำอุ่น
    'เครื่องทำน้ำอุ่น',
        // เครื่องดูดฝุ่น
    'เครื่องดูดฝุ่น แบบทั่วไป',
    'เครื่องดูดฝุ่น หุ่นยนต์',
    'เครื่องดูดฝุ่น แบบต้นฉบับ',
        // เตารีดไฟฟ้า
    'เตารีดไฟฟ้า แบบแห้ง',
    'เตารีดไฟฟ้า แบบไอน้ำ',
    'เตารีดไฟฟ้า แบบหม้อต้มไอน้ำ',
    'เตารีดไฟฟ้า แบบกดทับ',
    'เตารีดไฟฟ้า เครื่องรีดผ้าไอน้ำ',
        // ตู้เย็น
    'ตู้เย็น น้อยกว่า 100 ลิตร',
    'ตู้เย็น มากกว่าหรือเท่ากับ 100 ลิตร',
    'ตู้เย็น น้อยกว่า 450 ลิตร',
    'ตู้เย็น มากกว่าหรือเท่ากับ 450 ลิตร',
        // เครื่องปรับอากาศ หรือแอร์
    'เครื่องปรับอากาศ ติดผนัง 9000 บีทียู',
    'เครื่องปรับอากาศ ติดผนัง 12000 บีทียู',
    'เครื่องปรับอากาศ ติดผนัง 18000 บีทียู',
    'เครื่องปรับอากาศ ติดผนัง 21000 บีทียู',
    'เครื่องปรับอากาศ ติดผนัง 24000 บีทียู',
    'เครื่องปรับอากาศ ติดผนัง 26000 บีทียู',
    'เครื่องปรับอากาศ ตั้ง/แขวน 18000 บีทียู',
    'เครื่องปรับอากาศ ตั้ง/แขวน 21000 บีทียู',
    'เครื่องปรับอากาศ ตั้ง/แขวน 24000 บีทียู',
    'เครื่องปรับอากาศ ตั้ง/แขวน 26000 บีทียู',
    'เครื่องปรับอากาศ ตั้ง/แขวน 36000 บีทียู',
    'เครื่องปรับอากาศ ตั้ง/แขวน 40000 บีทียู',
    'เครื่องปรับอากาศ ตั้ง/แขวน 48000 บีทียู',
        // เครื่องซักผ้าและอบผ้า
    'เครื่องซักผ้าและอบผ้า ฝาหน้า 5.0-6.9 กก.',
    'เครื่องซักผ้าและอบผ้า ฝาหน้า 7.0-8.9 กก.',
    'เครื่องซักผ้าและอบผ้า ฝาหน้า 9.0-10.9 กก.',
    'เครื่องซักผ้าและอบผ้า ฝาหน้า 11.0 กก. ขึ้นไป',
    'เครื่องซักผ้าและอบผ้า ฝาบน ถังเดี่ยว 5.0-6.9 กก.',
    'เครื่องซักผ้าและอบผ้า ฝาบน ถังเดี่ยว 7.0-8.9 กก.',
    'เครื่องซักผ้าและอบผ้า ฝาบน ถังเดี่ยว 9.0-10.9 กก.',
    'เครื่องซักผ้าและอบผ้า ฝาบน ถังเดี่ยว 11.0 กก. ขึ้นไป',
    'เครื่องซักผ้าและอบผ้า ฝาบน 2 ถัง 5.0-6.9 กก.',
    'เครื่องซักผ้าและอบผ้า ฝาบน 2 ถัง 7.0-8.9 กก.',
    'เครื่องซักผ้าและอบผ้า ฝาบน 2 ถัง 9.0-10.9 กก.',
    'เครื่องซักผ้าและอบผ้า ฝาบน 2 ถัง 11.0 กก. ขึ้นไป',

    [],
    [],

    'เครื่องฉีดน้ำแรงดันสูง',
    'ปั้มอัตโนมัติ',
    'เครื่องทำน้ำอุ่นแก๊ส',
    ];
}