<?php

namespace App\Http\Controllers\Summary9;

use App\Main;
use App\Setting;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary917 extends Controller
{
    public static function report917()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.7';
        $outputFile = 'sum917.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        // คำนวณใหม่เอาเฉพาะคันที่ 1
        $table1 = [
//            ['no_ch1033_o376_ch495_o300_ra498'=>213],
            ['no_ch1033_o376_ch495_o300_ra498'=>214],
            ['no_ch1033_o376_ch495_o300_ra498'=>215],
//            ['no_ch1033_o376_ch495_o300_ra498'=>216],
//            ['no_ch1033_o376_ch495_o300_ra498'=>217],

//            ['no_ch1033_o377_ch504_o300_ra507'=>213],
            ['no_ch1033_o377_ch504_o300_ra507'=>214],
            ['no_ch1033_o377_ch504_o300_ra507'=>215],
            ['no_ch1033_o377_ch504_o300_ra507'=>216],
            ['no_ch1033_o377_ch504_o300_ra507'=>217],
//            ['no_ch1033_o377_ch504_o300_ra507'=>218],
//            ['no_ch1033_o377_ch504_o300_ra507'=>220],
//            ['no_ch1033_o377_ch504_o300_ra507'=>219],

//            ['no_ch1033_o378_ch513_o300_ra516'=>213],
//            ['no_ch1033_o378_ch513_o300_ra516'=>214],
//            ['no_ch1033_o378_ch513_o300_ra516'=>215],
//            ['no_ch1033_o378_ch513_o300_ra516'=>216],
//            ['no_ch1033_o378_ch513_o300_ra516'=>217],
            ['no_ch1033_o378_ch513_o300_ra516'=>218],
//            ['no_ch1033_o378_ch513_o300_ra516'=>220],
//            ['no_ch1033_o378_ch513_o300_ra516'=>219]
        ];

        $table2RadioArr = [
//            ['no_ch1033_o376_ch495_o300_ra498'=>213, 'no_ch1033_o376_ch495_o301_ra498'=>213, 'no_ch1033_o376_ch495_o302_ra498'=>213, 'no_ch1033_o376_ch495_o303_ra498'=>213, 'no_ch1033_o376_ch495_o304_ra498'=>213],
            ['no_ch1033_o376_ch495_o300_ra498'=>214, 'no_ch1033_o376_ch495_o301_ra498'=>214, 'no_ch1033_o376_ch495_o302_ra498'=>214, 'no_ch1033_o376_ch495_o303_ra498'=>214, 'no_ch1033_o376_ch495_o304_ra498'=>214],
            ['no_ch1033_o376_ch495_o300_ra498'=>215, 'no_ch1033_o376_ch495_o301_ra498'=>215, 'no_ch1033_o376_ch495_o302_ra498'=>215, 'no_ch1033_o376_ch495_o303_ra498'=>215, 'no_ch1033_o376_ch495_o304_ra498'=>215],
//            ['no_ch1033_o376_ch495_o300_ra498'=>216, 'no_ch1033_o376_ch495_o301_ra498'=>216, 'no_ch1033_o376_ch495_o302_ra498'=>216, 'no_ch1033_o376_ch495_o303_ra498'=>216, 'no_ch1033_o376_ch495_o304_ra498'=>216],
//            ['no_ch1033_o376_ch495_o300_ra498'=>217, 'no_ch1033_o376_ch495_o301_ra498'=>217, 'no_ch1033_o376_ch495_o302_ra498'=>217, 'no_ch1033_o376_ch495_o303_ra498'=>217, 'no_ch1033_o376_ch495_o304_ra498'=>213],

//            ['no_ch1033_o377_ch504_o300_ra507'=>213, 'no_ch1033_o377_ch504_o301_ra507'=>213, 'no_ch1033_o377_ch504_o302_ra507'=>213, 'no_ch1033_o377_ch504_o303_ra507'=>213, 'no_ch1033_o377_ch504_o304_ra507'=>213],
            ['no_ch1033_o377_ch504_o300_ra507'=>214, 'no_ch1033_o377_ch504_o301_ra507'=>214, 'no_ch1033_o377_ch504_o302_ra507'=>214, 'no_ch1033_o377_ch504_o303_ra507'=>214, 'no_ch1033_o377_ch504_o304_ra507'=>214],
            ['no_ch1033_o377_ch504_o300_ra507'=>215, 'no_ch1033_o377_ch504_o301_ra507'=>215, 'no_ch1033_o377_ch504_o302_ra507'=>215, 'no_ch1033_o377_ch504_o303_ra507'=>215, 'no_ch1033_o377_ch504_o304_ra507'=>215],
            ['no_ch1033_o377_ch504_o300_ra507'=>216, 'no_ch1033_o377_ch504_o301_ra507'=>216, 'no_ch1033_o377_ch504_o302_ra507'=>216, 'no_ch1033_o377_ch504_o303_ra507'=>216, 'no_ch1033_o377_ch504_o304_ra507'=>216],
            ['no_ch1033_o377_ch504_o300_ra507'=>217, 'no_ch1033_o377_ch504_o301_ra507'=>217, 'no_ch1033_o377_ch504_o302_ra507'=>217, 'no_ch1033_o377_ch504_o303_ra507'=>217, 'no_ch1033_o377_ch504_o304_ra507'=>217],
//            ['no_ch1033_o377_ch504_o300_ra507'=>218, 'no_ch1033_o377_ch504_o301_ra507'=>218, 'no_ch1033_o377_ch504_o302_ra507'=>218, 'no_ch1033_o377_ch504_o303_ra507'=>218, 'no_ch1033_o377_ch504_o304_ra507'=>218],
//            ['no_ch1033_o377_ch504_o300_ra507'=>220, 'no_ch1033_o377_ch504_o301_ra507'=>220, 'no_ch1033_o377_ch504_o302_ra507'=>220, 'no_ch1033_o377_ch504_o303_ra507'=>220, 'no_ch1033_o377_ch504_o304_ra507'=>220],
//            ['no_ch1033_o377_ch504_o300_ra507'=>219, 'no_ch1033_o377_ch504_o301_ra507'=>219, 'no_ch1033_o377_ch504_o302_ra507'=>219, 'no_ch1033_o377_ch504_o303_ra507'=>219, 'no_ch1033_o377_ch504_o304_ra507'=>219],

//            ['no_ch1033_o378_ch513_o300_ra516'=>213, 'no_ch1033_o378_ch513_o301_ra516'=>213, 'no_ch1033_o378_ch513_o302_ra516'=>213, 'no_ch1033_o378_ch513_o303_ra516'=>213, 'no_ch1033_o378_ch513_o304_ra516'=>213],
//            ['no_ch1033_o378_ch513_o300_ra516'=>214, 'no_ch1033_o378_ch513_o301_ra516'=>214, 'no_ch1033_o378_ch513_o302_ra516'=>214, 'no_ch1033_o378_ch513_o303_ra516'=>214, 'no_ch1033_o378_ch513_o304_ra516'=>214],
//            ['no_ch1033_o378_ch513_o300_ra516'=>215, 'no_ch1033_o378_ch513_o301_ra516'=>215, 'no_ch1033_o378_ch513_o302_ra516'=>215, 'no_ch1033_o378_ch513_o303_ra516'=>215, 'no_ch1033_o378_ch513_o304_ra516'=>215],
//            ['no_ch1033_o378_ch513_o300_ra516'=>216, 'no_ch1033_o378_ch513_o301_ra516'=>216, 'no_ch1033_o378_ch513_o302_ra516'=>216, 'no_ch1033_o378_ch513_o303_ra516'=>216, 'no_ch1033_o378_ch513_o304_ra516'=>216],
//            ['no_ch1033_o378_ch513_o300_ra516'=>217, 'no_ch1033_o378_ch513_o301_ra516'=>217, 'no_ch1033_o378_ch513_o302_ra516'=>217, 'no_ch1033_o378_ch513_o303_ra516'=>217, 'no_ch1033_o378_ch513_o304_ra516'=>217],
            ['no_ch1033_o378_ch513_o300_ra516'=>218, 'no_ch1033_o378_ch513_o301_ra516'=>218, 'no_ch1033_o378_ch513_o302_ra516'=>218, 'no_ch1033_o378_ch513_o303_ra516'=>218, 'no_ch1033_o378_ch513_o304_ra516'=>218],
//            ['no_ch1033_o378_ch513_o300_ra516'=>220, 'no_ch1033_o378_ch513_o301_ra516'=>220, 'no_ch1033_o378_ch513_o302_ra516'=>220, 'no_ch1033_o378_ch513_o303_ra516'=>220, 'no_ch1033_o378_ch513_o304_ra516'=>220],
//            ['no_ch1033_o378_ch513_o300_ra516'=>219, 'no_ch1033_o378_ch513_o301_ra516'=>219, 'no_ch1033_o378_ch513_o302_ra516'=>219, 'no_ch1033_o378_ch513_o303_ra516'=>219, 'no_ch1033_o378_ch513_o304_ra516'=>219]
        ];

        $table2 = [
//            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
//            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
//            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],

//            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
//            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
//            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
//            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],

//            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
//            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
//            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
//            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
//            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
//            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
//            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517']
        ];

        // จำนวนเงินที่เติม
        $moneyFill = [
//            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
//            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
//            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],

//            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
//            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
//            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
//            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],

//            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
//            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
//            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
//            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
//            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
//            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
//            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518']
        ];

        // ความถี่ที่เติม
        $frequencyFill = [
//            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
//            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
//            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],

//            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
//            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
//            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
//            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],

//            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
//            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
//            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
//            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
//            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
//            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
//            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519']
        ];

        $table3 = [];
        $countTable2 = count($table2);
        // จำนวนรถทุกคันที่ * จำนวนเงินที่เติม * ความถี่ที่เติม
//        $radioCondition = " (IF(SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))>0,1,0) * SUM(IF(unique_key='amountKey',answer_numeric,0))) ";

        $sql = " (SUM(IF(unique_key='amountKey',answer_numeric,0)) 
        * SUM(IF(unique_key='moneyFillKey',answer_numeric,0)) 
        * SUM(IF(unique_key='freqFillKey',answer_numeric,0))
        * IF(SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))>0,1,0)
         ) ";
        $whereSql = "";
        for($i=0;$i<$countTable2;$i++){
            $sumAmountSql = " ( ";
            for ($j=0;$j<5;$j++){
                $tempSql = $sql;
                $currentRadioKey = array_keys($table2RadioArr[$i])[$j];
                $tempSql = str_replace('radioKey', $currentRadioKey, $tempSql);
                $tempSql = str_replace('radioValue',$table2RadioArr[$i][$currentRadioKey], $tempSql);
                $tempSql = str_replace('amountKey',$table2[$i][$j], $tempSql);
                $tempSql = str_replace('moneyFillKey',$moneyFill[$i][$j], $tempSql);
                $tempSql = str_replace('freqFillKey',$frequencyFill[$i][$j], $tempSql);

                $sumAmountSql .= $tempSql . " + ";
            }
            $sumAmountSql = $sumAmountSql . " 0)  * param1 * param2 * 12 as sumAmount ";
            $table3[] = $sumAmountSql;
        }

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $fuelFactors = array();
        $fuelPrice = array();
        for ($i = 1; $i<=22; $i++){
            $fuelFactors[$i] = (float)$settings->where('code','tool_factor_fuel_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_fuel_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_fuel_'. $i)->first()->value;
            if ($i>=5){
                $fuelPrice[$i] = (float)$settings->where('code', 'fuel_price_fuel_' . $i)->first()->value;
            }
        }

        $table4 = [
//            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
//            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
//            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],

//            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
//            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
//            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
//            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],

//            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
//            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
//            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
//            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
//            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
//            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
//            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521']
        ];

        $isRadio = true;
        $startColumn = 'E';
//        $objPHPExcel = Summary::sum($table1,$startColumn,13,$objPHPExcel,$mainObj,$isRadio);
        $startColumn = 'U';
        $objPHPExcel =Summary::average($table2, $startColumn, 13, $objPHPExcel, $mainObj, $isRadio, $table2RadioArr);
        $startColumn = 'AL';
        $startRow = 13;

        $params = array('param1'=>0,'param2'=>1);
        $idx = 0;
        $fuelFactorsArr = [
            $fuelFactors[5],$fuelFactors[6],
            $fuelFactors[9],$fuelFactors[10],$fuelFactors[11],$fuelFactors[12],
            $fuelFactors[13]
        ];
        $fuelPriceArr = [
            $fuelPrice[5],$fuelPrice[6],
            $fuelPrice[9],$fuelPrice[10],$fuelPrice[11],$fuelPrice[12],
            $fuelPrice[13]
        ];
        $ktoeArr = [
            $settings->where('code', 'E4')->first()->value, $settings->where('code','E5')->first()->value,
            $settings->where('code','E4')->first()->value,$settings->where('code','E5')->first()->value,$settings->where('code','E6')->first()->value,$settings->where('code','E7')->first()->value,
            $settings->where('code','E8')->first()->value
        ];
        foreach ($table3 as $sqlStr){
            $uniqueKeyArr = array();
            $temp = [$fuelFactorsArr[$idx],$fuelPriceArr[$idx]];

            $temp = array_merge($temp,array_keys($table2RadioArr[$idx]),$table2[$idx], $moneyFill[$idx],$frequencyFill[$idx]);
            $uniqueKeyArr[]= $temp;

//            $objPHPExcel = Summary::usageElectric($uniqueKeyArr, $startColumn, $startRow,$objPHPExcel, $mainObj,$sqlStr,$params,$ktoeArr[$idx]);

            $startRow++;
            $idx++;
        }
//        $objPHPExcel = Summary::specialUsage($table3, $startColumn, 13, $objPHPExcel,$mainObj,$ktoe);
        $startColumn = 'BB';
        echo "=====</br>";
//        $objPHPExcel = Summary::averageLifetime($table4, $table2, $startColumn, 13, $objPHPExcel, $mainObj, $isRadio,$table2RadioArr);

        dd();
//        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
//        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

}
