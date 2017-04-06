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
    // พัดลม
    public static function report21()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '21';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'พัดลม.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o356_ch323_o149_ch324_o156',
            'no_ch1028_o356_ch323_o149_ch324_o157',
            'no_ch1028_o356_ch323_o149_ch324_o158',
            'no_ch1028_o356_ch323_o150_ch330_o156',
            'no_ch1028_o356_ch323_o150_ch330_o157',
            'no_ch1028_o356_ch323_o150_ch330_o158',
            'no_ch1028_o356_ch323_o151_ch336_o156',
            'no_ch1028_o356_ch323_o151_ch336_o157',
            'no_ch1028_o356_ch323_o151_ch336_o158',
            'no_ch1028_o356_ch323_o152_ch342_o157',
            'no_ch1028_o356_ch323_o152_ch342_o159',
            'no_ch1028_o356_ch323_o153_ch348_o158',
            'no_ch1028_o356_ch323_o153_ch348_o160',
            'no_ch1028_o356_ch323_o153_ch348_o161',
            'no_ch1028_o356_ch323_o154',
            'no_ch1028_o356_ch323_o155',
            // ทั้้งหมด
            'no_ch1028_o356',
            'no_ch1028_o356_ch323_o149',
            'no_ch1028_o356_ch323_o150',
            'no_ch1028_o356_ch323_o151',
            'no_ch1028_o356_ch323_o152',
            'no_ch1028_o356_ch323_o153'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o356_ch323_o149_ch324_o156_nu325',
            'no_ch1028_o356_ch323_o149_ch324_o157_nu325',
            'no_ch1028_o356_ch323_o149_ch324_o158_nu325',
            'no_ch1028_o356_ch323_o150_ch330_o156_nu331',
            'no_ch1028_o356_ch323_o150_ch330_o157_nu331',
            'no_ch1028_o356_ch323_o150_ch330_o158_nu331',
            'no_ch1028_o356_ch323_o151_ch336_o156_nu337',
            'no_ch1028_o356_ch323_o151_ch336_o157_nu338',
            'no_ch1028_o356_ch323_o151_ch336_o158_nu339',
            'no_ch1028_o356_ch323_o152_ch342_o157_nu343',
            'no_ch1028_o356_ch323_o152_ch342_o159_nu343',
            'no_ch1028_o356_ch323_o153_ch348_o158_nu349',
            'no_ch1028_o356_ch323_o153_ch348_o160_nu349',
            'no_ch1028_o356_ch323_o153_ch348_o161_nu349',
            'no_ch1028_o356_ch323_o154_nu353',
            'no_ch1028_o356_ch323_o155_nu353',
            // ทั้งหมด
            [            'no_ch1028_o356_ch323_o149_ch324_o156_nu325',
                'no_ch1028_o356_ch323_o149_ch324_o157_nu325',
                'no_ch1028_o356_ch323_o149_ch324_o158_nu325',
                'no_ch1028_o356_ch323_o150_ch330_o156_nu331',
                'no_ch1028_o356_ch323_o150_ch330_o157_nu331',
                'no_ch1028_o356_ch323_o150_ch330_o158_nu331',
                'no_ch1028_o356_ch323_o151_ch336_o156_nu337',
                'no_ch1028_o356_ch323_o151_ch336_o157_nu338',
                'no_ch1028_o356_ch323_o151_ch336_o158_nu339',
                'no_ch1028_o356_ch323_o152_ch342_o157_nu343',
                'no_ch1028_o356_ch323_o152_ch342_o159_nu343',
                'no_ch1028_o356_ch323_o153_ch348_o158_nu349',
                'no_ch1028_o356_ch323_o153_ch348_o160_nu349',
                'no_ch1028_o356_ch323_o153_ch348_o161_nu349',
                'no_ch1028_o356_ch323_o154_nu353',
                'no_ch1028_o356_ch323_o155_nu353'],
            [            'no_ch1028_o356_ch323_o149_ch324_o156_nu325',
                'no_ch1028_o356_ch323_o149_ch324_o157_nu325',
                'no_ch1028_o356_ch323_o149_ch324_o158_nu325'],
            [
                'no_ch1028_o356_ch323_o150_ch330_o156_nu331',
                'no_ch1028_o356_ch323_o150_ch330_o157_nu331',
                'no_ch1028_o356_ch323_o150_ch330_o158_nu331'
            ],
            [
                'no_ch1028_o356_ch323_o151_ch336_o156_nu337',
                'no_ch1028_o356_ch323_o151_ch336_o157_nu338',
                'no_ch1028_o356_ch323_o151_ch336_o158_nu339',
            ],
            [
                'no_ch1028_o356_ch323_o152_ch342_o157_nu343',
                'no_ch1028_o356_ch323_o152_ch342_o159_nu343',
            ],
            [
                'no_ch1028_o356_ch323_o153_ch348_o158_nu349',
                'no_ch1028_o356_ch323_o153_ch348_o160_nu349',
                'no_ch1028_o356_ch323_o153_ch348_o161_nu349',
            ]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            //พัดลม
            ['no_ch1028_o356_ch323_o149_ch324_o156_nu325', 'no_ch1028_o356_ch323_o149_ch324_o156_nu326','no_ch1028_o356_ch323_o149_ch324_o156_nu327',$factors[135],$electricPower[135],'no_ch1028_o356_ch323_o149_ch324_o156_ra329',81],
            ['no_ch1028_o356_ch323_o149_ch324_o156_nu325', 'no_ch1028_o356_ch323_o149_ch324_o156_nu326','no_ch1028_o356_ch323_o149_ch324_o156_nu327',$factors[136],$electricPower[136],'no_ch1028_o356_ch323_o149_ch324_o156_ra329',82],
            ['no_ch1028_o356_ch323_o149_ch324_o157_nu325', 'no_ch1028_o356_ch323_o149_ch324_o157_nu326','no_ch1028_o356_ch323_o149_ch324_o157_nu327',$factors[137], $electricPower[137],'no_ch1028_o356_ch323_o149_ch324_o157_ra329',81],
            ['no_ch1028_o356_ch323_o149_ch324_o157_nu325', 'no_ch1028_o356_ch323_o149_ch324_o157_nu326','no_ch1028_o356_ch323_o149_ch324_o157_nu327',$factors[138], $electricPower[138],'no_ch1028_o356_ch323_o149_ch324_o157_ra329',82],
            ['no_ch1028_o356_ch323_o149_ch324_o158_nu325', 'no_ch1028_o356_ch323_o149_ch324_o158_nu326','no_ch1028_o356_ch323_o149_ch324_o158_nu327',$factors[139], $electricPower[139],'no_ch1028_o356_ch323_o149_ch324_o158_ra329',81],
            ['no_ch1028_o356_ch323_o149_ch324_o158_nu325', 'no_ch1028_o356_ch323_o149_ch324_o158_nu326','no_ch1028_o356_ch323_o149_ch324_o158_nu327',$factors[140], $electricPower[140],'no_ch1028_o356_ch323_o149_ch324_o158_ra329',82],
            ['no_ch1028_o356_ch323_o150_ch330_o156_nu331', 'no_ch1028_o356_ch323_o150_ch330_o156_nu332','no_ch1028_o356_ch323_o150_ch330_o156_nu332',$factors[141], $electricPower[141],'no_ch1028_o356_ch323_o150_ch330_o156_ra335',81],
            ['no_ch1028_o356_ch323_o150_ch330_o156_nu331', 'no_ch1028_o356_ch323_o150_ch330_o156_nu332','no_ch1028_o356_ch323_o150_ch330_o156_nu332',$factors[142], $electricPower[142],'no_ch1028_o356_ch323_o150_ch330_o156_ra335',82],
            ['no_ch1028_o356_ch323_o150_ch330_o157_nu331', 'no_ch1028_o356_ch323_o150_ch330_o157_nu332','no_ch1028_o356_ch323_o150_ch330_o157_nu332',$factors[143], $electricPower[143],'no_ch1028_o356_ch323_o150_ch330_o157_ra335',81],
            ['no_ch1028_o356_ch323_o150_ch330_o157_nu331', 'no_ch1028_o356_ch323_o150_ch330_o157_nu332','no_ch1028_o356_ch323_o150_ch330_o157_nu332',$factors[144], $electricPower[144],'no_ch1028_o356_ch323_o150_ch330_o157_ra335',82],
            ['no_ch1028_o356_ch323_o150_ch330_o158_nu331', 'no_ch1028_o356_ch323_o150_ch330_o158_nu332','no_ch1028_o356_ch323_o150_ch330_o158_nu332',$factors[145], $electricPower[145],'no_ch1028_o356_ch323_o150_ch330_o158_ra335',81],
            ['no_ch1028_o356_ch323_o150_ch330_o158_nu331', 'no_ch1028_o356_ch323_o150_ch330_o158_nu332','no_ch1028_o356_ch323_o150_ch330_o158_nu332',$factors[146], $electricPower[146],'no_ch1028_o356_ch323_o150_ch330_o158_ra335',82],
            ['no_ch1028_o356_ch323_o151_ch336_o156_nu337', 'no_ch1028_o356_ch323_o151_ch336_o156_nu338','no_ch1028_o356_ch323_o151_ch336_o156_nu339',$factors[147], $electricPower[147],'no_ch1028_o356_ch323_o151_ch336_o156_ra341',81],
            ['no_ch1028_o356_ch323_o151_ch336_o156_nu337', 'no_ch1028_o356_ch323_o151_ch336_o156_nu338','no_ch1028_o356_ch323_o151_ch336_o156_nu339',$factors[148], $electricPower[148],'no_ch1028_o356_ch323_o151_ch336_o156_ra341',82],
            ['no_ch1028_o356_ch323_o151_ch336_o157_nu337', 'no_ch1028_o356_ch323_o151_ch336_o157_nu338','no_ch1028_o356_ch323_o151_ch336_o157_nu339',$factors[149], $electricPower[149],'no_ch1028_o356_ch323_o151_ch336_o157_ra341',81],
            ['no_ch1028_o356_ch323_o151_ch336_o157_nu337', 'no_ch1028_o356_ch323_o151_ch336_o157_nu338','no_ch1028_o356_ch323_o151_ch336_o157_nu339',$factors[150], $electricPower[150],'no_ch1028_o356_ch323_o151_ch336_o157_ra341',82],
            ['no_ch1028_o356_ch323_o151_ch336_o158_nu337', 'no_ch1028_o356_ch323_o151_ch336_o158_nu338','no_ch1028_o356_ch323_o151_ch336_o158_nu339',$factors[151], $electricPower[151],'no_ch1028_o356_ch323_o151_ch336_o158_ra341',81],
            ['no_ch1028_o356_ch323_o151_ch336_o158_nu337', 'no_ch1028_o356_ch323_o151_ch336_o158_nu338','no_ch1028_o356_ch323_o151_ch336_o158_nu339',$factors[152], $electricPower[152],'no_ch1028_o356_ch323_o151_ch336_o158_ra341',82],
            ['no_ch1028_o356_ch323_o152_ch342_o157_nu343', 'no_ch1028_o356_ch323_o152_ch342_o157_nu344','no_ch1028_o356_ch323_o152_ch342_o157_nu345',$factors[153], $electricPower[153],'no_ch1028_o356_ch323_o152_ch342_o157_ra347',81],
            ['no_ch1028_o356_ch323_o152_ch342_o157_nu343', 'no_ch1028_o356_ch323_o152_ch342_o157_nu344','no_ch1028_o356_ch323_o152_ch342_o157_nu345',$factors[154], $electricPower[154],'no_ch1028_o356_ch323_o152_ch342_o157_ra347',82],
            ['no_ch1028_o356_ch323_o152_ch342_o159_nu343', 'no_ch1028_o356_ch323_o152_ch342_o159_nu344','no_ch1028_o356_ch323_o152_ch342_o159_nu345',$factors[155], $electricPower[155],'',81],
            ['no_ch1028_o356_ch323_o153_ch348_o158_nu349', 'no_ch1028_o356_ch323_o153_ch348_o158_nu350','no_ch1028_o356_ch323_o153_ch348_o158_nu350',$factors[156], $electricPower[156],'',81],
            ['no_ch1028_o356_ch323_o153_ch348_o160_nu349', 'no_ch1028_o356_ch323_o153_ch348_o160_nu350','no_ch1028_o356_ch323_o153_ch348_o160_nu351',$factors[157], $electricPower[157],'',81],
            ['no_ch1028_o356_ch323_o153_ch348_o161_nu349', 'no_ch1028_o356_ch323_o153_ch348_o161_nu350','no_ch1028_o356_ch323_o153_ch348_o161_nu351',$factors[158], $electricPower[158],'',81],
            ['no_ch1028_o356_ch323_o154_nu353', 'no_ch1028_o356_ch323_o154_nu354','no_ch1028_o356_ch323_o154_nu355',$factors[159], $electricPower[159],'',81],
            ['no_ch1028_o356_ch323_o155_nu353', 'no_ch1028_o356_ch323_o155_nu354','no_ch1028_o356_ch323_o155_nu355',$factors[160], $electricPower[160],'',81]
        ];
        $startColumn = "AB";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o356_ch323_o149_ch324_o156_nu328',
            'no_ch1028_o356_ch323_o149_ch324_o157_nu328',
            'no_ch1028_o356_ch323_o149_ch324_o158_nu328',
            'no_ch1028_o356_ch323_o150_ch330_o156_nu334',
            'no_ch1028_o356_ch323_o150_ch330_o157_nu334',
            'no_ch1028_o356_ch323_o150_ch330_o158_nu334',
            'no_ch1028_o356_ch323_o151_ch336_o156_nu340',
            'no_ch1028_o356_ch323_o151_ch336_o157_nu340',
            'no_ch1028_o356_ch323_o151_ch336_o158_nu340',
            'no_ch1028_o356_ch323_o152_ch342_o157_nu346',
            'no_ch1028_o356_ch323_o152_ch342_o159_nu346',
            'no_ch1028_o356_ch323_o153_ch348_o158_nu352',
            'no_ch1028_o356_ch323_o153_ch348_o160_nu352',
            'no_ch1028_o356_ch323_o153_ch348_o161_nu352',
            'no_ch1028_o356_ch323_o154_nu356',
            'no_ch1028_o356_ch323_o155_nu356',
            [
                'no_ch1028_o356_ch323_o149_ch324_o156_nu328',
                'no_ch1028_o356_ch323_o149_ch324_o157_nu328',
                'no_ch1028_o356_ch323_o149_ch324_o158_nu328',
                'no_ch1028_o356_ch323_o150_ch330_o156_nu334',
                'no_ch1028_o356_ch323_o150_ch330_o157_nu334',
                'no_ch1028_o356_ch323_o150_ch330_o158_nu334',
                'no_ch1028_o356_ch323_o151_ch336_o156_nu340',
                'no_ch1028_o356_ch323_o151_ch336_o157_nu340',
                'no_ch1028_o356_ch323_o151_ch336_o158_nu340',
                'no_ch1028_o356_ch323_o152_ch342_o157_nu346',
                'no_ch1028_o356_ch323_o152_ch342_o159_nu346',
                'no_ch1028_o356_ch323_o153_ch348_o158_nu352',
                'no_ch1028_o356_ch323_o153_ch348_o160_nu352',
                'no_ch1028_o356_ch323_o153_ch348_o161_nu352',
                'no_ch1028_o356_ch323_o154_nu356',
                'no_ch1028_o356_ch323_o155_nu356'
            ],
            [
                'no_ch1028_o356_ch323_o149_ch324_o156_nu328',
                'no_ch1028_o356_ch323_o149_ch324_o157_nu328',
                'no_ch1028_o356_ch323_o149_ch324_o158_nu328'
            ],
            [
                'no_ch1028_o356_ch323_o150_ch330_o156_nu334',
                'no_ch1028_o356_ch323_o150_ch330_o157_nu334',
                'no_ch1028_o356_ch323_o150_ch330_o158_nu334'
            ],
            [
                'no_ch1028_o356_ch323_o151_ch336_o156_nu340',
                'no_ch1028_o356_ch323_o151_ch336_o157_nu340',
                'no_ch1028_o356_ch323_o151_ch336_o158_nu340',
            ],
            [
                'no_ch1028_o356_ch323_o152_ch342_o157_nu346',
                'no_ch1028_o356_ch323_o152_ch342_o159_nu346',
            ],
            [
                'no_ch1028_o356_ch323_o153_ch348_o158_nu352',
                'no_ch1028_o356_ch323_o153_ch348_o160_nu352',
                'no_ch1028_o356_ch323_o153_ch348_o161_nu352',
            ]
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // พัดลมดูดอากาศ
    public static function report22()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '22';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'พัดลมดูดอากาศ.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o357_ch358_o151_ch359_o163',
            'no_ch1028_o357_ch358_o151_ch359_o164',
            'no_ch1028_o357_ch358_o151_ch359_o165',
            'no_ch1028_o357'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o357_ch358_o151_ch359_o163_nu360',
            'no_ch1028_o357_ch358_o151_ch359_o164_nu360',
            'no_ch1028_o357_ch358_o151_ch359_o165_nu360',
            [            'no_ch1028_o357_ch358_o151_ch359_o163_nu360',
                'no_ch1028_o357_ch358_o151_ch359_o164_nu360',
                'no_ch1028_o357_ch358_o151_ch359_o165_nu360']
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            // พัดลมดูดอากาศ
            ['no_ch1028_o357_ch358_o151_ch359_o163_nu360', 'no_ch1028_o357_ch358_o151_ch359_o163_nu361','no_ch1028_o357_ch358_o151_ch359_o163_nu362',$factors[161], $electricPower[161],'no_ch1028_o357_ch358_o151_ch359_o163_ra364',81],
            ['no_ch1028_o357_ch358_o151_ch359_o163_nu360', 'no_ch1028_o357_ch358_o151_ch359_o163_nu361','no_ch1028_o357_ch358_o151_ch359_o163_nu362',$factors[162], $electricPower[162],'no_ch1028_o357_ch358_o151_ch359_o163_ra364',82],
            ['no_ch1028_o357_ch358_o151_ch359_o164_nu360', 'no_ch1028_o357_ch358_o151_ch359_o164_nu361','no_ch1028_o357_ch358_o151_ch359_o164_nu362',$factors[163], $electricPower[163],'no_ch1028_o357_ch358_o151_ch359_o164_ra364',81],
            ['no_ch1028_o357_ch358_o151_ch359_o164_nu360', 'no_ch1028_o357_ch358_o151_ch359_o164_nu361','no_ch1028_o357_ch358_o151_ch359_o164_nu362',$factors[164], $electricPower[164],'no_ch1028_o357_ch358_o151_ch359_o164_ra364',82],
            ['no_ch1028_o357_ch358_o151_ch359_o165_nu360', 'no_ch1028_o357_ch358_o151_ch359_o165_nu361','no_ch1028_o357_ch358_o151_ch359_o165_nu362',$factors[165], $electricPower[165],'no_ch1028_o357_ch358_o151_ch359_o165_ra364',81],
            ['no_ch1028_o357_ch358_o151_ch359_o165_nu360', 'no_ch1028_o357_ch358_o151_ch359_o165_nu361','no_ch1028_o357_ch358_o151_ch359_o165_nu362',$factors[166], $electricPower[166],'no_ch1028_o357_ch358_o151_ch359_o165_ra364',82],
        ];
        $startColumn = "AB";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o357_ch358_o151_ch359_o163_nu363',
            'no_ch1028_o357_ch358_o151_ch359_o164_nu363',
            'no_ch1028_o357_ch358_o151_ch359_o165_nu363',
            [
                'no_ch1028_o357_ch358_o151_ch359_o163_nu363',
                'no_ch1028_o357_ch358_o151_ch359_o164_nu363',
                'no_ch1028_o357_ch358_o151_ch359_o165_nu363'
            ]
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เครื่องฟอกอากาศ
    public static function report23()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '23';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'เครื่องฟอกอากาศ.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o358',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o358_nu367',
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            ['no_ch1028_o358_nu367', 'no_ch1028_o358_nu368','no_ch1028_o358_nu368', $factors[167], $electricPower[167],'', 81],
        ];
        $startColumn = "AB";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o358_nu370',
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เครื่องทำน้ำอุ่นไฟฟ้า
    public static function report24()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '24';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'เครื่องทำน้ำอุ่นไฟฟ้า.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o359',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o359_nu373',
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            ['no_ch1028_o359_nu373', 'no_ch1028_o359_nu374','no_ch1028_o359_nu375', $factors[168], $electricPower[168],'no_ch1028_o359_ra377',81],
            ['no_ch1028_o359_nu373', 'no_ch1028_o359_nu374','no_ch1028_o359_nu375', $factors[169], $electricPower[169],'no_ch1028_o359_ra377',82],
        ];
        $startColumn = "AB";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o359_nu376',
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    //เครื่องดูดฝุ่น
    public static function report25()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '25';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'เครื่องดูดฝุ่น.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o360_ch379_o167',
            'no_ch1028_o360_ch379_o168',
            'no_ch1028_o360_ch379_o169',
            'no_ch1028_o360',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o360_ch379_o167_nu380',
            'no_ch1028_o360_ch379_o168_nu380',
            'no_ch1028_o360_ch379_o169_nu380',
            [
                'no_ch1028_o360_ch379_o167_nu380',
                'no_ch1028_o360_ch379_o168_nu380',
                'no_ch1028_o360_ch379_o169_nu380',
            ]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            ['no_ch1028_o360_ch379_o167_nu380', 'no_ch1028_o360_ch379_o167_nu381','no_ch1028_o360_ch379_o167_nu382',$factors[170], $electricPower[170],'',81],
            ['no_ch1028_o360_ch379_o168_nu380', 'no_ch1028_o360_ch379_o168_nu381','no_ch1028_o360_ch379_o168_nu382',$factors[171], $electricPower[171],'',81],
            ['no_ch1028_o360_ch379_o169_nu380', 'no_ch1028_o360_ch379_o169_nu381','no_ch1028_o360_ch379_o169_nu382',$factors[172], $electricPower[172],'',81]
        ];
        $startColumn = "AB";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o360_ch379_o167_nu383',
            'no_ch1028_o360_ch379_o168_nu383',
            'no_ch1028_o360_ch379_o169_nu383',
            [
                'no_ch1028_o360_ch379_o167_nu383',
                'no_ch1028_o360_ch379_o168_nu383',
                'no_ch1028_o360_ch379_o169_nu383',
            ]
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เตารีดไฟฟ้า
    public static function report26()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '26';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'เตารีดไฟฟ้า.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o361_ch385_o170',
            'no_ch1028_o361_ch385_o171',
            'no_ch1028_o361_ch385_o172',
            'no_ch1028_o361_ch385_o173',
            'no_ch1028_o361_ch385_o174',
            'no_ch1028_o361',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o361_ch385_o170_nu386',
            'no_ch1028_o361_ch385_o171_nu386',
            'no_ch1028_o361_ch385_o172_nu386',
            'no_ch1028_o361_ch385_o173_nu386',
            'no_ch1028_o361_ch385_o174_nu386',
            [
                'no_ch1028_o361_ch385_o170_nu386',
                'no_ch1028_o361_ch385_o171_nu386',
                'no_ch1028_o361_ch385_o172_nu386',
                'no_ch1028_o361_ch385_o173_nu386',
                'no_ch1028_o361_ch385_o174_nu386',
            ]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            // เตารีดไฟฟ้า
            ['no_ch1028_o361_ch385_o170_nu386', 'no_ch1028_o361_ch385_o170_nu387','no_ch1028_o361_ch385_o170_nu388',$factors[173], $electricPower[173],'no_ch1028_o361_ch385_o170_ra390',81],
            ['no_ch1028_o361_ch385_o170_nu386', 'no_ch1028_o361_ch385_o170_nu387','no_ch1028_o361_ch385_o170_nu388',$factors[174], $electricPower[174],'no_ch1028_o361_ch385_o170_ra390',82],
            ['no_ch1028_o361_ch385_o171_nu386', 'no_ch1028_o361_ch385_o171_nu387','no_ch1028_o361_ch385_o171_nu388',$factors[175], $electricPower[175],'no_ch1028_o361_ch385_o171_ra390',81],
            ['no_ch1028_o361_ch385_o171_nu386', 'no_ch1028_o361_ch385_o171_nu387','no_ch1028_o361_ch385_o171_nu388',$factors[176], $electricPower[176],'no_ch1028_o361_ch385_o171_ra390',82],
            ['no_ch1028_o361_ch385_o172_nu386', 'no_ch1028_o361_ch385_o172_nu387','no_ch1028_o361_ch385_o172_nu388',$factors[177], $electricPower[177],''],
            ['no_ch1028_o361_ch385_o173_nu386', 'no_ch1028_o361_ch385_o173_nu387','no_ch1028_o361_ch385_o173_nu388',$factors[178], $electricPower[178],''],
            ['no_ch1028_o361_ch385_o174_nu386', 'no_ch1028_o361_ch385_o174_nu387','no_ch1028_o361_ch385_o174_nu388',$factors[179], $electricPower[179],''],
        ];
        $startColumn = "AB";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o361_ch385_o170_nu389',
            'no_ch1028_o361_ch385_o171_nu389',
            'no_ch1028_o361_ch385_o172_nu389',
            'no_ch1028_o361_ch385_o173_nu389',
            'no_ch1028_o361_ch385_o174_nu389',
            [
                'no_ch1028_o361_ch385_o170_nu389',
                'no_ch1028_o361_ch385_o171_nu389',
                'no_ch1028_o361_ch385_o172_nu389',
                'no_ch1028_o361_ch385_o173_nu389',
                'no_ch1028_o361_ch385_o174_nu389',
            ]
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // ตู้เย็น
    public static function report27()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '27';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'ตู้เย็น.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o362_ch392_o175_ch393_o177',
            'no_ch1028_o362_ch392_o175_ch393_o178',
            'no_ch1028_o362_ch392_o176_ch1001_o179',
            'no_ch1028_o362_ch392_o176_ch1001_o180',
            'no_ch1028_o362',
            'no_ch1028_o362_ch392_o175',
            'no_ch1028_o362_ch392_o176'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o362_ch392_o175_ch393_o177_nu394',
            'no_ch1028_o362_ch392_o175_ch393_o178_nu394',
            'no_ch1028_o362_ch392_o176_ch1001_o179_nu1002',
            'no_ch1028_o362_ch392_o176_ch1001_o180_nu1002',
            [
                'no_ch1028_o362_ch392_o175_ch393_o177_nu394',
                'no_ch1028_o362_ch392_o175_ch393_o178_nu394',
                'no_ch1028_o362_ch392_o176_ch1001_o179_nu1002',
                'no_ch1028_o362_ch392_o176_ch1001_o180_nu1002',
            ],
            [
                'no_ch1028_o362_ch392_o175_ch393_o177_nu394',
                'no_ch1028_o362_ch392_o175_ch393_o178_nu394',
            ],
            [
                'no_ch1028_o362_ch392_o176_ch1001_o179_nu1002',
                'no_ch1028_o362_ch392_o176_ch1001_o180_nu1002',
            ]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            // ตู้เย็น
            ['no_ch1028_o362_ch392_o175_ch393_o177_nu394', 'no_ch1028_o362_ch392_o175_ch393_o177_nu395','no_ch1028_o362_ch392_o175_ch393_o177_nu396',$factors[180], $electricPower[180],'no_ch1028_o362_ch392_o175_ch393_o177_ra398',81],
            ['no_ch1028_o362_ch392_o175_ch393_o177_nu394', 'no_ch1028_o362_ch392_o175_ch393_o177_nu395','no_ch1028_o362_ch392_o175_ch393_o177_nu396',$factors[181], $electricPower[181],'no_ch1028_o362_ch392_o175_ch393_o177_ra398',82],
            ['no_ch1028_o362_ch392_o175_ch393_o178_nu394', 'no_ch1028_o362_ch392_o175_ch393_o178_nu395','no_ch1028_o362_ch392_o175_ch393_o178_nu396',$factors[182], $electricPower[182],'no_ch1028_o362_ch392_o175_ch393_o178_ra398',81],
            ['no_ch1028_o362_ch392_o175_ch393_o178_nu394', 'no_ch1028_o362_ch392_o175_ch393_o178_nu395','no_ch1028_o362_ch392_o175_ch393_o178_nu396',$factors[183], $electricPower[183],'no_ch1028_o362_ch392_o175_ch393_o178_ra398',82],
            ['no_ch1028_o362_ch392_o176_ch1001_o179_nu1002', 'no_ch1028_o362_ch392_o176_ch1001_o179_nu1003','no_ch1028_o362_ch392_o176_ch1001_o179_nu1004',$factors[184], $electricPower[184],'no_ch1028_o362_ch392_o176_ch1001_o179_ra1006',81],
            ['no_ch1028_o362_ch392_o176_ch1001_o179_nu1002', 'no_ch1028_o362_ch392_o176_ch1001_o179_nu1003','no_ch1028_o362_ch392_o176_ch1001_o179_nu1004',$factors[185], $electricPower[185],'no_ch1028_o362_ch392_o176_ch1001_o179_ra1006',82],
            ['no_ch1028_o362_ch392_o176_ch1001_o180_nu1002', 'no_ch1028_o362_ch392_o176_ch1001_o180_nu1003','no_ch1028_o362_ch392_o176_ch1001_o180_nu1004',$factors[186], $electricPower[186],'no_ch1028_o362_ch392_o176_ch1001_o180_ra1006',81],
            ['no_ch1028_o362_ch392_o176_ch1001_o180_nu1002', 'no_ch1028_o362_ch392_o176_ch1001_o180_nu1003','no_ch1028_o362_ch392_o176_ch1001_o180_nu1004',$factors[187], $electricPower[187],'no_ch1028_o362_ch392_o176_ch1001_o180_ra1006',82],
        ];
        $startColumn = "AB";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o362_ch392_o175_ch393_o177_nu397',
            'no_ch1028_o362_ch392_o175_ch393_o178_nu397',
            'no_ch1028_o362_ch392_o176_ch1001_o179_nu1005',
            'no_ch1028_o362_ch392_o176_ch1001_o180_nu1005',
            [
                'no_ch1028_o362_ch392_o175_ch393_o177_nu397',
                'no_ch1028_o362_ch392_o175_ch393_o178_nu397',
                'no_ch1028_o362_ch392_o176_ch1001_o179_nu1005',
                'no_ch1028_o362_ch392_o176_ch1001_o180_nu1005',
            ],
            [
                'no_ch1028_o362_ch392_o175_ch393_o177_nu397',
                'no_ch1028_o362_ch392_o175_ch393_o178_nu397',
            ],
            [
                'no_ch1028_o362_ch392_o176_ch1001_o179_nu1005',
                'no_ch1028_o362_ch392_o176_ch1001_o180_nu1005',
            ]
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เครื่องปรับอากาศ
    public static function report28()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '28';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'เครื่องปรับอากาศ.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o363_ch400_o181_ch401_o183',
            'no_ch1028_o363_ch400_o181_ch401_o184',
            'no_ch1028_o363_ch400_o181_ch401_o185',
            'no_ch1028_o363_ch400_o181_ch401_o186',
            'no_ch1028_o363_ch400_o181_ch401_o187',
            'no_ch1028_o363_ch400_o181_ch401_o188',
            'no_ch1028_o363_ch400_o182_ch410_o185',
            'no_ch1028_o363_ch400_o182_ch410_o186',
            'no_ch1028_o363_ch400_o182_ch410_o187',
            'no_ch1028_o363_ch400_o182_ch410_o188',
            'no_ch1028_o363_ch400_o182_ch410_o189',
            'no_ch1028_o363_ch400_o182_ch410_o190',
            'no_ch1028_o363_ch400_o182_ch410_o191',

            'no_ch1028_o363',
            'no_ch1028_o363_ch400_o181',
            'no_ch1028_o363_ch400_o182',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o363_ch400_o181_ch401_o183_nu402',
            'no_ch1028_o363_ch400_o181_ch401_o184_nu402',
            'no_ch1028_o363_ch400_o181_ch401_o185_nu402',
            'no_ch1028_o363_ch400_o181_ch401_o186_nu402',
            'no_ch1028_o363_ch400_o181_ch401_o187_nu402',
            'no_ch1028_o363_ch400_o181_ch401_o188_nu402',
            'no_ch1028_o363_ch400_o182_ch410_o185_nu411',
            'no_ch1028_o363_ch400_o182_ch410_o186_nu411',
            'no_ch1028_o363_ch400_o182_ch410_o187_nu411',
            'no_ch1028_o363_ch400_o182_ch410_o188_nu411',
            'no_ch1028_o363_ch400_o182_ch410_o189_nu411',
            'no_ch1028_o363_ch400_o182_ch410_o190_nu411',
            'no_ch1028_o363_ch400_o182_ch410_o191_nu411',
            [
                'no_ch1028_o363_ch400_o181_ch401_o183_nu402',
                'no_ch1028_o363_ch400_o181_ch401_o184_nu402',
                'no_ch1028_o363_ch400_o181_ch401_o185_nu402',
                'no_ch1028_o363_ch400_o181_ch401_o186_nu402',
                'no_ch1028_o363_ch400_o181_ch401_o187_nu402',
                'no_ch1028_o363_ch400_o181_ch401_o188_nu402',
                'no_ch1028_o363_ch400_o182_ch410_o185_nu411',
                'no_ch1028_o363_ch400_o182_ch410_o186_nu411',
                'no_ch1028_o363_ch400_o182_ch410_o187_nu411',
                'no_ch1028_o363_ch400_o182_ch410_o188_nu411',
                'no_ch1028_o363_ch400_o182_ch410_o189_nu411',
                'no_ch1028_o363_ch400_o182_ch410_o190_nu411',
                'no_ch1028_o363_ch400_o182_ch410_o191_nu411',
            ],
            [
                'no_ch1028_o363_ch400_o181_ch401_o183_nu402',
                'no_ch1028_o363_ch400_o181_ch401_o184_nu402',
                'no_ch1028_o363_ch400_o181_ch401_o185_nu402',
                'no_ch1028_o363_ch400_o181_ch401_o186_nu402',
                'no_ch1028_o363_ch400_o181_ch401_o187_nu402',
                'no_ch1028_o363_ch400_o181_ch401_o188_nu402',
            ],
            [
                'no_ch1028_o363_ch400_o182_ch410_o185_nu411',
                'no_ch1028_o363_ch400_o182_ch410_o186_nu411',
                'no_ch1028_o363_ch400_o182_ch410_o187_nu411',
                'no_ch1028_o363_ch400_o182_ch410_o188_nu411',
                'no_ch1028_o363_ch400_o182_ch410_o189_nu411',
                'no_ch1028_o363_ch400_o182_ch410_o190_nu411',
                'no_ch1028_o363_ch400_o182_ch410_o191_nu411',
            ]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            // เครื่องปรับอากาศ หรือแอร์
            ['no_ch1028_o363_ch400_o181_ch401_o183_nu402', 'no_ch1028_o363_ch400_o181_ch401_o183_nu403','no_ch1028_o363_ch400_o181_ch401_o183_nu404',$factors[188], $electricPower[188],'no_ch1028_o363_ch400_o181_ch401_o183_ra406',81],
            ['no_ch1028_o363_ch400_o181_ch401_o183_nu402', 'no_ch1028_o363_ch400_o181_ch401_o183_nu403','no_ch1028_o363_ch400_o181_ch401_o183_nu404',$factors[189], $electricPower[189],'no_ch1028_o363_ch400_o181_ch401_o183_ra406',82],
            ['no_ch1028_o363_ch400_o181_ch401_o184_nu402', 'no_ch1028_o363_ch400_o181_ch401_o184_nu403','no_ch1028_o363_ch400_o181_ch401_o184_nu404',$factors[190], $electricPower[190],'no_ch1028_o363_ch400_o181_ch401_o184_ra406',81],
            ['no_ch1028_o363_ch400_o181_ch401_o184_nu402', 'no_ch1028_o363_ch400_o181_ch401_o184_nu403','no_ch1028_o363_ch400_o181_ch401_o184_nu404',$factors[191], $electricPower[191],'no_ch1028_o363_ch400_o181_ch401_o184_ra406',82],
            ['no_ch1028_o363_ch400_o181_ch401_o185_nu402', 'no_ch1028_o363_ch400_o181_ch401_o185_nu403','no_ch1028_o363_ch400_o181_ch401_o185_nu404',$factors[192], $electricPower[192],'no_ch1028_o363_ch400_o181_ch401_o185_ra406',81],
            ['no_ch1028_o363_ch400_o181_ch401_o185_nu402', 'no_ch1028_o363_ch400_o181_ch401_o185_nu403','no_ch1028_o363_ch400_o181_ch401_o185_nu404',$factors[193], $electricPower[193],'no_ch1028_o363_ch400_o181_ch401_o185_ra406',82],
            ['no_ch1028_o363_ch400_o181_ch401_o186_nu402', 'no_ch1028_o363_ch400_o181_ch401_o186_nu403','no_ch1028_o363_ch400_o181_ch401_o186_nu404',$factors[194], $electricPower[194],'no_ch1028_o363_ch400_o181_ch401_o186_ra406',81],
            ['no_ch1028_o363_ch400_o181_ch401_o186_nu402', 'no_ch1028_o363_ch400_o181_ch401_o186_nu403','no_ch1028_o363_ch400_o181_ch401_o186_nu404',$factors[195], $electricPower[195],'no_ch1028_o363_ch400_o181_ch401_o186_ra406',82],
            ['no_ch1028_o363_ch400_o181_ch401_o187_nu402', 'no_ch1028_o363_ch400_o181_ch401_o187_nu403','no_ch1028_o363_ch400_o181_ch401_o187_nu404',$factors[196], $electricPower[196],'no_ch1028_o363_ch400_o181_ch401_o187_ra406',81],
            ['no_ch1028_o363_ch400_o181_ch401_o187_nu402', 'no_ch1028_o363_ch400_o181_ch401_o187_nu403','no_ch1028_o363_ch400_o181_ch401_o187_nu404',$factors[197], $electricPower[197],'no_ch1028_o363_ch400_o181_ch401_o187_ra406',82],
            ['no_ch1028_o363_ch400_o181_ch401_o188_nu402', 'no_ch1028_o363_ch400_o181_ch401_o188_nu403','no_ch1028_o363_ch400_o181_ch401_o188_nu404',$factors[198], $electricPower[198],'no_ch1028_o363_ch400_o181_ch401_o188_ra406',81],
            ['no_ch1028_o363_ch400_o181_ch401_o188_nu402', 'no_ch1028_o363_ch400_o181_ch401_o188_nu403','no_ch1028_o363_ch400_o181_ch401_o188_nu404',$factors[199], $electricPower[199],'no_ch1028_o363_ch400_o181_ch401_o188_ra406',82],
            ['no_ch1028_o363_ch400_o182_ch410_o185_nu411', 'no_ch1028_o363_ch400_o182_ch410_o185_nu412','no_ch1028_o363_ch400_o182_ch410_o185_nu413',$factors[200], $electricPower[200],'no_ch1028_o363_ch400_o182_ch410_o185_ra415',81],
            ['no_ch1028_o363_ch400_o182_ch410_o185_nu411', 'no_ch1028_o363_ch400_o182_ch410_o185_nu412','no_ch1028_o363_ch400_o182_ch410_o185_nu413',$factors[201], $electricPower[201],'no_ch1028_o363_ch400_o182_ch410_o185_ra415',82],
            ['no_ch1028_o363_ch400_o182_ch410_o186_nu411', 'no_ch1028_o363_ch400_o182_ch410_o186_nu412','no_ch1028_o363_ch400_o182_ch410_o186_nu413',$factors[202], $electricPower[202],'no_ch1028_o363_ch400_o182_ch410_o186_ra415',81],
            ['no_ch1028_o363_ch400_o182_ch410_o186_nu411', 'no_ch1028_o363_ch400_o182_ch410_o186_nu412','no_ch1028_o363_ch400_o182_ch410_o186_nu413',$factors[203], $electricPower[203],'no_ch1028_o363_ch400_o182_ch410_o186_ra415',82],
            ['no_ch1028_o363_ch400_o182_ch410_o187_nu411', 'no_ch1028_o363_ch400_o182_ch410_o187_nu412','no_ch1028_o363_ch400_o182_ch410_o187_nu413',$factors[204], $electricPower[204],'no_ch1028_o363_ch400_o182_ch410_o187_ra415',81],
            ['no_ch1028_o363_ch400_o182_ch410_o187_nu411', 'no_ch1028_o363_ch400_o182_ch410_o187_nu412','no_ch1028_o363_ch400_o182_ch410_o187_nu413',$factors[205], $electricPower[205],'no_ch1028_o363_ch400_o182_ch410_o187_ra415',82],
            ['no_ch1028_o363_ch400_o182_ch410_o188_nu411', 'no_ch1028_o363_ch400_o182_ch410_o188_nu412','no_ch1028_o363_ch400_o182_ch410_o188_nu413',$factors[206], $electricPower[206],'no_ch1028_o363_ch400_o182_ch410_o188_ra415',81],
            ['no_ch1028_o363_ch400_o182_ch410_o188_nu411', 'no_ch1028_o363_ch400_o182_ch410_o188_nu412','no_ch1028_o363_ch400_o182_ch410_o188_nu413',$factors[207], $electricPower[207],'no_ch1028_o363_ch400_o182_ch410_o188_ra415',82],
            ['no_ch1028_o363_ch400_o182_ch410_o189_nu411', 'no_ch1028_o363_ch400_o182_ch410_o189_nu412','no_ch1028_o363_ch400_o182_ch410_o189_nu413',$factors[208], $electricPower[208],'no_ch1028_o363_ch400_o182_ch410_o189_ra415',81],
            ['no_ch1028_o363_ch400_o182_ch410_o189_nu411', 'no_ch1028_o363_ch400_o182_ch410_o189_nu412','no_ch1028_o363_ch400_o182_ch410_o189_nu413',$factors[209], $electricPower[209],'no_ch1028_o363_ch400_o182_ch410_o189_ra415',82],
            ['no_ch1028_o363_ch400_o182_ch410_o190_nu411', 'no_ch1028_o363_ch400_o182_ch410_o190_nu412','no_ch1028_o363_ch400_o182_ch410_o190_nu413',$factors[210], $electricPower[210],'no_ch1028_o363_ch400_o182_ch410_o190_ra415',81],
            ['no_ch1028_o363_ch400_o182_ch410_o190_nu411', 'no_ch1028_o363_ch400_o182_ch410_o190_nu412','no_ch1028_o363_ch400_o182_ch410_o190_nu413',$factors[211], $electricPower[211],'no_ch1028_o363_ch400_o182_ch410_o190_ra415',82],
            ['no_ch1028_o363_ch400_o182_ch410_o191_nu411', 'no_ch1028_o363_ch400_o182_ch410_o191_nu412','no_ch1028_o363_ch400_o182_ch410_o191_nu413',$factors[212], $electricPower[212],'no_ch1028_o363_ch400_o182_ch410_o191_ra415',81],
            ['no_ch1028_o363_ch400_o182_ch410_o191_nu411', 'no_ch1028_o363_ch400_o182_ch410_o191_nu412','no_ch1028_o363_ch400_o182_ch410_o191_nu413',$factors[213], $electricPower[213],'no_ch1028_o363_ch400_o182_ch410_o191_ra415',82],
        ];
        $startColumn = "AB";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o363_ch400_o181_ch401_o183_nu405',
            'no_ch1028_o363_ch400_o181_ch401_o184_nu405',
            'no_ch1028_o363_ch400_o181_ch401_o185_nu405',
            'no_ch1028_o363_ch400_o181_ch401_o186_nu405',
            'no_ch1028_o363_ch400_o181_ch401_o187_nu405',
            'no_ch1028_o363_ch400_o181_ch401_o188_nu405',
            'no_ch1028_o363_ch400_o182_ch410_o185_nu414',
            'no_ch1028_o363_ch400_o182_ch410_o186_nu414',
            'no_ch1028_o363_ch400_o182_ch410_o187_nu414',
            'no_ch1028_o363_ch400_o182_ch410_o188_nu414',
            'no_ch1028_o363_ch400_o182_ch410_o189_nu414',
            'no_ch1028_o363_ch400_o182_ch410_o190_nu414',
            'no_ch1028_o363_ch400_o182_ch410_o191_nu414',
            [
                'no_ch1028_o363_ch400_o181_ch401_o183_nu405',
                'no_ch1028_o363_ch400_o181_ch401_o184_nu405',
                'no_ch1028_o363_ch400_o181_ch401_o185_nu405',
                'no_ch1028_o363_ch400_o181_ch401_o186_nu405',
                'no_ch1028_o363_ch400_o181_ch401_o187_nu405',
                'no_ch1028_o363_ch400_o181_ch401_o188_nu405',
                'no_ch1028_o363_ch400_o182_ch410_o185_nu414',
                'no_ch1028_o363_ch400_o182_ch410_o186_nu414',
                'no_ch1028_o363_ch400_o182_ch410_o187_nu414',
                'no_ch1028_o363_ch400_o182_ch410_o188_nu414',
                'no_ch1028_o363_ch400_o182_ch410_o189_nu414',
                'no_ch1028_o363_ch400_o182_ch410_o190_nu414',
                'no_ch1028_o363_ch400_o182_ch410_o191_nu414',
            ],
            [
                'no_ch1028_o363_ch400_o181_ch401_o183_nu405',
                'no_ch1028_o363_ch400_o181_ch401_o184_nu405',
                'no_ch1028_o363_ch400_o181_ch401_o185_nu405',
                'no_ch1028_o363_ch400_o181_ch401_o186_nu405',
                'no_ch1028_o363_ch400_o181_ch401_o187_nu405',
                'no_ch1028_o363_ch400_o181_ch401_o188_nu405'
            ],
            [
                'no_ch1028_o363_ch400_o182_ch410_o185_nu414',
                'no_ch1028_o363_ch400_o182_ch410_o186_nu414',
                'no_ch1028_o363_ch400_o182_ch410_o187_nu414',
                'no_ch1028_o363_ch400_o182_ch410_o188_nu414',
                'no_ch1028_o363_ch400_o182_ch410_o189_nu414',
                'no_ch1028_o363_ch400_o182_ch410_o190_nu414',
                'no_ch1028_o363_ch400_o182_ch410_o191_nu414',
            ]
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เครื่องซักผ้าและอบผ้า
    public static function report29()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '29';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'เครื่องซักผ้าและอบผ้า.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o364_ch420_o195_ch421_o198',
            'no_ch1028_o364_ch420_o195_ch421_o199',
            'no_ch1028_o364_ch420_o195_ch421_o200',
            'no_ch1028_o364_ch420_o195_ch421_o201',
            'no_ch1028_o364_ch420_o196_ch421_o198',
            'no_ch1028_o364_ch420_o196_ch421_o199',
            'no_ch1028_o364_ch420_o196_ch421_o200',
            'no_ch1028_o364_ch420_o196_ch421_o201',
            'no_ch1028_o364_ch420_o197_ch421_o198',
            'no_ch1028_o364_ch420_o197_ch421_o199',
            'no_ch1028_o364_ch420_o197_ch421_o200',
            'no_ch1028_o364_ch420_o197_ch421_o201',

            'no_ch1028_o364',
            'no_ch1028_o364_ch420_o195',
            'no_ch1028_o364_ch420_o196',
            'no_ch1028_o364_ch420_o197',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o364_ch420_o195_ch421_o198_nu422',
            'no_ch1028_o364_ch420_o195_ch421_o199_nu422',
            'no_ch1028_o364_ch420_o195_ch421_o200_nu422',
            'no_ch1028_o364_ch420_o195_ch421_o201_nu422',
            'no_ch1028_o364_ch420_o196_ch421_o198_nu422',
            'no_ch1028_o364_ch420_o196_ch421_o199_nu422',
            'no_ch1028_o364_ch420_o196_ch421_o200_nu422',
            'no_ch1028_o364_ch420_o196_ch421_o201_nu422',
            'no_ch1028_o364_ch420_o197_ch421_o198_nu422',
            'no_ch1028_o364_ch420_o197_ch421_o199_nu422',
            'no_ch1028_o364_ch420_o197_ch421_o200_nu422',
            'no_ch1028_o364_ch420_o197_ch421_o201_nu422',
            [
                'no_ch1028_o364_ch420_o195_ch421_o198_nu422',
                'no_ch1028_o364_ch420_o195_ch421_o199_nu422',
                'no_ch1028_o364_ch420_o195_ch421_o200_nu422',
                'no_ch1028_o364_ch420_o195_ch421_o201_nu422',
                'no_ch1028_o364_ch420_o196_ch421_o198_nu422',
                'no_ch1028_o364_ch420_o196_ch421_o199_nu422',
                'no_ch1028_o364_ch420_o196_ch421_o200_nu422',
                'no_ch1028_o364_ch420_o196_ch421_o201_nu422',
                'no_ch1028_o364_ch420_o197_ch421_o198_nu422',
                'no_ch1028_o364_ch420_o197_ch421_o199_nu422',
                'no_ch1028_o364_ch420_o197_ch421_o200_nu422',
                'no_ch1028_o364_ch420_o197_ch421_o201_nu422',
            ],
            [
                'no_ch1028_o364_ch420_o195_ch421_o198_nu422',
                'no_ch1028_o364_ch420_o195_ch421_o199_nu422',
                'no_ch1028_o364_ch420_o195_ch421_o200_nu422',
                'no_ch1028_o364_ch420_o195_ch421_o201_nu422',
            ],
            [
                'no_ch1028_o364_ch420_o196_ch421_o198_nu422',
                'no_ch1028_o364_ch420_o196_ch421_o199_nu422',
                'no_ch1028_o364_ch420_o196_ch421_o200_nu422',
                'no_ch1028_o364_ch420_o196_ch421_o201_nu422',
            ],
            [
                'no_ch1028_o364_ch420_o197_ch421_o198_nu422',
                'no_ch1028_o364_ch420_o197_ch421_o199_nu422',
                'no_ch1028_o364_ch420_o197_ch421_o200_nu422',
                'no_ch1028_o364_ch420_o197_ch421_o201_nu422'
            ]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            ['no_ch1028_o364_ch420_o195_ch421_o198_nu422', 'no_ch1028_o364_ch420_o195_ch421_o198_nu423','no_ch1028_o364_ch420_o195_ch421_o198_nu424',$factors[214], $electricPower[214],'no_ch1028_o364_ch420_o195_ch421_o198_ra426',81],
            ['no_ch1028_o364_ch420_o195_ch421_o198_nu422', 'no_ch1028_o364_ch420_o195_ch421_o198_nu423','no_ch1028_o364_ch420_o195_ch421_o198_nu424',$factors[215], $electricPower[215],'no_ch1028_o364_ch420_o195_ch421_o198_ra426',82],
            ['no_ch1028_o364_ch420_o195_ch421_o199_nu422', 'no_ch1028_o364_ch420_o195_ch421_o199_nu423','no_ch1028_o364_ch420_o195_ch421_o199_nu424',$factors[216], $electricPower[216],'no_ch1028_o364_ch420_o195_ch421_o199_ra426',81],
            ['no_ch1028_o364_ch420_o195_ch421_o199_nu422', 'no_ch1028_o364_ch420_o195_ch421_o199_nu423','no_ch1028_o364_ch420_o195_ch421_o199_nu424',$factors[217], $electricPower[217],'no_ch1028_o364_ch420_o195_ch421_o199_ra426',82],
            ['no_ch1028_o364_ch420_o195_ch421_o200_nu422', 'no_ch1028_o364_ch420_o195_ch421_o200_nu423','no_ch1028_o364_ch420_o195_ch421_o200_nu424',$factors[218], $electricPower[218],'no_ch1028_o364_ch420_o195_ch421_o200_ra426',81],
            ['no_ch1028_o364_ch420_o195_ch421_o200_nu422', 'no_ch1028_o364_ch420_o195_ch421_o200_nu423','no_ch1028_o364_ch420_o195_ch421_o200_nu424',$factors[219], $electricPower[219],'no_ch1028_o364_ch420_o195_ch421_o200_ra426',82],
            ['no_ch1028_o364_ch420_o195_ch421_o201_nu422', 'no_ch1028_o364_ch420_o195_ch421_o201_nu423','no_ch1028_o364_ch420_o195_ch421_o201_nu424',$factors[220], $electricPower[220],'no_ch1028_o364_ch420_o195_ch421_o201_ra426',81],
            ['no_ch1028_o364_ch420_o195_ch421_o201_nu422', 'no_ch1028_o364_ch420_o195_ch421_o201_nu423','no_ch1028_o364_ch420_o195_ch421_o201_nu424',$factors[221], $electricPower[221],'no_ch1028_o364_ch420_o195_ch421_o201_ra426',82],
            ['no_ch1028_o364_ch420_o196_ch421_o198_nu422', 'no_ch1028_o364_ch420_o196_ch421_o198_nu423','no_ch1028_o364_ch420_o196_ch421_o198_nu424',$factors[222], $electricPower[222],'no_ch1028_o364_ch420_o196_ch421_o198_ra426',81],
            ['no_ch1028_o364_ch420_o196_ch421_o198_nu422', 'no_ch1028_o364_ch420_o196_ch421_o198_nu423','no_ch1028_o364_ch420_o196_ch421_o198_nu424',$factors[223], $electricPower[223],'no_ch1028_o364_ch420_o196_ch421_o198_ra426',82],
            ['no_ch1028_o364_ch420_o196_ch421_o199_nu422', 'no_ch1028_o364_ch420_o196_ch421_o199_nu423','no_ch1028_o364_ch420_o196_ch421_o199_nu424',$factors[224], $electricPower[224],'no_ch1028_o364_ch420_o196_ch421_o199_ra426',81],
            ['no_ch1028_o364_ch420_o196_ch421_o199_nu422', 'no_ch1028_o364_ch420_o196_ch421_o199_nu423','no_ch1028_o364_ch420_o196_ch421_o199_nu424',$factors[225], $electricPower[225],'no_ch1028_o364_ch420_o196_ch421_o199_ra426',82],
            ['no_ch1028_o364_ch420_o196_ch421_o200_nu422', 'no_ch1028_o364_ch420_o196_ch421_o200_nu423','no_ch1028_o364_ch420_o196_ch421_o200_nu424',$factors[226], $electricPower[226],'no_ch1028_o364_ch420_o196_ch421_o200_ra426',81],
            ['no_ch1028_o364_ch420_o196_ch421_o200_nu422', 'no_ch1028_o364_ch420_o196_ch421_o200_nu423','no_ch1028_o364_ch420_o196_ch421_o200_nu424',$factors[227], $electricPower[227],'no_ch1028_o364_ch420_o196_ch421_o200_ra426',82],
            ['no_ch1028_o364_ch420_o196_ch421_o201_nu422', 'no_ch1028_o364_ch420_o196_ch421_o201_nu423','no_ch1028_o364_ch420_o196_ch421_o201_nu424',$factors[228], $electricPower[228],'no_ch1028_o364_ch420_o196_ch421_o201_ra426',81],
            ['no_ch1028_o364_ch420_o196_ch421_o201_nu422', 'no_ch1028_o364_ch420_o196_ch421_o201_nu423','no_ch1028_o364_ch420_o196_ch421_o201_nu424',$factors[229], $electricPower[229],'no_ch1028_o364_ch420_o196_ch421_o201_ra426',82],
            ['no_ch1028_o364_ch420_o197_ch421_o198_nu422', 'no_ch1028_o364_ch420_o197_ch421_o198_nu423','no_ch1028_o364_ch420_o197_ch421_o198_nu424',$factors[230], $electricPower[230],'no_ch1028_o364_ch420_o197_ch421_o198_ra426',81],
            ['no_ch1028_o364_ch420_o197_ch421_o198_nu422', 'no_ch1028_o364_ch420_o197_ch421_o198_nu423','no_ch1028_o364_ch420_o197_ch421_o198_nu424',$factors[231], $electricPower[231],'no_ch1028_o364_ch420_o197_ch421_o198_ra426',82],
            ['no_ch1028_o364_ch420_o197_ch421_o199_nu422', 'no_ch1028_o364_ch420_o197_ch421_o199_nu423','no_ch1028_o364_ch420_o197_ch421_o199_nu424',$factors[232], $electricPower[232],'no_ch1028_o364_ch420_o197_ch421_o199_ra426',81],
            ['no_ch1028_o364_ch420_o197_ch421_o199_nu422', 'no_ch1028_o364_ch420_o197_ch421_o199_nu423','no_ch1028_o364_ch420_o197_ch421_o199_nu424',$factors[233], $electricPower[233],'no_ch1028_o364_ch420_o197_ch421_o199_ra426',82],
            ['no_ch1028_o364_ch420_o197_ch421_o200_nu422', 'no_ch1028_o364_ch420_o197_ch421_o200_nu423','no_ch1028_o364_ch420_o197_ch421_o200_nu424',$factors[234], $electricPower[234],'no_ch1028_o364_ch420_o197_ch421_o200_ra426',81],
            ['no_ch1028_o364_ch420_o197_ch421_o200_nu422', 'no_ch1028_o364_ch420_o197_ch421_o200_nu423','no_ch1028_o364_ch420_o197_ch421_o200_nu424',$factors[235], $electricPower[235],'no_ch1028_o364_ch420_o197_ch421_o200_ra426',82],
            ['no_ch1028_o364_ch420_o197_ch421_o201_nu422', 'no_ch1028_o364_ch420_o197_ch421_o201_nu423','no_ch1028_o364_ch420_o197_ch421_o201_nu424',$factors[236], $electricPower[236],'no_ch1028_o364_ch420_o197_ch421_o201_ra426',81],
            ['no_ch1028_o364_ch420_o197_ch421_o201_nu422', 'no_ch1028_o364_ch420_o197_ch421_o201_nu423','no_ch1028_o364_ch420_o197_ch421_o201_nu424',$factors[237], $electricPower[237],'no_ch1028_o364_ch420_o197_ch421_o201_ra426',82],
        ];
        $startColumn = "AB";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o364_ch420_o195_ch421_o198_nu425',
            'no_ch1028_o364_ch420_o195_ch421_o199_nu425',
            'no_ch1028_o364_ch420_o195_ch421_o200_nu425',
            'no_ch1028_o364_ch420_o195_ch421_o201_nu425',
            'no_ch1028_o364_ch420_o196_ch421_o198_nu425',
            'no_ch1028_o364_ch420_o196_ch421_o199_nu425',
            'no_ch1028_o364_ch420_o196_ch421_o200_nu425',
            'no_ch1028_o364_ch420_o196_ch421_o201_nu425',
            'no_ch1028_o364_ch420_o197_ch421_o198_nu425',
            'no_ch1028_o364_ch420_o197_ch421_o199_nu426',
            'no_ch1028_o364_ch420_o197_ch421_o200_nu427',
            'no_ch1028_o364_ch420_o197_ch421_o201_nu428',
            [
                'no_ch1028_o364_ch420_o195_ch421_o198_nu425',
                'no_ch1028_o364_ch420_o195_ch421_o199_nu425',
                'no_ch1028_o364_ch420_o195_ch421_o200_nu425',
                'no_ch1028_o364_ch420_o195_ch421_o201_nu425',
                'no_ch1028_o364_ch420_o196_ch421_o198_nu425',
                'no_ch1028_o364_ch420_o196_ch421_o199_nu425',
                'no_ch1028_o364_ch420_o196_ch421_o200_nu425',
                'no_ch1028_o364_ch420_o196_ch421_o201_nu425',
                'no_ch1028_o364_ch420_o197_ch421_o198_nu425',
                'no_ch1028_o364_ch420_o197_ch421_o199_nu426',
                'no_ch1028_o364_ch420_o197_ch421_o200_nu427',
                'no_ch1028_o364_ch420_o197_ch421_o201_nu428'
            ],
            [
                'no_ch1028_o364_ch420_o195_ch421_o198_nu425',
                'no_ch1028_o364_ch420_o195_ch421_o199_nu425',
                'no_ch1028_o364_ch420_o195_ch421_o200_nu425',
                'no_ch1028_o364_ch420_o195_ch421_o201_nu425'
            ],
            [
                'no_ch1028_o364_ch420_o196_ch421_o198_nu425',
                'no_ch1028_o364_ch420_o196_ch421_o199_nu425',
                'no_ch1028_o364_ch420_o196_ch421_o200_nu425',
                'no_ch1028_o364_ch420_o196_ch421_o201_nu425'
            ],
            [
                'no_ch1028_o364_ch420_o197_ch421_o198_nu425',
                'no_ch1028_o364_ch420_o197_ch421_o199_nu426',
                'no_ch1028_o364_ch420_o197_ch421_o200_nu427',
                'no_ch1028_o364_ch420_o197_ch421_o201_nu428',
            ]
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // ไดร์เป่าผม
    public static function report30()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '30';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'ไดร์เป่าผม.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o365'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o365_nu429'
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        // ไดร์เป่าผม
        $tabl3MinuteEachTime = [
            ['no_ch1028_o365_nu429', 'no_ch1028_o365_nu430','no_ch1028_o365_nu431',$factors[238], $electricPower[238],'']
        ];
        $startColumn = "AL";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/วัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * ({$week}/60.0)
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=81,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";
        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (นาที/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5  //ฉลากประหยัดไฟ
        ];
        $objPHPExcel = Summary::usageElectric($tabl3MinuteEachTime, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o365_nu432'
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เครื่องหนีบผม
    public static function report31()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '31';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'เครื่องหนีบผม.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o366'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o366_nu435'
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        // ไดร์เป่าผม
        $tabl3MinuteEachTime = [
            ['no_ch1028_o366_nu435', 'no_ch1028_o366_nu436','no_ch1028_o366_nu437',$factors[239], $electricPower[239],'']
        ];
        $startColumn = "AL";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/วัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * ({$week}/60.0)
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=81,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";
        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (นาที/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5  //ฉลากประหยัดไฟ
        ];
        $objPHPExcel = Summary::usageElectric($tabl3MinuteEachTime, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o366_nu438'
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // จักรเย็บผ้าไฟฟ้า
    public static function report32()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '32';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'จักรเย็บผ้าไฟฟ้า.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o367',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o367_nu441'
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            ['no_ch1028_o367_nu441', 'no_ch1028_o367_nu442','no_ch1028_o367_nu443',$factors[240], $electricPower[240],'',81],
        ];
        $startColumn = "AB";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o367_nu444'
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เครื่องฉีดน้ำแรงดันสูง
    public static function report33()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '33';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'เครื่องฉีดน้ำแรงดันสูง.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o368'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o368_nu447'
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            ['no_ch1028_o368_nu447', 'no_ch1028_o368_nu448','no_ch1028_o368_nu449',$factors[241], $electricPower[241],'',81],
        ];
        $startColumn = "AB";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o368_nu450'
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // ปั้มน้ำอัตโนมัติ
    public static function report34()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '34';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'ปั้มน้ำอัตโนมัติ.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o369'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o369_nu453'
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            ['no_ch1028_o369_nu453', 'no_ch1028_o369_nu454','no_ch1028_o369_nu455',$factors[242], $electricPower[242],'no_ch1028_o369_ra457',81],
            ['no_ch1028_o369_nu453', 'no_ch1028_o369_nu454','no_ch1028_o369_nu455',$factors[243], $electricPower[243],'no_ch1028_o369_ra457',82],
        ];
        $startColumn = "AB";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5,  //ฉลากประหยัดไฟ
            'param7'=>6
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1028_o369_nu456'
        ];
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เครื่องดักแมลง
    public static function report35()
    {
        set_time_limit(3600);

        // หมวดไล่และล่อแมลง
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '35';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'เครื่องดักแมลง.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);
        $table1 = [
            'no_ch1031_o373'
        ];

        $table2=[
            'no_ch1031_o373_nu479'
        ];

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $factorLastDigit = [244,245];
        foreach ($factorLastDigit as $lastDigit){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        $table3_1 = [
            ['no_ch1031_o373_nu479','no_ch1031_o373_nu480','no_ch1031_o373_nu481',$factors[244], $electricPower[244]]
        ];

        $table4 = [
            'no_ch1031_o373_nu482'
        ];

        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        $sumAmountSQL_1 = " sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(IF(unique_key='param2',answer_numeric,0)) 
        * sum(IF(unique_key='param3',answer_numeric,0)) 
        * {$week} 
        * param4
        * param5 as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2,
            'param4'=>3,
            'param5'=>4
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($table3_1, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL_1,$params,$ktoe);
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $table2,$startColumn, $startRow, $objPHPExcel, $mainObj);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // ไม้ตียุง
    public static function report36()
    {
        set_time_limit(3600);

        // หมวดไล่และล่อแมลง
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolElectric::$inputFile;
        $inputSheet = '36';
        $startRow = 5;
        $outputFile = Summary9ByToolElectric::$outputFile;
        $outputName = 'ไม้ตียุง.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);
        $table1 = [
            'no_ch1031_o374',
        ];

        $table2=[
            'no_ch1031_o374_nu485',
        ];

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $factorLastDigit = [244,245];
        foreach ($factorLastDigit as $lastDigit){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        $table3_1 = [
            ['no_ch1031_o374_nu485','no_ch1031_o374_nu486','no_ch1031_o374_nu487',$factors[245], $electricPower[245]]
        ];

        $table4 = [
            'no_ch1031_o374_nu488',
        ];

        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;
        $sumAmountSQL_1 = " sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(IF(unique_key='param2',answer_numeric,0)) 
        * sum(IF(unique_key='param3',answer_numeric,0)) 
        * {$week} 
        * param4
        * param5 as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2,
            'param4'=>3,
            'param5'=>4
        ];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($table3_1, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL_1,$params,$ktoe);
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $table2,$startColumn, $startRow, $objPHPExcel, $mainObj);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
}
