<?php

namespace App\Http\Controllers\Summary12;

use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary124 extends Controller
{

    public static function report124()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary12.xlsx';
        $inputSheet = '12.4';
        $outputFile = 'sum124.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/' . $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        //ตารางที่ 12.15 จำนวนและร้อยละของครัวเรือนที่มีการผลิตไฟฟ้าด้วยเซลล์แสงอาทิตย์จำแนกตามเขตปกครอง
        $table1 = [
            ['no_ra735' => 82],
            ['no_ra735' => 81],
        ];

        //ตารางที่ 12.16 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของกิจกรรมการใช้พลังงานแสงอาทิตย์ในการผลิต
        $table2 = [
            'no_ra735_o81_ch736_o254_ti737_nu738',
            'no_ra735_o81_ch736_o254_ti737_nu739',
            'no_ra735_o81_ch736_o254_ti737_nu740',
            'no_ra735_o81_ch736_o254_ti741_nu742',
            'no_ra735_o81_ch736_o254_nu744',
            'no_ra735_o81_ch736_o254_ti745_nu746',
        ];

        //ตารางที่ 12.17 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของกิจกรรมการใช้พลังงานแสงอาทิตย์ในการอบแห้ง
        $table3 = [
            'no_ra735_o81_ch736_o255_ch749_o260_nu750',
            'no_ra735_o81_ch736_o255_ch749_o260_nu751',
            'no_ra735_o81_ch736_o255_ch749_o260_nu752',
            [],
            'no_ra735_o81_ch736_o255_ch749_o261_nu753',
            'no_ra735_o81_ch736_o255_ch749_o261_nu754',
            'no_ra735_o81_ch736_o255_ch749_o261_nu755',
            [],
            'no_ra735_o81_ch736_o255_ch749_o262_nu756',
            'no_ra735_o81_ch736_o255_ch749_o262_nu757',
            'no_ra735_o81_ch736_o255_ch749_o262_nu758',
        ];

        $isRadio = true;
        $startColumn = 'C';
        $startRow = 11;
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj, $isRadio);

        $startColumn = 'R';
        $startRow = 11;
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $startColumn = 'AG';
        $startRow = 12;
        $objPHPExcel = Summary::average($table3, $startColumn, $startRow, $objPHPExcel, $mainObj);


        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/' . $outputFile)));
    }
}
