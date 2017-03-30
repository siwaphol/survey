<?php

namespace App\Http\Controllers\Summary13;

use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary133 extends Controller
{

    public static function report133()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary13.xlsx';
        $inputSheet = '13.3';
        $outputFile = 'sum133.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        //ตารางที่ 13.18
        $table1 = [
            ['no_ra908' => 264],
            ['no_ra908' => 263],
            ['no_ra908' => 265]
        ];

        //ตารางที่ 13.19
        $table2 = [
            ['no_ra908_o264_ra910' => 281],
            ['no_ra908_o264_ra910' => 282],
            ['no_ra908_o264_ra910' => 283],
            ['no_ra908_o264_ra910' => 284],
            ['no_ra908_o264_ra910' => 285],
            ['no_ra908_o264_ra910' => 1],
        ];

        //ตารางที่ 13.20
        $table3 = [
            ['no_ra908_o264_ra910' => 280],
            ['no_ra908_o264_ra910' => 282],
            ['no_ra908_o264_ra910' => 283],
            ['no_ra908_o264_ra910' => 284],
            ['no_ra908_o264_ra910' => 285],
            ['no_ra908_o264_ra910' => 1],
        ];

        //ตารางที่ 13.21
        $table4 = [
            ['no_ra908_o264_ra910' => 280],
            ['no_ra908_o264_ra910' => 281],
            ['no_ra908_o264_ra910' => 283],
            ['no_ra908_o264_ra910' => 284],
            ['no_ra908_o264_ra910' => 285],
            ['no_ra908_o264_ra910' => 1],
        ];


        //ตารางที่ 13.22
        $table5 = [
            ['no_ra908_o264_ra910' => 280],
            ['no_ra908_o264_ra910' => 281],
            ['no_ra908_o264_ra910' => 282],
            ['no_ra908_o264_ra910' => 284],
            ['no_ra908_o264_ra910' => 285],
            ['no_ra908_o264_ra910' => 1],
        ];

        //ตารางที่ 13.23
        $table6 = [
            ['no_ra908_o264_ra910' => 280],
            ['no_ra908_o264_ra910' => 281],
            ['no_ra908_o264_ra910' => 282],
            ['no_ra908_o264_ra910' => 283],
            ['no_ra908_o264_ra910' => 285],
            ['no_ra908_o264_ra910' => 1],
        ];

        //ตารางที่ 13.24
        $table7 = [
            ['no_ra908_o264_ra910' => 280],
            ['no_ra908_o264_ra910' => 281],
            ['no_ra908_o264_ra910' => 282],
            ['no_ra908_o264_ra910' => 283],
            ['no_ra908_o264_ra910' => 284],
            ['no_ra908_o264_ra910' => 1],
        ];

        $isRadio = true;
        $startColumn = 'C';
        $startRow = 11;
        $objPHPExcel = Summary::sum($table1,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);

        $startColumn = 'Q';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table2,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra908_o264_ra910', 'no_ra908_o265_ra909', 'no_ra908_o265_ra910', 'no_ra908_o264_ra909', 280
        ,'no_ra908', 264);

        $startColumn = 'AE';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table3,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra908_o264_ra910', 'no_ra908_o265_ra909', 'no_ra908_o265_ra910', 'no_ra908_o264_ra909', 281
        ,'no_ra908', 264);

        $startColumn = 'AS';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table4,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra908_o264_ra910', 'no_ra908_o265_ra909', 'no_ra908_o265_ra910', 'no_ra908_o264_ra909', 282
        ,'no_ra908', 264);

        $startColumn = 'BG';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table5,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra908_o264_ra910', 'no_ra908_o265_ra909', 'no_ra908_o265_ra910', 'no_ra908_o264_ra909', 283
        ,'no_ra908', 264);

        $startColumn = 'BU';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table6,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra908_o264_ra910', 'no_ra908_o265_ra909', 'no_ra908_o265_ra910', 'no_ra908_o264_ra909', 284
        ,'no_ra908', 264);

        $startColumn = 'CI';
        $startRow = 11;
        $objPHPExcel = Summary::sum13($table7,$startColumn,$startRow,$objPHPExcel,$mainObj, 'no_ra908_o264_ra910', 'no_ra908_o265_ra909', 'no_ra908_o265_ra910', 'no_ra908_o264_ra909', 285
        ,'no_ra908', 264);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }
}
