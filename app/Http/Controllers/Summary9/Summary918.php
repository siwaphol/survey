<?php

namespace App\Http\Controllers\Summary9;

use App\Main;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary918 extends Controller
{
    public static function report918()
    {
        set_time_limit(3600);

        $table1 = [
//            'no_ch1034_o379_ch523_o213',
            'no_ch1034_o379_ch523_o214',
            'no_ch1034_o379_ch523_o215',
//            'no_ch1034_o379_ch523_o216',
//            'no_ch1034_o379_ch523_o217',

//            'no_ch1034_o380_ch529_o213',
            'no_ch1034_o380_ch529_o214',
            'no_ch1034_o380_ch529_o215',
//            'no_ch1034_o380_ch529_o216',
//            'no_ch1034_o380_ch529_o217',

//            'no_ch1034_o381_ch535_o213',
            'no_ch1034_o381_ch535_o214',
            'no_ch1034_o381_ch535_o215',
//            'no_ch1034_o381_ch535_o216',
//            'no_ch1034_o381_ch535_o217',

//            'no_ch1034_o382_ch541_o213',
//            'no_ch1034_o382_ch541_o214',
//            'no_ch1034_o382_ch541_o215',
//            'no_ch1034_o382_ch541_o216',
//            'no_ch1034_o382_ch541_o217',
            'no_ch1034_o382_ch541_o218',

//            'no_ch1034_o383_ch547_o213',
//            'no_ch1034_o383_ch547_o214',
//            'no_ch1034_o383_ch547_o215',
//            'no_ch1034_o383_ch547_o216',
//            'no_ch1034_o383_ch547_o217',
            'no_ch1034_o383_ch547_o218',

//            'no_ch1034_o384_ch553_o213',
//            'no_ch1034_o384_ch553_o214',
//            'no_ch1034_o384_ch553_o215',
//            'no_ch1034_o384_ch553_o216',
//            'no_ch1034_o384_ch553_o217',
            'no_ch1034_o384_ch553_o218',
        ];
        $table2 = [
//            'no_ch1034_o379_ch523_o213_nu524',
            'no_ch1034_o379_ch523_o214_nu524',
            'no_ch1034_o379_ch523_o215_nu524',
//            'no_ch1034_o379_ch523_o216_nu524',
//            'no_ch1034_o379_ch523_o217_nu524',

//            'no_ch1034_o380_ch529_o213_nu530',
            'no_ch1034_o380_ch529_o214_nu530',
            'no_ch1034_o380_ch529_o215_nu530',
//            'no_ch1034_o380_ch529_o216_nu530',
//            'no_ch1034_o380_ch529_o217_nu530',

//            'no_ch1034_o381_ch535_o213_nu536',
            'no_ch1034_o381_ch535_o214_nu536',
            'no_ch1034_o381_ch535_o215_nu536',
//            'no_ch1034_o381_ch535_o216_nu536',
//            'no_ch1034_o381_ch535_o217_nu536',

//            'no_ch1034_o382_ch541_o213_nu542',
//            'no_ch1034_o382_ch541_o214_nu542',
//            'no_ch1034_o382_ch541_o215_nu542',
//            'no_ch1034_o382_ch541_o216_nu542',
//            'no_ch1034_o382_ch541_o217_nu542',
            'no_ch1034_o382_ch541_o218_nu542',

//            'no_ch1034_o383_ch547_o213_nu548',
//            'no_ch1034_o383_ch547_o214_nu548',
//            'no_ch1034_o383_ch547_o215_nu548',
//            'no_ch1034_o383_ch547_o216_nu548',
//            'no_ch1034_o383_ch547_o217_nu548',
            'no_ch1034_o383_ch547_o218_nu548',

//            'no_ch1034_o384_ch553_o213_nu554',
//            'no_ch1034_o384_ch553_o214_nu554',
//            'no_ch1034_o384_ch553_o215_nu554',
//            'no_ch1034_o384_ch553_o216_nu554',
//            'no_ch1034_o384_ch553_o217_nu554',
            'no_ch1034_o384_ch553_o218_nu554',
        ];
        $table3 = [
//            ['no_ch1034_o379_ch523_o213_nu524','no_ch1034_o379_ch523_o213_nu525','no_ch1034_o379_ch523_o213_nu526'],
            ['no_ch1034_o379_ch523_o214_nu524','no_ch1034_o379_ch523_o214_nu525','no_ch1034_o379_ch523_o214_nu526'],
            ['no_ch1034_o379_ch523_o215_nu524','no_ch1034_o379_ch523_o215_nu525','no_ch1034_o379_ch523_o215_nu526'],
//            ['no_ch1034_o379_ch523_o216_nu524','no_ch1034_o379_ch523_o216_nu525','no_ch1034_o379_ch523_o216_nu526'],
//            ['no_ch1034_o379_ch523_o217_nu524','no_ch1034_o379_ch523_o217_nu525','no_ch1034_o379_ch523_o217_nu526'],

//            ['no_ch1034_o380_ch529_o213_nu530','no_ch1034_o380_ch529_o213_nu531','no_ch1034_o380_ch529_o213_nu532'],
            ['no_ch1034_o380_ch529_o214_nu530','no_ch1034_o380_ch529_o214_nu531','no_ch1034_o380_ch529_o214_nu532'],
            ['no_ch1034_o380_ch529_o215_nu530','no_ch1034_o380_ch529_o215_nu531','no_ch1034_o380_ch529_o215_nu532'],
//            ['no_ch1034_o380_ch529_o216_nu530','no_ch1034_o380_ch529_o216_nu531','no_ch1034_o380_ch529_o216_nu532'],
//            ['no_ch1034_o380_ch529_o217_nu530','no_ch1034_o380_ch529_o217_nu531','no_ch1034_o380_ch529_o217_nu532'],

//            ['no_ch1034_o381_ch535_o213_nu536','no_ch1034_o381_ch535_o213_nu537','no_ch1034_o381_ch535_o213_nu538'],
            ['no_ch1034_o381_ch535_o214_nu536','no_ch1034_o381_ch535_o214_nu537','no_ch1034_o381_ch535_o214_nu538'],
            ['no_ch1034_o381_ch535_o215_nu536','no_ch1034_o381_ch535_o215_nu537','no_ch1034_o381_ch535_o215_nu538'],
//            ['no_ch1034_o381_ch535_o216_nu536','no_ch1034_o381_ch535_o216_nu537','no_ch1034_o381_ch535_o216_nu538'],
//            ['no_ch1034_o381_ch535_o217_nu536','no_ch1034_o381_ch535_o217_nu537','no_ch1034_o381_ch535_o217_nu538'],

//            ['no_ch1034_o382_ch541_o213_nu542','no_ch1034_o382_ch541_o213_nu543','no_ch1034_o382_ch541_o213_nu544'],
//            ['no_ch1034_o382_ch541_o214_nu542','no_ch1034_o382_ch541_o214_nu543','no_ch1034_o382_ch541_o214_nu544'],
//            ['no_ch1034_o382_ch541_o215_nu542','no_ch1034_o382_ch541_o215_nu543','no_ch1034_o382_ch541_o215_nu544'],
//            ['no_ch1034_o382_ch541_o216_nu542','no_ch1034_o382_ch541_o216_nu543','no_ch1034_o382_ch541_o216_nu544'],
//            ['no_ch1034_o382_ch541_o217_nu542','no_ch1034_o382_ch541_o217_nu543','no_ch1034_o382_ch541_o217_nu544'],
            ['no_ch1034_o382_ch541_o218_nu542','no_ch1034_o382_ch541_o218_nu543','no_ch1034_o382_ch541_o218_nu544'],

//            ['no_ch1034_o383_ch547_o213_nu548','no_ch1034_o383_ch547_o213_nu549','no_ch1034_o383_ch547_o213_nu550'],
//            ['no_ch1034_o383_ch547_o214_nu548','no_ch1034_o383_ch547_o214_nu549','no_ch1034_o383_ch547_o214_nu550'],
//            ['no_ch1034_o383_ch547_o215_nu548','no_ch1034_o383_ch547_o215_nu549','no_ch1034_o383_ch547_o215_nu550'],
//            ['no_ch1034_o383_ch547_o216_nu548','no_ch1034_o383_ch547_o216_nu549','no_ch1034_o383_ch547_o216_nu550'],
//            ['no_ch1034_o383_ch547_o217_nu548','no_ch1034_o383_ch547_o217_nu549','no_ch1034_o383_ch547_o217_nu550'],
            ['no_ch1034_o383_ch547_o218_nu548','no_ch1034_o383_ch547_o218_nu549','no_ch1034_o383_ch547_o218_nu550'],

//            ['no_ch1034_o384_ch553_o213_nu554','no_ch1034_o384_ch553_o213_nu555','no_ch1034_o384_ch553_o213_nu556'],
//            ['no_ch1034_o384_ch553_o214_nu554','no_ch1034_o384_ch553_o214_nu555','no_ch1034_o384_ch553_o214_nu556'],
//            ['no_ch1034_o384_ch553_o215_nu554','no_ch1034_o384_ch553_o215_nu555','no_ch1034_o384_ch553_o215_nu556'],
//            ['no_ch1034_o384_ch553_o216_nu554','no_ch1034_o384_ch553_o216_nu555','no_ch1034_o384_ch553_o216_nu556'],
//            ['no_ch1034_o384_ch553_o217_nu554','no_ch1034_o384_ch553_o217_nu555','no_ch1034_o384_ch553_o217_nu556'],
            ['no_ch1034_o384_ch553_o218_nu554','no_ch1034_o384_ch553_o218_nu555','no_ch1034_o384_ch553_o218_nu556']
        ];
        $ktoe = 0.745;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12 as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2
        ];

        $table4 = [
//            'no_ch1034_o379_ch523_o213_nu527',
            'no_ch1034_o379_ch523_o214_nu527',
            'no_ch1034_o379_ch523_o215_nu527',
//            'no_ch1034_o379_ch523_o216_nu527',
//            'no_ch1034_o379_ch523_o217_nu527',

//            'no_ch1034_o380_ch529_o213_nu533',
            'no_ch1034_o380_ch529_o214_nu533',
            'no_ch1034_o380_ch529_o215_nu533',
//            'no_ch1034_o380_ch529_o216_nu533',
//            'no_ch1034_o380_ch529_o217_nu533',

//            'no_ch1034_o381_ch535_o213_nu539',
            'no_ch1034_o381_ch535_o214_nu539',
            'no_ch1034_o381_ch535_o215_nu539',
//            'no_ch1034_o381_ch535_o216_nu539',
//            'no_ch1034_o381_ch535_o217_nu539',

//            'no_ch1034_o382_ch541_o213_nu545',
//            'no_ch1034_o382_ch541_o214_nu545',
//            'no_ch1034_o382_ch541_o215_nu545',
//            'no_ch1034_o382_ch541_o216_nu545',
//            'no_ch1034_o382_ch541_o217_nu545',
            'no_ch1034_o382_ch541_o218_nu545',

//            'no_ch1034_o383_ch547_o213_nu551',
//            'no_ch1034_o383_ch547_o214_nu551',
//            'no_ch1034_o383_ch547_o215_nu551',
//            'no_ch1034_o383_ch547_o216_nu551',
//            'no_ch1034_o383_ch547_o217_nu551',
            'no_ch1034_o383_ch547_o218_nu551',

//            'no_ch1034_o384_ch553_o213_nu557',
//            'no_ch1034_o384_ch553_o214_nu557',
//            'no_ch1034_o384_ch553_o215_nu557',
//            'no_ch1034_o384_ch553_o216_nu557',
//            'no_ch1034_o384_ch553_o217_nu557',
            'no_ch1034_o384_ch553_o218_nu557',
        ];

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.8';
        $outputFile = 'sum918.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $startColumn = 'E';
        $objPHPExcel = Summary::sum($table1,$startColumn,13,$objPHPExcel,$mainObj);
        $startColumn = 'U';
        $objPHPExcel = Summary::average($table2, $startColumn, 13, $objPHPExcel, $mainObj);
        $startColumn = 'AL';
//        Summary::usageElectric();
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, 13,$objPHPExcel, $mainObj,$sumAmountSQL,$params,$ktoe);
        $startColumn = 'BB';
        $objPHPExcel = Summary::average($table4, $startColumn, 13, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

}
