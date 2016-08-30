<?php

namespace App\Http\Controllers\Summary13;

use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary132 extends Controller
{

    public static function report132()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary13.xlsx';
        $inputSheet = '13.2';
        $outputFile = 'sum132.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);


        //ตารางที่ 13.9
        $table1 = [
            ['no_ra904' => 264],
            ['no_ra904' => 263],
            ['no_ra904' => 265]
        ];
        //ตารางที่ 13.10
        $table2 = [
            ['no_ra900_o264_ra905' => 229],
            ['no_ra900_o264_ra905' => 230],
            ['no_ra900_o264_ra905' => 231],
            ['no_ra900_o264_ra905' => 232],
            ['no_ra900_o264_ra905' => 233],
            ['no_ra900_o264_ra905' => 270],
            ['no_ra900_o264_ra905' => 234],
        ];
        //ตารางที่ 13.11
        $table3 = [
            ['no_ra900_o264_ra905' => 228],
            ['no_ra900_o264_ra905' => 230],
            ['no_ra900_o264_ra905' => 231],
            ['no_ra900_o264_ra905' => 232],
            ['no_ra900_o264_ra905' => 233],
            ['no_ra900_o264_ra905' => 270],
            ['no_ra900_o264_ra905' => 234],
        ];

        //ตารางที่ 13.12
        $table4 = [
            ['no_ra900_o264_ra905' => 228],
            ['no_ra900_o264_ra905' => 229],
            ['no_ra900_o264_ra905' => 231],
            ['no_ra900_o264_ra905' => 232],
            ['no_ra900_o264_ra905' => 233],
            ['no_ra900_o264_ra905' => 270],
            ['no_ra900_o264_ra905' => 234],
        ];
        //ตารางที่ 13.13
        $table5 = [
            ['no_ra900_o264_ra905' => 228],
            ['no_ra900_o264_ra905' => 229],
            ['no_ra900_o264_ra905' => 230],
            ['no_ra900_o264_ra905' => 232],
            ['no_ra900_o264_ra905' => 233],
            ['no_ra900_o264_ra905' => 270],
            ['no_ra900_o264_ra905' => 234],
        ];
        //ตารางที่ 13.14
        $table6 = [
            ['no_ra900_o264_ra905' => 228],
            ['no_ra900_o264_ra905' => 229],
            ['no_ra900_o264_ra905' => 230],
            ['no_ra900_o264_ra905' => 232],
            ['no_ra900_o264_ra905' => 233],
            ['no_ra900_o264_ra905' => 270],
            ['no_ra900_o264_ra905' => 234],
        ];
        //ตารางที่ 13.15
        $table7 = [
            ['no_ra900_o264_ra905' => 228],
            ['no_ra900_o264_ra905' => 229],
            ['no_ra900_o264_ra905' => 230],
            ['no_ra900_o264_ra905' => 231],
            ['no_ra900_o264_ra905' => 232],
            ['no_ra900_o264_ra905' => 270],
            ['no_ra900_o264_ra905' => 234],
        ];
        //ตารางที่ 13.16
        $table8 = [
            ['no_ra900_o264_ra905' => 228],
            ['no_ra900_o264_ra905' => 229],
            ['no_ra900_o264_ra905' => 230],
            ['no_ra900_o264_ra905' => 231],
            ['no_ra900_o264_ra905' => 232],
            ['no_ra900_o264_ra905' => 233],
            ['no_ra900_o264_ra905' => 234],
        ];

        //ตารางที่ 13.17
        $table9 = [
            ['no_ra900_o264_ra905' => 228],
            ['no_ra900_o264_ra905' => 229],
            ['no_ra900_o264_ra905' => 230],
            ['no_ra900_o264_ra905' => 231],
            ['no_ra900_o264_ra905' => 232],
            ['no_ra900_o264_ra905' => 233],
            ['no_ra900_o264_ra905' => 270],
        ];

        $isRadio = true;
        $startColumn = 'C';
        $startRow = 11;
        $objPHPExcel = Summary::sum($table1,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);

        $startColumn = 'Q';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table2,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra904_o264_ra906', 'no_ra904_o265_ra905', 'no_ra904_o265_ra905', 'no_ra904_o264_ra905', 228);

        $startColumn = 'AE';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table3,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra904_o264_ra906', 'no_ra904_o265_ra905', 'no_ra904_o265_ra905', 'no_ra904_o264_ra905', 229);

        $startColumn = 'AS';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table4,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra904_o264_ra906', 'no_ra904_o265_ra905', 'no_ra904_o265_ra905', 'no_ra904_o264_ra905', 230);

        $startColumn = 'BG';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table5,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra904_o264_ra906', 'no_ra904_o265_ra905', 'no_ra904_o265_ra905', 'no_ra904_o264_ra905', 231);

        $startColumn = 'BU';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table6,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra904_o264_ra906', 'no_ra904_o265_ra905', 'no_ra904_o265_ra905', 'no_ra904_o264_ra905', 232);

        $startColumn = 'CI';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table7,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra904_o264_ra906', 'no_ra904_o265_ra905', 'no_ra904_o265_ra905', 'no_ra904_o264_ra905', 233);

        $startColumn = 'CW';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table8,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra904_o264_ra906', 'no_ra904_o265_ra905', 'no_ra904_o265_ra905', 'no_ra904_o264_ra905', 270);

        $startColumn = 'DK';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table9,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra904_o264_ra906', 'no_ra904_o265_ra905', 'no_ra904_o265_ra905', 'no_ra904_o264_ra905', 234);


        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }
}
