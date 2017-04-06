<?php

namespace App\Http\Controllers;

use App\Main;
use App\Parameter;
use App\Setting;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary9ByToolRenewEnergy extends Controller
{
    public static $inputFile = 'summary9_by_tool_renew.xlsx';
    public static $outputFile = 'sum912bytool.xlsx';

    public function reportTool($tool_number)
    {
        $tool_number = (int)$tool_number;
        $currentClass = new Summary9ByToolRenewEnergy();
        if(method_exists($currentClass,'report'.$tool_number)){
            list($outputFile, $outputName) = Summary9ByToolRenewEnergy::{"report".$tool_number}();

            return response()->download(storage_path('excel/'.$outputFile), $outputName);
        }
    }
    // เตาบาร์บีคิว
    public static function report1()
    {
        set_time_limit(3600);

        // หมวดไล่และล่อแมลง
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolRenewEnergy::$inputFile;
        $inputSheet = '1';
        $startRow = 5;
        $outputFile = Summary9ByToolRenewEnergy::$outputFile;
        $outputName = 'เตาบาร์บีคิว.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $amount = [
            //======== พลังงานหมุนเวียนดั้งเดิม
            'no_ch1026_o342_ch210_o100',
            'no_ch1026_o342_ch210_o101',
            'no_ch1026_o342_ch210_o102',
            'no_ch1026_o342_ch210_o103',
            'no_ch1026_o342',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            //======== พลังงานหมุนเวียนดั้งเดิม
            'no_ch1026_o342_ch210_o100_nu211',
            'no_ch1026_o342_ch210_o101_nu211',
            'no_ch1026_o342_ch210_o102_nu211',
            'no_ch1026_o342_ch210_o103_nu211',
            [
                'no_ch1026_o342_ch210_o100_nu211',
                'no_ch1026_o342_ch210_o101_nu211',
                'no_ch1026_o342_ch210_o102_nu211',
                'no_ch1026_o342_ch210_o103_nu211'
            ]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        // ดั้งเดิม
        $renewableFactors = array();
        for ($i = 1; $i<=32; $i++){
            $renewableFactors[$i] = (float)$settings->where('code','tool_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_renewable_'. $i)->first()->value;
        }
        // สูตรคำนวณ (จำนวนเตา * อัตราการเติมเชื้อเพลิง (กก/ครั้ง) * อัตราการใช้ (ครั้ง/เดือน) * 12) * factor
        $usage3 = [
            ['no_ch1026_o342_ch210_o100_nu211','no_ch1026_o342_ch210_o100_nu212','no_ch1026_o342_ch210_o100_nu213',$settings->where('code','E10')->first()->value, $renewableFactors[1] ],
            ['no_ch1026_o342_ch210_o101_nu211','no_ch1026_o342_ch210_o101_nu212','no_ch1026_o342_ch210_o101_nu213',$settings->where('code','E11')->first()->value, $renewableFactors[2] ],
            ['no_ch1026_o342_ch210_o102_nu211','no_ch1026_o342_ch210_o102_nu212','no_ch1026_o342_ch210_o102_nu213',$settings->where('code','E12')->first()->value, $renewableFactors[3] ],
            ['no_ch1026_o342_ch210_o103_nu211','no_ch1026_o342_ch210_o103_nu212','no_ch1026_o342_ch210_o103_nu213',$settings->where('code','E13')->first()->value, $renewableFactors[4] ],
            ];
//        // ตำแหน่ง index ที่เก็บค่า ktoe ของเชื้อเพลิงแต่ละประเภท
        $ktoeIdx = 3;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12.0 * param4 as sumAmount ";
        $params = ['param1'=>0, 'param2'=>1, 'param3'=>2, 'param4'=>4];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage3, $startColumn, $startRow, $objPHPExcel, $mainObj, $sumAmountSQL, $params,0,true, $ktoeIdx);

        //Table4
        $table4 = [
            //======== พลังงานหมุนเวียนดั้งเดิม
            'no_ch1026_o342_ch210_o100_nu214',
            'no_ch1026_o342_ch210_o101_nu214',
            'no_ch1026_o342_ch210_o102_nu214',
            'no_ch1026_o342_ch210_o103_nu214',
            [
                'no_ch1026_o342_ch210_o100_nu214',
                'no_ch1026_o342_ch210_o101_nu214',
                'no_ch1026_o342_ch210_o102_nu214',
                'no_ch1026_o342_ch210_o103_nu214'
            ]
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เตาอังโล่
    public static function report2()
    {
        set_time_limit(3600);

        // หมวดไล่และล่อแมลง
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolRenewEnergy::$inputFile;
        $inputSheet = '2';
        $startRow = 5;
        $outputFile = Summary9ByToolRenewEnergy::$outputFile;
        $outputName = 'เตาอังโล่.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $amount = [
            'no_ch1026_o343_ch216_o100',
            'no_ch1026_o343_ch216_o101',
            'no_ch1026_o343_ch216_o102',
            'no_ch1026_o343_ch216_o103',
            'no_ch1026_o343'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            'no_ch1026_o343_ch216_o100_nu217',
            'no_ch1026_o343_ch216_o101_nu217',
            'no_ch1026_o343_ch216_o102_nu217',
            'no_ch1026_o343_ch216_o103_nu217',
            [
                'no_ch1026_o343_ch216_o100_nu217',
                'no_ch1026_o343_ch216_o101_nu217',
                'no_ch1026_o343_ch216_o102_nu217',
                'no_ch1026_o343_ch216_o103_nu217'
            ]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        // ดั้งเดิม
        $renewableFactors = array();
        for ($i = 1; $i<=32; $i++){
            $renewableFactors[$i] = (float)$settings->where('code','tool_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_renewable_'. $i)->first()->value;
        }
        // สูตรคำนวณ (จำนวนเตา * อัตราการเติมเชื้อเพลิง (กก/ครั้ง) * อัตราการใช้ (ครั้ง/เดือน) * 12) * factor
        $usage3 = [
            ['no_ch1026_o343_ch216_o100_nu217','no_ch1026_o343_ch216_o100_nu218','no_ch1026_o343_ch216_o100_nu219',$settings->where('code','E10')->first()->value, $renewableFactors[5] ],
            ['no_ch1026_o343_ch216_o101_nu217','no_ch1026_o343_ch216_o101_nu218','no_ch1026_o343_ch216_o101_nu219',$settings->where('code','E11')->first()->value, $renewableFactors[6] ],
            ['no_ch1026_o343_ch216_o102_nu217','no_ch1026_o343_ch216_o102_nu218','no_ch1026_o343_ch216_o102_nu219',$settings->where('code','E12')->first()->value, $renewableFactors[7] ],
            ['no_ch1026_o343_ch216_o103_nu217','no_ch1026_o343_ch216_o103_nu218','no_ch1026_o343_ch216_o103_nu219',$settings->where('code','E13')->first()->value, $renewableFactors[8] ],
        ];
//        // ตำแหน่ง index ที่เก็บค่า ktoe ของเชื้อเพลิงแต่ละประเภท
        $ktoeIdx = 3;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12.0 * param4 as sumAmount ";
        $params = ['param1'=>0, 'param2'=>1, 'param3'=>2, 'param4'=>4];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage3, $startColumn, $startRow, $objPHPExcel, $mainObj, $sumAmountSQL, $params,0,true, $ktoeIdx);

        //Table4
        $table4 = [
            'no_ch1026_o343_ch216_o100_nu220',
            'no_ch1026_o343_ch216_o101_nu220',
            'no_ch1026_o343_ch216_o102_nu220',
            'no_ch1026_o343_ch216_o103_nu220',
            [
                'no_ch1026_o343_ch216_o100_nu220',
                'no_ch1026_o343_ch216_o101_nu220',
                'no_ch1026_o343_ch216_o102_nu220',
                'no_ch1026_o343_ch216_o103_nu220'
            ]
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เตาประสิทธิภาพสูง
    public static function report3()
    {
        set_time_limit(3600);

        // หมวดไล่และล่อแมลง
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolRenewEnergy::$inputFile;
        $inputSheet = '3';
        $startRow = 5;
        $outputFile = Summary9ByToolRenewEnergy::$outputFile;
        $outputName = 'เตาประสิทธิภาพสูง.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $amount = [
            'no_ch1026_o344_ch222_o100',
            'no_ch1026_o344_ch222_o101',
            'no_ch1026_o344_ch222_o102',
            'no_ch1026_o344_ch222_o103',
            'no_ch1026_o344',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            'no_ch1026_o344_ch222_o100_nu223',
            'no_ch1026_o344_ch222_o101_nu223',
            'no_ch1026_o344_ch222_o102_nu223',
            'no_ch1026_o344_ch222_o103_nu223',
            [
                'no_ch1026_o344_ch222_o100_nu223',
                'no_ch1026_o344_ch222_o101_nu223',
                'no_ch1026_o344_ch222_o102_nu223',
                'no_ch1026_o344_ch222_o103_nu223',
            ]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        // ดั้งเดิม
        $renewableFactors = array();
        for ($i = 1; $i<=32; $i++){
            $renewableFactors[$i] = (float)$settings->where('code','tool_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_renewable_'. $i)->first()->value;
        }
        // สูตรคำนวณ (จำนวนเตา * อัตราการเติมเชื้อเพลิง (กก/ครั้ง) * อัตราการใช้ (ครั้ง/เดือน) * 12) * factor
        $usage3 = [
            ['no_ch1026_o344_ch222_o100_nu223','no_ch1026_o344_ch222_o100_nu224','no_ch1026_o344_ch222_o100_nu225',$settings->where('code','E10')->first()->value, $renewableFactors[9] ],
            ['no_ch1026_o344_ch222_o101_nu223','no_ch1026_o344_ch222_o101_nu224','no_ch1026_o344_ch222_o101_nu225',$settings->where('code','E11')->first()->value, $renewableFactors[10] ],
            ['no_ch1026_o344_ch222_o102_nu223','no_ch1026_o344_ch222_o102_nu224','no_ch1026_o344_ch222_o102_nu225',$settings->where('code','E12')->first()->value, $renewableFactors[11] ],
            ['no_ch1026_o344_ch222_o103_nu223','no_ch1026_o344_ch222_o103_nu224','no_ch1026_o344_ch222_o103_nu225',$settings->where('code','E13')->first()->value, $renewableFactors[12] ],
        ];
//        // ตำแหน่ง index ที่เก็บค่า ktoe ของเชื้อเพลิงแต่ละประเภท
        $ktoeIdx = 3;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12.0 * param4 as sumAmount ";
        $params = ['param1'=>0, 'param2'=>1, 'param3'=>2, 'param4'=>4];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage3, $startColumn, $startRow, $objPHPExcel, $mainObj, $sumAmountSQL, $params,0,true, $ktoeIdx);

        //Table4
        $table4 = [
            'no_ch1026_o344_ch222_o100_nu226',
            'no_ch1026_o344_ch222_o101_nu226',
            'no_ch1026_o344_ch222_o102_nu226',
            'no_ch1026_o344_ch222_o103_nu226',
            [
                'no_ch1026_o344_ch222_o100_nu226',
                'no_ch1026_o344_ch222_o101_nu226',
                'no_ch1026_o344_ch222_o102_nu226',
                'no_ch1026_o344_ch222_o103_nu226',
            ]
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เตาเศรษฐกิจ
    public static function report4()
    {
        set_time_limit(3600);

        // หมวดไล่และล่อแมลง
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolRenewEnergy::$inputFile;
        $inputSheet = '4';
        $startRow = 5;
        $outputFile = Summary9ByToolRenewEnergy::$outputFile;
        $outputName = 'เตาเศรษฐกิจ.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $amount = [
            'no_ch1026_o345_ch228_o100',
            'no_ch1026_o345_ch228_o101',
            'no_ch1026_o345_ch228_o102',
            'no_ch1026_o345_ch228_o103',
            'no_ch1026_o345',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            'no_ch1026_o345_ch228_o100_nu229',
            'no_ch1026_o345_ch228_o101_nu229',
            'no_ch1026_o345_ch228_o102_nu229',
            'no_ch1026_o345_ch228_o103_nu229',
            [
                'no_ch1026_o345_ch228_o100_nu229',
                'no_ch1026_o345_ch228_o101_nu229',
                'no_ch1026_o345_ch228_o102_nu229',
                'no_ch1026_o345_ch228_o103_nu229',
            ]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        // ดั้งเดิม
        $renewableFactors = array();
        for ($i = 1; $i<=32; $i++){
            $renewableFactors[$i] = (float)$settings->where('code','tool_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_renewable_'. $i)->first()->value;
        }
        // สูตรคำนวณ (จำนวนเตา * อัตราการเติมเชื้อเพลิง (กก/ครั้ง) * อัตราการใช้ (ครั้ง/เดือน) * 12) * factor
        $usage3 = [
            ['no_ch1026_o345_ch228_o100_nu229','no_ch1026_o345_ch228_o100_nu230','no_ch1026_o345_ch228_o100_nu231',$settings->where('code','E10')->first()->value, $renewableFactors[13] ],
            ['no_ch1026_o345_ch228_o101_nu229','no_ch1026_o345_ch228_o101_nu230','no_ch1026_o345_ch228_o101_nu231',$settings->where('code','E11')->first()->value, $renewableFactors[14] ],
            ['no_ch1026_o345_ch228_o102_nu229','no_ch1026_o345_ch228_o102_nu230','no_ch1026_o345_ch228_o102_nu231',$settings->where('code','E12')->first()->value, $renewableFactors[15] ],
            ['no_ch1026_o345_ch228_o103_nu229','no_ch1026_o345_ch228_o103_nu230','no_ch1026_o345_ch228_o103_nu231',$settings->where('code','E13')->first()->value, $renewableFactors[16] ],
        ];
//        // ตำแหน่ง index ที่เก็บค่า ktoe ของเชื้อเพลิงแต่ละประเภท
        $ktoeIdx = 3;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12.0 * param4 as sumAmount ";
        $params = ['param1'=>0, 'param2'=>1, 'param3'=>2, 'param4'=>4];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage3, $startColumn, $startRow, $objPHPExcel, $mainObj, $sumAmountSQL, $params,0,true, $ktoeIdx);

        //Table4
        $table4 = [
            'no_ch1026_o345_ch228_o100_nu232',
            'no_ch1026_o345_ch228_o101_nu232',
            'no_ch1026_o345_ch228_o102_nu232',
            'no_ch1026_o345_ch228_o103_nu232',
            [
                'no_ch1026_o345_ch228_o100_nu232',
                'no_ch1026_o345_ch228_o101_nu232',
                'no_ch1026_o345_ch228_o102_nu232',
                'no_ch1026_o345_ch228_o103_nu232',
            ]
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เตาเหล็ก3ขา
    public static function report5()
    {
        set_time_limit(3600);

        // หมวดไล่และล่อแมลง
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolRenewEnergy::$inputFile;
        $inputSheet = '5';
        $startRow = 5;
        $outputFile = Summary9ByToolRenewEnergy::$outputFile;
        $outputName = 'เตาเหล็ก3ขา.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $amount = [
            'no_ch1026_o346_ch234_o100',
            'no_ch1026_o346_ch234_o101',
            'no_ch1026_o346_ch234_o102',
            'no_ch1026_o346_ch234_o103',
            'no_ch1026_o346'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
            'no_ch1026_o346_ch234_o100_nu235',
            'no_ch1026_o346_ch234_o101_nu235',
            'no_ch1026_o346_ch234_o102_nu235',
            'no_ch1026_o346_ch234_o103_nu235',
            [
            'no_ch1026_o346_ch234_o100_nu235',
            'no_ch1026_o346_ch234_o101_nu235',
            'no_ch1026_o346_ch234_o102_nu235',
            'no_ch1026_o346_ch234_o103_nu235'
            ]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        // ดั้งเดิม
        $renewableFactors = array();
        for ($i = 1; $i<=32; $i++){
            $renewableFactors[$i] = (float)$settings->where('code','tool_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_renewable_'. $i)->first()->value;
        }
        // สูตรคำนวณ (จำนวนเตา * อัตราการเติมเชื้อเพลิง (กก/ครั้ง) * อัตราการใช้ (ครั้ง/เดือน) * 12) * factor
        $usage3 = [
            ['no_ch1026_o346_ch234_o100_nu235','no_ch1026_o346_ch234_o100_nu236','no_ch1026_o346_ch234_o100_nu237',$settings->where('code','E10')->first()->value, $renewableFactors[17] ],
            ['no_ch1026_o346_ch234_o101_nu235','no_ch1026_o346_ch234_o101_nu236','no_ch1026_o346_ch234_o101_nu237',$settings->where('code','E11')->first()->value, $renewableFactors[18] ],
            ['no_ch1026_o346_ch234_o102_nu235','no_ch1026_o346_ch234_o102_nu236','no_ch1026_o346_ch234_o102_nu237',$settings->where('code','E12')->first()->value, $renewableFactors[19] ],
            ['no_ch1026_o346_ch234_o103_nu235','no_ch1026_o346_ch234_o103_nu236','no_ch1026_o346_ch234_o103_nu237',$settings->where('code','E13')->first()->value, $renewableFactors[20] ],
        ];
//        // ตำแหน่ง index ที่เก็บค่า ktoe ของเชื้อเพลิงแต่ละประเภท
        $ktoeIdx = 3;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12.0 * param4 as sumAmount ";
        $params = ['param1'=>0, 'param2'=>1, 'param3'=>2, 'param4'=>4];
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage3, $startColumn, $startRow, $objPHPExcel, $mainObj, $sumAmountSQL, $params,0,true, $ktoeIdx);

        //Table4
        $table4 = [
            'no_ch1026_o346_ch228_o100_nu232',
            'no_ch1026_o346_ch228_o101_nu232',
            'no_ch1026_o346_ch228_o102_nu232',
            'no_ch1026_o346_ch228_o103_nu232',
            [
            'no_ch1026_o346_ch228_o100_nu232',
            'no_ch1026_o346_ch228_o101_nu232',
            'no_ch1026_o346_ch228_o102_nu232',
            'no_ch1026_o346_ch228_o103_nu232'
            ]
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $amountUniqueKey, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }

}
