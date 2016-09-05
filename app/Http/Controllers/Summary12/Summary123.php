<?php

namespace App\Http\Controllers\Summary12;

use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary123 extends Controller
{

    public static function report123()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary12.xlsx';
        $inputSheet = '12.3';
        $outputFile = 'sum123.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/' . $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);
        //ตารางที่ 12.12 จำนวนและร้อยละของครัวเรือนที่มีการผลิตไฟฟ้าด้วยเครื่องปั่นไฟจำแนกตามเขตปกครอง
        $table1 = [
            ['no_ra729' => 250],
            ['no_ra729' => 251],
        ];

        //ตารางที่ 12.13 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของระยะเวลาการใช้ไฟฟ้าจากเครื่องปั่นไฟจำแนกตาม
        $table2 = [
            'no_ra729_o251_ch730_o252_nu731',
            'no_ra729_o251_ch730_o253_nu731',
            'no_ra729_o251_nu732'
        ];

        //ตารางที่ 12.14 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของปริมาณพลังงานที่ใช้ในการผลิตไฟฟ้าจำแนกตาม
        $table3 = [
            'no_ra729_o251_ch733_o228_nu734',
            'no_ra729_o251_ch733_o229_nu734',
            'no_ra729_o251_ch733_o230_nu734',
            'no_ra729_o251_ch733_o231_nu734',
            'no_ra729_o251_ch733_o232_nu734',
            'no_ra729_o251_ch733_o233_nu734',
            'no_ra729_o251_ch733_o1_nu734'
        ];



        $isRadio = true;
        $startColumn = 'C';
        $startRow = 11;
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj, $isRadio);

        $startColumn = 'R';
        $startRow = 11;
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $startColumn = 'AF';
        $startRow = 11;
        $objPHPExcel = Summary::average($table3, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/' . $outputFile)));
    }
}
