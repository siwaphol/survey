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
}
