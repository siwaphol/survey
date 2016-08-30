<?php

namespace App\Http\Controllers\Summary13;

use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary131 extends Controller
{

    public static function report131()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary13.xlsx';
        $inputSheet = '13.1';
        $outputFile = 'sum131.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);


        //ตารางที่ 13.1 จำนวนและร้อยละของครัวเรือนที่มีแนมโน้มการเปลี่ยนการใช้พลังงานในการประกอบอาหารจำแนก
        $table1 = [
            ['no_ra900' => 264],
            ['no_ra900' => 263],
            ['no_ra900' => 265]
        ];
        //ตารางที่ 13.2 จำนวนและร้อยละของการเปลี่ยนการใช้พลังงานในการประกอบอาหารจากฟืนจำแนกตามเขตปกครอง
        $table2 = [
            ['no_ra900_o264_ra901' => 267],
            ['no_ra900_o264_ra901' => 268],
            ['no_ra900_o264_ra901' => 269],
            ['no_ra900_o264_ra901' => 270],
            ['no_ra900_o264_ra901' => 271],
            ['no_ra900_o264_ra901' => 272],
        ];
        //ตารางที่ 13.3
        $table3 = [
            ['no_ra900_o264_ra901' => 266],
            ['no_ra900_o264_ra901' => 268],
            ['no_ra900_o264_ra901' => 269],
            ['no_ra900_o264_ra901' => 270],
            ['no_ra900_o264_ra901' => 271],
            ['no_ra900_o264_ra901' => 272],
        ];

        //ตารางที่ 13.4
        $table4 = [
            ['no_ra900_o264_ra901' => 266],
            ['no_ra900_o264_ra901' => 267],
            ['no_ra900_o264_ra901' => 269],
            ['no_ra900_o264_ra901' => 270],
            ['no_ra900_o264_ra901' => 271],
            ['no_ra900_o264_ra901' => 272],
        ];
        //ตารางที่ 13.5
        $table5 = [
            ['no_ra900_o264_ra901' => 266],
            ['no_ra900_o264_ra901' => 267],
            ['no_ra900_o264_ra901' => 268],
            ['no_ra900_o264_ra901' => 270],
            ['no_ra900_o264_ra901' => 271],
            ['no_ra900_o264_ra901' => 272],
        ];
        //ตารางที่ 13.6
        $table6 = [
            ['no_ra900_o264_ra901' => 266],
            ['no_ra900_o264_ra901' => 267],
            ['no_ra900_o264_ra901' => 268],
            ['no_ra900_o264_ra901' => 269],
            ['no_ra900_o264_ra901' => 271],
            ['no_ra900_o264_ra901' => 272],
        ];
        //ตารางที่ 13.7
        $table7 = [
            ['no_ra900_o264_ra901' => 266],
            ['no_ra900_o264_ra901' => 267],
            ['no_ra900_o264_ra901' => 268],
            ['no_ra900_o264_ra901' => 269],
            ['no_ra900_o264_ra901' => 270],
            ['no_ra900_o264_ra901' => 272],
        ];
        //ตารางที่ 13.8
        $table8 = [
            ['no_ra900_o264_ra901' => 266],
            ['no_ra900_o264_ra901' => 267],
            ['no_ra900_o264_ra901' => 268],
            ['no_ra900_o264_ra901' => 269],
            ['no_ra900_o264_ra901' => 270],
            ['no_ra900_o264_ra901' => 271],
        ];

        $isRadio = true;
        $startColumn = 'C';
        $startRow = 11;
        $objPHPExcel = Summary::sum($table1,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);

        $startColumn = 'Q';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table2,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra900_o264_ra902', 'no_ra900_o265_ra901', 'no_ra900_o265_ra902', 'no_ra900_o264_ra901', 266);

        $startColumn = 'AE';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table2,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra900_o264_ra902', 'no_ra900_o265_ra901', 'no_ra900_o265_ra902', 'no_ra900_o264_ra901', 267);

        $startColumn = 'AS';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table2,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra900_o264_ra902', 'no_ra900_o265_ra901', 'no_ra900_o265_ra902', 'no_ra900_o264_ra901', 268);

        $startColumn = 'BG';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table2,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra900_o264_ra902', 'no_ra900_o265_ra901', 'no_ra900_o265_ra902', 'no_ra900_o264_ra901', 269);

        $startColumn = 'BU';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table2,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra900_o264_ra902', 'no_ra900_o265_ra901', 'no_ra900_o265_ra902', 'no_ra900_o264_ra901', 270);

        $startColumn = 'CI';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table2,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra900_o264_ra902', 'no_ra900_o265_ra901', 'no_ra900_o265_ra902', 'no_ra900_o264_ra901', 271);

        $startColumn = 'CW';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table2,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra900_o264_ra902', 'no_ra900_o265_ra901', 'no_ra900_o265_ra902', 'no_ra900_o264_ra901', 272);


        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }
}
