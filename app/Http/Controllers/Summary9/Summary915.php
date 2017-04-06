<?php

namespace App\Http\Controllers\Summary9;

use App\Main;
use App\Setting;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary915 extends Controller
{
    public static function report915()
    {
        set_time_limit(3600);

        // หมวดเพื่อความอบอุ่น
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.5';
        $outputFile = 'sum915.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);
        $table1 = [
            'no_ch1030_o371_ch466_o100',
            'no_ch1030_o371_ch466_o101',
            'no_ch1030_o371_ch466_o102',
            'no_ch1030_o371_ch466_o103',

            'no_ch1030_o372_ch472_o100',
            'no_ch1030_o372_ch472_o101',
            'no_ch1030_o372_ch472_o102',
            'no_ch1030_o372_ch472_o103'
        ];

        $table2 = [
            'no_ch1030_o371_ch466_o100_nu467',
            'no_ch1030_o371_ch466_o101_nu467',
            'no_ch1030_o371_ch466_o102_nu467',
            'no_ch1030_o371_ch466_o103_nu467',

            'no_ch1030_o372_ch472_o100_nu473',
            'no_ch1030_o372_ch472_o101_nu473',
            'no_ch1030_o372_ch472_o102_nu473',
            'no_ch1030_o372_ch472_o103_nu473',
        ];

        $renewableFactors = array();
        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        for ($i = 1; $i<=32; $i++){
            $renewableFactors[$i] = (float)$settings->where('code','tool_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_renewable_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_renewable_'. $i)->first()->value;
        }

        $table3 = [
            ['no_ch1030_o371_ch466_o100_nu467','no_ch1030_o371_ch466_o100_nu468','no_ch1030_o371_ch466_o100_nu469',$settings->where('code','E10')->first()->value, $renewableFactors[21]],
            ['no_ch1030_o371_ch466_o101_nu467','no_ch1030_o371_ch466_o101_nu468','no_ch1030_o371_ch466_o101_nu469',$settings->where('code','E11')->first()->value, $renewableFactors[22]],
            ['no_ch1030_o371_ch466_o102_nu467','no_ch1030_o371_ch466_o102_nu468','no_ch1030_o371_ch466_o102_nu469',$settings->where('code','E12')->first()->value, $renewableFactors[23]],
            ['no_ch1030_o371_ch466_o103_nu467','no_ch1030_o371_ch466_o103_nu468','no_ch1030_o371_ch466_o103_nu469',$settings->where('code','E13')->first()->value, $renewableFactors[24]],

            ['no_ch1030_o372_ch472_o100_nu473','no_ch1030_o372_ch472_o100_nu474','no_ch1030_o372_ch472_o100_nu475',$settings->where('code','E10')->first()->value, $renewableFactors[25]],
            ['no_ch1030_o372_ch472_o101_nu473','no_ch1030_o372_ch472_o101_nu474','no_ch1030_o372_ch472_o101_nu475',$settings->where('code','E11')->first()->value, $renewableFactors[26]],
            ['no_ch1030_o372_ch472_o102_nu473','no_ch1030_o372_ch472_o102_nu474','no_ch1030_o372_ch472_o102_nu475',$settings->where('code','E12')->first()->value, $renewableFactors[27]],
            ['no_ch1030_o372_ch472_o103_nu473','no_ch1030_o372_ch472_o103_nu474','no_ch1030_o372_ch472_o103_nu475',$settings->where('code','E13')->first()->value, $renewableFactors[28]],
        ];

        $table4 = [
            'no_ch1030_o371_ch466_o100_nu470',
            'no_ch1030_o371_ch466_o101_nu470',
            'no_ch1030_o371_ch466_o102_nu470',
            'no_ch1030_o371_ch466_o103_nu470',

            'no_ch1030_o372_ch472_o100_nu476',
            'no_ch1030_o372_ch472_o101_nu476',
            'no_ch1030_o372_ch472_o102_nu476',
            'no_ch1030_o372_ch472_o103_nu476',
        ];

        $startColumn = ['E','U','AL','BB'];

        $startRow = 13;

        $objPHPExcel = Summary::sum($table1, $startColumn[0], $startRow, $objPHPExcel, $mainObj);
        $objPHPExcel = Summary::average($table2, $startColumn[1], $startRow, $objPHPExcel, $mainObj);
        $ktoeIdx = 3;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12.0 * param4 as sumAmount ";
        $params = ['param1'=>0, 'param2'=>1, 'param3'=>2, 'param4'=>4];
        $objPHPExcel = Summary::usageElectric($table3, $startColumn[2], $startRow, $objPHPExcel, $mainObj, $sumAmountSQL, $params,0,true, $ktoeIdx);


        $objPHPExcel = Summary::averageLifetime($table4,$table2, $startColumn[3], $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

}
