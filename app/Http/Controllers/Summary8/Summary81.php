<?php

namespace App\Http\Controllers\Summary8;

use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary81 extends Controller
{

    public static function report81()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary8.xlsx';
        $inputSheet = '8.1';
        $outputFile = 'sum81.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);
        //ตารางที่ 8.1 จำนวนและร้อยละของครัวเรือนจำแนกตามลักษณะที่อยู่อาศัยและเขตปกครอง
        $table1 = [
            ['no_ra27'=>8],
            ['no_ra27'=>9],
            ['no_ra27'=>10],
            ['no_ra27'=>11],
            ['no_ra27'=>12],
            ['no_ra27'=>13],
            ['no_ra27'=>1],
        ];

        //ตารางที่ 8.2 จำนวนและร้อยละของครัวเรือนจำแนกตามประเภทที่อยู่อาศัยและเขตปกครอง
        $table2 = [
            ['no_ra29'=>14],
            ['no_ra29_ra30'=>305],
            ['no_ra29_ra30'=>306],
            ['no_ra29_ra30'=>307],
            ['no_ra29'=>15],
            ['no_ra29_ra30'=>305],
            ['no_ra29_ra30'=>306],
            ['no_ra29_ra30'=>307],
            ['no_ra29'=>16],
            ['no_ra29_ra30'=>305],
            ['no_ra29_ra30'=>306],
            ['no_ra29_ra30'=>307],
            ['no_ra29'=>17],
            ['no_ra29_ra30'=>305],
            ['no_ra29_ra30'=>306],
            ['no_ra29_ra30'=>307],
            ['no_ra29'=>1],
            ['no_ra29_ra30'=>305],
            ['no_ra29_ra30'=>306],
            ['no_ra29_ra30'=>307],
        ];

        //ตารางที่ 8.3 จำนวนและร้อยละของครัวเรือนจำแนกตามกรรมสิทธิ์ในที่อยู่อาศัยและเขตปกครอง
        $table3 = [
            ['no_ra33'=>18],
            ['no_ra33'=>19],
            ['no_ra33'=>20],
            ['no_ra33'=>21],
            ['no_ra33'=>1],
        ];

        //ตารางที่ 8.4 จำนวนและร้อยละของครัวเรือนจำแนกตามประเภทน้ำที่ใช้ในที่อยู่อาศัยและเขตปกครอง
        $table4 = [
            'no_ch34_o22',
            'no_ch34_o23',
            'no_ch34_o24',
            'no_ch34_o1',
        ];


        $isRadio = true;
        $startColumn = 'C';
        $objPHPExcel = Summary::sum($table1,$startColumn,11,$objPHPExcel,$mainObj,$isRadio);
        $startColumn = 'Q';
        $objPHPExcel = Summary::sum($table2,$startColumn,11,$objPHPExcel,$mainObj,$isRadio);
        $startColumn = 'AE';
        $objPHPExcel = Summary::sum($table3,$startColumn,11,$objPHPExcel,$mainObj,$isRadio);
        $startColumn = 'AS';
        $isRadio = false;
        $objPHPExcel = Summary::sum($table4,$startColumn,11,$objPHPExcel,$mainObj,$isRadio);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }
}
