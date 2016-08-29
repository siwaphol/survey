<?php

namespace App\Http\Controllers\Summary8;

use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary83 extends Controller
{

    public static function report83()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary8.xlsx';
        $inputSheet = '8.3';
        $outputFile = 'sum83.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);


        //ตารางที่ 8.9 จำนวนและร้อยละจำแนกตามระดับการศึกษาของหัวหน้าครัวเรือนและเขตปกครอง
        $table1 = [
            ['no_ra47'=>32],
            ['no_ra47'=>33],
            ['no_ra47'=>34],
            ['no_ra47'=>35],
            ['no_ra47'=>36],
            ['no_ra47'=>37],
            ['no_ra47'=>38],
            ['no_ra47'=>1],
        ];
        
        $isRadio = true;
        $startColumn = 'C';
        $startRow = 11;
        Summary::sum($table1,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);


        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }
}
