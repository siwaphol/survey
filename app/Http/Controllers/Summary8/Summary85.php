<?php

namespace App\Http\Controllers\Summary8;

use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary85 extends Controller
{

    public static function report85()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary8.xlsx';
        $inputSheet = '8.5';
        $outputFile = 'sum85.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);


        //ตารางที่ 8.10 จำนวนและร้อยละจำแนกตามอาชีพหลักของหัวหน้าครัวเรือนและเขตปกครอง
        $table1 = [
            ['no_ti59_ch60_o46_nu61','no_ti59_ch60_o47_nu61','no_ti59_ch60_o48_nu61','no_ti59_ch60_o49_nu61'],
            ['no_ti62_ch63_o53_nu64','no_ti62_ch63_o54_nu64','no_ti62_ch63_o55_nu64','no_ti62_ch63_o56_nu64'],
        ];
        //ตารางที่ 8.13 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของแหล่งที่มาของรายได้และเขตปกครอง
        $table2 = [
            'no_ti59_ch60_o46_nu61',
            'no_ti59_ch60_o47_nu61',
            'no_ti59_ch60_o48_nu61',
            'no_ti59_ch60_o49_nu61',
            'no_ti59_ch60_o50_nu61',
            'no_ti59_ch60_o51_nu61',
            'no_ti59_ch60_o52_nu61',
            'no_ti59_ch60_o1_nu61',
        ];
        //ตารางที่ 8.14 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของประเภทรายจ่ายและเขตปกครอง
        $table3 = [
            'no_ti62_ch63_o53_nu64',
            'no_ti62_ch63_o54_nu64',
            'no_ti62_ch63_o55_nu64',
            'no_ti62_ch63_o56_nu64',
            'no_ti62_ch63_o57_nu64',
            'no_ti62_ch63_o58_nu64',
            'no_ti62_ch63_o59_nu64',
            'no_ti62_ch63_o60_nu64',
            'no_ti62_ch63_o61_nu64',
            'no_ti62_ch63_o62_nu64',
            'no_ti62_ch63_o63_nu64',
            'no_ti62_ch63_o64_nu64',
            'no_ti62_ch63_o65_nu64',
            'no_ti62_ch63_o1_nu64',
        ];
        //ตารางที่ 8.15 จำนวนและร้อยของครัวเรือนจำแนกตามการมีหนี้สินและเขตปกครอง
        $table4 = [
            ['no_ra65'=>66],
            ['no_ra65'=>67],
        ];

        //ตารางที่ 8.16 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของจำนวนหนี้สินเฉลี่ยและเขตปกครอง
        $table5 = [
            'no_ra65_o66_nu66',
        ];

        $startColumn = 'C';
        $startRow = 11;
        $objPHPExcel = Summary::average($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $startColumn = 'Q';
        $startRow = 11;
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $startColumn = 'AE';
        $startRow = 11;
        $objPHPExcel = Summary::average($table3, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $isRadio = true;
        $startColumn = 'AR';
        $startRow = 11;
        $objPHPExcel = Summary::sum($table4,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);

        $startColumn = 'BG';
        $startRow = 11;
        $objPHPExcel = Summary::average($table5, $startColumn, $startRow, $objPHPExcel, $mainObj);


        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }
}
