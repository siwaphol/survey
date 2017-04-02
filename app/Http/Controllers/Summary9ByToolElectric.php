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
    public function reportTool($tool_number)
    {
        $tool_number = (int)$tool_number;
        $currentClass = new Summary9ByToolElectric();
        if(method_exists($currentClass,'report'.$tool_number)){
            Summary9ByToolElectric::{"report".$tool_number}();
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

        return response()->download(storage_path('excel/'.$outputFile), 'หลอดไฟ.xlsx');
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
        $factorLastDigit = [7,9,11,13,15,17,19,21,22,23,25,27,29,31,33,35,37,38,39];
        foreach ($factorLastDigit as $lastDigit){
            $electricPower[$lastDigit] = (float)$settings->where('code', 'electric_power_' . $lastDigit)->first()->value;

            $factors[$lastDigit] = (float)$settings->where('code','tool_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','season_factor_'. $lastDigit)->first()->value
                * (float)$settings->where('code','usage_factor_'. $lastDigit)->first()->value;
        }

        //usage and ktoe
        $usage = [
            ['no_ch1024_o331_ch123_o75_ch124_o78_nu125','no_ch1024_o331_ch123_o75_ch124_o78_nu126', 'no_ch1024_o331_ch123_o75_ch124_o78_nu127','no_ch1024_o331_ch123_o75_ch124_o78_nu128',$factors[7], $electricPower[7],'no_ch1024_o331_ch123_o75_ch124_o78_ra130'],
            ['no_ch1024_o331_ch123_o75_ch124_o79_nu125','no_ch1024_o331_ch123_o75_ch124_o79_nu126', 'no_ch1024_o331_ch123_o75_ch124_o79_nu127','no_ch1024_o331_ch123_o75_ch124_o79_nu128',$factors[9], $electricPower[9], 'no_ch1024_o331_ch123_o75_ch124_o79_ra130'],
            ['no_ch1024_o331_ch123_o75_ch124_o80_nu125','no_ch1024_o331_ch123_o75_ch124_o80_nu126', 'no_ch1024_o331_ch123_o75_ch124_o80_nu127','no_ch1024_o331_ch123_o75_ch124_o80_nu128',$factors[11], $electricPower[11], 'no_ch1024_o331_ch123_o75_ch124_o80_ra130'],
            ['no_ch1024_o331_ch123_o76_ch1011_o78_nu1012','no_ch1024_o331_ch123_o76_ch1011_o78_nu1013', 'no_ch1024_o331_ch123_o76_ch1011_o78_nu1014','no_ch1024_o331_ch123_o76_ch1011_o78_nu1015',$factors[13], $electricPower[13],'no_ch1024_o331_ch123_o76_ch1011_o78_ra1017'],
            ['no_ch1024_o331_ch123_o76_ch1011_o79_nu1012','no_ch1024_o331_ch123_o76_ch1011_o79_nu1013', 'no_ch1024_o331_ch123_o76_ch1011_o79_nu1014','no_ch1024_o331_ch123_o76_ch1011_o79_nu1015',$factors[15], $electricPower[15],'no_ch1024_o331_ch123_o76_ch1011_o79_ra1017'],
            ['no_ch1024_o331_ch123_o77_ch1011_o78_nu1012','no_ch1024_o331_ch123_o77_ch1011_o78_nu1013', 'no_ch1024_o331_ch123_o77_ch1011_o78_nu1014','no_ch1024_o331_ch123_o77_ch1011_o78_nu1015',$factors[17], $electricPower[17],'no_ch1024_o331_ch123_o77_ch1011_o78_ra1017'],
            ['no_ch1024_o331_ch123_o77_ch1011_o79_nu1012','no_ch1024_o331_ch123_o77_ch1011_o79_nu1013', 'no_ch1024_o331_ch123_o77_ch1011_o79_nu1014','no_ch1024_o331_ch123_o77_ch1011_o79_nu1015',$factors[19], $electricPower[19],'no_ch1024_o331_ch123_o77_ch1011_o79_ra1017'],
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
        * (if(sum(if(unique_key='param7' and option_id=81,1,0)) + if('param7'='',1,0) >0,1,0)) 
        as sumAmount ";

        $params = [
            'param1'=>0, //จำนวนหม้อ
            'param2'=>1, //อัตราการใช้ (นาที/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้งต่อวัน)
            'param4'=>3, //อัตราการใช้ (วัน/สัปดาห์)
            'param5'=>4, //factor
            'param6'=>5, //electric power
            'param7'=>6  //ฉลากประหยัดไฟ
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

        return response()->download(storage_path('excel/'.$outputFile), 'หม้อหุงข้าว.xlsx');
    }


}
