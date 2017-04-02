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
            //============ ทั้งหมด
            'no_ch1023_o329',
            [],
            'no_ch1023_o329_ch101_o69'
        ];
        $startColumn = 'E';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1023_o329_ch101_o68_nu103',
            'no_ch1023_o329_ch101_o69_ch102_o72_nu107',
            'no_ch1023_o329_ch101_o69_ch102_o73_nu107',
            'no_ch1023_o329_ch101_o69_ch102_o74_nu107',
            'no_ch1023_o329_ch101_o70_nu103',
            'no_ch1023_o329_ch101_o71_nu103',
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
            [   'no_ch1023_o329_ch101_o68_nu106',
                'no_ch1023_o329_ch101_o69_ch102_o72_nu110',
                'no_ch1023_o329_ch101_o69_ch102_o73_nu110',
                'no_ch1023_o329_ch101_o69_ch102_o74_nu110',
                'no_ch1023_o329_ch101_o70_nu106',
                'no_ch1023_o329_ch101_o71_nu106'],
            [],
            [   'no_ch1023_o329_ch101_o69_ch102_o72_nu110',
                'no_ch1023_o329_ch101_o69_ch102_o73_nu110',
                'no_ch1023_o329_ch101_o69_ch102_o74_nu110']
        ];
        $startColumn = 'BB';
        $objPHPExcel = Summary::averageLifetime($table4, $table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

}
