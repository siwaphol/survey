<?php

namespace App\Http\Controllers\Summary9;

use App\Main;
use App\Parameter;
use App\Setting;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary912 extends Controller
{
    public static function report912()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

//        $settings = Setting::all();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.2';
        $startRow = 13;
        $outputFile = 'sum912.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        // หมวดประกอบอาหาร
        $amount = [
//            'no_ch1024_o331_ch123_o75_ch124_o78',
//            'no_ch1024_o331_ch123_o75_ch124_o79',
//            'no_ch1024_o331_ch123_o75_ch124_o80',
//            'no_ch1024_o331_ch123_o76_ch1011_o78',
//            'no_ch1024_o331_ch123_o76_ch1011_o79',
//            'no_ch1024_o331_ch123_o77_ch1011_o78',
//            'no_ch1024_o331_ch123_o77_ch1011_o79',
        // เตาหุงต้มไฟฟ้า
//            'no_ch1024_o332_ch132_o83',
//            'no_ch1024_o332_ch132_o84',
//            'no_ch1024_o332_ch132_o85',
        //ไมโครเวฟ
//            'no_ch1024_o333',
            //เตาอบไฟฟ้า
//            'no_ch1024_o334',
        // กระติกน้ำร้อน
//            'no_ch1024_o335_ch156_o287',
//            'no_ch1024_o335_ch156_o288',
        // กาต้อมน้ำไฟฟ้า
//            'no_ch1024_o336',
        // กระทะไฟฟ้า
//            'no_ch1024_o337',
//            'no_ch1024_o338',
//            'no_ch1024_o339',
//            'no_ch1024_o340',
// ==== น้ำมันสำเร็จรูป
//            'no_ch1025_o341_ch202_o94',
//            'no_ch1025_o341_ch202_o95',
//            'no_ch1025_o341_ch202_o96',
        //======== พลังงานหมุนเวียนดั้งเดิม
            'no_ch1026_o342_ch210_o100',
            'no_ch1026_o342_ch210_o101',
            'no_ch1026_o342_ch210_o102',
            'no_ch1026_o342_ch210_o103',
            'no_ch1026_o343_ch216_o100',
            'no_ch1026_o343_ch216_o101',
            'no_ch1026_o343_ch216_o102',
            'no_ch1026_o343_ch216_o103',
            'no_ch1026_o344_ch222_o100',
            'no_ch1026_o344_ch222_o101',
            'no_ch1026_o344_ch222_o102',
            'no_ch1026_o344_ch222_o103',
            'no_ch1026_o345_ch228_o100',
            'no_ch1026_o345_ch228_o101',
            'no_ch1026_o345_ch228_o102',
            'no_ch1026_o345_ch228_o103',
            'no_ch1026_o346_ch234_o100',
            'no_ch1026_o346_ch234_o101',
            'no_ch1026_o346_ch234_o102',
            'no_ch1026_o346_ch234_o103'
        ];
        $startColumn = 'E';
        $objPHPExcel = Summary::sum($amount, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $amountUniqueKey = [
//            'no_ch1024_o331_ch123_o75_ch124_o78_nu125',
//            'no_ch1024_o331_ch123_o75_ch124_o79_nu125',
//            'no_ch1024_o331_ch123_o75_ch124_o80_nu125',
//            'no_ch1024_o331_ch123_o76_ch1011_o78_nu1012',
//            'no_ch1024_o331_ch123_o76_ch1011_o79_nu1012',
//            'no_ch1024_o331_ch123_o77_ch1011_o78_nu1012',
//            'no_ch1024_o331_ch123_o77_ch1011_o79_nu1012',
            // เตาหุงต้มไฟฟ้า
//            'no_ch1024_o332_ch132_o83_nu133',
//            'no_ch1024_o332_ch132_o84_nu133',
//            'no_ch1024_o332_ch132_o85_nu133',
//ไมโครเวฟ
//            'no_ch1024_o333_nu141',
//เตาอบไฟฟ้า
//            'no_ch1024_o334_nu149',
        // กระติกน้ำร้อน
//            'no_ch1024_o335_ch156_o287_nu157',
//            'no_ch1024_o335_ch156_o288_nu157',
        // กาต้มน้ำไฟฟ้า
//            'no_ch1024_o336_nu166',
//กระทะไฟฟ้า
//            'no_ch1024_o337_nu174',
//            'no_ch1024_o338_nu182',
//            'no_ch1024_o339_nu189',
//            'no_ch1024_o340_nu196',
// ==== น้ำมันสำเร็จรูป
//            'no_ch1025_o341_ch202_o94_ch204_o98_nu205',
//            'no_ch1025_o341_ch202_o95_ch204_o98_nu205',
//            'no_ch1025_o341_ch202_o96_ch1018_o97_nu1019',
//======== พลังงานหมุนเวียนดั้งเดิม
            'no_ch1026_o342_ch210_o100_nu211',
            'no_ch1026_o342_ch210_o101_nu211',
            'no_ch1026_o342_ch210_o102_nu211',
            'no_ch1026_o342_ch210_o103_nu211',
            'no_ch1026_o343_ch216_o100_nu217',
            'no_ch1026_o343_ch216_o101_nu217',
            'no_ch1026_o343_ch216_o102_nu217',
            'no_ch1026_o343_ch216_o103_nu217',
            'no_ch1026_o344_ch222_o100_nu223',
            'no_ch1026_o344_ch222_o101_nu223',
            'no_ch1026_o344_ch222_o102_nu223',
            'no_ch1026_o344_ch222_o103_nu223',
            'no_ch1026_o345_ch228_o100_nu229',
            'no_ch1026_o345_ch228_o101_nu229',
            'no_ch1026_o345_ch228_o102_nu229',
            'no_ch1026_o345_ch228_o103_nu229',
            'no_ch1026_o346_ch234_o100_nu235',
            'no_ch1026_o346_ch234_o101_nu235',
            'no_ch1026_o346_ch234_o102_nu235',
            'no_ch1026_o346_ch234_o103_nu235'
        ];
        $startColumn = 'U';
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
//            ['no_ch1024_o331_ch123_o75_ch124_o78_nu125','no_ch1024_o331_ch123_o75_ch124_o78_nu126', 'no_ch1024_o331_ch123_o75_ch124_o78_nu127','no_ch1024_o331_ch123_o75_ch124_o78_nu128',$factors[7], $electricPower[7],'no_ch1024_o331_ch123_o75_ch124_o78_ra130'],
//            ['no_ch1024_o331_ch123_o75_ch124_o79_nu125','no_ch1024_o331_ch123_o75_ch124_o79_nu126', 'no_ch1024_o331_ch123_o75_ch124_o79_nu127','no_ch1024_o331_ch123_o75_ch124_o79_nu128',$factors[9], $electricPower[9], 'no_ch1024_o331_ch123_o75_ch124_o79_ra130'],
//            ['no_ch1024_o331_ch123_o75_ch124_o80_nu125','no_ch1024_o331_ch123_o75_ch124_o80_nu126', 'no_ch1024_o331_ch123_o75_ch124_o80_nu127','no_ch1024_o331_ch123_o75_ch124_o80_nu128',$factors[11], $electricPower[11], 'no_ch1024_o331_ch123_o75_ch124_o80_ra130'],
//            ['no_ch1024_o331_ch123_o76_ch1011_o78_nu1012','no_ch1024_o331_ch123_o76_ch1011_o78_nu1013', 'no_ch1024_o331_ch123_o76_ch1011_o78_nu1014','no_ch1024_o331_ch123_o76_ch1011_o78_nu1015',$factors[13], $electricPower[13],'no_ch1024_o331_ch123_o76_ch1011_o78_ra1017'],
//            ['no_ch1024_o331_ch123_o76_ch1011_o79_nu1012','no_ch1024_o331_ch123_o76_ch1011_o79_nu1013', 'no_ch1024_o331_ch123_o76_ch1011_o79_nu1014','no_ch1024_o331_ch123_o76_ch1011_o79_nu1015',$factors[15], $electricPower[15],'no_ch1024_o331_ch123_o76_ch1011_o79_ra1017'],
//            ['no_ch1024_o331_ch123_o77_ch1011_o78_nu1012','no_ch1024_o331_ch123_o77_ch1011_o78_nu1013', 'no_ch1024_o331_ch123_o77_ch1011_o78_nu1014','no_ch1024_o331_ch123_o77_ch1011_o78_nu1015',$factors[17], $electricPower[17],'no_ch1024_o331_ch123_o77_ch1011_o78_ra1017'],
//            ['no_ch1024_o331_ch123_o77_ch1011_o79_nu1012','no_ch1024_o331_ch123_o77_ch1011_o79_nu1013', 'no_ch1024_o331_ch123_o77_ch1011_o79_nu1014','no_ch1024_o331_ch123_o77_ch1011_o79_nu1015',$factors[19], $electricPower[19],'no_ch1024_o331_ch123_o77_ch1011_o79_ra1017'],
            // เตาหุงต้มไฟฟ้า
//            ['no_ch1024_o332_ch132_o83_nu133','no_ch1024_o332_ch132_o83_nu134', 'no_ch1024_o332_ch132_o83_nu135','no_ch1024_o332_ch132_o83_nu136',$factors[21],$electricPower[21],''],
//            ['no_ch1024_o332_ch132_o84_nu133','no_ch1024_o332_ch132_o84_nu134', 'no_ch1024_o332_ch132_o84_nu135','no_ch1024_o332_ch132_o84_nu136',$factors[22],$electricPower[22],''],
//            ['no_ch1024_o332_ch132_o85_nu133','no_ch1024_o332_ch132_o85_nu134', 'no_ch1024_o332_ch132_o85_nu135','no_ch1024_o332_ch132_o85_nu136',$factors[23],$electricPower[23],'no_ch1024_o332_ch132_o85_ra138'],
//ไมโครเวฟ
//            ['no_ch1024_o333_nu141','no_ch1024_o333_nu142', 'no_ch1024_o333_nu143','no_ch1024_o333_nu144',$factors[25],$electricPower[25],'no_ch1024_o333_ra146'],
//เตาอบไฟฟ้า
//            ['no_ch1024_o334_nu149','no_ch1024_o334_nu150', 'no_ch1024_o334_nu151','no_ch1024_o334_nu152',$factors[27], $electricPower[27], 'no_ch1024_o334_ra154'],
            //กระติกน้ำร้อน
//            ['no_ch1024_o335_ch156_o287_nu157','no_ch1024_o335_ch156_o287_nu158', 'no_ch1024_o335_ch156_o287_nu159','no_ch1024_o335_ch156_o287_nu160',$factors[29], $electricPower[29], 'no_ch1024_o335_ch156_o287_ra162'],
//            ['no_ch1024_o335_ch156_o288_nu157','no_ch1024_o335_ch156_o288_nu158', 'no_ch1024_o335_ch156_o288_nu159','no_ch1024_o335_ch156_o288_nu160',$factors[31], $electricPower[31], 'no_ch1024_o335_ch156_o288_ra162'],
            // กาต้มน้ำไฟฟ้า
//            ['no_ch1024_o336_nu166','no_ch1024_o336_nu167', 'no_ch1024_o336_nu168','no_ch1024_o336_nu169',$factors[33], $electricPower[33], 'no_ch1024_o336_ra171'],
        // กระทะไฟฟ้า
//            ['no_ch1024_o337_nu174','no_ch1024_o337_nu175', 'no_ch1024_o337_nu176','no_ch1024_o337_nu177',$factors[35], $electricPower[35], 'no_ch1024_o337_ra179'],
//            ['no_ch1024_o338_nu182','no_ch1024_o338_nu183', 'no_ch1024_o338_nu184','no_ch1024_o338_nu185',$factors[37], $electricPower[37], ''],
//            ['no_ch1024_o339_nu189','no_ch1024_o339_nu190', 'no_ch1024_o339_nu191','no_ch1024_o339_nu192',$factors[38], $electricPower[38], ''],
//            ['no_ch1024_o340_nu196','no_ch1024_o340_nu197', 'no_ch1024_o340_nu198','no_ch1024_o340_nu199',$factors[39], $electricPower[39], '']
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
        $startColumn = 'AL';
//        $objPHPExcel = Summary::usageElectric($usage, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // 13.เตาหุงต้มแก๊ส
        $fuelFactors = array();
        $fuelPrice = array();
        $volumes = array();

        for ($i = 1; $i<=22; $i++){
            $fuelFactors[$i] = (float)$settings->where('code','tool_factor_fuel_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_fuel_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_fuel_'. $i)->first()->value;
            if ($i<5){
                $volumes[$i] = (float)$settings->where('code', 'volume_fuel_'. $i)->first()->value;
            }
            if ($i>=5){
                $fuelPrice[$i] = (float)$settings->where('code', 'fuel_price_fuel_' . $i)->first()->value;
            }
        }
        $usage2 = [
            ['no_ch1025_o341_ch202_o94_ch204_o98_nu206',$volumes[1], $fuelFactors[1]],
            ['no_ch1025_o341_ch202_o95_ch204_o98_nu206',$volumes[2], $fuelFactors[2]],
            ['no_ch1025_o341_ch202_o96_ch1018_o97_nu1020',$volumes[3], $fuelFactors[3]]
        ];

        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2
        ];
        $ktoe = Setting::where('code','E2')->first()->value;
        $startRow = 32;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) 
        * param2 * param3
        as sumAmount ";
//        $objPHPExcel = Summary::usageElectric($usage2, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

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

            ['no_ch1026_o343_ch216_o100_nu217','no_ch1026_o343_ch216_o100_nu218','no_ch1026_o343_ch216_o100_nu219',$settings->where('code','E10')->first()->value, $renewableFactors[5] ],
            ['no_ch1026_o343_ch216_o101_nu217','no_ch1026_o343_ch216_o101_nu218','no_ch1026_o343_ch216_o101_nu219',$settings->where('code','E11')->first()->value, $renewableFactors[6] ],
            ['no_ch1026_o343_ch216_o102_nu217','no_ch1026_o343_ch216_o102_nu218','no_ch1026_o343_ch216_o102_nu219',$settings->where('code','E12')->first()->value, $renewableFactors[7] ],
            ['no_ch1026_o343_ch216_o103_nu217','no_ch1026_o343_ch216_o103_nu218','no_ch1026_o343_ch216_o103_nu219',$settings->where('code','E13')->first()->value, $renewableFactors[8] ],

            ['no_ch1026_o344_ch222_o100_nu223','no_ch1026_o344_ch222_o100_nu224','no_ch1026_o344_ch222_o100_nu225',$settings->where('code','E10')->first()->value, $renewableFactors[9] ],
            ['no_ch1026_o344_ch222_o101_nu223','no_ch1026_o344_ch222_o101_nu224','no_ch1026_o344_ch222_o101_nu225',$settings->where('code','E11')->first()->value, $renewableFactors[10] ],
            ['no_ch1026_o344_ch222_o102_nu223','no_ch1026_o344_ch222_o102_nu224','no_ch1026_o344_ch222_o102_nu225',$settings->where('code','E12')->first()->value, $renewableFactors[11] ],
            ['no_ch1026_o344_ch222_o103_nu223','no_ch1026_o344_ch222_o103_nu224','no_ch1026_o344_ch222_o103_nu225',$settings->where('code','E13')->first()->value, $renewableFactors[12] ],

            ['no_ch1026_o345_ch228_o100_nu229','no_ch1026_o345_ch228_o100_nu230','no_ch1026_o345_ch228_o100_nu231',$settings->where('code','E10')->first()->value, $renewableFactors[13] ],
            ['no_ch1026_o345_ch228_o101_nu229','no_ch1026_o345_ch228_o101_nu230','no_ch1026_o345_ch228_o101_nu231',$settings->where('code','E11')->first()->value, $renewableFactors[14] ],
            ['no_ch1026_o345_ch228_o102_nu229','no_ch1026_o345_ch228_o102_nu230','no_ch1026_o345_ch228_o102_nu231',$settings->where('code','E12')->first()->value, $renewableFactors[15] ],
            ['no_ch1026_o345_ch228_o103_nu229','no_ch1026_o345_ch228_o103_nu230','no_ch1026_o345_ch228_o103_nu231',$settings->where('code','E13')->first()->value, $renewableFactors[16] ],

            ['no_ch1026_o346_ch234_o100_nu235','no_ch1026_o346_ch234_o100_nu236','no_ch1026_o346_ch234_o100_nu237',$settings->where('code','E10')->first()->value, $renewableFactors[17] ],
            ['no_ch1026_o346_ch234_o101_nu235','no_ch1026_o346_ch234_o101_nu236','no_ch1026_o346_ch234_o101_nu237',$settings->where('code','E11')->first()->value, $renewableFactors[18] ],
            ['no_ch1026_o346_ch234_o102_nu235','no_ch1026_o346_ch234_o102_nu236','no_ch1026_o346_ch234_o102_nu237',$settings->where('code','E12')->first()->value, $renewableFactors[19] ],
            ['no_ch1026_o346_ch234_o103_nu235','no_ch1026_o346_ch234_o103_nu236','no_ch1026_o346_ch234_o103_nu237',$settings->where('code','E13')->first()->value, $renewableFactors[20] ],
        ];
//        // ตำแหน่ง index ที่เก็บค่า ktoe ของเชื้อเพลิงแต่ละประเภท
        $ktoeIdx = 3;
        $startRow = 35;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12.0 * param4 as sumAmount ";
        $params = ['param1'=>0, 'param2'=>1, 'param3'=>2, 'param4'=>4];
        $objPHPExcel = Summary::usageElectric($usage3, $startColumn, $startRow, $objPHPExcel, $mainObj, $sumAmountSQL, $params,$ktoe,true, $ktoeIdx);

        //Table4
        $table4 = [
//            'no_ch1024_o331_ch123_o75_ch124_o78_nu129',
//            'no_ch1024_o331_ch123_o75_ch124_o79_nu129',
//            'no_ch1024_o331_ch123_o75_ch124_o80_nu129',
//            'no_ch1024_o331_ch123_o76_ch1011_o78_nu1016',
//            'no_ch1024_o331_ch123_o76_ch1011_o79_nu1016',
//            'no_ch1024_o331_ch123_o77_ch1011_o78_nu1016',
//            'no_ch1024_o331_ch123_o77_ch1011_o79_nu1016',
        // เตาหุงต้มไฟฟ้า
//            'no_ch1024_o332_ch132_o83_nu137',
//            'no_ch1024_o332_ch132_o84_nu137',
//            'no_ch1024_o332_ch132_o85_nu137',
// ไมโครเวฟ
//            'no_ch1024_o333_nu145',
            //เตาอบไฟฟ้า
//            'no_ch1024_o334_nu153',
//กระติกน้ำร้อน
//            'no_ch1024_o335_ch156_o287_nu161',
//            'no_ch1024_o335_ch156_o288_nu161',
        // กาต้มน้ำ
//            'no_ch1024_o336_nu170',
// กระทะไฟฟ้า
//            'no_ch1024_o337_nu178',
//            'no_ch1024_o338_nu186',
//            'no_ch1024_o339_nu193',
//            'no_ch1024_o340_nu200',
//======== พลังงานหมุนเวียนดั้งเดิม
            'no_ch1026_o342_ch210_o100_nu214',
            'no_ch1026_o342_ch210_o101_nu214',
            'no_ch1026_o342_ch210_o102_nu214',
            'no_ch1026_o342_ch210_o103_nu214',
            'no_ch1026_o343_ch216_o100_nu220',
            'no_ch1026_o343_ch216_o101_nu220',
            'no_ch1026_o343_ch216_o102_nu220',
            'no_ch1026_o343_ch216_o103_nu220',
            'no_ch1026_o344_ch222_o100_nu226',
            'no_ch1026_o344_ch222_o101_nu226',
            'no_ch1026_o344_ch222_o102_nu226',
            'no_ch1026_o344_ch222_o103_nu226',
            'no_ch1026_o345_ch228_o100_nu232',
            'no_ch1026_o345_ch228_o101_nu232',
            'no_ch1026_o345_ch228_o102_nu232',
            'no_ch1026_o345_ch228_o103_nu232',
            'no_ch1026_o346_ch228_o100_nu232',
            'no_ch1026_o346_ch228_o101_nu232',
            'no_ch1026_o346_ch228_o102_nu232',
            'no_ch1026_o346_ch228_o103_nu232'
        ];
        $table4Amount = [
//            'no_ch1024_o331_ch123_o75_ch124_o78_nu125',
//            'no_ch1024_o331_ch123_o75_ch124_o79_nu125',
//            'no_ch1024_o331_ch123_o75_ch124_o80_nu125',
//            'no_ch1024_o331_ch123_o76_ch1011_o78_nu1012',
//            'no_ch1024_o331_ch123_o76_ch1011_o79_nu1012',
//            'no_ch1024_o331_ch123_o77_ch1011_o78_nu1012',
//            'no_ch1024_o331_ch123_o77_ch1011_o79_nu1012',
//         เตาหุงต้มไฟฟ้า
//            'no_ch1024_o332_ch132_o83_nu133',
//            'no_ch1024_o332_ch132_o84_nu133',
//            'no_ch1024_o332_ch132_o85_nu133',
        // ไมโครเวฟ
//            'no_ch1024_o333_nu141',
            //เตาอบไฟฟ้า
//            'no_ch1024_o334_nu149',
        //กระติกน้ำร้อน
//            'no_ch1024_o335_ch156_o287_nu157',
//            'no_ch1024_o335_ch156_o288_nu157',
            // กาต้มน้ำ
//            'no_ch1024_o336_nu166',
        // กระทะไฟฟ้า
//            'no_ch1024_o337_nu174',
//            'no_ch1024_o338_nu182',
//            'no_ch1024_o339_nu189',
//            'no_ch1024_o340_nu196',
            //======== พลังงานหมุนเวียนดั้งเดิม
            'no_ch1026_o342_ch210_o100_nu211',
            'no_ch1026_o342_ch210_o101_nu211',
            'no_ch1026_o342_ch210_o102_nu211',
            'no_ch1026_o342_ch210_o103_nu211',
            'no_ch1026_o343_ch216_o100_nu217',
            'no_ch1026_o343_ch216_o101_nu217',
            'no_ch1026_o343_ch216_o102_nu217',
            'no_ch1026_o343_ch216_o103_nu217',
            'no_ch1026_o344_ch222_o100_nu223',
            'no_ch1026_o344_ch222_o101_nu223',
            'no_ch1026_o344_ch222_o102_nu223',
            'no_ch1026_o344_ch222_o103_nu223',
            'no_ch1026_o345_ch228_o100_nu229',
            'no_ch1026_o345_ch228_o101_nu229',
            'no_ch1026_o345_ch228_o102_nu229',
            'no_ch1026_o345_ch228_o103_nu229',
            'no_ch1026_o346_ch234_o100_nu235',
            'no_ch1026_o346_ch234_o101_nu235',
            'no_ch1026_o346_ch234_o102_nu235',
            'no_ch1026_o346_ch234_o103_nu235'
        ];

        $table4_2 = [
// =========== น้ำมันสำเร็จรูป ===========
//            'no_ch1025_o341_ch202_o94_ch204_o98_nu207',
//            'no_ch1025_o341_ch202_o95_ch204_o98_nu207',
//            'no_ch1025_o341_ch202_o96_ch1018_o97_nu1021',
        ];
        $table4Amount_2 = [
            //====== น้ำมันสำเร็จรูป =======
//            'no_ch1025_o341_ch202_o94',
//            'no_ch1025_o341_ch202_o95',
//            'no_ch1025_o341_ch202_o96',
        ];

        $startColumn = 'BB';
        $startRow = 13;
        // สำหรับเครื่องใช้ไฟฟ้า
        $objPHPExcel = Summary::averageLifetime($table4, $table4Amount, $startColumn, $startRow, $objPHPExcel, $mainObj);
        // สำหรับเตาแก๊ส
//        $objPHPExcel = Summary::averageLifetime($table4_2, $table4Amount_2, $startColumn, $startRow, $objPHPExcel, $mainObj
//            ,false,[],false,null,1);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return response()->download(storage_path('excel/'.$outputFile), 'ตารางสรุปหมวดประกอบอาหาร.xlsx');
    }

}
