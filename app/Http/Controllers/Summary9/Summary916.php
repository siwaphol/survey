<?php

namespace App\Http\Controllers\Summary9;

use App\Main;
use App\Parameter;
use App\Setting;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary916 extends Controller
{
    public static function report916()
    {
        set_time_limit(3600);

        // หมวดไล่และล่อแมลง
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.6';
        $outputFile = 'sum916.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);
        $table1 = [
            'no_ch1031_o373',
            'no_ch1031_o374',

            'no_ch1032_o375_ch490_o100',
            'no_ch1032_o375_ch490_o101',
            'no_ch1032_o375_ch490_o102',
            'no_ch1032_o375_ch490_o103'
        ];

        $table2=[
            'no_ch1031_o373_nu479',
            'no_ch1031_o374_nu485',

            'no_ch1032_o375_ch490_o100_nu491',
            'no_ch1032_o375_ch490_o101_nu491',
            'no_ch1032_o375_ch490_o102_nu491',
            'no_ch1032_o375_ch490_o103_nu491'
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

        $renewableFactors = array();
        for ($i = 1; $i<=32; $i++){
            $renewableFactors[$i] = (float)$settings->where('code','tool_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_renewable_'. $i)->first()->value;
        }

        $table3_1 = [
            ['no_ch1031_o373_nu479','no_ch1031_o373_nu480','no_ch1031_o373_nu481',$factors[244], $electricPower[244]],
            ['no_ch1031_o374_nu485','no_ch1031_o374_nu486','no_ch1031_o374_nu487',$factors[245], $electricPower[245]]
        ];
        $table3_2 = [
            ['no_ch1032_o375_ch490_o100_nu491','no_ch1032_o375_ch490_o100_nu492','no_ch1032_o375_ch490_o100_nu493',$settings->where('code','E10')->first()->value, $renewableFactors[29]],
            ['no_ch1032_o375_ch490_o101_nu491','no_ch1032_o375_ch490_o101_nu492','no_ch1032_o375_ch490_o101_nu493',$settings->where('code','E11')->first()->value, $renewableFactors[30]],
            ['no_ch1032_o375_ch490_o102_nu491','no_ch1032_o375_ch490_o102_nu492','no_ch1032_o375_ch490_o102_nu493',$settings->where('code','E12')->first()->value, $renewableFactors[31]],
            ['no_ch1032_o375_ch490_o103_nu491','no_ch1032_o375_ch490_o103_nu492','no_ch1032_o375_ch490_o103_nu493',$settings->where('code','E13')->first()->value, $renewableFactors[32]]
        ];

        $table4 = [
            'no_ch1031_o373_nu482',
            'no_ch1031_o374_nu488',
            'no_ch1032_o375_ch490_o100_nu494',
            'no_ch1032_o375_ch490_o101_nu494',
            'no_ch1032_o375_ch490_o102_nu494',
            'no_ch1032_o375_ch490_o103_nu494',
        ];

        $startColumn = 'E';
        $startRow = 13;
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $startColumn = 'U';
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
        $startColumn = 'AL';
        $objPHPExcel = Summary::usageElectric($table3_1, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL_1,$params,$ktoe);

        $startRow = 15;
        $ktoeIdx = 3;
        $sumAmountSQL_2 = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12.0 * param4 as sumAmount ";
        $params = ['param1'=>0, 'param2'=>1, 'param3'=>2, 'param4'=>4];
        $objPHPExcel = Summary::usageElectric($table3_2, $startColumn, $startRow, $objPHPExcel, $mainObj, $sumAmountSQL_2, $params,0,true, $ktoeIdx);

        $startRow = 13;
        $startColumn = 'BB';
        $objPHPExcel = Summary::averageLifetime($table4, $table2,$startColumn, $startRow, $objPHPExcel, $mainObj);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

}
