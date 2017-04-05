<?php

namespace App\Http\Controllers;

use App\Main;
use App\Parameter;
use App\Setting;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary9ByToolElectric extends Controller
{

    public static $inputFile = 'summary9_by_tool_electric.xlsx';
    public static $outputFile = 'sum912bytool.xlsx';

    public function reportTool($tool_number)
    {
        $tool_number = (int)$tool_number;
        $currentClass = new Summary9ByToolElectric();
        if(method_exists($currentClass,'report'.$tool_number)){
            list($outputFile, $outputName) = Summary9ByToolElectric::{"report".$tool_number}();

            return response()->download(storage_path('excel/'.$outputFile), $outputName);
        }
    }
    // หลอดไฟ
    public static function report1()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9_by_tool_electric.xlsx';
        $inputSheet = '1';
        $startRow = 5;
        $outputFile = 'sum911bytool.xlsx';

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
            //============ ทั้งหมด
            'no_ch1023_o329',
            [],
            'no_ch1023_o329_ch101_o69'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1023_o329_ch101_o68_nu103',
            'no_ch1023_o329_ch101_o69_ch102_o72_nu107',
            'no_ch1023_o329_ch101_o69_ch102_o73_nu107',
            'no_ch1023_o329_ch101_o69_ch102_o74_nu107',
            'no_ch1023_o329_ch101_o70_nu103',
            'no_ch1023_o329_ch101_o71_nu103',
            //============ ทั้งหมด
            [ 'no_ch1023_o329_ch101_o68_nu103',
                'no_ch1023_o329_ch101_o69_ch102_o72_nu107',
                'no_ch1023_o329_ch101_o69_ch102_o73_nu107',
                'no_ch1023_o329_ch101_o69_ch102_o74_nu107',
                'no_ch1023_o329_ch101_o70_nu103',
                'no_ch1023_o329_ch101_o71_nu103'],
            [],
            [    'no_ch1023_o329_ch101_o69_ch102_o72_nu107',
                'no_ch1023_o329_ch101_o69_ch102_o73_nu107',
                'no_ch1023_o329_ch101_o69_ch102_o74_nu107']
        ];

        $startColumn = 'O';
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
        $startColumn = 'AB';
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
            [   'no_ch1023_o329_ch101_o68_nu106',
                'no_ch1023_o329_ch101_o69_ch102_o72_nu110',
                'no_ch1023_o329_ch101_o69_ch102_o73_nu110',
                'no_ch1023_o329_ch101_o69_ch102_o74_nu110',
                'no_ch1023_o329_ch101_o70_nu106',
                'no_ch1023_o329_ch101_o71_nu106'],
            [],
            [            'no_ch1023_o329_ch101_o69_ch102_o72_nu110',
                'no_ch1023_o329_ch101_o69_ch102_o73_nu110',
                'no_ch1023_o329_ch101_o69_ch102_o74_nu110']
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'หลอดไฟ.xlsx');
    }
    // หม้อหุงข้าวไฟฟ้า
    public static function report2()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9_by_tool_electric.xlsx';
        $inputSheet = '2';
        $startRow = 5;
        $outputFile = 'sum912bytool.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        // หมวดประกอบอาหาร
        $amount = [
            'no_ch1024_o331_ch123_o75_ch124_o78',
            'no_ch1024_o331_ch123_o75_ch124_o79',
            'no_ch1024_o331_ch123_o75_ch124_o80',
            'no_ch1024_o331_ch123_o76_ch1011_o78',
            'no_ch1024_o331_ch123_o76_ch1011_o79',
            'no_ch1024_o331_ch123_o77_ch1011_o78',
            'no_ch1024_o331_ch123_o77_ch1011_o79',
            [            'no_ch1024_o331_ch123_o75_ch124_o78',
                'no_ch1024_o331_ch123_o75_ch124_o79',
                'no_ch1024_o331_ch123_o75_ch124_o80',
                'no_ch1024_o331_ch123_o76_ch1011_o78',
                'no_ch1024_o331_ch123_o76_ch1011_o79',
                'no_ch1024_o331_ch123_o77_ch1011_o78',
                'no_ch1024_o331_ch123_o77_ch1011_o79'],
            ['no_ch1024_o331_ch123_o75_ch124_o78',
                'no_ch1024_o331_ch123_o75_ch124_o79',
                'no_ch1024_o331_ch123_o75_ch124_o80'],
            ['no_ch1024_o331_ch123_o76_ch1011_o78',
                'no_ch1024_o331_ch123_o76_ch1011_o79'],
            ['no_ch1024_o331_ch123_o77_ch1011_o78',
                'no_ch1024_o331_ch123_o77_ch1011_o79']
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            'no_ch1024_o331_ch123_o75_ch124_o78_nu125',
            'no_ch1024_o331_ch123_o75_ch124_o79_nu125',
            'no_ch1024_o331_ch123_o75_ch124_o80_nu125',
            'no_ch1024_o331_ch123_o76_ch1011_o78_nu1012',
            'no_ch1024_o331_ch123_o76_ch1011_o79_nu1012',
            'no_ch1024_o331_ch123_o77_ch1011_o78_nu1012',
            'no_ch1024_o331_ch123_o77_ch1011_o79_nu1012',
            [            'no_ch1024_o331_ch123_o75_ch124_o78_nu125',
                'no_ch1024_o331_ch123_o75_ch124_o79_nu125',
                'no_ch1024_o331_ch123_o75_ch124_o80_nu125',
                'no_ch1024_o331_ch123_o76_ch1011_o78_nu1012',
                'no_ch1024_o331_ch123_o76_ch1011_o79_nu1012',
                'no_ch1024_o331_ch123_o77_ch1011_o78_nu1012',
                'no_ch1024_o331_ch123_o77_ch1011_o79_nu1012'],
            ['no_ch1024_o331_ch123_o75_ch124_o78_nu125',
                'no_ch1024_o331_ch123_o75_ch124_o79_nu125',
                'no_ch1024_o331_ch123_o75_ch124_o80_nu125'],
            ['no_ch1024_o331_ch123_o76_ch1011_o78_nu1012',
                'no_ch1024_o331_ch123_o76_ch1011_o79_nu1012'],
            ['no_ch1024_o331_ch123_o77_ch1011_o78_nu1012',
                'no_ch1024_o331_ch123_o77_ch1011_o79_nu1012']
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        for ($lastDigit=7;$lastDigit<=40;$lastDigit++){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        //usage and ktoe
        $usage = [
            ['no_ch1024_o331_ch123_o75_ch124_o78_nu125','no_ch1024_o331_ch123_o75_ch124_o78_nu126', 'no_ch1024_o331_ch123_o75_ch124_o78_nu127','no_ch1024_o331_ch123_o75_ch124_o78_nu128',$factors[7], $electricPower[7],'no_ch1024_o331_ch123_o75_ch124_o78_ra130',81],
            ['no_ch1024_o331_ch123_o75_ch124_o78_nu125','no_ch1024_o331_ch123_o75_ch124_o78_nu126', 'no_ch1024_o331_ch123_o75_ch124_o78_nu127','no_ch1024_o331_ch123_o75_ch124_o78_nu128',$factors[8], $electricPower[8],'no_ch1024_o331_ch123_o75_ch124_o78_ra130',82],
            ['no_ch1024_o331_ch123_o75_ch124_o79_nu125','no_ch1024_o331_ch123_o75_ch124_o79_nu126', 'no_ch1024_o331_ch123_o75_ch124_o79_nu127','no_ch1024_o331_ch123_o75_ch124_o79_nu128',$factors[9], $electricPower[9], 'no_ch1024_o331_ch123_o75_ch124_o79_ra130',81],
            ['no_ch1024_o331_ch123_o75_ch124_o79_nu125','no_ch1024_o331_ch123_o75_ch124_o79_nu126', 'no_ch1024_o331_ch123_o75_ch124_o79_nu127','no_ch1024_o331_ch123_o75_ch124_o79_nu128',$factors[10], $electricPower[10], 'no_ch1024_o331_ch123_o75_ch124_o79_ra130',82],
            ['no_ch1024_o331_ch123_o75_ch124_o80_nu125','no_ch1024_o331_ch123_o75_ch124_o80_nu126', 'no_ch1024_o331_ch123_o75_ch124_o80_nu127','no_ch1024_o331_ch123_o75_ch124_o80_nu128',$factors[11], $electricPower[11], 'no_ch1024_o331_ch123_o75_ch124_o80_ra130',81],
            ['no_ch1024_o331_ch123_o75_ch124_o80_nu125','no_ch1024_o331_ch123_o75_ch124_o80_nu126', 'no_ch1024_o331_ch123_o75_ch124_o80_nu127','no_ch1024_o331_ch123_o75_ch124_o80_nu128',$factors[12], $electricPower[12], 'no_ch1024_o331_ch123_o75_ch124_o80_ra130',82],
            ['no_ch1024_o331_ch123_o76_ch1011_o78_nu1012','no_ch1024_o331_ch123_o76_ch1011_o78_nu1013', 'no_ch1024_o331_ch123_o76_ch1011_o78_nu1014','no_ch1024_o331_ch123_o76_ch1011_o78_nu1015',$factors[13], $electricPower[13],'no_ch1024_o331_ch123_o76_ch1011_o78_ra1017',81],
            ['no_ch1024_o331_ch123_o76_ch1011_o78_nu1012','no_ch1024_o331_ch123_o76_ch1011_o78_nu1013', 'no_ch1024_o331_ch123_o76_ch1011_o78_nu1014','no_ch1024_o331_ch123_o76_ch1011_o78_nu1015',$factors[14], $electricPower[14],'no_ch1024_o331_ch123_o76_ch1011_o78_ra1017',82],
            ['no_ch1024_o331_ch123_o76_ch1011_o79_nu1012','no_ch1024_o331_ch123_o76_ch1011_o79_nu1013', 'no_ch1024_o331_ch123_o76_ch1011_o79_nu1014','no_ch1024_o331_ch123_o76_ch1011_o79_nu1015',$factors[15], $electricPower[15],'no_ch1024_o331_ch123_o76_ch1011_o79_ra1017',81],
            ['no_ch1024_o331_ch123_o76_ch1011_o79_nu1012','no_ch1024_o331_ch123_o76_ch1011_o79_nu1013', 'no_ch1024_o331_ch123_o76_ch1011_o79_nu1014','no_ch1024_o331_ch123_o76_ch1011_o79_nu1015',$factors[16], $electricPower[16],'no_ch1024_o331_ch123_o76_ch1011_o79_ra1017',82],
            ['no_ch1024_o331_ch123_o77_ch1011_o78_nu1012','no_ch1024_o331_ch123_o77_ch1011_o78_nu1013', 'no_ch1024_o331_ch123_o77_ch1011_o78_nu1014','no_ch1024_o331_ch123_o77_ch1011_o78_nu1015',$factors[17], $electricPower[17],'no_ch1024_o331_ch123_o77_ch1011_o78_ra1017',81],
            ['no_ch1024_o331_ch123_o77_ch1011_o78_nu1012','no_ch1024_o331_ch123_o77_ch1011_o78_nu1013', 'no_ch1024_o331_ch123_o77_ch1011_o78_nu1014','no_ch1024_o331_ch123_o77_ch1011_o78_nu1015',$factors[18], $electricPower[18],'no_ch1024_o331_ch123_o77_ch1011_o78_ra1017',82],
            ['no_ch1024_o331_ch123_o77_ch1011_o79_nu1012','no_ch1024_o331_ch123_o77_ch1011_o79_nu1013', 'no_ch1024_o331_ch123_o77_ch1011_o79_nu1014','no_ch1024_o331_ch123_o77_ch1011_o79_nu1015',$factors[19], $electricPower[19],'no_ch1024_o331_ch123_o77_ch1011_o79_ra1017',81],
            ['no_ch1024_o331_ch123_o77_ch1011_o79_nu1012','no_ch1024_o331_ch123_o77_ch1011_o79_nu1013', 'no_ch1024_o331_ch123_o77_ch1011_o79_nu1014','no_ch1024_o331_ch123_o77_ch1011_o79_nu1015',$factors[20], $electricPower[20],'no_ch1024_o331_ch123_o77_ch1011_o79_ra1017',82],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * sum(if(unique_key='param4',answer_numeric,0)
        * ({$week} / 60)) 
        * param5
        * param6 
        * (if(sum(if(unique_key='param7' and option_id=param8,1,0)) + if('param7'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวนหม้อ
            'param2'=>1, //อัตราการใช้ (นาที/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้งต่อวัน)
            'param4'=>3, //อัตราการใช้ (วัน/สัปดาห์)
            'param5'=>4, //factor
            'param6'=>5, //electric power
            'param7'=>6 , //ฉลากประหยัดไฟ
            'param8'=>7
        ];
        $startColumn = 'AB';
//        $startRow = 13;
        $objPHPExcel = Summary::usageElectric($usage, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        //Table4
        $table4 = [
            'no_ch1024_o331_ch123_o75_ch124_o78_nu129',
            'no_ch1024_o331_ch123_o75_ch124_o79_nu129',
            'no_ch1024_o331_ch123_o75_ch124_o80_nu129',
            'no_ch1024_o331_ch123_o76_ch1011_o78_nu1016',
            'no_ch1024_o331_ch123_o76_ch1011_o79_nu1016',
            'no_ch1024_o331_ch123_o77_ch1011_o78_nu1016',
            'no_ch1024_o331_ch123_o77_ch1011_o79_nu1016',
            [ 'no_ch1024_o331_ch123_o75_ch124_o78_nu129',
                'no_ch1024_o331_ch123_o75_ch124_o79_nu129',
                'no_ch1024_o331_ch123_o75_ch124_o80_nu129',
                'no_ch1024_o331_ch123_o76_ch1011_o78_nu1016',
                'no_ch1024_o331_ch123_o76_ch1011_o79_nu1016',
                'no_ch1024_o331_ch123_o77_ch1011_o78_nu1016',
                'no_ch1024_o331_ch123_o77_ch1011_o79_nu1016'],
            [ 'no_ch1024_o331_ch123_o75_ch124_o78_nu129',
                'no_ch1024_o331_ch123_o75_ch124_o79_nu129',
                'no_ch1024_o331_ch123_o75_ch124_o80_nu129'],
            [ 'no_ch1024_o331_ch123_o76_ch1011_o78_nu1016',
                'no_ch1024_o331_ch123_o76_ch1011_o79_nu1016'],
            [ 'no_ch1024_o331_ch123_o77_ch1011_o78_nu1016',
                'no_ch1024_o331_ch123_o77_ch1011_o79_nu1016']
        ];

        $startColumn = 'AM';
//        $startRow = 13;
        // สำหรับเครื่องใช้ไฟฟ้า
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'หม้อหุงข้าว.xlsx');
    }
    // เตาหุงต้มไฟฟ้า
    public static function report3()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9_by_tool_electric.xlsx';
        $inputSheet = '3';
        $startRow = 5;
        $outputFile = 'sum912bytool.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        // หมวดประกอบอาหาร
        $amount = [
            // เตาหุงต้มไฟฟ้า
            'no_ch1024_o332_ch132_o83',
            'no_ch1024_o332_ch132_o84',
            'no_ch1024_o332_ch132_o85',
            'no_ch1024_o332'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            // เตาหุงต้มไฟฟ้า
            'no_ch1024_o332_ch132_o83_nu133',
            'no_ch1024_o332_ch132_o84_nu133',
            'no_ch1024_o332_ch132_o85_nu133',
            [            'no_ch1024_o332_ch132_o83_nu133',
                'no_ch1024_o332_ch132_o84_nu133',
                'no_ch1024_o332_ch132_o85_nu133',]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        for ($lastDigit=7;$lastDigit<=40;$lastDigit++){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        //usage and ktoe
        $usage = [
            ['no_ch1024_o332_ch132_o83_nu133','no_ch1024_o332_ch132_o83_nu134', 'no_ch1024_o332_ch132_o83_nu135','no_ch1024_o332_ch132_o83_nu136',$factors[21],$electricPower[21],'',81],
            ['no_ch1024_o332_ch132_o84_nu133','no_ch1024_o332_ch132_o84_nu134', 'no_ch1024_o332_ch132_o84_nu135','no_ch1024_o332_ch132_o84_nu136',$factors[22],$electricPower[22],'',81],
            ['no_ch1024_o332_ch132_o85_nu133','no_ch1024_o332_ch132_o85_nu134', 'no_ch1024_o332_ch132_o85_nu135','no_ch1024_o332_ch132_o85_nu136',$factors[23],$electricPower[23],'no_ch1024_o332_ch132_o85_ra138',81],
            ['no_ch1024_o332_ch132_o85_nu133','no_ch1024_o332_ch132_o85_nu134', 'no_ch1024_o332_ch132_o85_nu135','no_ch1024_o332_ch132_o85_nu136',$factors[24],$electricPower[24],'no_ch1024_o332_ch132_o85_ra138',82],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * sum(if(unique_key='param4',answer_numeric,0)
        * ({$week} / 60)) 
        * param5
        * param6 
        * (if(sum(if(unique_key='param7' and option_id=param8,1,0)) + if('param7'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวนหม้อ
            'param2'=>1, //อัตราการใช้ (นาที/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้งต่อวัน)
            'param4'=>3, //อัตราการใช้ (วัน/สัปดาห์)
            'param5'=>4, //factor
            'param6'=>5, //electric power
            'param7'=>6,  //ฉลากประหยัดไฟ
            'param8'=>7
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        //Table4
        $table4 = [
            'no_ch1024_o332_ch132_o83_nu137',
            'no_ch1024_o332_ch132_o84_nu137',
            'no_ch1024_o332_ch132_o85_nu137',
            [            'no_ch1024_o332_ch132_o83_nu137',
                'no_ch1024_o332_ch132_o84_nu137',
                'no_ch1024_o332_ch132_o85_nu137',]
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'เตาหุงต้มไฟฟ้า.xlsx');
    }
// ไมโครเวฟ
    public static function report4()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9_by_tool_electric.xlsx';
        $inputSheet = '4';
        $startRow = 5;
        $outputFile = 'sum912bytool.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        // หมวดประกอบอาหาร
        $amount = [
            //ไมโครเวฟ
            'no_ch1024_o333',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            //ไมโครเวฟ
            'no_ch1024_o333_nu141',
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        for ($lastDigit=7;$lastDigit<=40;$lastDigit++){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        //usage and ktoe
        $usage = [
//ไมโครเวฟ
            ['no_ch1024_o333_nu141','no_ch1024_o333_nu142', 'no_ch1024_o333_nu143','no_ch1024_o333_nu144',$factors[25],$electricPower[25],'no_ch1024_o333_ra146',81],
            ['no_ch1024_o333_nu141','no_ch1024_o333_nu142', 'no_ch1024_o333_nu143','no_ch1024_o333_nu144',$factors[26],$electricPower[26],'no_ch1024_o333_ra146',82],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * sum(if(unique_key='param4',answer_numeric,0)
        * ({$week} / 60)) 
        * param5
        * param6 
        * (if(sum(if(unique_key='param7' and option_id=param8,1,0)) + if('param7'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวนหม้อ
            'param2'=>1, //อัตราการใช้ (นาที/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้งต่อวัน)
            'param4'=>3, //อัตราการใช้ (วัน/สัปดาห์)
            'param5'=>4, //factor
            'param6'=>5, //electric power
            'param7'=>6,  //ฉลากประหยัดไฟ
            'param8'=>7
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        //Table4
        $table4 = [
            // ไมโครเวฟ
            'no_ch1024_o333_nu145',
        ];

        $startColumn = 'AM';
        // สำหรับเครื่องใช้ไฟฟ้า
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'ไมโครเวฟ.xlsx');
    }
// เตาอบไฟฟ้า
    public static function report5()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '5';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        // หมวดประกอบอาหาร
        $amount = [
            //เตาอบไฟฟ้า
            'no_ch1024_o334'];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            //เตาอบไฟฟ้า
            'no_ch1024_o334_nu149',
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        for ($lastDigit=7;$lastDigit<=40;$lastDigit++){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        //usage and ktoe
        $usage = [
            //เตาอบไฟฟ้า
            ['no_ch1024_o334_nu149','no_ch1024_o334_nu150', 'no_ch1024_o334_nu151','no_ch1024_o334_nu152',$factors[27], $electricPower[27], 'no_ch1024_o334_ra154',81],
            ['no_ch1024_o334_nu149','no_ch1024_o334_nu150', 'no_ch1024_o334_nu151','no_ch1024_o334_nu152',$factors[28], $electricPower[28], 'no_ch1024_o334_ra154',82],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * sum(if(unique_key='param4',answer_numeric,0)
        * ({$week} / 60)) 
        * param5
        * param6 
        * (if(sum(if(unique_key='param7' and option_id=param8,1,0)) + if('param7'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวนหม้อ
            'param2'=>1, //อัตราการใช้ (นาที/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้งต่อวัน)
            'param4'=>3, //อัตราการใช้ (วัน/สัปดาห์)
            'param5'=>4, //factor
            'param6'=>5, //electric power
            'param7'=>6,  //ฉลากประหยัดไฟ
            'param8'=>7
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        //Table4
        $table4 = [
            'no_ch1024_o334_nu153',
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'เตาอบไฟฟ้า.xlsx');
    }
// กระติกน้ำร้อน
    public static function report6()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '6';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        // หมวดประกอบอาหาร
        $amount = [
            'no_ch1024_o335_ch156_o287',
            'no_ch1024_o335_ch156_o288',
            'no_ch1024_o335'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            'no_ch1024_o335_ch156_o287_nu157',
            'no_ch1024_o335_ch156_o288_nu157',
            [            'no_ch1024_o335_ch156_o287_nu157',
                'no_ch1024_o335_ch156_o288_nu157',]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        for ($lastDigit=7;$lastDigit<=40;$lastDigit++){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        //usage and ktoe
        $usage = [
            //กระติกน้ำร้อน
            ['no_ch1024_o335_ch156_o287_nu157','no_ch1024_o335_ch156_o287_nu158', 'no_ch1024_o335_ch156_o287_nu159','no_ch1024_o335_ch156_o287_nu160',$factors[29], $electricPower[29], 'no_ch1024_o335_ch156_o287_ra162',81],
            ['no_ch1024_o335_ch156_o287_nu157','no_ch1024_o335_ch156_o287_nu158', 'no_ch1024_o335_ch156_o287_nu159','no_ch1024_o335_ch156_o287_nu160',$factors[30], $electricPower[30], 'no_ch1024_o335_ch156_o287_ra162',82],
            ['no_ch1024_o335_ch156_o288_nu157','no_ch1024_o335_ch156_o288_nu158', 'no_ch1024_o335_ch156_o288_nu159','no_ch1024_o335_ch156_o288_nu160',$factors[31], $electricPower[31], 'no_ch1024_o335_ch156_o288_ra162',81],
            ['no_ch1024_o335_ch156_o288_nu157','no_ch1024_o335_ch156_o288_nu158', 'no_ch1024_o335_ch156_o288_nu159','no_ch1024_o335_ch156_o288_nu160',$factors[32], $electricPower[32], 'no_ch1024_o335_ch156_o288_ra162',82],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * sum(if(unique_key='param4',answer_numeric,0)
        * ({$week} / 60)) 
        * param5
        * param6 
        * (if(sum(if(unique_key='param7' and option_id=param8,1,0)) + if('param7'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวนหม้อ
            'param2'=>1, //อัตราการใช้ (นาที/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้งต่อวัน)
            'param4'=>3, //อัตราการใช้ (วัน/สัปดาห์)
            'param5'=>4, //factor
            'param6'=>5, //electric power
            'param7'=>6,  //ฉลากประหยัดไฟ
            'param8'=>7
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        //Table4
        $table4 = [
            //กระติกน้ำร้อน
            'no_ch1024_o335_ch156_o287_nu161',
            'no_ch1024_o335_ch156_o288_nu161',
            [
                'no_ch1024_o335_ch156_o287_nu161',
                'no_ch1024_o335_ch156_o288_nu161',
            ]
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'กระติกน้ำร้อน.xlsx');
    }
    // กาต้มน้ำไฟฟ้า
    public static function report7()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '7';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        // หมวดประกอบอาหาร
        $amount = [
            'no_ch1024_o336',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            'no_ch1024_o336_nu166',
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        for ($lastDigit=7;$lastDigit<=40;$lastDigit++){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        //usage and ktoe
        $usage = [
            ['no_ch1024_o336_nu166','no_ch1024_o336_nu167', 'no_ch1024_o336_nu168','no_ch1024_o336_nu169',$factors[33], $electricPower[33], 'no_ch1024_o336_ra171', 81],
            ['no_ch1024_o336_nu166','no_ch1024_o336_nu167', 'no_ch1024_o336_nu168','no_ch1024_o336_nu169',$factors[34], $electricPower[34], 'no_ch1024_o336_ra171', 82],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * sum(if(unique_key='param4',answer_numeric,0)
        * ({$week} / 60)) 
        * param5
        * param6 
        * (if(sum(if(unique_key='param7' and option_id=param8,1,0)) + if('param7'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวนหม้อ
            'param2'=>1, //อัตราการใช้ (นาที/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้งต่อวัน)
            'param4'=>3, //อัตราการใช้ (วัน/สัปดาห์)
            'param5'=>4, //factor
            'param6'=>5, //electric power
            'param7'=>6,  //ฉลากประหยัดไฟ
            'param8'=>7
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        //Table4
        $table4 = [
            'no_ch1024_o336_nu170',
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'กาต้มน้ำไฟฟ้า.xlsx');
    }
    // กระทะไฟฟ้า
    public static function report8()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '8';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $amount = [
            'no_ch1024_o337',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            'no_ch1024_o337_nu174',
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        for ($lastDigit=7;$lastDigit<=40;$lastDigit++){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        //usage and ktoe
        $usage = [
            ['no_ch1024_o337_nu174','no_ch1024_o337_nu175', 'no_ch1024_o337_nu176','no_ch1024_o337_nu177',$factors[35], $electricPower[35], 'no_ch1024_o337_ra179',81],
            ['no_ch1024_o337_nu174','no_ch1024_o337_nu175', 'no_ch1024_o337_nu176','no_ch1024_o337_nu177',$factors[36], $electricPower[36], 'no_ch1024_o337_ra179',82],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * sum(if(unique_key='param4',answer_numeric,0)
        * ({$week} / 60)) 
        * param5
        * param6 
        * (if(sum(if(unique_key='param7' and option_id=param8,1,0)) + if('param7'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวนหม้อ
            'param2'=>1, //อัตราการใช้ (นาที/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้งต่อวัน)
            'param4'=>3, //อัตราการใช้ (วัน/สัปดาห์)
            'param5'=>4, //factor
            'param6'=>5, //electric power
            'param7'=>6,  //ฉลากประหยัดไฟ
            'param8'=>7
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        //Table4
        $table4 = [
            'no_ch1024_o337_nu178',
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'กระทะไฟฟ้า.xlsx');
    }
    // เครื่องปิ้งขนมปัง
    public static function report9()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '9';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $amount = [
            'no_ch1024_o338',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            'no_ch1024_o338_nu182',
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        for ($lastDigit=7;$lastDigit<=40;$lastDigit++){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        //usage and ktoe
        $usage = [
            ['no_ch1024_o338_nu182','no_ch1024_o338_nu183', 'no_ch1024_o338_nu184','no_ch1024_o338_nu185',$factors[37], $electricPower[37], '',81],
            ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * sum(if(unique_key='param4',answer_numeric,0)
        * ({$week} / 60)) 
        * param5
        * param6 
        * (if(sum(if(unique_key='param7' and option_id=param8,1,0)) + if('param7'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวนหม้อ
            'param2'=>1, //อัตราการใช้ (นาที/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้งต่อวัน)
            'param4'=>3, //อัตราการใช้ (วัน/สัปดาห์)
            'param5'=>4, //factor
            'param6'=>5, //electric power
            'param7'=>6,  //ฉลากประหยัดไฟ
            'param8'=>7
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        //Table4
        $table4 = [
            'no_ch1024_o338_nu186',
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'เครื่องปิ้งขนมปัง.xlsx');
    }
    // เครื่องทำแซนวิช
    public static function report10()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '10';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $amount = [
            'no_ch1024_o339',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            'no_ch1024_o339_nu189',
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        for ($lastDigit=7;$lastDigit<=40;$lastDigit++){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        //usage and ktoe
        $usage = [
            ['no_ch1024_o339_nu189','no_ch1024_o339_nu190', 'no_ch1024_o339_nu191','no_ch1024_o339_nu192',$factors[38], $electricPower[38], '',81],
            ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * sum(if(unique_key='param4',answer_numeric,0)
        * ({$week} / 60)) 
        * param5
        * param6 
        * (if(sum(if(unique_key='param7' and option_id=param8,1,0)) + if('param7'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวนหม้อ
            'param2'=>1, //อัตราการใช้ (นาที/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้งต่อวัน)
            'param4'=>3, //อัตราการใช้ (วัน/สัปดาห์)
            'param5'=>4, //factor
            'param6'=>5, //electric power
            'param7'=>6,  //ฉลากประหยัดไฟ
            'param8'=>7
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        //Table4
        $table4 = [
            'no_ch1024_o339_nu193',
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'เครื่องทำแซนวิช.xlsx');
    }
    // เตาบาร์บีคิวไฟฟ้า
    public static function report11()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '11';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $amount = [
            'no_ch1024_o340'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            'no_ch1024_o340_nu196'
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        for ($lastDigit=7;$lastDigit<=40;$lastDigit++){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        //usage and ktoe
        $usage = [
            ['no_ch1024_o340_nu196','no_ch1024_o340_nu197', 'no_ch1024_o340_nu198','no_ch1024_o340_nu199',$factors[39], $electricPower[39], '', 81]
            ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * sum(if(unique_key='param4',answer_numeric,0)
        * ({$week} / 60)) 
        * param5
        * param6 
        * (if(sum(if(unique_key='param7' and option_id=param8,1,0)) + if('param7'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวนหม้อ
            'param2'=>1, //อัตราการใช้ (นาที/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้งต่อวัน)
            'param4'=>3, //อัตราการใช้ (วัน/สัปดาห์)
            'param5'=>4, //factor
            'param6'=>5, //electric power
            'param7'=>6,  //ฉลากประหยัดไฟ
            'param8'=>7
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        //Table4
        $table4 = [
            'no_ch1024_o340_nu200'
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'เตาบาร์บีคิวไฟฟ้า.xlsx');
    }
    // โทรทัศน์
    public static function report12()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '12';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1027_o347_ch240_o104_ch241_o108',
            'no_ch1027_o347_ch240_o104_ch241_o109',
            'no_ch1027_o347_ch240_o104_ch241_o110',
            'no_ch1027_o347_ch240_o104_ch241_o111',
            'no_ch1027_o347_ch240_o104_ch241_o112',
            'no_ch1027_o347_ch240_o104_ch241_o113',
            'no_ch1027_o347_ch240_o104_ch241_o114',

            'no_ch1027_o347_ch240_o105_ch248_o115',
            'no_ch1027_o347_ch240_o105_ch248_o116',
            'no_ch1027_o347_ch240_o105_ch248_o117',
            'no_ch1027_o347_ch240_o105_ch248_o118',
            'no_ch1027_o347_ch240_o105_ch248_o119',

            'no_ch1027_o347_ch240_o106_ch254_o108',
            'no_ch1027_o347_ch240_o106_ch254_o109',
            'no_ch1027_o347_ch240_o106_ch254_o110',
            'no_ch1027_o347_ch240_o106_ch254_o111',
            'no_ch1027_o347_ch240_o106_ch254_o112',
            'no_ch1027_o347_ch240_o106_ch254_o113',
            'no_ch1027_o347_ch240_o106_ch254_o114',
            'no_ch1027_o347_ch240_o106_ch254_o115',
            'no_ch1027_o347_ch240_o106_ch254_o116',
            'no_ch1027_o347_ch240_o106_ch254_o117',
            'no_ch1027_o347_ch240_o106_ch254_o118',
            'no_ch1027_o347_ch240_o106_ch254_o119',

            'no_ch1027_o347_ch240_o107_ch260_o108',
            'no_ch1027_o347_ch240_o107_ch260_o109',
            'no_ch1027_o347_ch240_o107_ch260_o110',
            'no_ch1027_o347_ch240_o107_ch260_o111',
            'no_ch1027_o347_ch240_o107_ch260_o112',
            'no_ch1027_o347_ch240_o107_ch260_o113',
            'no_ch1027_o347_ch240_o107_ch260_o114',
            'no_ch1027_o347_ch240_o107_ch260_o115',
            'no_ch1027_o347_ch240_o107_ch260_o116',
            'no_ch1027_o347_ch240_o107_ch260_o117',
            'no_ch1027_o347_ch240_o107_ch260_o118',
            'no_ch1027_o347_ch240_o107_ch260_o119',
            // รวม
            'no_ch1027_o347',

            'no_ch1027_o347_ch240_o104',
            'no_ch1027_o347_ch240_o105',
            'no_ch1027_o347_ch240_o106',
            'no_ch1027_o347_ch240_o107'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1027_o347_ch240_o104_ch241_o108_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o109_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o110_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o111_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o112_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o113_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o114_nu242',

            'no_ch1027_o347_ch240_o105_ch248_o115_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o116_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o117_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o118_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o119_nu249',

            'no_ch1027_o347_ch240_o106_ch254_o108_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o109_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o110_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o111_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o112_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o113_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o114_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o115_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o116_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o117_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o118_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o119_nu255',

            'no_ch1027_o347_ch240_o107_ch260_o108_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o109_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o110_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o111_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o112_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o113_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o114_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o115_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o116_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o117_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o118_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o119_nu261',
            [
                'no_ch1027_o347_ch240_o104_ch241_o108_nu242',
                'no_ch1027_o347_ch240_o104_ch241_o109_nu242',
                'no_ch1027_o347_ch240_o104_ch241_o110_nu242',
                'no_ch1027_o347_ch240_o104_ch241_o111_nu242',
                'no_ch1027_o347_ch240_o104_ch241_o112_nu242',
                'no_ch1027_o347_ch240_o104_ch241_o113_nu242',
                'no_ch1027_o347_ch240_o104_ch241_o114_nu242',
                'no_ch1027_o347_ch240_o105_ch248_o115_nu249',
                'no_ch1027_o347_ch240_o105_ch248_o116_nu249',
                'no_ch1027_o347_ch240_o105_ch248_o117_nu249',
                'no_ch1027_o347_ch240_o105_ch248_o118_nu249',
                'no_ch1027_o347_ch240_o105_ch248_o119_nu249',
                'no_ch1027_o347_ch240_o106_ch254_o108_nu255',
                'no_ch1027_o347_ch240_o106_ch254_o109_nu255',
                'no_ch1027_o347_ch240_o106_ch254_o110_nu255',
                'no_ch1027_o347_ch240_o106_ch254_o111_nu255',
                'no_ch1027_o347_ch240_o106_ch254_o112_nu255',
                'no_ch1027_o347_ch240_o106_ch254_o113_nu255',
                'no_ch1027_o347_ch240_o106_ch254_o114_nu255',
                'no_ch1027_o347_ch240_o106_ch254_o115_nu255',
                'no_ch1027_o347_ch240_o106_ch254_o116_nu255',
                'no_ch1027_o347_ch240_o106_ch254_o117_nu255',
                'no_ch1027_o347_ch240_o106_ch254_o118_nu255',
                'no_ch1027_o347_ch240_o106_ch254_o119_nu255',
                'no_ch1027_o347_ch240_o107_ch260_o108_nu261',
                'no_ch1027_o347_ch240_o107_ch260_o109_nu261',
                'no_ch1027_o347_ch240_o107_ch260_o110_nu261',
                'no_ch1027_o347_ch240_o107_ch260_o111_nu261',
                'no_ch1027_o347_ch240_o107_ch260_o112_nu261',
                'no_ch1027_o347_ch240_o107_ch260_o113_nu261',
                'no_ch1027_o347_ch240_o107_ch260_o114_nu261',
                'no_ch1027_o347_ch240_o107_ch260_o115_nu261',
                'no_ch1027_o347_ch240_o107_ch260_o116_nu261',
                'no_ch1027_o347_ch240_o107_ch260_o117_nu261',
                'no_ch1027_o347_ch240_o107_ch260_o118_nu261',
                'no_ch1027_o347_ch240_o107_ch260_o119_nu261'
            ],

            ['no_ch1027_o347_ch240_o104_ch241_o108_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o109_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o110_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o111_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o112_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o113_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o114_nu242'],

            ['no_ch1027_o347_ch240_o105_ch248_o115_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o116_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o117_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o118_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o119_nu249'],

            ['no_ch1027_o347_ch240_o106_ch254_o108_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o109_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o110_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o111_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o112_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o113_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o114_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o115_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o116_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o117_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o118_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o119_nu255'],

            ['no_ch1027_o347_ch240_o107_ch260_o108_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o109_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o110_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o111_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o112_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o113_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o114_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o115_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o116_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o117_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o118_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o119_nu261']
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 40;
        $endLastDigit = 134;
        // โทรทัศน์ เพราะมีที่มีเบอร์ 5 กับไม่มีเบอร์ 5
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        //table 3
        $table3 = [
            ['no_ch1027_o347_ch240_o104_ch241_o108_nu242','no_ch1027_o347_ch240_o104_ch241_o108_nu243','no_ch1027_o347_ch240_o104_ch241_o108_nu244',$factors[40],$electricPower[40],'no_ch1027_o347_ch240_o104_ch241_o108_ra246',81],
            ['no_ch1027_o347_ch240_o104_ch241_o108_nu242','no_ch1027_o347_ch240_o104_ch241_o108_nu243','no_ch1027_o347_ch240_o104_ch241_o108_nu244',$factors[41],$electricPower[41],'no_ch1027_o347_ch240_o104_ch241_o108_ra246',82],
            ['no_ch1027_o347_ch240_o104_ch241_o109_nu242','no_ch1027_o347_ch240_o104_ch241_o109_nu243','no_ch1027_o347_ch240_o104_ch241_o109_nu244',$factors[42],$electricPower[42],'no_ch1027_o347_ch240_o104_ch241_o109_ra246',81],
            ['no_ch1027_o347_ch240_o104_ch241_o109_nu242','no_ch1027_o347_ch240_o104_ch241_o109_nu243','no_ch1027_o347_ch240_o104_ch241_o109_nu244',$factors[43],$electricPower[43],'no_ch1027_o347_ch240_o104_ch241_o109_ra246',82],
            ['no_ch1027_o347_ch240_o104_ch241_o110_nu242','no_ch1027_o347_ch240_o104_ch241_o110_nu243','no_ch1027_o347_ch240_o104_ch241_o110_nu244',$factors[44],$electricPower[44],'no_ch1027_o347_ch240_o104_ch241_o110_ra246',81],
            ['no_ch1027_o347_ch240_o104_ch241_o110_nu242','no_ch1027_o347_ch240_o104_ch241_o110_nu243','no_ch1027_o347_ch240_o104_ch241_o110_nu244',$factors[45],$electricPower[45],'no_ch1027_o347_ch240_o104_ch241_o110_ra246',82],
            ['no_ch1027_o347_ch240_o104_ch241_o111_nu242','no_ch1027_o347_ch240_o104_ch241_o111_nu243','no_ch1027_o347_ch240_o104_ch241_o111_nu244',$factors[46],$electricPower[46],'no_ch1027_o347_ch240_o104_ch241_o111_ra246',81],
            ['no_ch1027_o347_ch240_o104_ch241_o111_nu242','no_ch1027_o347_ch240_o104_ch241_o111_nu243','no_ch1027_o347_ch240_o104_ch241_o111_nu244',$factors[47],$electricPower[47],'no_ch1027_o347_ch240_o104_ch241_o111_ra246',82],
            ['no_ch1027_o347_ch240_o104_ch241_o112_nu242','no_ch1027_o347_ch240_o104_ch241_o112_nu243','no_ch1027_o347_ch240_o104_ch241_o112_nu244',$factors[48],$electricPower[48],'no_ch1027_o347_ch240_o104_ch241_o112_ra246',81],
            ['no_ch1027_o347_ch240_o104_ch241_o112_nu242','no_ch1027_o347_ch240_o104_ch241_o112_nu243','no_ch1027_o347_ch240_o104_ch241_o112_nu244',$factors[49],$electricPower[49],'no_ch1027_o347_ch240_o104_ch241_o112_ra246',82],
            ['no_ch1027_o347_ch240_o104_ch241_o113_nu242','no_ch1027_o347_ch240_o104_ch241_o113_nu243','no_ch1027_o347_ch240_o104_ch241_o113_nu244',$factors[50],$electricPower[50],'no_ch1027_o347_ch240_o104_ch241_o113_ra246',81],
            ['no_ch1027_o347_ch240_o104_ch241_o113_nu242','no_ch1027_o347_ch240_o104_ch241_o113_nu243','no_ch1027_o347_ch240_o104_ch241_o113_nu244',$factors[51],$electricPower[51],'no_ch1027_o347_ch240_o104_ch241_o113_ra246',82],
            ['no_ch1027_o347_ch240_o104_ch241_o114_nu242','no_ch1027_o347_ch240_o104_ch241_o114_nu243','no_ch1027_o347_ch240_o104_ch241_o114_nu244',$factors[52],$electricPower[52],'no_ch1027_o347_ch240_o104_ch241_o114_ra246',81],
            ['no_ch1027_o347_ch240_o104_ch241_o114_nu242','no_ch1027_o347_ch240_o104_ch241_o114_nu243','no_ch1027_o347_ch240_o104_ch241_o114_nu244',$factors[53],$electricPower[53],'no_ch1027_o347_ch240_o104_ch241_o114_ra246',82],

            ['no_ch1027_o347_ch240_o105_ch248_o115_nu249','no_ch1027_o347_ch240_o105_ch248_o115_nu250','no_ch1027_o347_ch240_o105_ch248_o115_nu251',$factors[54],$electricPower[54],'no_ch1027_o347_ch240_o105_ch248_o115_ra253',81],
            ['no_ch1027_o347_ch240_o105_ch248_o115_nu249','no_ch1027_o347_ch240_o105_ch248_o115_nu250','no_ch1027_o347_ch240_o105_ch248_o115_nu251',$factors[55],$electricPower[55],'no_ch1027_o347_ch240_o105_ch248_o115_ra253',82],
            ['no_ch1027_o347_ch240_o105_ch248_o116_nu249','no_ch1027_o347_ch240_o105_ch248_o116_nu250','no_ch1027_o347_ch240_o105_ch248_o116_nu251',$factors[56],$electricPower[56],'no_ch1027_o347_ch240_o105_ch248_o116_ra253',81],
            ['no_ch1027_o347_ch240_o105_ch248_o116_nu249','no_ch1027_o347_ch240_o105_ch248_o116_nu250','no_ch1027_o347_ch240_o105_ch248_o116_nu251',$factors[57],$electricPower[57],'no_ch1027_o347_ch240_o105_ch248_o116_ra253',82],
            ['no_ch1027_o347_ch240_o105_ch248_o117_nu249','no_ch1027_o347_ch240_o105_ch248_o117_nu250','no_ch1027_o347_ch240_o105_ch248_o117_nu251',$factors[58],$electricPower[58],'no_ch1027_o347_ch240_o105_ch248_o117_ra253',81],
            ['no_ch1027_o347_ch240_o105_ch248_o117_nu249','no_ch1027_o347_ch240_o105_ch248_o117_nu250','no_ch1027_o347_ch240_o105_ch248_o117_nu251',$factors[59],$electricPower[59],'no_ch1027_o347_ch240_o105_ch248_o117_ra253',82],
            ['no_ch1027_o347_ch240_o105_ch248_o118_nu249','no_ch1027_o347_ch240_o105_ch248_o118_nu250','no_ch1027_o347_ch240_o105_ch248_o118_nu251',$factors[60],$electricPower[60],'no_ch1027_o347_ch240_o105_ch248_o118_ra253',81],
            ['no_ch1027_o347_ch240_o105_ch248_o118_nu249','no_ch1027_o347_ch240_o105_ch248_o118_nu250','no_ch1027_o347_ch240_o105_ch248_o118_nu251',$factors[61],$electricPower[61],'no_ch1027_o347_ch240_o105_ch248_o118_ra253',82],
            ['no_ch1027_o347_ch240_o105_ch248_o119_nu249','no_ch1027_o347_ch240_o105_ch248_o119_nu250','no_ch1027_o347_ch240_o105_ch248_o119_nu251',$factors[62],$electricPower[62],'no_ch1027_o347_ch240_o105_ch248_o119_ra253',81],
            ['no_ch1027_o347_ch240_o105_ch248_o119_nu249','no_ch1027_o347_ch240_o105_ch248_o119_nu250','no_ch1027_o347_ch240_o105_ch248_o119_nu251',$factors[63],$electricPower[63],'no_ch1027_o347_ch240_o105_ch248_o119_ra253',82],

            ['no_ch1027_o347_ch240_o106_ch254_o108_nu255','no_ch1027_o347_ch240_o106_ch254_o108_nu256','no_ch1027_o347_ch240_o106_ch254_o108_nu257',$factors[64],$electricPower[64],'no_ch1027_o347_ch240_o106_ch254_o108_ra259',81],
            ['no_ch1027_o347_ch240_o106_ch254_o108_nu255','no_ch1027_o347_ch240_o106_ch254_o108_nu256','no_ch1027_o347_ch240_o106_ch254_o108_nu257',$factors[65],$electricPower[65],'no_ch1027_o347_ch240_o106_ch254_o108_ra259',82],
            ['no_ch1027_o347_ch240_o106_ch254_o109_nu255','no_ch1027_o347_ch240_o106_ch254_o109_nu256','no_ch1027_o347_ch240_o106_ch254_o109_nu257',$factors[66],$electricPower[66],'no_ch1027_o347_ch240_o106_ch254_o109_ra259',81],
            ['no_ch1027_o347_ch240_o106_ch254_o109_nu255','no_ch1027_o347_ch240_o106_ch254_o109_nu256','no_ch1027_o347_ch240_o106_ch254_o109_nu257',$factors[67],$electricPower[67],'no_ch1027_o347_ch240_o106_ch254_o109_ra259',82],
            ['no_ch1027_o347_ch240_o106_ch254_o110_nu255','no_ch1027_o347_ch240_o106_ch254_o110_nu256','no_ch1027_o347_ch240_o106_ch254_o110_nu257',$factors[68],$electricPower[68],'no_ch1027_o347_ch240_o106_ch254_o110_ra259',81],
            ['no_ch1027_o347_ch240_o106_ch254_o110_nu255','no_ch1027_o347_ch240_o106_ch254_o110_nu256','no_ch1027_o347_ch240_o106_ch254_o110_nu257',$factors[68],$electricPower[68],'no_ch1027_o347_ch240_o106_ch254_o110_ra259',82],
            ['no_ch1027_o347_ch240_o106_ch254_o111_nu255','no_ch1027_o347_ch240_o106_ch254_o111_nu256','no_ch1027_o347_ch240_o106_ch254_o111_nu257',$factors[70],$electricPower[70],'no_ch1027_o347_ch240_o106_ch254_o111_ra259',81],
            ['no_ch1027_o347_ch240_o106_ch254_o111_nu255','no_ch1027_o347_ch240_o106_ch254_o111_nu256','no_ch1027_o347_ch240_o106_ch254_o111_nu257',$factors[71],$electricPower[71],'no_ch1027_o347_ch240_o106_ch254_o111_ra259',82],
            ['no_ch1027_o347_ch240_o106_ch254_o112_nu255','no_ch1027_o347_ch240_o106_ch254_o112_nu256','no_ch1027_o347_ch240_o106_ch254_o112_nu257',$factors[72],$electricPower[72],'no_ch1027_o347_ch240_o106_ch254_o112_ra259',81],
            ['no_ch1027_o347_ch240_o106_ch254_o112_nu255','no_ch1027_o347_ch240_o106_ch254_o112_nu256','no_ch1027_o347_ch240_o106_ch254_o112_nu257',$factors[73],$electricPower[73],'no_ch1027_o347_ch240_o106_ch254_o112_ra259',82],
            ['no_ch1027_o347_ch240_o106_ch254_o113_nu255','no_ch1027_o347_ch240_o106_ch254_o113_nu256','no_ch1027_o347_ch240_o106_ch254_o113_nu257',$factors[74],$electricPower[74],'no_ch1027_o347_ch240_o106_ch254_o113_ra259',81],
            ['no_ch1027_o347_ch240_o106_ch254_o113_nu255','no_ch1027_o347_ch240_o106_ch254_o113_nu256','no_ch1027_o347_ch240_o106_ch254_o113_nu257',$factors[75],$electricPower[75],'no_ch1027_o347_ch240_o106_ch254_o113_ra259',82],
            ['no_ch1027_o347_ch240_o106_ch254_o114_nu255','no_ch1027_o347_ch240_o106_ch254_o114_nu256','no_ch1027_o347_ch240_o106_ch254_o114_nu257',$factors[76],$electricPower[76],'no_ch1027_o347_ch240_o106_ch254_o114_ra259',81],
            ['no_ch1027_o347_ch240_o106_ch254_o114_nu255','no_ch1027_o347_ch240_o106_ch254_o114_nu256','no_ch1027_o347_ch240_o106_ch254_o114_nu257',$factors[77],$electricPower[77],'no_ch1027_o347_ch240_o106_ch254_o114_ra259',82],
            ['no_ch1027_o347_ch240_o106_ch254_o115_nu255','no_ch1027_o347_ch240_o106_ch254_o115_nu256','no_ch1027_o347_ch240_o106_ch254_o115_nu257',$factors[78],$electricPower[78],'no_ch1027_o347_ch240_o106_ch254_o115_ra259',81],
            ['no_ch1027_o347_ch240_o106_ch254_o115_nu255','no_ch1027_o347_ch240_o106_ch254_o115_nu256','no_ch1027_o347_ch240_o106_ch254_o115_nu257',$factors[79],$electricPower[79],'no_ch1027_o347_ch240_o106_ch254_o115_ra259',82],
            ['no_ch1027_o347_ch240_o106_ch254_o116_nu255','no_ch1027_o347_ch240_o106_ch254_o116_nu256','no_ch1027_o347_ch240_o106_ch254_o116_nu257',$factors[80],$electricPower[80],'no_ch1027_o347_ch240_o106_ch254_o116_ra259',81],
            ['no_ch1027_o347_ch240_o106_ch254_o116_nu255','no_ch1027_o347_ch240_o106_ch254_o116_nu256','no_ch1027_o347_ch240_o106_ch254_o116_nu257',$factors[81],$electricPower[81],'no_ch1027_o347_ch240_o106_ch254_o116_ra259',82],
            ['no_ch1027_o347_ch240_o106_ch254_o117_nu255','no_ch1027_o347_ch240_o106_ch254_o117_nu256','no_ch1027_o347_ch240_o106_ch254_o117_nu257',$factors[82],$electricPower[82],'no_ch1027_o347_ch240_o106_ch254_o117_ra259',81],
            ['no_ch1027_o347_ch240_o106_ch254_o117_nu255','no_ch1027_o347_ch240_o106_ch254_o117_nu256','no_ch1027_o347_ch240_o106_ch254_o117_nu257',$factors[83],$electricPower[83],'no_ch1027_o347_ch240_o106_ch254_o117_ra259',82],
            ['no_ch1027_o347_ch240_o106_ch254_o118_nu255','no_ch1027_o347_ch240_o106_ch254_o118_nu256','no_ch1027_o347_ch240_o106_ch254_o118_nu257',$factors[84],$electricPower[84],'no_ch1027_o347_ch240_o106_ch254_o118_ra259',81],
            ['no_ch1027_o347_ch240_o106_ch254_o118_nu255','no_ch1027_o347_ch240_o106_ch254_o118_nu256','no_ch1027_o347_ch240_o106_ch254_o118_nu257',$factors[85],$electricPower[85],'no_ch1027_o347_ch240_o106_ch254_o118_ra259',82],
            ['no_ch1027_o347_ch240_o106_ch254_o119_nu255','no_ch1027_o347_ch240_o106_ch254_o119_nu256','no_ch1027_o347_ch240_o106_ch254_o119_nu257',$factors[86],$electricPower[86],'no_ch1027_o347_ch240_o106_ch254_o119_ra259',81],
            ['no_ch1027_o347_ch240_o106_ch254_o119_nu255','no_ch1027_o347_ch240_o106_ch254_o119_nu256','no_ch1027_o347_ch240_o106_ch254_o119_nu257',$factors[87],$electricPower[87],'no_ch1027_o347_ch240_o106_ch254_o119_ra259',82],

            ['no_ch1027_o347_ch240_o107_ch260_o108_nu261','no_ch1027_o347_ch240_o107_ch260_o108_nu262','no_ch1027_o347_ch240_o107_ch260_o108_nu261',$factors[88],$electricPower[88],'no_ch1027_o347_ch240_o107_ch260_o108_ra265',81],
            ['no_ch1027_o347_ch240_o107_ch260_o108_nu261','no_ch1027_o347_ch240_o107_ch260_o108_nu262','no_ch1027_o347_ch240_o107_ch260_o108_nu261',$factors[89],$electricPower[89],'no_ch1027_o347_ch240_o107_ch260_o108_ra265',82],
            ['no_ch1027_o347_ch240_o107_ch260_o109_nu261','no_ch1027_o347_ch240_o107_ch260_o109_nu262','no_ch1027_o347_ch240_o107_ch260_o109_nu261',$factors[90],$electricPower[90],'no_ch1027_o347_ch240_o107_ch260_o109_ra265',81],
            ['no_ch1027_o347_ch240_o107_ch260_o109_nu261','no_ch1027_o347_ch240_o107_ch260_o109_nu262','no_ch1027_o347_ch240_o107_ch260_o109_nu261',$factors[91],$electricPower[91],'no_ch1027_o347_ch240_o107_ch260_o109_ra265',82],
            ['no_ch1027_o347_ch240_o107_ch260_o110_nu261','no_ch1027_o347_ch240_o107_ch260_o110_nu262','no_ch1027_o347_ch240_o107_ch260_o110_nu261',$factors[92],$electricPower[92],'no_ch1027_o347_ch240_o107_ch260_o110_ra265',81],
            ['no_ch1027_o347_ch240_o107_ch260_o110_nu261','no_ch1027_o347_ch240_o107_ch260_o110_nu262','no_ch1027_o347_ch240_o107_ch260_o110_nu261',$factors[93],$electricPower[93],'no_ch1027_o347_ch240_o107_ch260_o110_ra265',82],
            ['no_ch1027_o347_ch240_o107_ch260_o111_nu261','no_ch1027_o347_ch240_o107_ch260_o111_nu262','no_ch1027_o347_ch240_o107_ch260_o111_nu263',$factors[94],$electricPower[94],'no_ch1027_o347_ch240_o107_ch260_o111_ra265',81],
            ['no_ch1027_o347_ch240_o107_ch260_o111_nu261','no_ch1027_o347_ch240_o107_ch260_o111_nu262','no_ch1027_o347_ch240_o107_ch260_o111_nu263',$factors[95],$electricPower[95],'no_ch1027_o347_ch240_o107_ch260_o111_ra265',82],
            ['no_ch1027_o347_ch240_o107_ch260_o112_nu261','no_ch1027_o347_ch240_o107_ch260_o112_nu262','no_ch1027_o347_ch240_o107_ch260_o112_nu263',$factors[96],$electricPower[96],'no_ch1027_o347_ch240_o107_ch260_o112_ra265',81],
            ['no_ch1027_o347_ch240_o107_ch260_o112_nu261','no_ch1027_o347_ch240_o107_ch260_o112_nu262','no_ch1027_o347_ch240_o107_ch260_o112_nu263',$factors[97],$electricPower[97],'no_ch1027_o347_ch240_o107_ch260_o112_ra265',82],
            ['no_ch1027_o347_ch240_o107_ch260_o113_nu261','no_ch1027_o347_ch240_o107_ch260_o113_nu262','no_ch1027_o347_ch240_o107_ch260_o113_nu263',$factors[98],$electricPower[98],'no_ch1027_o347_ch240_o107_ch260_o113_ra265',81],
            ['no_ch1027_o347_ch240_o107_ch260_o113_nu261','no_ch1027_o347_ch240_o107_ch260_o113_nu262','no_ch1027_o347_ch240_o107_ch260_o113_nu263',$factors[99],$electricPower[99],'no_ch1027_o347_ch240_o107_ch260_o113_ra265',82],
            ['no_ch1027_o347_ch240_o107_ch260_o114_nu261','no_ch1027_o347_ch240_o107_ch260_o114_nu262','no_ch1027_o347_ch240_o107_ch260_o114_nu263',$factors[100],$electricPower[100],'no_ch1027_o347_ch240_o107_ch260_o114_ra265',81],
            ['no_ch1027_o347_ch240_o107_ch260_o114_nu261','no_ch1027_o347_ch240_o107_ch260_o114_nu262','no_ch1027_o347_ch240_o107_ch260_o114_nu263',$factors[101],$electricPower[101],'no_ch1027_o347_ch240_o107_ch260_o114_ra265',82],
            ['no_ch1027_o347_ch240_o107_ch260_o115_nu261','no_ch1027_o347_ch240_o107_ch260_o115_nu262','no_ch1027_o347_ch240_o107_ch260_o115_nu263',$factors[102],$electricPower[102],'no_ch1027_o347_ch240_o107_ch260_o115_ra265',81],
            ['no_ch1027_o347_ch240_o107_ch260_o115_nu261','no_ch1027_o347_ch240_o107_ch260_o115_nu262','no_ch1027_o347_ch240_o107_ch260_o115_nu263',$factors[103],$electricPower[103],'no_ch1027_o347_ch240_o107_ch260_o115_ra265',82],
            ['no_ch1027_o347_ch240_o107_ch260_o116_nu261','no_ch1027_o347_ch240_o107_ch260_o116_nu262','no_ch1027_o347_ch240_o107_ch260_o116_nu263',$factors[104],$electricPower[104],'no_ch1027_o347_ch240_o107_ch260_o116_ra265',81],
            ['no_ch1027_o347_ch240_o107_ch260_o116_nu261','no_ch1027_o347_ch240_o107_ch260_o116_nu262','no_ch1027_o347_ch240_o107_ch260_o116_nu263',$factors[105],$electricPower[105],'no_ch1027_o347_ch240_o107_ch260_o116_ra265',82],
            ['no_ch1027_o347_ch240_o107_ch260_o117_nu261','no_ch1027_o347_ch240_o107_ch260_o117_nu262','no_ch1027_o347_ch240_o107_ch260_o117_nu263',$factors[106],$electricPower[106],'no_ch1027_o347_ch240_o107_ch260_o117_ra265',81],
            ['no_ch1027_o347_ch240_o107_ch260_o117_nu261','no_ch1027_o347_ch240_o107_ch260_o117_nu262','no_ch1027_o347_ch240_o107_ch260_o117_nu263',$factors[107],$electricPower[107],'no_ch1027_o347_ch240_o107_ch260_o117_ra265',82],
            ['no_ch1027_o347_ch240_o107_ch260_o118_nu261','no_ch1027_o347_ch240_o107_ch260_o118_nu262','no_ch1027_o347_ch240_o107_ch260_o118_nu263',$factors[108],$electricPower[108],'no_ch1027_o347_ch240_o107_ch260_o118_ra265',81],
            ['no_ch1027_o347_ch240_o107_ch260_o118_nu261','no_ch1027_o347_ch240_o107_ch260_o118_nu262','no_ch1027_o347_ch240_o107_ch260_o118_nu263',$factors[109],$electricPower[109],'no_ch1027_o347_ch240_o107_ch260_o118_ra265',82],
            ['no_ch1027_o347_ch240_o107_ch260_o119_nu261','no_ch1027_o347_ch240_o107_ch260_o119_nu262','no_ch1027_o347_ch240_o107_ch260_o119_nu263',$factors[110],$electricPower[110],'no_ch1027_o347_ch240_o107_ch260_o119_ra265',81],
            ['no_ch1027_o347_ch240_o107_ch260_o119_nu261','no_ch1027_o347_ch240_o107_ch260_o119_nu262','no_ch1027_o347_ch240_o107_ch260_o119_nu263',$factors[111],$electricPower[111],'no_ch1027_o347_ch240_o107_ch260_o119_ra265',82],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * {$week}
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=param7,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // Table 4
        $table4 = [
            'no_ch1027_o347_ch240_o104_ch241_o108_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o109_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o110_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o111_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o112_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o113_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o114_nu245',
            'no_ch1027_o347_ch240_o105_ch248_o115_nu252',
            'no_ch1027_o347_ch240_o105_ch248_o116_nu252',
            'no_ch1027_o347_ch240_o105_ch248_o117_nu252',
            'no_ch1027_o347_ch240_o105_ch248_o118_nu252',
            'no_ch1027_o347_ch240_o105_ch248_o119_nu252',
            'no_ch1027_o347_ch240_o106_ch254_o108_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o109_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o110_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o111_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o112_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o113_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o114_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o115_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o116_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o117_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o118_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o119_nu258',
            'no_ch1027_o347_ch240_o107_ch260_o108_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o109_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o110_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o111_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o112_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o113_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o114_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o115_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o116_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o117_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o118_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o119_nu264',
            [
                'no_ch1027_o347_ch240_o104_ch241_o108_nu245',
                'no_ch1027_o347_ch240_o104_ch241_o109_nu245',
                'no_ch1027_o347_ch240_o104_ch241_o110_nu245',
                'no_ch1027_o347_ch240_o104_ch241_o111_nu245',
                'no_ch1027_o347_ch240_o104_ch241_o112_nu245',
                'no_ch1027_o347_ch240_o104_ch241_o113_nu245',
                'no_ch1027_o347_ch240_o104_ch241_o114_nu245',
                'no_ch1027_o347_ch240_o105_ch248_o115_nu252',
                'no_ch1027_o347_ch240_o105_ch248_o116_nu252',
                'no_ch1027_o347_ch240_o105_ch248_o117_nu252',
                'no_ch1027_o347_ch240_o105_ch248_o118_nu252',
                'no_ch1027_o347_ch240_o105_ch248_o119_nu252',
                'no_ch1027_o347_ch240_o106_ch254_o108_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o109_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o110_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o111_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o112_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o113_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o114_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o115_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o116_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o117_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o118_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o119_nu258',
                'no_ch1027_o347_ch240_o107_ch260_o108_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o109_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o110_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o111_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o112_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o113_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o114_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o115_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o116_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o117_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o118_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o119_nu264',
            ],
            [
                'no_ch1027_o347_ch240_o104_ch241_o108_nu245',
                'no_ch1027_o347_ch240_o104_ch241_o109_nu245',
                'no_ch1027_o347_ch240_o104_ch241_o110_nu245',
                'no_ch1027_o347_ch240_o104_ch241_o111_nu245',
                'no_ch1027_o347_ch240_o104_ch241_o112_nu245',
                'no_ch1027_o347_ch240_o104_ch241_o113_nu245',
                'no_ch1027_o347_ch240_o104_ch241_o114_nu245',
            ],
            [
                'no_ch1027_o347_ch240_o105_ch248_o115_nu252',
                'no_ch1027_o347_ch240_o105_ch248_o116_nu252',
                'no_ch1027_o347_ch240_o105_ch248_o117_nu252',
                'no_ch1027_o347_ch240_o105_ch248_o118_nu252',
                'no_ch1027_o347_ch240_o105_ch248_o119_nu252',
            ],
            [
                'no_ch1027_o347_ch240_o106_ch254_o108_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o109_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o110_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o111_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o112_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o113_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o114_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o115_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o116_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o117_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o118_nu258',
                'no_ch1027_o347_ch240_o106_ch254_o119_nu258',
            ],
            [
                'no_ch1027_o347_ch240_o107_ch260_o108_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o109_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o110_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o111_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o112_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o113_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o114_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o115_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o116_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o117_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o118_nu264',
                'no_ch1027_o347_ch240_o107_ch260_o119_nu264',
            ]
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'โทรทัศน์.xlsx');
    }
    // เครื่องเล่นแผ่น
    public static function report13()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '13';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1027_o348_ch267_o122',
            'no_ch1027_o348_ch267_o123',
            'no_ch1027_o348',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1027_o348_ch267_o122_nu268',
            'no_ch1027_o348_ch267_o123_nu268',
            ['no_ch1027_o348_ch267_o122_nu268',
                'no_ch1027_o348_ch267_o123_nu268']
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 40;
        $endLastDigit = 134;
        // โทรทัศน์ เพราะมีที่มีเบอร์ 5 กับไม่มีเบอร์ 5
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        //table 3
        $table3 = [
            ['no_ch1027_o348_ch267_o122_nu268','no_ch1027_o348_ch267_o122_nu269','no_ch1027_o348_ch267_o122_nu270',$factors[112],$electricPower[112],'',81],
            ['no_ch1027_o348_ch267_o123_nu268','no_ch1027_o348_ch267_o123_nu269','no_ch1027_o348_ch267_o123_nu270',$factors[113],$electricPower[113],'',81],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * {$week}
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=param7,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // Table 4
        $table4 = [
            'no_ch1027_o348_ch267_o122_nu271',
            'no_ch1027_o348_ch267_o123_nu271',
            ['no_ch1027_o348_ch267_o122_nu271',
                'no_ch1027_o348_ch267_o123_nu271']
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'เครื่องเล่นแผ่น.xlsx');
    }
    // มินิคอมโป
    public static function report14()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '14';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1027_o349'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1027_o349_nu274',
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 40;
        $endLastDigit = 134;
        // โทรทัศน์ เพราะมีที่มีเบอร์ 5 กับไม่มีเบอร์ 5
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        //table 3
        $table3 = [
            ['no_ch1027_o349_nu274','no_ch1027_o349_nu275','no_ch1027_o349_nu276',$factors[115],$electricPower[115],'',81],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * {$week}
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=param7,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // Table 4
        $table4 = [
            'no_ch1027_o349_nu277',
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'มินิคอมโป.xlsx');
    }
    // ชุดโฮมเธียเตอร์
    public static function report15()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '15';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1027_o350',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1027_o350_nu280'
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 40;
        $endLastDigit = 134;
        // โทรทัศน์ เพราะมีที่มีเบอร์ 5 กับไม่มีเบอร์ 5
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        //table 3
        $table3 = [
            ['no_ch1027_o350_nu280','no_ch1027_o350_nu281','no_ch1027_o350_nu282',$factors[116],$electricPower[116],'',81]
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * {$week}
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=param7,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // Table 4
        $table4 = [
            'no_ch1027_o350_nu283',
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, 'ชุดโฮมเธียเตอร์.xlsx');
    }
    // เครื่องเล่นวิทยุพกพา
    public static function report16()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '16';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'เครื่องเล่นวิทยุพกพา.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1027_o351',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1027_o351_nu286',
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 40;
        $endLastDigit = 134;
        // โทรทัศน์ เพราะมีที่มีเบอร์ 5 กับไม่มีเบอร์ 5
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        //table 3
        $table3 = [
            ['no_ch1027_o351_nu286','no_ch1027_o351_nu287','no_ch1027_o351_nu288',$factors[117],$electricPower[117],'',81],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * {$week}
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=param7,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // Table 4
        $table4 = [
            'no_ch1027_o351_nu289',
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // คอมพิวเตอร์
    public static function report17()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '17';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'คอมพิวเตอร์.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1027_o352_ch291_o128_ch293_o134',
            'no_ch1027_o352_ch291_o128_ch293_o135',
            'no_ch1027_o352_ch291_o128_ch293_o136',
            'no_ch1027_o352_ch291_o128_ch293_o137',
            'no_ch1027_o352_ch291_o128_ch293_o138',
            'no_ch1027_o352_ch291_o129_ch298_o132',
            'no_ch1027_o352_ch291_o129_ch298_o133',
            'no_ch1027_o352_ch291_o129_ch298_o134',
            'no_ch1027_o352',
            'no_ch1027_o352_ch291_o128',
            'no_ch1027_o352_ch291_o129'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1027_o352_ch291_o128_ch293_o134_nu294',
            'no_ch1027_o352_ch291_o128_ch293_o135_nu294',
            'no_ch1027_o352_ch291_o128_ch293_o136_nu294',
            'no_ch1027_o352_ch291_o128_ch293_o137_nu294',
            'no_ch1027_o352_ch291_o128_ch293_o138_nu294',
            'no_ch1027_o352_ch291_o129_ch298_o132_nu299',
            'no_ch1027_o352_ch291_o129_ch298_o133_nu299',
            'no_ch1027_o352_ch291_o129_ch298_o134_nu299',
            [            'no_ch1027_o352_ch291_o128_ch293_o134_nu294',
                'no_ch1027_o352_ch291_o128_ch293_o135_nu294',
                'no_ch1027_o352_ch291_o128_ch293_o136_nu294',
                'no_ch1027_o352_ch291_o128_ch293_o137_nu294',
                'no_ch1027_o352_ch291_o128_ch293_o138_nu294',
                'no_ch1027_o352_ch291_o129_ch298_o132_nu299',
                'no_ch1027_o352_ch291_o129_ch298_o133_nu299',
                'no_ch1027_o352_ch291_o129_ch298_o134_nu299'],
            [            'no_ch1027_o352_ch291_o128_ch293_o134_nu294',
                'no_ch1027_o352_ch291_o128_ch293_o135_nu294',
                'no_ch1027_o352_ch291_o128_ch293_o136_nu294',
                'no_ch1027_o352_ch291_o128_ch293_o137_nu294',
                'no_ch1027_o352_ch291_o128_ch293_o138_nu294'],
            ['no_ch1027_o352_ch291_o129_ch298_o132_nu299',
                'no_ch1027_o352_ch291_o129_ch298_o133_nu299',
                'no_ch1027_o352_ch291_o129_ch298_o134_nu299']
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 40;
        $endLastDigit = 134;
        // โทรทัศน์ เพราะมีที่มีเบอร์ 5 กับไม่มีเบอร์ 5
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        //table 3
        $table3 = [
            ['no_ch1027_o352_ch291_o128_ch293_o134_nu294','no_ch1027_o352_ch291_o128_ch293_o134_nu295','no_ch1027_o352_ch291_o128_ch293_o134_nu296',$factors[118],$electricPower[118],'',81],
            ['no_ch1027_o352_ch291_o128_ch293_o135_nu294','no_ch1027_o352_ch291_o128_ch293_o135_nu295','no_ch1027_o352_ch291_o128_ch293_o135_nu296',$factors[119],$electricPower[119],'',81],
            ['no_ch1027_o352_ch291_o128_ch293_o136_nu294','no_ch1027_o352_ch291_o128_ch293_o136_nu295','no_ch1027_o352_ch291_o128_ch293_o136_nu296',$factors[120],$electricPower[120],'',81],
            ['no_ch1027_o352_ch291_o128_ch293_o137_nu294','no_ch1027_o352_ch291_o128_ch293_o137_nu295','no_ch1027_o352_ch291_o128_ch293_o137_nu296',$factors[121],$electricPower[121],'',81],
            ['no_ch1027_o352_ch291_o128_ch293_o138_nu294','no_ch1027_o352_ch291_o128_ch293_o138_nu295','no_ch1027_o352_ch291_o128_ch293_o138_nu296',$factors[122],$electricPower[122],'',81],
            ['no_ch1027_o352_ch291_o129_ch298_o132_nu299','no_ch1027_o352_ch291_o129_ch298_o132_nu300','no_ch1027_o352_ch291_o129_ch298_o132_nu301',$factors[123],$electricPower[123],'',81],
            ['no_ch1027_o352_ch291_o129_ch298_o133_nu299','no_ch1027_o352_ch291_o129_ch298_o133_nu300','no_ch1027_o352_ch291_o129_ch298_o133_nu301',$factors[124],$electricPower[124],'',81],
            ['no_ch1027_o352_ch291_o129_ch298_o134_nu299','no_ch1027_o352_ch291_o129_ch298_o134_nu300','no_ch1027_o352_ch291_o129_ch298_o134_nu301',$factors[125],$electricPower[125],'',81],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * {$week}
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=param7,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // Table 4
        $table4 = [
            'no_ch1027_o352_ch291_o128_ch293_o134_nu297',
            'no_ch1027_o352_ch291_o128_ch293_o135_nu297',
            'no_ch1027_o352_ch291_o128_ch293_o136_nu297',
            'no_ch1027_o352_ch291_o128_ch293_o137_nu297',
            'no_ch1027_o352_ch291_o128_ch293_o138_nu297',
            'no_ch1027_o352_ch291_o129_ch298_o132_nu302',
            'no_ch1027_o352_ch291_o129_ch298_o133_nu302',
            'no_ch1027_o352_ch291_o129_ch298_o134_nu302',
            [            'no_ch1027_o352_ch291_o128_ch293_o134_nu297',
                'no_ch1027_o352_ch291_o128_ch293_o135_nu297',
                'no_ch1027_o352_ch291_o128_ch293_o136_nu297',
                'no_ch1027_o352_ch291_o128_ch293_o137_nu297',
                'no_ch1027_o352_ch291_o128_ch293_o138_nu297',
                'no_ch1027_o352_ch291_o129_ch298_o132_nu302',
                'no_ch1027_o352_ch291_o129_ch298_o133_nu302',
                'no_ch1027_o352_ch291_o129_ch298_o134_nu302'],
            [            'no_ch1027_o352_ch291_o128_ch293_o134_nu297',
                'no_ch1027_o352_ch291_o128_ch293_o135_nu297',
                'no_ch1027_o352_ch291_o128_ch293_o136_nu297',
                'no_ch1027_o352_ch291_o128_ch293_o137_nu297',
                'no_ch1027_o352_ch291_o128_ch293_o138_nu297'],
            [
                'no_ch1027_o352_ch291_o129_ch298_o132_nu302',
                'no_ch1027_o352_ch291_o129_ch298_o133_nu302',
                'no_ch1027_o352_ch291_o129_ch298_o134_nu302',]
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เครื่องพิมพ์
    public static function report18()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '18';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'เครื่องพิมพ์.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1027_o353_ch304_o139',
            'no_ch1027_o353_ch304_o140',
            'no_ch1027_o353_ch304_o141',
            'no_ch1027_o353'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1027_o353_ch304_o139_nu305',
            'no_ch1027_o353_ch304_o140_nu305',
            'no_ch1027_o353_ch304_o141_nu305',
            ['no_ch1027_o353_ch304_o139_nu305',
                'no_ch1027_o353_ch304_o140_nu305',
                'no_ch1027_o353_ch304_o141_nu305']
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 40;
        $endLastDigit = 134;
        // โทรทัศน์ เพราะมีที่มีเบอร์ 5 กับไม่มีเบอร์ 5
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        //table 3
        $table3 = [
            ['no_ch1027_o353_ch304_o139_nu305','no_ch1027_o353_ch304_o139_nu306','no_ch1027_o353_ch304_o139_nu307',$factors[126],$electricPower[126],'',81],
            ['no_ch1027_o353_ch304_o140_nu305','no_ch1027_o353_ch304_o140_nu306','no_ch1027_o353_ch304_o140_nu307',$factors[127],$electricPower[127],'',81],
            ['no_ch1027_o353_ch304_o141_nu305','no_ch1027_o353_ch304_o141_nu306','no_ch1027_o353_ch304_o141_nu307',$factors[128],$electricPower[128],'',81]
            ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * {$week}
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=param7,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // Table 4
        $table4 = [
            'no_ch1027_o353_ch304_o139_nu308',
            'no_ch1027_o353_ch304_o140_nu308',
            'no_ch1027_o353_ch304_o141_nu308',
            ['no_ch1027_o353_ch304_o139_nu308',
                'no_ch1027_o353_ch304_o140_nu308',
                'no_ch1027_o353_ch304_o141_nu308']
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // โทรศัพท์มือถือ
    public static function report19()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '19';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'โทรศัพท์มือถือ.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1027_o354_ch310_o142',
            'no_ch1027_o354_ch310_o143',
            'no_ch1027_o354_ch310_o144',
            'no_ch1027_o354'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1027_o354_ch310_o142_nu311',
            'no_ch1027_o354_ch310_o143_nu311',
            'no_ch1027_o354_ch310_o144_nu311',
            ['no_ch1027_o354_ch310_o142_nu311',
                'no_ch1027_o354_ch310_o143_nu311',
                'no_ch1027_o354_ch310_o144_nu311']
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 40;
        $endLastDigit = 134;
        // โทรทัศน์ เพราะมีที่มีเบอร์ 5 กับไม่มีเบอร์ 5
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        //table 3
        $table3 = [
            ['no_ch1027_o354_ch310_o142_nu311','no_ch1027_o354_ch310_o142_nu312','no_ch1027_o354_ch310_o142_nu313',$factors[129],$electricPower[129],'',81],
            ['no_ch1027_o354_ch310_o143_nu311','no_ch1027_o354_ch310_o143_nu312','no_ch1027_o354_ch310_o143_nu313',$factors[130],$electricPower[130],'',81],
            ['no_ch1027_o354_ch310_o144_nu311','no_ch1027_o354_ch310_o144_nu312','no_ch1027_o354_ch310_o144_nu313',$factors[131],$electricPower[131],'',81]
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * {$week}
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=param7,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // Table 4
        $table4 = [
            'no_ch1027_o354_ch310_o142_nu314',
            'no_ch1027_o354_ch310_o143_nu314',
            'no_ch1027_o354_ch310_o144_nu314',
            ['no_ch1027_o354_ch310_o142_nu314',
                'no_ch1027_o354_ch310_o143_nu314',
                'no_ch1027_o354_ch310_o144_nu314']
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // แบตเตอรี่สำรอง
    public static function report20()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '20';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'แบตเตอรี่สำรอง.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1027_o355_ch317_o146',
            'no_ch1027_o355_ch317_o147',
            'no_ch1027_o355_ch317_o148',
            'no_ch1027_o355'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1027_o355_ch317_o146_nu318',
            'no_ch1027_o355_ch317_o147_nu318',
            'no_ch1027_o355_ch317_o148_nu318',
            ['no_ch1027_o355_ch317_o146_nu318',
                'no_ch1027_o355_ch317_o147_nu318',
                'no_ch1027_o355_ch317_o148_nu318']
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 40;
        $endLastDigit = 134;
        // โทรทัศน์ เพราะมีที่มีเบอร์ 5 กับไม่มีเบอร์ 5
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        //table 3
        $table3 = [
            ['no_ch1027_o355_ch317_o146_nu318','no_ch1027_o355_ch317_o146_nu319','no_ch1027_o355_ch317_o146_nu320',$factors[132],$electricPower[132],'',81],
            ['no_ch1027_o355_ch317_o147_nu318','no_ch1027_o355_ch317_o147_nu319','no_ch1027_o355_ch317_o147_nu320',$factors[133],$electricPower[133],'',81],
            ['no_ch1027_o355_ch317_o148_nu318','no_ch1027_o355_ch317_o148_nu319','no_ch1027_o355_ch317_o148_nu320',$factors[134],$electricPower[134],'',81],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * {$week}
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=param7,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // Table 4
        $table4 = [
            'no_ch1027_o355_ch317_o146_nu321',
            'no_ch1027_o355_ch317_o147_nu321',
            'no_ch1027_o355_ch317_o148_nu321',
            [            'no_ch1027_o355_ch317_o146_nu321',
                'no_ch1027_o355_ch317_o147_nu321',
                'no_ch1027_o355_ch317_o148_nu321']
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }

}
