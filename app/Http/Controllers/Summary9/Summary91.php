<?php

namespace App\Http\Controllers\Summary9;

use App\Main;
use App\Parameter;
use App\Setting;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary91 extends Controller
{

    public static function report911()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.1';
        $startRow = 13;
        $outputFile = 'sum911.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1023_o329_ch101_o68',
            'no_ch1023_o329_ch101_o69_ch102_o72',
            'no_ch1023_o329_ch101_o69_ch102_o73',
            'no_ch1023_o329_ch101_o69_ch102_o74',
            'no_ch1023_o329_ch101_o70',
            'no_ch1023_o329_ch101_o71',
        ];
        $startColumn = 'E';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1023_o329_ch101_o68_nu103',
            'no_ch1023_o329_ch101_o69_ch102_o72_nu107',
            'no_ch1023_o329_ch101_o69_ch102_o73_nu107',
            'no_ch1023_o329_ch101_o69_ch102_o74_nu107',
            'no_ch1023_o329_ch101_o70_nu103',
            'no_ch1023_o329_ch101_o71_nu103'
        ];

        $startColumn = 'U';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,9,10,11])->get();
        $factor1 = (float)$settings->where('code','tool_factor_1')->first()->value
            * (float)$settings->where('code','season_factor_1')->first()->value
            * (float)$settings->where('code','usage_factor_1')->first()->value;
        $factor2 = (float)$settings->where('code','tool_factor_2')->first()->value
            * (float)$settings->where('code','season_factor_2')->first()->value
            * (float)$settings->where('code','usage_factor_2')->first()->value;
        $factor3 = (float)$settings->where('code','tool_factor_3')->first()->value
            * (float)$settings->where('code','season_factor_3')->first()->value
            * (float)$settings->where('code','usage_factor_3')->first()->value;
        $factor4 = (float)$settings->where('code','tool_factor_4')->first()->value
            * (float)$settings->where('code','season_factor_4')->first()->value
            * (float)$settings->where('code','usage_factor_4')->first()->value;
        $factor5 = (float)$settings->where('code','tool_factor_5')->first()->value
            * (float)$settings->where('code','season_factor_5')->first()->value
            * (float)$settings->where('code','usage_factor_5')->first()->value;
        $factor6 = (float)$settings->where('code','tool_factor_6')->first()->value
            * (float)$settings->where('code','season_factor_6')->first()->value
            * (float)$settings->where('code','usage_factor_6')->first()->value;

        $electricPower = array();
        for ($i=0;$i<6;$i++)
        {
            $electricPower[$i] = (float)$settings->where('code','electric_power_'.($i+1))->first()->value;
        }

        $table3 = [
            ['no_ch1023_o329_ch101_o68_nu104','no_ch1023_o329_ch101_o68_nu105','no_ch1023_o329_ch101_o68_nu103',$factor1,$electricPower[0]],
            ['no_ch1023_o329_ch101_o69_ch102_o72_nu108','no_ch1023_o329_ch101_o69_ch102_o72_nu109','no_ch1023_o329_ch101_o69_ch102_o72_nu107',$factor2,$electricPower[1]],
            ['no_ch1023_o329_ch101_o69_ch102_o73_nu108','no_ch1023_o329_ch101_o69_ch102_o73_nu109','no_ch1023_o329_ch101_o69_ch102_o73_nu107',$factor3,$electricPower[2]],
            ['no_ch1023_o329_ch101_o69_ch102_o74_nu108','no_ch1023_o329_ch101_o69_ch102_o74_nu109','no_ch1023_o329_ch101_o69_ch102_o74_nu107',$factor4,$electricPower[3]],
            ['no_ch1023_o329_ch101_o70_nu104','no_ch1023_o329_ch101_o70_nu105','no_ch1023_o329_ch101_o70_nu103',$factor5,$electricPower[4]],
            ['no_ch1023_o329_ch101_o71_nu104','no_ch1023_o329_ch101_o71_nu105','no_ch1023_o329_ch101_o71_nu103',$factor6,$electricPower[5]]
        ];
        $startColumn = 'AL';
        $ktoe = Setting::where('code', 'E9')->first()->value;
        $week = Parameter::WEEK_PER_YEAR;

        // ระยะเวลาใช้งาน (ชั่วโมง / ปี) คำนวณจาก จำนวนหลอด*อัตราการใช้ (ชั่วโมง/วัน)*อัตราการใช้(วัน/สัปดาห์)*จำนวนอาทิตย์ต่อปี กะไว้ประมาณ 52
        $sumAmountSQL = " ( sum(IF(unique_key='param1',answer_numeric,0)) * sum(if(unique_key='param2', answer_numeric,0)) * sum(if(unique_key='param3',answer_numeric,0)) * {$week} ) 
        * (param4) * (param5) as sumAmount ";

        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2,
            'param4'=>3,
            'param5'=>4
        ];
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1023_o329_ch101_o68_nu106',
            'no_ch1023_o329_ch101_o69_ch102_o72_nu110',
            'no_ch1023_o329_ch101_o69_ch102_o73_nu110',
            'no_ch1023_o329_ch101_o69_ch102_o74_nu110',
            'no_ch1023_o329_ch101_o70_nu106',
            'no_ch1023_o329_ch101_o71_nu106',
        ];
        $table4Amount = [
            'no_ch1023_o329_ch101_o68_nu103',
            'no_ch1023_o329_ch101_o69_ch102_o72_nu107',
            'no_ch1023_o329_ch101_o69_ch102_o73_nu107',
            'no_ch1023_o329_ch101_o69_ch102_o74_nu107',
            'no_ch1023_o329_ch101_o70_nu103',
            'no_ch1023_o329_ch101_o71_nu103'
        ];
        $startColumn = 'BB';
        $objPHPExcel = Summary::averageLifetime($table4, $table4Amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

    // รวมในบ้านกับนอกบ้านเข้าด้วยกัน
    public static function report911Special()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'sum911special.xlsx';
        $inputSheet = 0;
        $outputFile = '1,2.หลอดไฟ.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/raw_excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheet($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndex($inputSheet);

        // จำนวนครัวเรือน
        $table1 = [
            ['no_ch1023_o329_ch101_o68', 'no_ch1023_o330_ch112_o68'],
            ['no_ch1023_o329_ch101_o69_ch102_o72','no_ch1023_o330_ch112_o69_ch113_o72'],
            ['no_ch1023_o329_ch101_o69_ch102_o73','no_ch1023_o330_ch112_o69_ch113_o73'],
            ['no_ch1023_o329_ch101_o69_ch102_o74','no_ch1023_o330_ch112_o69_ch113_o74'],
            ['no_ch1023_o329_ch101_o70','no_ch1023_o330_ch112_o70'],
            ['no_ch1023_o329_ch101_o71','no_ch1023_o330_ch112_o71']
        ];
        $startRow = 5;
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj, false, false,null, true);

        // ค่าเฉลี่ยจำนวนอุปกรณ์ กับ SE
        $table2 = [
            ['no_ch1023_o329_ch101_o68_nu103','no_ch1023_o330_ch112_o68_nu114'],
            ['no_ch1023_o329_ch101_o69_ch102_o72_nu107','no_ch1023_o330_ch112_o69_ch113_o72_nu118'],
            ['no_ch1023_o329_ch101_o69_ch102_o73_nu107','no_ch1023_o330_ch112_o69_ch113_o73_nu118'],
            ['no_ch1023_o329_ch101_o69_ch102_o74_nu107','no_ch1023_o330_ch112_o69_ch113_o74_nu118'],
            ['no_ch1023_o329_ch101_o70_nu103','no_ch1023_o330_ch112_o70_nu114'],
            ['no_ch1023_o329_ch101_o71_nu103','no_ch1023_o330_ch112_o71_nu114']
        ];
        $startColumn = 'D';
        $startRow = 21;
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        // ปริมาณการใช้งาน
        $table3UniqueKeys = [
            [
                'no_ch1023_o329_ch101_o68_nu104','no_ch1023_o329_ch101_o68_nu105','no_ch1023_o329_ch101_o68_nu103',0.06,
                'no_ch1023_o330_ch112_o68_nu115','no_ch1023_o330_ch112_o68_nu116','no_ch1023_o330_ch112_o68_nu114',0.060
            ],
            [
                'no_ch1023_o329_ch101_o69_ch102_o72_nu108','no_ch1023_o329_ch101_o69_ch102_o72_nu109','no_ch1023_o329_ch101_o69_ch102_o72_nu107',0.024,
                'no_ch1023_o330_ch112_o69_ch113_o72_nu119','no_ch1023_o330_ch112_o69_ch113_o72_nu120','no_ch1023_o330_ch112_o69_ch113_o72_nu118',0.024
            ],
            [
                'no_ch1023_o329_ch101_o69_ch102_o73_nu108','no_ch1023_o329_ch101_o69_ch102_o73_nu109','no_ch1023_o329_ch101_o69_ch102_o73_nu107',0.036,
                'no_ch1023_o330_ch112_o69_ch113_o73_nu119','no_ch1023_o330_ch112_o69_ch113_o73_nu120','no_ch1023_o330_ch112_o69_ch113_o73_nu118',0.036
            ],
            [
                'no_ch1023_o329_ch101_o69_ch102_o74_nu108','no_ch1023_o329_ch101_o69_ch102_o74_nu109','no_ch1023_o329_ch101_o69_ch102_o74_nu107',0.018,
                'no_ch1023_o330_ch112_o69_ch113_o74_nu119','no_ch1023_o330_ch112_o69_ch113_o74_nu120','no_ch1023_o330_ch112_o69_ch113_o74_nu118',0.018
            ],
            [
                'no_ch1023_o329_ch101_o70_nu104','no_ch1023_o329_ch101_o70_nu105','no_ch1023_o329_ch101_o70_nu103',0.018,
                'no_ch1023_o330_ch112_o70_nu115','no_ch1023_o330_ch112_o70_nu116','no_ch1023_o330_ch112_o70_nu114',0.018
            ],
            [
                'no_ch1023_o329_ch101_o71_nu104','no_ch1023_o329_ch101_o71_nu105','no_ch1023_o329_ch101_o71_nu103',0.010,
                'no_ch1023_o330_ch112_o71_nu115','no_ch1023_o330_ch112_o71_nu116','no_ch1023_o330_ch112_o71_nu114',0.010
            ]
        ];
        $startColumn = 'E';
        $startRow = 37;
        $ktoe = Parameter::$ktoe[Parameter::ELECTRIC];
        $week = Parameter::WEEK_PER_YEAR;
        $table3 = [];
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0))* sum(if(unique_key='param2', answer_numeric,0))* {$week})* (param4) * sum(if(unique_key='param3',1,0)) ";
        foreach ($table3UniqueKeys as $param){
            $finalSql = "";
            for ($i=0;$i<8;$i+=4){
                $finalSql .= $sumAmountSQL;
                $finalSql = str_replace("param1", $param[$i], $finalSql);
                $finalSql = str_replace("param2", $param[$i+1], $finalSql);
                $finalSql = str_replace("param3", $param[$i+2], $finalSql);
                $finalSql = str_replace("param4", $param[$i+3], $finalSql);

                $finalSql .= " + ";
            }
            $finalSql .= " 0 ";
            $table3[] = $finalSql;
        }
        $objPHPExcel = Summary::specialUsage($table3, $startColumn, $startRow, $objPHPExcel, $mainObj, $ktoe);

        $table4 = [
            ['no_ch1023_o329_ch101_o68_nu106','no_ch1023_o330_ch112_o68_nu117'],
            ['no_ch1023_o329_ch101_o69_ch102_o72_nu110','no_ch1023_o330_ch112_o69_ch113_o72_nu121'],
            ['no_ch1023_o329_ch101_o69_ch102_o73_nu110','no_ch1023_o330_ch112_o69_ch113_o73_nu121'],
            ['no_ch1023_o329_ch101_o69_ch102_o74_nu110','no_ch1023_o330_ch112_o69_ch113_o74_nu121'],
            ['no_ch1023_o329_ch101_o70_nu106','no_ch1023_o330_ch112_o70_nu117'],
            ['no_ch1023_o329_ch101_o71_nu106','no_ch1023_o330_ch112_o71_nu117'],
        ];
        $startColumn = 'D';
        $startRow = 53;
        $objPHPExcel = Summary::average($table4, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/raw_excel_output/'.$outputFile)));
    }

    public static function report918()
    {
        set_time_limit(3600);

        $table1 = [
            'no_ch1034_o379_ch523_o213',
            'no_ch1034_o379_ch523_o214',
            'no_ch1034_o379_ch523_o215',
            'no_ch1034_o379_ch523_o216',
            'no_ch1034_o379_ch523_o217',
            'no_ch1034_o380_ch529_o213',
            'no_ch1034_o380_ch529_o214',
            'no_ch1034_o380_ch529_o215',
            'no_ch1034_o380_ch529_o216',
            'no_ch1034_o380_ch529_o217',
            'no_ch1034_o381_ch535_o213',
            'no_ch1034_o381_ch535_o214',
            'no_ch1034_o381_ch535_o215',
            'no_ch1034_o381_ch535_o216',
            'no_ch1034_o381_ch535_o217',
            'no_ch1034_o382_ch541_o213',
            'no_ch1034_o382_ch541_o214',
            'no_ch1034_o382_ch541_o215',
            'no_ch1034_o382_ch541_o216',
            'no_ch1034_o382_ch541_o217',
            'no_ch1034_o382_ch541_o218',
            'no_ch1034_o383_ch547_o213',
            'no_ch1034_o383_ch547_o214',
            'no_ch1034_o383_ch547_o215',
            'no_ch1034_o383_ch547_o216',
            'no_ch1034_o383_ch547_o217',
            'no_ch1034_o383_ch547_o218',
            'no_ch1034_o384_ch553_o213',
            'no_ch1034_o384_ch553_o214',
            'no_ch1034_o384_ch553_o215',
            'no_ch1034_o384_ch553_o216',
            'no_ch1034_o384_ch553_o217',
            'no_ch1034_o384_ch553_o218',
        ];
        $table2 = [
            'no_ch1034_o379_ch523_o213_nu524',
            'no_ch1034_o379_ch523_o214_nu524',
            'no_ch1034_o379_ch523_o215_nu524',
            'no_ch1034_o379_ch523_o216_nu524',
            'no_ch1034_o379_ch523_o217_nu524',
            'no_ch1034_o380_ch529_o213_nu530',
            'no_ch1034_o380_ch529_o214_nu530',
            'no_ch1034_o380_ch529_o215_nu530',
            'no_ch1034_o380_ch529_o216_nu530',
            'no_ch1034_o380_ch529_o217_nu530',
            'no_ch1034_o381_ch535_o213_nu536',
            'no_ch1034_o381_ch535_o214_nu536',
            'no_ch1034_o381_ch535_o215_nu536',
            'no_ch1034_o381_ch535_o216_nu536',
            'no_ch1034_o381_ch535_o217_nu536',
            'no_ch1034_o382_ch541_o213_nu542',
            'no_ch1034_o382_ch541_o214_nu542',
            'no_ch1034_o382_ch541_o215_nu542',
            'no_ch1034_o382_ch541_o216_nu542',
            'no_ch1034_o382_ch541_o217_nu542',
            'no_ch1034_o382_ch541_o218_nu542',
            'no_ch1034_o383_ch547_o213_nu548',
            'no_ch1034_o383_ch547_o214_nu548',
            'no_ch1034_o383_ch547_o215_nu548',
            'no_ch1034_o383_ch547_o216_nu548',
            'no_ch1034_o383_ch547_o217_nu548',
            'no_ch1034_o383_ch547_o218_nu548',
            'no_ch1034_o384_ch553_o213_nu554',
            'no_ch1034_o384_ch553_o214_nu554',
            'no_ch1034_o384_ch553_o215_nu554',
            'no_ch1034_o384_ch553_o216_nu554',
            'no_ch1034_o384_ch553_o217_nu554',
            'no_ch1034_o384_ch553_o218_nu554',
        ];
        $table3 = [
            ['no_ch1034_o379_ch523_o213_nu524','no_ch1034_o379_ch523_o213_nu525','no_ch1034_o379_ch523_o213_nu526'],
            ['no_ch1034_o379_ch523_o214_nu524','no_ch1034_o379_ch523_o214_nu525','no_ch1034_o379_ch523_o214_nu526'],
            ['no_ch1034_o379_ch523_o215_nu524','no_ch1034_o379_ch523_o215_nu525','no_ch1034_o379_ch523_o215_nu526'],
            ['no_ch1034_o379_ch523_o216_nu524','no_ch1034_o379_ch523_o216_nu525','no_ch1034_o379_ch523_o216_nu526'],
            ['no_ch1034_o379_ch523_o217_nu524','no_ch1034_o379_ch523_o217_nu525','no_ch1034_o379_ch523_o217_nu526'],
            ['no_ch1034_o380_ch529_o213_nu530','no_ch1034_o380_ch529_o213_nu531','no_ch1034_o380_ch529_o213_nu532'],
            ['no_ch1034_o380_ch529_o214_nu530','no_ch1034_o380_ch529_o214_nu531','no_ch1034_o380_ch529_o214_nu532'],
            ['no_ch1034_o380_ch529_o215_nu530','no_ch1034_o380_ch529_o215_nu531','no_ch1034_o380_ch529_o215_nu532'],
            ['no_ch1034_o380_ch529_o216_nu530','no_ch1034_o380_ch529_o216_nu531','no_ch1034_o380_ch529_o216_nu532'],
            ['no_ch1034_o380_ch529_o217_nu530','no_ch1034_o380_ch529_o217_nu531','no_ch1034_o380_ch529_o217_nu532'],
            ['no_ch1034_o381_ch535_o213_nu536','no_ch1034_o381_ch535_o213_nu537','no_ch1034_o381_ch535_o213_nu538'],
            ['no_ch1034_o381_ch535_o214_nu536','no_ch1034_o381_ch535_o214_nu537','no_ch1034_o381_ch535_o214_nu538'],
            ['no_ch1034_o381_ch535_o215_nu536','no_ch1034_o381_ch535_o215_nu537','no_ch1034_o381_ch535_o215_nu538'],
            ['no_ch1034_o381_ch535_o216_nu536','no_ch1034_o381_ch535_o216_nu537','no_ch1034_o381_ch535_o216_nu538'],
            ['no_ch1034_o381_ch535_o217_nu536','no_ch1034_o381_ch535_o217_nu537','no_ch1034_o381_ch535_o217_nu538'],
            ['no_ch1034_o382_ch541_o213_nu542','no_ch1034_o382_ch541_o213_nu543','no_ch1034_o382_ch541_o213_nu544'],
            ['no_ch1034_o382_ch541_o214_nu542','no_ch1034_o382_ch541_o214_nu543','no_ch1034_o382_ch541_o214_nu544'],
            ['no_ch1034_o382_ch541_o215_nu542','no_ch1034_o382_ch541_o215_nu543','no_ch1034_o382_ch541_o215_nu544'],
            ['no_ch1034_o382_ch541_o216_nu542','no_ch1034_o382_ch541_o216_nu543','no_ch1034_o382_ch541_o216_nu544'],
            ['no_ch1034_o382_ch541_o217_nu542','no_ch1034_o382_ch541_o217_nu543','no_ch1034_o382_ch541_o217_nu544'],
            ['no_ch1034_o382_ch541_o218_nu542','no_ch1034_o382_ch541_o218_nu543','no_ch1034_o382_ch541_o218_nu544'],
            ['no_ch1034_o383_ch547_o213_nu548','no_ch1034_o383_ch547_o213_nu549','no_ch1034_o383_ch547_o213_nu550'],
            ['no_ch1034_o383_ch547_o214_nu548','no_ch1034_o383_ch547_o214_nu549','no_ch1034_o383_ch547_o214_nu550'],
            ['no_ch1034_o383_ch547_o215_nu548','no_ch1034_o383_ch547_o215_nu549','no_ch1034_o383_ch547_o215_nu550'],
            ['no_ch1034_o383_ch547_o216_nu548','no_ch1034_o383_ch547_o216_nu549','no_ch1034_o383_ch547_o216_nu550'],
            ['no_ch1034_o383_ch547_o217_nu548','no_ch1034_o383_ch547_o217_nu549','no_ch1034_o383_ch547_o217_nu550'],
            ['no_ch1034_o383_ch547_o218_nu548','no_ch1034_o383_ch547_o218_nu549','no_ch1034_o383_ch547_o218_nu550'],
            ['no_ch1034_o384_ch553_o213_nu554','no_ch1034_o384_ch553_o213_nu555','no_ch1034_o384_ch553_o213_nu556'],
            ['no_ch1034_o384_ch553_o214_nu554','no_ch1034_o384_ch553_o214_nu555','no_ch1034_o384_ch553_o214_nu556'],
            ['no_ch1034_o384_ch553_o215_nu554','no_ch1034_o384_ch553_o215_nu555','no_ch1034_o384_ch553_o215_nu556'],
            ['no_ch1034_o384_ch553_o216_nu554','no_ch1034_o384_ch553_o216_nu555','no_ch1034_o384_ch553_o216_nu556'],
            ['no_ch1034_o384_ch553_o217_nu554','no_ch1034_o384_ch553_o217_nu555','no_ch1034_o384_ch553_o217_nu556'],
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
            'no_ch1034_o379_ch523_o213_nu527',
            'no_ch1034_o379_ch523_o214_nu527',
            'no_ch1034_o379_ch523_o215_nu527',
            'no_ch1034_o379_ch523_o216_nu527',
            'no_ch1034_o379_ch523_o217_nu527',
            'no_ch1034_o380_ch529_o213_nu533',
            'no_ch1034_o380_ch529_o214_nu533',
            'no_ch1034_o380_ch529_o215_nu533',
            'no_ch1034_o380_ch529_o216_nu533',
            'no_ch1034_o380_ch529_o217_nu533',
            'no_ch1034_o381_ch535_o213_nu539',
            'no_ch1034_o381_ch535_o214_nu539',
            'no_ch1034_o381_ch535_o215_nu539',
            'no_ch1034_o381_ch535_o216_nu539',
            'no_ch1034_o381_ch535_o217_nu539',
            'no_ch1034_o382_ch541_o213_nu545',
            'no_ch1034_o382_ch541_o214_nu545',
            'no_ch1034_o382_ch541_o215_nu545',
            'no_ch1034_o382_ch541_o216_nu545',
            'no_ch1034_o382_ch541_o217_nu545',
            'no_ch1034_o382_ch541_o218_nu545',
            'no_ch1034_o383_ch547_o213_nu551',
            'no_ch1034_o383_ch547_o214_nu551',
            'no_ch1034_o383_ch547_o215_nu551',
            'no_ch1034_o383_ch547_o216_nu551',
            'no_ch1034_o383_ch547_o217_nu551',
            'no_ch1034_o383_ch547_o218_nu551',
            'no_ch1034_o384_ch553_o213_nu557',
            'no_ch1034_o384_ch553_o214_nu557',
            'no_ch1034_o384_ch553_o215_nu557',
            'no_ch1034_o384_ch553_o216_nu557',
            'no_ch1034_o384_ch553_o217_nu557',
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
