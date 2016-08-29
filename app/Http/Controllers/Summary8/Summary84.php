<?php

namespace App\Http\Controllers\Summary8;

use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary84 extends Controller
{

    public static function report84()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary8.xlsx';
        $inputSheet = '8.4';
        $outputFile = 'sum84.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);


        //ตารางที่ 8.10 จำนวนและร้อยละจำแนกตามอาชีพหลักของหัวหน้าครัวเรือนและเขตปกครอง
        $table1 = [
            ['no_ra52'=>39],
            ['no_ra52'=>40],
            ['no_ra52'=>41],
            ['no_ra52'=>42],
            ['no_ra52'=>43],
            ['no_ra52'=>44],
            ['no_ra52'=>1],
        ];

        //ตารางที่ 8.11 จำนวนและร้อยละจำแนกตามอาชีพรองของหัวหน้าครัวเรือนและเขตปกครอง
        $table2 = [
            ['no_ra56'=>319],
            ['no_ra56'=>320],
            ['no_ra56'=>321],
            ['no_ra56'=>322],
            ['no_ra56'=>323],
            ['no_ra56'=>324],
            ['no_ra56'=>325],
            ['no_ra56'=>1]
        ];
        
        $isRadio = true;
        $startColumn = 'C';
        $startRow = 11;
        Summary::sum($table1,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);

        $startColumn = 'Q';
        $startRow = 11;
        Summary::sum($table2,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);


        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }
}
