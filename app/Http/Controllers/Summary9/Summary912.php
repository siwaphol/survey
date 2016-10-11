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

        $settings = Setting::all();

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
            'no_ch1024_o331_ch123_o75_ch124_o78',
            'no_ch1024_o331_ch123_o75_ch124_o79',
            'no_ch1024_o331_ch123_o75_ch124_o80',
            'no_ch1024_o331_ch123_o76_ch1011_o78',
            'no_ch1024_o331_ch123_o76_ch1011_o79',
            'no_ch1024_o331_ch123_o77_ch1011_o78',
            'no_ch1024_o331_ch123_o77_ch1011_o79',
            'no_ch1024_o332_ch132_o83',
            'no_ch1024_o332_ch132_o84',
            'no_ch1024_o332_ch132_o85',
            'no_ch1024_o333',
            'no_ch1024_o334',
            'no_ch1024_o335_ch156_o287',
            'no_ch1024_o335_ch156_o288',
            'no_ch1024_o336',
            'no_ch1024_o337',
            'no_ch1024_o338',
            'no_ch1024_o339',
            'no_ch1024_o340',
            'no_ch1025_o341_ch202_o94',
            'no_ch1025_o341_ch202_o95',
            'no_ch1025_o341_ch202_o96',
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
            'no_ch1024_o331_ch123_o75_ch124_o78_nu125',
            'no_ch1024_o331_ch123_o75_ch124_o79_nu125',
            'no_ch1024_o331_ch123_o75_ch124_o80_nu125',
            'no_ch1024_o331_ch123_o76_ch1011_o78_nu1012',
            'no_ch1024_o331_ch123_o76_ch1011_o79_nu1012',
            'no_ch1024_o331_ch123_o77_ch1011_o78_nu1012',
            'no_ch1024_o331_ch123_o77_ch1011_o79_nu1012',
            'no_ch1024_o332_ch132_o83_nu133',
            'no_ch1024_o332_ch132_o84_nu133',
            'no_ch1024_o332_ch132_o85_nu133',
            'no_ch1024_o333_nu141',
            'no_ch1024_o334_nu149',
            'no_ch1024_o335_ch156_o287_nu157',
            'no_ch1024_o335_ch156_o288_nu157',
            'no_ch1024_o336_nu166',
            'no_ch1024_o337_nu174',
            'no_ch1024_o338_nu182',
            'no_ch1024_o339_nu189',
            'no_ch1024_o340_nu196',
            ['no_ch1025_o341_ch202_o94_ch204_o97_nu205','no_ch1025_o341_ch202_o94_ch204_o98_nu205','no_ch1025_o341_ch202_o94_ch204_o99_nu205'],
            ['no_ch1025_o341_ch202_o95_ch204_o97_nu205','no_ch1025_o341_ch202_o95_ch204_o98_nu205','no_ch1025_o341_ch202_o95_ch204_o99_nu205'],
            ['no_ch1025_o341_ch202_o96_ch1018_o97_nu1019'],
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

        //usage and ktoe
        $usage = [
            ['no_ch1024_o331_ch123_o75_ch124_o78_nu126', 'no_ch1024_o331_ch123_o75_ch124_o78_nu127','no_ch1024_o331_ch123_o75_ch124_o78_nu128', ((float)$settings->where('code','A13')->first()->value/1000.0) ,'no_ch1024_o331_ch123_o75_ch124_o78_nu125'],
            ['no_ch1024_o331_ch123_o75_ch124_o79_nu126', 'no_ch1024_o331_ch123_o75_ch124_o79_nu127','no_ch1024_o331_ch123_o75_ch124_o79_nu128',((float)$settings->where('code','A14')->first()->value/1000.0),'no_ch1024_o331_ch123_o75_ch124_o79_nu125'],
            ['no_ch1024_o331_ch123_o75_ch124_o80_nu126', 'no_ch1024_o331_ch123_o75_ch124_o80_nu127','no_ch1024_o331_ch123_o75_ch124_o80_nu128',((float)$settings->where('code','A15')->first()->value/1000.0),'no_ch1024_o331_ch123_o75_ch124_o80_nu125'],
            ['no_ch1024_o331_ch123_o76_ch1011_o78_nu1013', 'no_ch1024_o331_ch123_o76_ch1011_o78_nu1014','no_ch1024_o331_ch123_o76_ch1011_o78_nu1015',((float)$settings->where('code','A16')->first()->value/1000.0),'no_ch1024_o331_ch123_o76_ch1011_o78_nu1012'],
            ['no_ch1024_o331_ch123_o76_ch1011_o79_nu1013', 'no_ch1024_o331_ch123_o76_ch1011_o79_nu1014','no_ch1024_o331_ch123_o76_ch1011_o79_nu1015',((float)$settings->where('code','A17')->first()->value/1000.0),'no_ch1024_o331_ch123_o76_ch1011_o79_nu1012'],
            ['no_ch1024_o331_ch123_o77_ch1011_o78_nu1013', 'no_ch1024_o331_ch123_o77_ch1011_o78_nu1014','no_ch1024_o331_ch123_o77_ch1011_o78_nu1015',((float)$settings->where('code','A18')->first()->value/1000.0),'no_ch1024_o331_ch123_o77_ch1011_o78_nu1012'],
            ['no_ch1024_o331_ch123_o77_ch1011_o79_nu1013', 'no_ch1024_o331_ch123_o77_ch1011_o79_nu1014','no_ch1024_o331_ch123_o77_ch1011_o79_nu1015',((float)$settings->where('code','A19')->first()->value/1000.0),'no_ch1024_o331_ch123_o77_ch1011_o79_nu1012'],
            ['no_ch1024_o332_ch132_o83_nu134', 'no_ch1024_o332_ch132_o83_nu135','no_ch1024_o332_ch132_o83_nu136',((float)$settings->where('code','A20')->first()->value/1000.0),'no_ch1024_o332_ch132_o83_nu133'],
            ['no_ch1024_o332_ch132_o84_nu134', 'no_ch1024_o332_ch132_o84_nu135','no_ch1024_o332_ch132_o84_nu136',((float)$settings->where('code','A21')->first()->value/1000.0),'no_ch1024_o332_ch132_o84_nu133'],
            ['no_ch1024_o332_ch132_o85_nu134', 'no_ch1024_o332_ch132_o85_nu135','no_ch1024_o332_ch132_o85_nu136',((float)$settings->where('code','A22')->first()->value/1000.0),'no_ch1024_o332_ch132_o85_nu133'],
            ['no_ch1024_o333_nu142', 'no_ch1024_o333_nu143','no_ch1024_o333_nu144',((float)$settings->where('code','A23')->first()->value/1000.0),'no_ch1024_o333_nu141'],
            ['no_ch1024_o334_nu150', 'no_ch1024_o334_nu151','no_ch1024_o334_nu152',((float)$settings->where('code','A24')->first()->value/1000.0),'no_ch1024_o334_nu149'],
            ['no_ch1024_o335_ch156_o287_nu158', 'no_ch1024_o335_ch156_o287_nu159','no_ch1024_o335_ch156_o287_nu160',((float)$settings->where('code','A25')->first()->value/1000.0),'no_ch1024_o335_ch156_o287_nu157'],
            ['no_ch1024_o335_ch156_o288_nu158', 'no_ch1024_o335_ch156_o288_nu159','no_ch1024_o335_ch156_o288_nu160',((float)$settings->where('code','A26')->first()->value/1000.0),'no_ch1024_o335_ch156_o288_nu157'],
            ['no_ch1024_o336_nu167', 'no_ch1024_o336_nu168','no_ch1024_o336_nu169',((float)$settings->where('code','A27')->first()->value/1000.0),'no_ch1024_o336_nu166'],
            ['no_ch1024_o337_nu175', 'no_ch1024_o337_nu176','no_ch1024_o337_nu177',((float)$settings->where('code','A28')->first()->value/1000.0),'no_ch1024_o337_nu174'],
            ['no_ch1024_o338_nu183', 'no_ch1024_o338_nu184','no_ch1024_o338_nu185',((float)$settings->where('code','A29')->first()->value/1000.0),'no_ch1024_o338_nu182'],
            ['no_ch1024_o339_nu190', 'no_ch1024_o339_nu191','no_ch1024_o339_nu192',((float)$settings->where('code','A30')->first()->value/1000.0),'no_ch1024_o339_nu189'],
            ['no_ch1024_o340_nu197', 'no_ch1024_o340_nu198','no_ch1024_o340_nu199',((float)$settings->where('code','A31')->first()->value/1000.0),'no_ch1024_o340_nu196']
        ];

        $week = $settings->where('code','B7')->first()->value;
        $ktoe = $settings->where('code','E9')->first()->value;

        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) * sum(if(unique_key='param2', answer_numeric,0)) * sum(if(unique_key='param3', answer_numeric,0))) * {$week} / 60 * (param4) * sum(if(unique_key='param5',answer_numeric,0)) as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2,
            'param4'=>3,
            'param5'=>4
        ];
        $startColumn = 'AL';
        $objPHPExcel = Summary::usageElectric($usage, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);
        // 13.เตาหุงต้มแก๊ส
        $usage2 = [
            [
                4,'no_ch1025_o341_ch202_o94_ch204_o97_nu205','no_ch1025_o341_ch202_o94_ch204_o97_nu206'
                ,15,'no_ch1025_o341_ch202_o94_ch204_o98_nu205','no_ch1025_o341_ch202_o94_ch204_o98_nu206'
                ,48,'no_ch1025_o341_ch202_o94_ch204_o99_nu205','no_ch1025_o341_ch202_o94_ch204_o99_nu206'
            ],
            [
                4,'no_ch1025_o341_ch202_o95_ch204_o97_nu205','no_ch1025_o341_ch202_o95_ch204_o97_nu206',
                15,'no_ch1025_o341_ch202_o95_ch204_o98_nu205','no_ch1025_o341_ch202_o95_ch204_o98_nu206',
                48,'no_ch1025_o341_ch202_o95_ch204_o99_nu205','no_ch1025_o341_ch202_o95_ch204_o99_nu206'
            ],
            [4,'no_ch1025_o341_ch202_o96_ch1018_o97_nu1019','no_ch1025_o341_ch202_o96_ch1018_o97_nu1020']
        ];
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2
        ];
        
        // คูณกับ 0.00042 เพื่อแปลงหน่วยก๊าซเป็นหน่วย m^3
        $ktoe = $settings->where('code','E1')->first()->value * 0.00042;

        $startRow = 32;
        $sumAmountSQL = " ( param1 * sum(IF(unique_key='param2',answer_numeric,0)) * sum(if(unique_key='param3', answer_numeric,0)) ) ";
        $table97 = [];
        foreach ($usage2 as $eachGasTank){
            $temp = $sumAmountSQL;
            $finalSql = "";
            for ($i=0; $i<count($eachGasTank); $i+=3){
                $temp = str_replace("param1", $eachGasTank[$i], $temp);
                $temp = str_replace("param2", $eachGasTank[$i+1], $temp);
                $temp = str_replace("param3", $eachGasTank[$i+2], $temp);

                $finalSql .= $temp ." + ";
            }
            $finalSql .= " 0 ";
            $table97[] = $finalSql;
        }
        $objPHPExcel = Summary::specialUsage($table97,$startColumn,$startRow,$objPHPExcel,$mainObj,$ktoe);
//        $objPHPExcel = Summary::usageElectric($usage2, $startColumn, $startRow, $objPHPExcel,$mainObj, $sumAmountSQL, $params,$ktoe,true);

        $usage3 = [
            ['no_ch1026_o342_ch210_o100_nu211','no_ch1026_o342_ch210_o100_nu212','no_ch1026_o342_ch210_o100_nu213',$settings->where('code','E10')->first()->value ],
            ['no_ch1026_o342_ch210_o101_nu211','no_ch1026_o342_ch210_o101_nu212','no_ch1026_o342_ch210_o101_nu213',$settings->where('code','E11')->first()->value ],
            ['no_ch1026_o342_ch210_o102_nu211','no_ch1026_o342_ch210_o102_nu212','no_ch1026_o342_ch210_o102_nu213',$settings->where('code','E12')->first()->value ],
            ['no_ch1026_o342_ch210_o103_nu211','no_ch1026_o342_ch210_o103_nu212','no_ch1026_o342_ch210_o103_nu213',$settings->where('code','E13')->first()->value ],
            ['no_ch1026_o343_ch216_o100_nu217','no_ch1026_o343_ch216_o100_nu218','no_ch1026_o343_ch216_o100_nu219',$settings->where('code','E10')->first()->value ],
            ['no_ch1026_o343_ch216_o101_nu217','no_ch1026_o343_ch216_o101_nu218','no_ch1026_o343_ch216_o101_nu219',$settings->where('code','E11')->first()->value ],
            ['no_ch1026_o343_ch216_o102_nu217','no_ch1026_o343_ch216_o102_nu218','no_ch1026_o343_ch216_o102_nu219',$settings->where('code','E12')->first()->value ],
            ['no_ch1026_o343_ch216_o103_nu217','no_ch1026_o343_ch216_o103_nu218','no_ch1026_o343_ch216_o103_nu219',$settings->where('code','E13')->first()->value ],
            ['no_ch1026_o344_ch222_o100_nu223','no_ch1026_o344_ch222_o100_nu224','no_ch1026_o344_ch222_o100_nu225',$settings->where('code','E10')->first()->value ],
            ['no_ch1026_o344_ch222_o101_nu223','no_ch1026_o344_ch222_o101_nu224','no_ch1026_o344_ch222_o101_nu225',$settings->where('code','E11')->first()->value ],
            ['no_ch1026_o344_ch222_o102_nu223','no_ch1026_o344_ch222_o102_nu224','no_ch1026_o344_ch222_o102_nu225',$settings->where('code','E12')->first()->value ],
            ['no_ch1026_o344_ch222_o103_nu223','no_ch1026_o344_ch222_o103_nu224','no_ch1026_o344_ch222_o103_nu225',$settings->where('code','E13')->first()->value ],
            ['no_ch1026_o345_ch228_o100_nu229','no_ch1026_o345_ch228_o100_nu230','no_ch1026_o345_ch228_o100_nu231',$settings->where('code','E10')->first()->value ],
            ['no_ch1026_o345_ch228_o101_nu229','no_ch1026_o345_ch228_o101_nu230','no_ch1026_o345_ch228_o101_nu231',$settings->where('code','E11')->first()->value ],
            ['no_ch1026_o345_ch228_o102_nu229','no_ch1026_o345_ch228_o102_nu230','no_ch1026_o345_ch228_o102_nu231',$settings->where('code','E12')->first()->value ],
            ['no_ch1026_o345_ch228_o103_nu229','no_ch1026_o345_ch228_o103_nu230','no_ch1026_o345_ch228_o103_nu231',$settings->where('code','E13')->first()->value ],
            ['no_ch1026_o346_ch234_o100_nu235','no_ch1026_o346_ch234_o100_nu236','no_ch1026_o346_ch234_o100_nu237',$settings->where('code','E10')->first()->value ],
            ['no_ch1026_o346_ch234_o101_nu235','no_ch1026_o346_ch234_o101_nu236','no_ch1026_o346_ch234_o101_nu237',$settings->where('code','E11')->first()->value ],
            ['no_ch1026_o346_ch234_o102_nu235','no_ch1026_o346_ch234_o102_nu236','no_ch1026_o346_ch234_o102_nu237',$settings->where('code','E12')->first()->value ],
            ['no_ch1026_o346_ch234_o103_nu235','no_ch1026_o346_ch234_o103_nu236','no_ch1026_o346_ch234_o103_nu237',$settings->where('code','E13')->first()->value ],
        ];
        // ตำแหน่ง index ที่เก็บค่า ktoe ของเชื้อเพลิงแต่ละประเภท
        $ktoeIdx = 3;
        $startRow = 35;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12.0 as sumAmount ";
        $params = ['param1'=>0, 'param2'=>1, 'param3'=>2];
        $objPHPExcel = Summary::usageElectric($usage3, $startColumn, $startRow, $objPHPExcel, $mainObj, $sumAmountSQL, $params,$ktoe,true, $ktoeIdx);

        //Table4
        $amount4 = [
            'no_ch1024_o331_ch123_o75_ch124_o78_nu129',
            'no_ch1024_o331_ch123_o75_ch124_o79_nu129',
            'no_ch1024_o331_ch123_o75_ch124_o80_nu129',
            'no_ch1024_o331_ch123_o76_ch1011_o78_nu1016',
            'no_ch1024_o331_ch123_o76_ch1011_o79_nu1016',
            'no_ch1024_o331_ch123_o77_ch1011_o78_nu1016',
            'no_ch1024_o331_ch123_o77_ch1011_o79_nu1016',
            'no_ch1024_o332_ch132_o83_nu137',
            'no_ch1024_o332_ch132_o84_nu137',
            'no_ch1024_o332_ch132_o85_nu137',
            'no_ch1024_o333_nu144',
            'no_ch1024_o334_nu153',
            'no_ch1024_o335_ch156_o287_nu161',
            'no_ch1024_o335_ch156_o288_nu161',
            'no_ch1024_o336_nu170',
            'no_ch1024_o337_nu178',
            'no_ch1024_o338_nu186',
            'no_ch1024_o339_nu193',
            'no_ch1024_o340_nu200',
            ['no_ch1025_o341_ch202_o94_ch204_o97_nu207', 'no_ch1025_o341_ch202_o94_ch204_o98_nu207', 'no_ch1025_o341_ch202_o94_ch204_o99_nu207'],
            ['no_ch1025_o341_ch202_o95_ch204_o97_nu207', 'no_ch1025_o341_ch202_o95_ch204_o98_nu207', 'no_ch1025_o341_ch202_o95_ch204_o99_nu207'],
            'no_ch1025_o341_ch202_o96_ch1018_o97_nu1021',
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
        $startColumn = 'BB';
        $startRow = 13;
        $objPHPExcel = Summary::average($amount4, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return response()->download(storage_path('excel/'.$outputFile), 'ตารางสรุปหมวดประกอบอาหาร.xlsx');
    }

}