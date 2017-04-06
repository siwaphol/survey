<?php

namespace App\Http\Controllers;

use App\Main;
use App\Setting;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary9ByToolFuel extends Controller
{
    public static $inputFile = 'summary9_by_tool_fuel.xlsx';
    public static $outputFile = 'sum912bytool.xlsx';

    public function reportTool($tool_number)
    {
        $tool_number = (int)$tool_number;
        $currentClass = new Summary9ByToolFuel();
        if(method_exists($currentClass,'report'.$tool_number)){
            list($outputFile, $outputName) = Summary9ByToolFuel::{"report".$tool_number}();

            return response()->download(storage_path('excel/'.$outputFile), $outputName);
        }
    }
    //เตาหุงต้มแก๊ส
    public static function report1()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolFuel::$inputFile;
        $inputSheet = '1';
        $startRow = 5;
        $outputFile = Summary9ByToolFuel::$outputFile;
        $outputName = 'เตาหุงต้มแก๊ส.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1025_o341_ch202_o94',
            'no_ch1025_o341_ch202_o95',
            'no_ch1025_o341_ch202_o96',
            'no_ch1025_o341',
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1025_o341_ch202_o94_ch204_o98_nu205',
            'no_ch1025_o341_ch202_o95_ch204_o98_nu205',
            'no_ch1025_o341_ch202_o96_ch1018_o97_nu1019',
            [
                'no_ch1025_o341_ch202_o94_ch204_o98_nu205',
                'no_ch1025_o341_ch202_o95_ch204_o98_nu205',
                'no_ch1025_o341_ch202_o96_ch1018_o97_nu1019'
            ]
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();

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
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($usage2, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        //Table4
        $table4_2 = [
            // =========== น้ำมันสำเร็จรูป ===========
            'no_ch1025_o341_ch202_o94_ch204_o98_nu207',
            'no_ch1025_o341_ch202_o95_ch204_o98_nu207',
            'no_ch1025_o341_ch202_o96_ch1018_o97_nu1021',
            [
                'no_ch1025_o341_ch202_o94_ch204_o98_nu207',
                'no_ch1025_o341_ch202_o95_ch204_o98_nu207',
                'no_ch1025_o341_ch202_o96_ch1018_o97_nu1021'
            ]
        ];

        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4_2, $table2, $startColumn, $startRow, $objPHPExcel, $mainObj
            ,false,[],false,null,1);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // เครื่องทำน้ำอุ่นแก๊ส
    public static function report2()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolFuel::$inputFile;
        $inputSheet = '2';
        $startRow = 5;
        $outputFile = Summary9ByToolFuel::$outputFile;
        $outputName = 'เครื่องทำน้ำอุ่นแก๊ส.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1029_o370'
        ];
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1029_o370_ch461_o209_nu462',
        ];
        $startColumn = 'O';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();

        // เครื่องทำน้ำอุ่นแก๊ส
        $volumes = (float)$settings->where('code', 'volume_fuel_4')->first()->value;
        $fuelFactors = (float)$settings->where('code','tool_factor_fuel_4')->first()->value
            * (float)$settings->where('code','season_factor_fuel_4')->first()->value
            * (float)$settings->where('code','usage_factor_fuel_4')->first()->value;
        $table3Petro = [
            ['no_ch1029_o370_ch461_o209_nu463',$volumes,$fuelFactors]
        ];
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2
        ];
        $ktoe = Setting::where('code','E2')->first()->value;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) 
        * param2 * param3
        as sumAmount ";
        $startColumn = 'AB';
        $objPHPExcel = Summary::usageElectric($table3Petro, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // สำหรับเครื่องทำน้ำอุ่น
        $startColumn = 'AM';
        $table4_2 = ['no_ch1029_o370_ch461_o209_nu464'];
        $table4_2_amount = ['no_ch1029_o370'];
        $objPHPExcel = Summary::averageLifetime($table4_2, $table4_2_amount, $startColumn, $startRow, $objPHPExcel, $mainObj
            ,false,[],false,null,1);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }
    // รถจักรยานยนต์
    public static function report3()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = Summary9ByToolFuel::$inputFile;
        $inputSheet = '3';
        $startRow = 5;
        $outputFile = Summary9ByToolFuel::$outputFile;
        $outputName = 'รถจักรยานยนต์.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        // คำนวณใหม่เอาเฉพาะคันที่ 1
        $table1 = [
            ['no_ch1033_o376_ch495_o300_ra498'=>214],
            ['no_ch1033_o376_ch495_o300_ra498'=>215]
        ];
        $table1All = [
            'no_ch1033_o376'
        ];
        $isRadio = true;
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);
        $objPHPExcel = Summary::sum($table1All,$startColumn,($startRow+2),$objPHPExcel,$mainObj);

        $table2RadioArr = [
            ['no_ch1033_o376_ch495_o300_ra498'=>214, 'no_ch1033_o376_ch495_o301_ra498'=>214, 'no_ch1033_o376_ch495_o302_ra498'=>214, 'no_ch1033_o376_ch495_o303_ra498'=>214, 'no_ch1033_o376_ch495_o304_ra498'=>214],
            ['no_ch1033_o376_ch495_o300_ra498'=>215, 'no_ch1033_o376_ch495_o301_ra498'=>215, 'no_ch1033_o376_ch495_o302_ra498'=>215, 'no_ch1033_o376_ch495_o303_ra498'=>215, 'no_ch1033_o376_ch495_o304_ra498'=>215],
            ['no_ch1033_o376_ch495_o300_ra498'=>214, 'no_ch1033_o376_ch495_o301_ra498'=>214, 'no_ch1033_o376_ch495_o302_ra498'=>214, 'no_ch1033_o376_ch495_o303_ra498'=>214, 'no_ch1033_o376_ch495_o304_ra498'=>214,
            'no_ch1033_o376_ch495_o300_ra498@'=>215, 'no_ch1033_o376_ch495_o301_ra498@'=>215, 'no_ch1033_o376_ch495_o302_ra498@'=>215, 'no_ch1033_o376_ch495_o303_ra498@'=>215, 'no_ch1033_o376_ch495_o304_ra498@'=>215
            ]
        ];

        $table2 = [
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            [
                'no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499',
            'no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499',
            ]
        ];

        $table2Usage = [
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
        ];

        // จำนวนเงินที่เติม
        $moneyFill = [
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
        ];

        // ความถี่ที่เติม
        $frequencyFill = [
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
        ];

        $table3 = [];
        $countTable2 = count($table2Usage);
        // จำนวนรถทุกคันที่ * จำนวนเงินที่เติม * ความถี่ที่เติม
//        $radioCondition = " (IF(SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))>0,1,0) * SUM(IF(unique_key='amountKey',answer_numeric,0))) ";

        $sql = " (SUM(IF(unique_key='amountKey',answer_numeric,0)) 
        * SUM(IF(unique_key='moneyFillKey',answer_numeric,0)) 
        * SUM(IF(unique_key='freqFillKey',answer_numeric,0))
        * IF(SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))>0,1,0)
         ) ";
        $whereSql = "";
        for($i=0;$i<$countTable2;$i++){
            $sumAmountSql = " ( ";
            for ($j=0;$j< count($table2Usage[$i]);$j++){
                $tempSql = $sql;
                $currentRadioKey = array_keys($table2RadioArr[$i])[$j];
                $tempSql = str_replace('radioKey', $currentRadioKey, $tempSql);
                $tempSql = str_replace('radioValue',$table2RadioArr[$i][$currentRadioKey], $tempSql);
                $tempSql = str_replace('amountKey',$table2[$i][$j], $tempSql);
                $tempSql = str_replace('moneyFillKey',$moneyFill[$i][$j], $tempSql);
                $tempSql = str_replace('freqFillKey',$frequencyFill[$i][$j], $tempSql);

                $sumAmountSql .= $tempSql . " + ";
            }
            $sumAmountSql = $sumAmountSql . " 0)  * param1 * param2 * 12 as sumAmount ";
            $table3[] = $sumAmountSql;
        }

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $fuelFactors = array();
        $fuelPrice = array();
        for ($i = 1; $i<=22; $i++){
            $fuelFactors[$i] = (float)$settings->where('code','tool_factor_fuel_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_fuel_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_fuel_'. $i)->first()->value;
            if ($i>=5){
                $fuelPrice[$i] = (float)$settings->where('code', 'fuel_price_fuel_' . $i)->first()->value;
            }
        }

        $table4 = [
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503',
            'no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
        ];

        $startColumn = 'O';
        $objPHPExcel =Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj, $isRadio, $table2RadioArr);
        $startColumn = 'AB';

        $params = array('param1'=>0,'param2'=>1);
        $idx = 0;
        $fuelFactorsArr = [
            $fuelFactors[5],$fuelFactors[6],
            $fuelFactors[9],$fuelFactors[10],$fuelFactors[11],$fuelFactors[12],
            $fuelFactors[13]
        ];
        $fuelPriceArr = [
            $fuelPrice[5],$fuelPrice[6],
            $fuelPrice[9],$fuelPrice[10],$fuelPrice[11],$fuelPrice[12],
            $fuelPrice[13]
        ];
        $ktoeArr = [
            $settings->where('code', 'E4')->first()->value, $settings->where('code','E5')->first()->value,
            $settings->where('code','E4')->first()->value,$settings->where('code','E5')->first()->value,$settings->where('code','E6')->first()->value,$settings->where('code','E7')->first()->value,
            $settings->where('code','E8')->first()->value
        ];
        $usageStartRow = $startRow;
        foreach ($table3 as $sqlStr){
            $uniqueKeyArr = array();
            $temp = [$fuelFactorsArr[$idx],$fuelPriceArr[$idx]];

            $temp = array_merge($temp,array_keys($table2RadioArr[$idx]),$table2[$idx], $moneyFill[$idx],$frequencyFill[$idx]);
            $uniqueKeyArr[]= $temp;

            $objPHPExcel = Summary::usageElectric($uniqueKeyArr, $startColumn, $usageStartRow,$objPHPExcel, $mainObj,$sqlStr,$params,$ktoeArr[$idx]);

            $usageStartRow++;
            $idx++;
        }
        $startColumn = 'AM';
        $objPHPExcel = Summary::averageLifetime($table4, $table2, $startColumn, $startRow, $objPHPExcel, $mainObj, $isRadio,$table2RadioArr);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return array($outputFile, $outputName);
    }

}
