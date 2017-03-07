<?php

namespace App\Http\Controllers\Summary9;

use App\Main;
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

//        $table1 = [
//            ['no_ch1033_o376_ch495_o300_ra498'=>213, 'no_ch1033_o376_ch495_o301_ra498'=>213, 'no_ch1033_o376_ch495_o302_ra498'=>213, 'no_ch1033_o376_ch495_o303_ra498'=>213, 'no_ch1033_o376_ch495_o304_ra498'=>213],
//            ['no_ch1033_o376_ch495_o300_ra498'=>214, 'no_ch1033_o376_ch495_o301_ra498'=>214, 'no_ch1033_o376_ch495_o302_ra498'=>214, 'no_ch1033_o376_ch495_o303_ra498'=>214, 'no_ch1033_o376_ch495_o304_ra498'=>214],
//            ['no_ch1033_o376_ch495_o300_ra498'=>215, 'no_ch1033_o376_ch495_o301_ra498'=>215, 'no_ch1033_o376_ch495_o302_ra498'=>215, 'no_ch1033_o376_ch495_o303_ra498'=>215, 'no_ch1033_o376_ch495_o304_ra498'=>215],
//            ['no_ch1033_o376_ch495_o300_ra498'=>216, 'no_ch1033_o376_ch495_o301_ra498'=>216, 'no_ch1033_o376_ch495_o302_ra498'=>216, 'no_ch1033_o376_ch495_o303_ra498'=>216, 'no_ch1033_o376_ch495_o304_ra498'=>216],
//            ['no_ch1033_o376_ch495_o300_ra498'=>217, 'no_ch1033_o376_ch495_o301_ra498'=>217, 'no_ch1033_o376_ch495_o302_ra498'=>217, 'no_ch1033_o376_ch495_o303_ra498'=>217, 'no_ch1033_o376_ch495_o304_ra498'=>213],
//            ['no_ch1033_o377_ch504_o300_ra507'=>213, 'no_ch1033_o377_ch504_o301_ra507'=>213, 'no_ch1033_o377_ch504_o302_ra507'=>213, 'no_ch1033_o377_ch504_o303_ra507'=>213, 'no_ch1033_o377_ch504_o304_ra507'=>213],
//            ['no_ch1033_o377_ch504_o300_ra507'=>214, 'no_ch1033_o377_ch504_o301_ra507'=>214, 'no_ch1033_o377_ch504_o302_ra507'=>214, 'no_ch1033_o377_ch504_o303_ra507'=>214, 'no_ch1033_o377_ch504_o304_ra507'=>214],
//            ['no_ch1033_o377_ch504_o300_ra507'=>215, 'no_ch1033_o377_ch504_o301_ra507'=>215, 'no_ch1033_o377_ch504_o302_ra507'=>215, 'no_ch1033_o377_ch504_o303_ra507'=>215, 'no_ch1033_o377_ch504_o304_ra507'=>215],
//            ['no_ch1033_o377_ch504_o300_ra507'=>216, 'no_ch1033_o377_ch504_o301_ra507'=>216, 'no_ch1033_o377_ch504_o302_ra507'=>216, 'no_ch1033_o377_ch504_o303_ra507'=>216, 'no_ch1033_o377_ch504_o304_ra507'=>216],
//            ['no_ch1033_o377_ch504_o300_ra507'=>217, 'no_ch1033_o377_ch504_o301_ra507'=>217, 'no_ch1033_o377_ch504_o302_ra507'=>217, 'no_ch1033_o377_ch504_o303_ra507'=>217, 'no_ch1033_o377_ch504_o304_ra507'=>217],
//            ['no_ch1033_o377_ch504_o300_ra507'=>218, 'no_ch1033_o377_ch504_o301_ra507'=>218, 'no_ch1033_o377_ch504_o302_ra507'=>218, 'no_ch1033_o377_ch504_o303_ra507'=>218, 'no_ch1033_o377_ch504_o304_ra507'=>218],
//            ['no_ch1033_o377_ch504_o300_ra507'=>220, 'no_ch1033_o377_ch504_o301_ra507'=>220, 'no_ch1033_o377_ch504_o302_ra507'=>220, 'no_ch1033_o377_ch504_o303_ra507'=>220, 'no_ch1033_o377_ch504_o304_ra507'=>220],
//            ['no_ch1033_o377_ch504_o300_ra507'=>219, 'no_ch1033_o377_ch504_o301_ra507'=>219, 'no_ch1033_o377_ch504_o302_ra507'=>219, 'no_ch1033_o377_ch504_o303_ra507'=>219, 'no_ch1033_o377_ch504_o304_ra507'=>219],
//            ['no_ch1033_o378_ch513_o300_ra516'=>213, 'no_ch1033_o378_ch513_o301_ra516'=>213, 'no_ch1033_o378_ch513_o302_ra516'=>213, 'no_ch1033_o378_ch513_o303_ra516'=>213, 'no_ch1033_o378_ch513_o304_ra516'=>213],
//            ['no_ch1033_o378_ch513_o300_ra516'=>214, 'no_ch1033_o378_ch513_o301_ra516'=>214, 'no_ch1033_o378_ch513_o302_ra516'=>214, 'no_ch1033_o378_ch513_o303_ra516'=>214, 'no_ch1033_o378_ch513_o304_ra516'=>214],
//            ['no_ch1033_o378_ch513_o300_ra516'=>215, 'no_ch1033_o378_ch513_o301_ra516'=>215, 'no_ch1033_o378_ch513_o302_ra516'=>215, 'no_ch1033_o378_ch513_o303_ra516'=>215, 'no_ch1033_o378_ch513_o304_ra516'=>215],
//            ['no_ch1033_o378_ch513_o300_ra516'=>216, 'no_ch1033_o378_ch513_o301_ra516'=>216, 'no_ch1033_o378_ch513_o302_ra516'=>216, 'no_ch1033_o378_ch513_o303_ra516'=>216, 'no_ch1033_o378_ch513_o304_ra516'=>216],
//            ['no_ch1033_o378_ch513_o300_ra516'=>217, 'no_ch1033_o378_ch513_o301_ra516'=>217, 'no_ch1033_o378_ch513_o302_ra516'=>217, 'no_ch1033_o378_ch513_o303_ra516'=>217, 'no_ch1033_o378_ch513_o304_ra516'=>217],
//            ['no_ch1033_o378_ch513_o300_ra516'=>218, 'no_ch1033_o378_ch513_o301_ra516'=>218, 'no_ch1033_o378_ch513_o302_ra516'=>218, 'no_ch1033_o378_ch513_o303_ra516'=>218, 'no_ch1033_o378_ch513_o304_ra516'=>218],
//            ['no_ch1033_o378_ch513_o300_ra516'=>220, 'no_ch1033_o378_ch513_o301_ra516'=>220, 'no_ch1033_o378_ch513_o302_ra516'=>220, 'no_ch1033_o378_ch513_o303_ra516'=>220, 'no_ch1033_o378_ch513_o304_ra516'=>220],
//            ['no_ch1033_o378_ch513_o300_ra516'=>219, 'no_ch1033_o378_ch513_o301_ra516'=>219, 'no_ch1033_o378_ch513_o302_ra516'=>219, 'no_ch1033_o378_ch513_o303_ra516'=>219, 'no_ch1033_o378_ch513_o304_ra516'=>219]
//        ];
        // คำนวณใหม่เอาเฉพาะคันที่ 1
        $table1 = [
            ['no_ch1033_o376_ch495_o300_ra498'=>213],
            ['no_ch1033_o376_ch495_o300_ra498'=>214],
            ['no_ch1033_o376_ch495_o300_ra498'=>215],
            ['no_ch1033_o376_ch495_o300_ra498'=>216],
            ['no_ch1033_o376_ch495_o300_ra498'=>217],
            ['no_ch1033_o377_ch504_o300_ra507'=>213],
            ['no_ch1033_o377_ch504_o300_ra507'=>214],
            ['no_ch1033_o377_ch504_o300_ra507'=>215],
            ['no_ch1033_o377_ch504_o300_ra507'=>216],
            ['no_ch1033_o377_ch504_o300_ra507'=>217],
            ['no_ch1033_o377_ch504_o300_ra507'=>218],
            ['no_ch1033_o377_ch504_o300_ra507'=>220],
            ['no_ch1033_o377_ch504_o300_ra507'=>219],
            ['no_ch1033_o378_ch513_o300_ra516'=>213],
            ['no_ch1033_o378_ch513_o300_ra516'=>214],
            ['no_ch1033_o378_ch513_o300_ra516'=>215],
            ['no_ch1033_o378_ch513_o300_ra516'=>216],
            ['no_ch1033_o378_ch513_o300_ra516'=>217],
            ['no_ch1033_o378_ch513_o300_ra516'=>218],
            ['no_ch1033_o378_ch513_o300_ra516'=>220],
            ['no_ch1033_o378_ch513_o300_ra516'=>219]
        ];

        $table2RadioArr = [
            ['no_ch1033_o376_ch495_o300_ra498'=>213, 'no_ch1033_o376_ch495_o301_ra498'=>213, 'no_ch1033_o376_ch495_o302_ra498'=>213, 'no_ch1033_o376_ch495_o303_ra498'=>213, 'no_ch1033_o376_ch495_o304_ra498'=>213],
            ['no_ch1033_o376_ch495_o300_ra498'=>214, 'no_ch1033_o376_ch495_o301_ra498'=>214, 'no_ch1033_o376_ch495_o302_ra498'=>214, 'no_ch1033_o376_ch495_o303_ra498'=>214, 'no_ch1033_o376_ch495_o304_ra498'=>214],
            ['no_ch1033_o376_ch495_o300_ra498'=>215, 'no_ch1033_o376_ch495_o301_ra498'=>215, 'no_ch1033_o376_ch495_o302_ra498'=>215, 'no_ch1033_o376_ch495_o303_ra498'=>215, 'no_ch1033_o376_ch495_o304_ra498'=>215],
            ['no_ch1033_o376_ch495_o300_ra498'=>216, 'no_ch1033_o376_ch495_o301_ra498'=>216, 'no_ch1033_o376_ch495_o302_ra498'=>216, 'no_ch1033_o376_ch495_o303_ra498'=>216, 'no_ch1033_o376_ch495_o304_ra498'=>216],
            ['no_ch1033_o376_ch495_o300_ra498'=>217, 'no_ch1033_o376_ch495_o301_ra498'=>217, 'no_ch1033_o376_ch495_o302_ra498'=>217, 'no_ch1033_o376_ch495_o303_ra498'=>217, 'no_ch1033_o376_ch495_o304_ra498'=>213],
            ['no_ch1033_o377_ch504_o300_ra507'=>213, 'no_ch1033_o377_ch504_o301_ra507'=>213, 'no_ch1033_o377_ch504_o302_ra507'=>213, 'no_ch1033_o377_ch504_o303_ra507'=>213, 'no_ch1033_o377_ch504_o304_ra507'=>213],
            ['no_ch1033_o377_ch504_o300_ra507'=>214, 'no_ch1033_o377_ch504_o301_ra507'=>214, 'no_ch1033_o377_ch504_o302_ra507'=>214, 'no_ch1033_o377_ch504_o303_ra507'=>214, 'no_ch1033_o377_ch504_o304_ra507'=>214],
            ['no_ch1033_o377_ch504_o300_ra507'=>215, 'no_ch1033_o377_ch504_o301_ra507'=>215, 'no_ch1033_o377_ch504_o302_ra507'=>215, 'no_ch1033_o377_ch504_o303_ra507'=>215, 'no_ch1033_o377_ch504_o304_ra507'=>215],
            ['no_ch1033_o377_ch504_o300_ra507'=>216, 'no_ch1033_o377_ch504_o301_ra507'=>216, 'no_ch1033_o377_ch504_o302_ra507'=>216, 'no_ch1033_o377_ch504_o303_ra507'=>216, 'no_ch1033_o377_ch504_o304_ra507'=>216],
            ['no_ch1033_o377_ch504_o300_ra507'=>217, 'no_ch1033_o377_ch504_o301_ra507'=>217, 'no_ch1033_o377_ch504_o302_ra507'=>217, 'no_ch1033_o377_ch504_o303_ra507'=>217, 'no_ch1033_o377_ch504_o304_ra507'=>217],
            ['no_ch1033_o377_ch504_o300_ra507'=>218, 'no_ch1033_o377_ch504_o301_ra507'=>218, 'no_ch1033_o377_ch504_o302_ra507'=>218, 'no_ch1033_o377_ch504_o303_ra507'=>218, 'no_ch1033_o377_ch504_o304_ra507'=>218],
            ['no_ch1033_o377_ch504_o300_ra507'=>220, 'no_ch1033_o377_ch504_o301_ra507'=>220, 'no_ch1033_o377_ch504_o302_ra507'=>220, 'no_ch1033_o377_ch504_o303_ra507'=>220, 'no_ch1033_o377_ch504_o304_ra507'=>220],
            ['no_ch1033_o377_ch504_o300_ra507'=>219, 'no_ch1033_o377_ch504_o301_ra507'=>219, 'no_ch1033_o377_ch504_o302_ra507'=>219, 'no_ch1033_o377_ch504_o303_ra507'=>219, 'no_ch1033_o377_ch504_o304_ra507'=>219],
            ['no_ch1033_o378_ch513_o300_ra516'=>213, 'no_ch1033_o378_ch513_o301_ra516'=>213, 'no_ch1033_o378_ch513_o302_ra516'=>213, 'no_ch1033_o378_ch513_o303_ra516'=>213, 'no_ch1033_o378_ch513_o304_ra516'=>213],
            ['no_ch1033_o378_ch513_o300_ra516'=>214, 'no_ch1033_o378_ch513_o301_ra516'=>214, 'no_ch1033_o378_ch513_o302_ra516'=>214, 'no_ch1033_o378_ch513_o303_ra516'=>214, 'no_ch1033_o378_ch513_o304_ra516'=>214],
            ['no_ch1033_o378_ch513_o300_ra516'=>215, 'no_ch1033_o378_ch513_o301_ra516'=>215, 'no_ch1033_o378_ch513_o302_ra516'=>215, 'no_ch1033_o378_ch513_o303_ra516'=>215, 'no_ch1033_o378_ch513_o304_ra516'=>215],
            ['no_ch1033_o378_ch513_o300_ra516'=>216, 'no_ch1033_o378_ch513_o301_ra516'=>216, 'no_ch1033_o378_ch513_o302_ra516'=>216, 'no_ch1033_o378_ch513_o303_ra516'=>216, 'no_ch1033_o378_ch513_o304_ra516'=>216],
            ['no_ch1033_o378_ch513_o300_ra516'=>217, 'no_ch1033_o378_ch513_o301_ra516'=>217, 'no_ch1033_o378_ch513_o302_ra516'=>217, 'no_ch1033_o378_ch513_o303_ra516'=>217, 'no_ch1033_o378_ch513_o304_ra516'=>217],
            ['no_ch1033_o378_ch513_o300_ra516'=>218, 'no_ch1033_o378_ch513_o301_ra516'=>218, 'no_ch1033_o378_ch513_o302_ra516'=>218, 'no_ch1033_o378_ch513_o303_ra516'=>218, 'no_ch1033_o378_ch513_o304_ra516'=>218],
            ['no_ch1033_o378_ch513_o300_ra516'=>220, 'no_ch1033_o378_ch513_o301_ra516'=>220, 'no_ch1033_o378_ch513_o302_ra516'=>220, 'no_ch1033_o378_ch513_o303_ra516'=>220, 'no_ch1033_o378_ch513_o304_ra516'=>220],
            ['no_ch1033_o378_ch513_o300_ra516'=>219, 'no_ch1033_o378_ch513_o301_ra516'=>219, 'no_ch1033_o378_ch513_o302_ra516'=>219, 'no_ch1033_o378_ch513_o303_ra516'=>219, 'no_ch1033_o378_ch513_o304_ra516'=>219]
        ];
        $table2 = [
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517']
        ];

        // จำนวนเงินที่เติม
        $moneyFill = [
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518']
        ];

        // ความถี่ที่เติม
        $frequencyFill = [
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519']
        ];

        $priceFields = [
            'no_ra808_o81_ti809_ch810_o228_nu820',
            'no_ra808_o81_ti809_ch810_o229_nu820',
            'no_ra808_o81_ti809_ch810_o230_nu820',
            'no_ra808_o81_ti809_ch810_o231_nu820',
            'no_ra808_o81_ti809_ch810_o232_nu820',

            'no_ra808_o81_ti809_ch810_o228_nu820',
            'no_ra808_o81_ti809_ch810_o229_nu820',
            'no_ra808_o81_ti809_ch810_o230_nu820',
            'no_ra808_o81_ti809_ch810_o231_nu820',
            'no_ra808_o81_ti809_ch810_o232_nu820',
            'no_ra808_o81_ti809_ch810_o233_nu820',
            'no_ra808_o81_ti809_ch810_o234_nu820',
            'no_ra808_o81_ti809_ch810_o235_nu820',

            'no_ra808_o81_ti809_ch810_o228_nu820',
            'no_ra808_o81_ti809_ch810_o229_nu820',
            'no_ra808_o81_ti809_ch810_o230_nu820',
            'no_ra808_o81_ti809_ch810_o231_nu820',
            'no_ra808_o81_ti809_ch810_o232_nu820',
            'no_ra808_o81_ti809_ch810_o233_nu820',
            'no_ra808_o81_ti809_ch810_o234_nu820',
            'no_ra808_o81_ti809_ch810_o235_nu820'
        ];
        $table3 = [];
        $countTable1 = count($table1);
        $gasPrice = 20;
        $ktoe = 0.745;
        $radioCondition = " IF(SUM(IF(unique_key='param1' AND option_id=param2,1,0))>1,1,SUM(IF(unique_key='param1' AND option_id=param2,1,0))) ";
        $sql = " (param1 * (SUM(IF(unique_key='param2', answer_numeric,0))) * 
         (SUM(IF(unique_key='param3', answer_numeric,0))/ SUM(IF(unique_key='param4', answer_numeric,0))) *
         (SUM(IF(unique_key='param5', answer_numeric,0))) * 12) ";
        $whereSql = "";
        for($i=0;$i<$countTable1;$i++){
            $sumAmountSql = "";
            $j=0;
            foreach ($table1[$i] as $key=>$value){
                $tempRadioCon = $radioCondition;
                $tempRadioCon = str_replace('param1', $key, $tempRadioCon);
                $tempRadioCon = str_replace('param2', $value, $tempRadioCon);

                $tempSql = $sql;
                $tempSql = str_replace('param1',$tempRadioCon, $tempSql);
                $tempSql = str_replace('param2',$table2[$i][$j], $tempSql);
                $tempSql = str_replace('param3',$moneyFill[$i][$j], $tempSql);
                $tempSql = str_replace('param4',$priceFields[$i], $tempSql);
                $tempSql = str_replace('param5',$frequencyFill[$i][$j], $tempSql);
//                $tempSql = str_replace('param5',$table2[$i][$j], $tempSql);

                $sumAmountSql .= $tempSql . " + ";
                $j++;
            }
            $sumAmountSql .= $sumAmountSql . " 0 ";
            $table3[] = $sumAmountSql;
        }

        $table4 = [
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521']
        ];

        $isRadio = true;
        $startColumn = 'E';
        $objPHPExcel = Summary::sum($table1,$startColumn,13,$objPHPExcel,$mainObj,$isRadio);
        $startColumn = 'U';
        $objPHPExcel =Summary::average($table2, $startColumn, 13, $objPHPExcel, $mainObj, $isRadio, $table2RadioArr);
        $startColumn = 'AL';
//        $objPHPExcel = Summary::specialUsage($table3, $startColumn, 13, $objPHPExcel,$mainObj,$ktoe);
        $startColumn = 'BB';
        $objPHPExcel = Summary::averageLifetime($table4, $table2, $startColumn, 13, $objPHPExcel, $mainObj, $isRadio,$table2);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

}
