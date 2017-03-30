<?php

namespace App\Http\Controllers\Summary8;

use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary82 extends Controller
{

    public static function report82()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary8.xlsx';
        $inputSheet = '8.2';
        $outputFile = 'sum82.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);
        //ตารางที่ 8.5 จำนวนและร้อยละจำแนกตามเพศของหัวหน้าครัวเรือนและเขตปกครอง
        $table1 = [
            ['no_ra35'=>25],
            ['no_ra35'=>26],
        ];

        //ตารางที่ 8.6 จำนวนและร้อยละจำแนกตามสถานภาพสมรสและเขตปกครอง
        $table2 = [
            ['no_ra36'=>27],
            ['no_ra36'=>28],
            ['no_ra36'=>29],
            ['no_ra36'=>30],
            ['no_ra36'=>31],
        ];

        //ตารางที่ 8.7 จำนวนและร้อยละจำแนกตามอายุของหัวหน้าครัวเรือนและเขตปกครอง
        $ageUniqueKey = 'no_ti37_te38';
        $table3 = [
            " HAVING SUM(IF(unique_key='$ageUniqueKey', answer_text,0))<=17 ",
            " HAVING SUM(IF(unique_key='$ageUniqueKey', answer_text,0)) between 18 and 25 ",
            " HAVING SUM(IF(unique_key='$ageUniqueKey', answer_text,0)) between 26 and 49 ",
            " HAVING SUM(IF(unique_key='$ageUniqueKey', answer_text,0)) between 50 and 60 ",
//            " HAVING SUM(IF(unique_key='$ageUniqueKey', answer_text,0)) between 51 and 60 ",
            " HAVING SUM(IF(unique_key='$ageUniqueKey', answer_text,0))>60 ",
        ];

        //ตารางที่ 8.8 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของจำนวนสมาชิกและเขตปกครอง
        $table4 = [
            'no_ti39_nu40',
            'no_ti39_ch2000_o308_nu41',
            'no_ti39_ch2000_o309_nu41',
            'no_ti43_nu44',
            'no_ti43_ch2001_o308_nu45',
            'no_ti43_ch2001_o309_nu45',
        ];


        $isRadio = true;
        $startColumn = 'C';
        $startRow = 11;
        $objPHPExcel = Summary::sum($table1,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);
        $startColumn = 'Q';
        $startRow = 11;
        $objPHPExcel = Summary::sum($table2,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);
        $startColumn = 'AE';
        $objPHPExcel = Summary::sum($table3, $startColumn, $startRow, $objPHPExcel,$mainObj,false,true,$ageUniqueKey);
        //Summary::sum($table3,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);
        $startColumn = 'AS';
        $startRow = 11;
        $objPHPExcel = Summary::average($table4, $startColumn, $startRow, $objPHPExcel, $mainObj
            , false,array(), false, null, false,false, true);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }
}
