<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary11Controller extends Controller
{
    public function downloadSum11_1()
    {
        Summary11Controller::report11_1();
        return response()->download(storage_path('excel/sum11_1.xlsx'), '11.1 แหล่งพลังงานที่หาเองได้.xlsx');
    }
    public function downloadSum11_2()
    {
        Summary11Controller::report11_2();
        return response()->download(storage_path('excel/sum11_2.xlsx'), '11.2 แหล่งพลังงานที่ซื้อ.xlsx');
    }
    public static function report11_1()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary11.xlsx';
        $inputSheet = '11.1';
        $outputFile = 'sum11_1.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ra800_o81_ti801_ch802_o266_ra803'=>[221,222,223,224],
            'no_ra800_o81_ti801_ch802_o267_ra803'=>[221,222,223,224],
            'no_ra800_o81_ti801_ch802_o268_ra803'=>[221,222,223,224],
            'no_ra800_o81_ti801_ch802_o269_ra803'=>[221,222,223,224],
            'no_ra800_o81_ti801_ch802_o1_ra803'=>[221,222,223,224]
        ];
        $startColumn = [
            Main::NORTHERN=>'C',
            Main::NORTHERN_INNER=> 'M',
            Main::NORTHERN_OUTER=>'W'];
        $startRow = 10;
        $objPHPExcel = Summary11Controller::sum($table1, $startColumn,$startRow,$objPHPExcel,$mainObj);

        $table7 = [
            'no_ra800_o81_ti801_ch802_o266_nu806',
            'no_ra800_o81_ti801_ch802_o267_nu806',
            'no_ra800_o81_ti801_ch802_o268_nu806',
            'no_ra800_o81_ti801_ch802_o269_nu806',
            'no_ra800_o81_ti801_ch802_o1_nu806'
        ];
        $objPHPExcel = Summary::average($table7,'BK',11,$objPHPExcel,$mainObj);

        $table8 = [
            'no_ra800_o81_ti801_ch802_o266_nu805',
            'no_ra800_o81_ti801_ch802_o267_nu805',
            'no_ra800_o81_ti801_ch802_o268_nu805',
            'no_ra800_o81_ti801_ch802_o269_nu805',
            'no_ra800_o81_ti801_ch802_o1_nu805'
        ];
        $objPHPExcel = Summary::average($table8, 'BY', 11,$objPHPExcel,$mainObj);

        $startColumn = 'CM';
        $isRadio = true;
        $table9_1 = [
            ['no_ra800_o81_ti801_ch802_o266_ra804'=>225],
            ['no_ra800_o81_ti801_ch802_o266_ra804'=>226]
        ];
        $startRow = 12;
        $objPHPExcel = Summary::sum($table9_1,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);
        $table9_2 = [
            ['no_ra800_o81_ti801_ch802_o267_ra804'=>225],
            ['no_ra800_o81_ti801_ch802_o267_ra804'=>226]
        ];
        $startRow = 15;
        $objPHPExcel = Summary::sum($table9_2,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);
        $table9_3 = [
            ['no_ra800_o81_ti801_ch802_o268_ra804'=>225],
            ['no_ra800_o81_ti801_ch802_o268_ra804'=>226]
        ];
        $startRow = 18;
        $objPHPExcel = Summary::sum($table9_3,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);
        $table9_4 = [
            ['no_ra800_o81_ti801_ch802_o269_ra804'=>225],
            ['no_ra800_o81_ti801_ch802_o269_ra804'=>226]
        ];
        $startRow = 21;
        $objPHPExcel = Summary::sum($table9_4,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);
        $table9_5 = [
            ['no_ra800_o81_ti801_ch802_o1_ra804'=>225],
            ['no_ra800_o81_ti801_ch802_o1_ra804'=>226]
        ];
        $startRow = 24;
        $objPHPExcel = Summary::sum($table9_5,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);

        $table10 = [
            'no_ra800_o81_ti801_ch802_o266_nu807',
            'no_ra800_o81_ti801_ch802_o267_nu807',
            'no_ra800_o81_ti801_ch802_o268_nu807',
            'no_ra800_o81_ti801_ch802_o269_nu807',
            'no_ra800_o81_ti801_ch802_o1_nu807'
        ];
        $startColumn = "DA";
        $startRow = 11;
        $objPHPExcel = Summary::average($table10,$startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return true;
    }

    public static function report11_2()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary11.xlsx';
        $inputSheet = '11.2';
        $outputFile = 'sum11_2.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ra808_o81_ti829_ch830_o266_ra831'=>[326,327,328],
            'no_ra808_o81_ti829_ch830_o267_ra831'=>[326,327,328],
            'no_ra808_o81_ti829_ch830_o268_ra831'=>[326,327,328],
            'no_ra808_o81_ti829_ch830_o269_ra831'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o228_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o229_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o230_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o231_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o232_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o233_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o234_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o235_ra811'=>[326,327,328],
            'no_ra808_o81_ti821_ch822_o236_ra823'=>[326,327,328],
            'no_ra808_o81_ti829_ch830_o1_ra831'=>[326,327,328]
        ];
        
        $startColumn = [
            Main::NORTHERN=>'C',
            Main::NORTHERN_INNER=> 'K',
            Main::NORTHERN_OUTER=>'S'];
        $startRow = 10;
        $objPHPExcel = Summary11Controller::sum($table1, $startColumn,$startRow,$objPHPExcel,$mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
        dd();

        return true;
    }

    public static function sum($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj)
    {
        $w = [];
        $w[1] = Main::$weight[Main::INNER_GROUP_1];
        $w[2] = Main::$weight[Main::INNER_GROUP_2];
        $w[3] = Main::$weight[Main::OUTER_GROUP_1];
        $w[4] = Main::$weight[Main::OUTER_GROUP_2];

        $s = [];
        $s[1] = Main::$sample[Main::INNER_GROUP_1];
        $s[2] = Main::$sample[Main::INNER_GROUP_2];
        $s[3] = Main::$sample[Main::OUTER_GROUP_1];
        $s[4] = Main::$sample[Main::OUTER_GROUP_2];

        $parameterExcel = \PHPExcel_IOFactory::load(storage_path('excel/parameters.xlsx'));
        $parameterExcel->setActiveSheetIndex(2);
        $paramSheet = $parameterExcel->getActiveSheet();
        $S = [];
        $S[1] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_INNER])->getValue();
        $S[2] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_INNER])->getValue();
        $S[3] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_OUTER])->getValue();
        $S[4] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_OUTER])->getValue();

//        $rows = [];
//        $rowNumber = $startRow;
//        foreach ($uniqueKeyArr as $uniqueKey){
//            $rows[$startCol.$rowNumber] = $uniqueKey;
//            $rowNumber++;
//        }
//        $answerObj = Answer::whereIn('unique_key', $uniqueKeyArr)->get();

//        $whereIn = [];
        $answers = [];
        $percents = [];
        if (!isset($answers[Main::NORTHERN_INNER])){
            $answers[Main::NORTHERN_INNER] = array();
            $percents[Main::NORTHERN_INNER] = array();
        }
        if (!isset($answers[Main::NORTHERN_OUTER])){
            $answers[Main::NORTHERN_OUTER] = array();
            $percents[Main::NORTHERN_OUTER] = array();
        }
        if (!isset($answers[Main::NORTHERN])){
            $answers[Main::NORTHERN] = array();
            $percents[Main::NORTHERN] = array();
        }
        foreach ($uniqueKeyArr as $unique_key=>$options){
//            $whereIn[] = $value;

            $p = [];
            $count = [];

            for ($i=1; $i<=4; $i++){
                $mainList = $mainObj->filterMain($i);
                $whereCondition = "";
                $selectCountSql = "";

                $idx = 0;
                $count[$i] = array();
                $p[$i] = array();
                $allCountAttr = [];
                foreach ($options as $option_id){
                    if ($idx===0){
                        $whereCondition .= " AND ( ";
                        $selectCountSql .= " SUM(IF(unique_key='$unique_key' AND option_id=$option_id,1,0)) AS count{$option_id} ";
                        $allCountAttr[] = "count".$option_id;
                    }
                    else{
                        $whereCondition .= " OR ";
                        $selectCountSql .= " ,SUM(IF(unique_key='$unique_key' AND option_id=$option_id,1,0)) AS count{$option_id} ";
                        $allCountAttr[] = "count".$option_id;
                    }
                    $whereCondition .= " (unique_key='$unique_key' AND option_id=$option_id) ";

                    $idx++;
                }
                $whereCondition .= " )";

                $whereInMainId = implode(",", $mainList);
                $sql = "SELECT {$selectCountSql} FROM (SELECT main_id,unique_key,option_id FROM answers WHERE main_id IN ($whereInMainId) " . $whereCondition . " GROUP BY main_id,unique_key,option_id) t1";

                $result = \DB::select($sql)[0];

                foreach ($allCountAttr as $attr){
                    $count[$i][$attr] = $result->{$attr};
                    $percents[$i][$attr] = $w[$i] * ((float)$count[$i][$attr] / $s[$i]);
//                    $p[$i][$attr] = $w[$i] * ((float)$count[$i][$attr] / $s[$i]);
                }
//                $count[$i] = \DB::select($sql)[0]->count;
//                $p[$i] = $w[$i] * ((float)$count[$i]/ $s[$i]) * $S[$i];
            }

            $tempCol = $startCol;
            foreach ($allCountAttr as $attr){
                $percents[Main::NORTHERN_INNER][$attr] = $percents[Main::INNER_GROUP_1][$attr]+$percents[Main::INNER_GROUP_2][$attr];
                $percents[Main::NORTHERN_OUTER][$attr] = $percents[Main::OUTER_GROUP_1][$attr]+$percents[Main::OUTER_GROUP_2][$attr];

                $answers[Main::NORTHERN_INNER][$attr] = $percents[Main::NORTHERN_INNER][$attr]*Main::$population[Main::NORTHERN_INNER];
                $answers[Main::NORTHERN_OUTER][$attr] = $percents[Main::NORTHERN_OUTER][$attr]*Main::$population[Main::NORTHERN_OUTER];

                $percents[Main::NORTHERN][$attr] = $percents[Main::NORTHERN_INNER][$attr]*Main::$weight[Main::NORTHERN_INNER] + $percents[Main::NORTHERN_OUTER][$attr]*Main::$weight[Main::NORTHERN_OUTER];
                $answers[Main::NORTHERN][$attr] = $percents[Main::NORTHERN][$attr]*Main::$population[Main::NORTHERN];

                $l_loop = [Main::NORTHERN,Main::NORTHERN_OUTER, Main::NORTHERN_INNER];
                foreach ($l_loop as $l_key){
                    $objPHPExcel->getActiveSheet()->setCellValue($tempCol[$l_key].$startRow, $answers[$l_key][$attr]);
                    $objPHPExcel->getActiveSheet()->getStyle($tempCol[$l_key].$startRow)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
                    $tempCol[$l_key]++;
                    $objPHPExcel->getActiveSheet()->setCellValue($tempCol[$l_key].$startRow, $percents[$l_key][$attr]*100);
                    $objPHPExcel->getActiveSheet()->getStyle($tempCol[$l_key].$startRow)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
                    $tempCol[$l_key]++;
                }
            }
            $startRow++;
//            $answers[Main::NORTHERN_INNER] = (int)($p[Main::INNER_GROUP_1] + $p[Main::INNER_GROUP_2]);
        }

        return $objPHPExcel;
    }

}
