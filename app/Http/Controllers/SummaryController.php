<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Main;
use App\Menu;
use App\Parameter;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SummaryController extends Controller
{
    public function sum()
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

        $objPHPExcel = \PHPExcel_IOFactory::load(storage_path('excel/sum91.xlsx'));
        $objPHPExcel->setActiveSheetIndex(0);

        $rows = [
            'E13'=>'no_ch1023_o329_ch101_o68',
            'E14'=>'no_ch1023_o329_ch101_o69_ch102_o72',
            'E15'=>'no_ch1023_o329_ch101_o69_ch102_o73',
            'E16'=>'no_ch1023_o329_ch101_o69_ch102_o74',
            'E17'=>'no_ch1023_o329_ch101_o70',
            'E18'=>'no_ch1023_o329_ch101_o71',
            'E19'=>'no_ch1023_o330_ch112_o68',
            'E20'=>'no_ch1023_o330_ch112_o69_ch113_o72',
            'E21'=>'no_ch1023_o330_ch112_o69_ch113_o73',
            'E22'=>'no_ch1023_o330_ch112_o69_ch113_o74',
            'E23'=>'no_ch1023_o330_ch112_o70',
            'E24'=>'no_ch1023_o330_ch112_o71'
        ];

        $whereIn = [];
        $answers = [];
        foreach ($rows as $key=>$value){
            $whereIn[] = $value;

            $p = [];
            $count = [];
            for ($i=1; $i<=4; $i++){
                $mainList = Main::getMainList($i);
                $count[$i] = Answer::where('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->groupBy('main_id')
                    ->get()
                    ->count();
                $p[$i] = $w[$i] * ((float)$count[$i]/ $s[$i]) * $S[$i];
            }
            $answers[$key] = (int)($p[1] + $p[2]);
            $key2 = str_replace('E','F',$key);
            $answers[$key2] = ($answers[$key]*100.0)/(float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_INNER])->getValue();
            $key3 = str_replace('E','G',$key);
            $answers[$key3] = (int)($p[3] + $p[4]);
            $key4 = str_replace('E','H',$key);
            $answers[$key4] = ($answers[$key3]*100.0)/(float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_OUTER])->getValue();
            //รวม
            $key5 = str_replace('E','I', $key);
            $key6 = str_replace('E','J', $key);
            $answers[$key6] = ($answers[$key2]+$answers[$key4])/2.0;
            $answers[$key5] = ($answers[$key6]/100.0) * (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN])->getValue();

            $objPHPExcel->getActiveSheet()->setCellValue($key, $answers[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, round($answers[$key2], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key3, $answers[$key3]);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, round($answers[$key4], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key5, $answers[$key5]);
            $objPHPExcel->getActiveSheet()->setCellValue($key6, round($answers[$key6], 2));

            $objPHPExcel->getActiveSheet()->getStyle($key)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key2)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key3)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key4)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key5)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key6)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
        }

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/sum91.xlsx')));
    }

    public function average()
    {
        $objPHPExcel = \PHPExcel_IOFactory::load(storage_path('excel/sum91.xlsx'));
        $objPHPExcel->setActiveSheetIndex(0);

        $rows = [
            'U13'=>'no_ch1023_o329_ch101_o68_nu103',
            'U14'=>'no_ch1023_o329_ch101_o69_ch102_o72_nu107',
            'U15'=>'no_ch1023_o329_ch101_o69_ch102_o73_nu107',
            'U16'=>'no_ch1023_o329_ch101_o69_ch102_o74_nu107',
            'U17'=>'no_ch1023_o329_ch101_o70_nu103',
            'U18'=>'no_ch1023_o329_ch101_o71_nu103',
            'U19'=>'no_ch1023_o330_ch112_o68_nu114',
            'U20'=>'no_ch1023_o330_ch112_o69_ch113_o72_nu118',
            'U21'=>'no_ch1023_o330_ch112_o69_ch113_o73_nu118',
            'U22'=>'no_ch1023_o330_ch112_o69_ch113_o74_nu118',
            'U23'=>'no_ch1023_o330_ch112_o70_nu114',
            'U24'=>'no_ch1023_o330_ch112_o71_nu114'
        ];

        $whereIn = [];
        $answers = [];
        $count = [];
        $A = [];
        foreach ($rows as $key=>$value){
            $whereIn[] = $value;

            $p = [];
            $avg = [];

            foreach (Main::$provinceWeight as $p_key=>$p_weight){
                $mainList = Main::getMainList($p_key);

                $count[$p_key] = Answer::where('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->groupBy('main_id')
                    ->get()
                    ->count();

                $avg[$p_key] = Answer::where('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->select(\DB::raw(" AVG(answer_numeric) as average "))
                    ->first()->average;
            }

            foreach (Main::$borderWeight as $b_key=>$b_weight){
                $mainList = Main::getMainList($b_key);

                $count[$b_key] = Answer::where('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->groupBy('main_id')
                    ->get()
                    ->count();

                $avg[$b_key] = Answer::where('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->select(\DB::raw(" AVG(answer_numeric) as average "))
                    ->first()->average;
                $p[$b_key] = $avg[$b_key]*$b_weight;
            }

            $answers[$key] = $p[Main::INNER_GROUP_1] + $p[Main::INNER_GROUP_2];
            $A[Main::INNER_GROUP_1] = (1.0/($count[Main::INNER_GROUP_1]-1))
                * (
                    pow(($avg[Main::CHIANGMAI_INNER] - $avg[Main::INNER_GROUP_1]), 2)
                    +pow(($avg[Main::UTARADIT_INNER] - $avg[Main::INNER_GROUP_1]),2)
                );
            $A[Main::INNER_GROUP_2] = (1.0/($count[Main::INNER_GROUP_2]-1))
                *(
                    pow(($avg[Main::NAN_INNER] - $avg[Main::INNER_GROUP_2]), 2)
                    +pow(($avg[Main::PITSANULOK_INNER] - $avg[Main::INNER_GROUP_2]),2)
                    +pow(($avg[Main::PETCHABUL_INNER] - $avg[Main::INNER_GROUP_2]),2)
                );
            $key2 = str_replace('U','V',$key);

            $answers[$key2] = sqrt(
                (
                    Main::$weight[Main::INNER_GROUP_1]
                    * (1.0-($count[Main::INNER_GROUP_1]/($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2])))
                    * ($A[Main::INNER_GROUP_1] / $count[Main::INNER_GROUP_1])
                )
                +
                (
                    Main::$weight[Main::INNER_GROUP_2]
                    * (1.0-($count[Main::INNER_GROUP_2]/($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2])))
                    * ($A[Main::INNER_GROUP_2] / $count[Main::INNER_GROUP_2])
                )
            );
            $key3 = str_replace('U','W',$key);
            $answers[$key3] = $p[Main::OUTER_GROUP_1] + $p[Main::OUTER_GROUP_2];
            $A[Main::OUTER_GROUP_1] = (1.0/($count[Main::OUTER_GROUP_1]-1))
                *(
                    pow(($avg[Main::CHIANGMAI_OUTER] - $avg[Main::OUTER_GROUP_1]), 2)
                    +pow(($avg[Main::UTARADIT_OUTER] - $avg[Main::OUTER_GROUP_1]),2)
                );
            $A[Main::OUTER_GROUP_2] = (1.0/($count[Main::OUTER_GROUP_2]-1))
                *(
                    pow(($avg[Main::NAN_OUTER] - $avg[Main::OUTER_GROUP_2]), 2)
                    +pow(($avg[Main::PITSANULOK_OUTER] - $avg[Main::OUTER_GROUP_2]),2)
                    +pow(($avg[Main::PETCHABUL_OUTER] - $avg[Main::OUTER_GROUP_2]),2)
                );
            $key4 = str_replace('U','X',$key);
            $answers[$key4] = sqrt(
                (
                    Main::$weight[Main::OUTER_GROUP_1]
                    * (1.0-($count[Main::OUTER_GROUP_1]/($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2])))
                    * ($A[Main::OUTER_GROUP_1] / $count[Main::OUTER_GROUP_1])
                )
                +
                (
                    Main::$weight[Main::OUTER_GROUP_2]
                    * (1.0-($count[Main::OUTER_GROUP_2]/($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2])))
                    * ($A[Main::OUTER_GROUP_2] / $count[Main::OUTER_GROUP_2])
                )
            );

            $objPHPExcel->getActiveSheet()->setCellValue($key,  round($answers[$key], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key2, round($answers[$key2], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key3, round($answers[$key3], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key4, round($answers[$key4], 2));
            $key5 = str_replace('U','Y',$key);
            $key6 = str_replace('U','Z',$key);
            $objPHPExcel->getActiveSheet()->setCellValue($key5, round(($answers[$key]+$answers[$key3])/2.0, 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key6, round(($answers[$key2]+$answers[$key4])/2.0, 2));

            $objPHPExcel->getActiveSheet()->getStyle($key)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key2)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key3)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key4)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key5)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key6)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
        }

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/sum91.xlsx')));
    }

    public function usage()
    {
        $objPHPExcel = \PHPExcel_IOFactory::load(storage_path('excel/sum91.xlsx'));
        $objPHPExcel->setActiveSheetIndex(0);

        $power = [
            'AL13'=>60,
            'AL14'=>24,
            'AL15'=>36,
            'AL16'=>18,
            'AL17'=>18,
            'AL18'=>10,
            'AL19'=>60,
            'AL20'=>24,
            'AL21'=>36,
            'AL22'=>18,
            'AL23'=>18,
            'AL24'=>10
        ];

        $week = 52.14;
        $ktoe = 0.08521;

        $hourPerDay  = 0;
        $dayPerWeek = 1;
        $amount = 2;

        $sumAll = [];
        $sumRow = 26;
        $sumKey = [];

        $attributes = [
            'AL13'=>['no_ch1023_o329_ch101_o68_nu104','no_ch1023_o329_ch101_o68_nu105','no_ch1023_o329_ch101_o68_nu103'],
            'AL14'=>['no_ch1023_o329_ch101_o69_ch102_o72_nu108','no_ch1023_o329_ch101_o69_ch102_o72_nu109','no_ch1023_o329_ch101_o69_ch102_o72_nu107'],
            'AL15'=>['no_ch1023_o329_ch101_o69_ch102_o73_nu108','no_ch1023_o329_ch101_o69_ch102_o73_nu109','no_ch1023_o329_ch101_o69_ch102_o73_nu107'],
            'AL16'=>['no_ch1023_o329_ch101_o69_ch102_o74_nu108','no_ch1023_o329_ch101_o69_ch102_o74_nu109','no_ch1023_o329_ch101_o69_ch102_o74_nu107'],
            'AL17'=>['no_ch1023_o329_ch101_o70_nu104','no_ch1023_o329_ch101_o70_nu105','no_ch1023_o329_ch101_o70_nu103'],
            'AL18'=>['no_ch1023_o329_ch101_o71_nu104','no_ch1023_o329_ch101_o71_nu105','no_ch1023_o329_ch101_o71_nu103'],
            'AL19'=>['no_ch1023_o330_ch112_o68_nu115','no_ch1023_o330_ch112_o68_nu116','no_ch1023_o330_ch112_o68_nu114'],
            'AL20'=>['no_ch1023_o330_ch112_o69_ch113_o72_nu119','no_ch1023_o330_ch112_o69_ch113_o72_nu120','no_ch1023_o330_ch112_o69_ch113_o72_nu118'],
            'AL21'=>['no_ch1023_o330_ch112_o69_ch113_o73_nu119','no_ch1023_o330_ch112_o69_ch113_o73_nu120','no_ch1023_o330_ch112_o69_ch113_o73_nu118'],
            'AL22'=>['no_ch1023_o330_ch112_o69_ch113_o74_nu119','no_ch1023_o330_ch112_o69_ch113_o74_nu120','no_ch1023_o330_ch112_o69_ch113_o74_nu118'],
            'AL23'=>['no_ch1023_o330_ch112_o70_nu115','no_ch1023_o330_ch112_o70_nu116','no_ch1023_o330_ch112_o70_nu114'],
            'AL24'=>['no_ch1023_o330_ch112_o71_nu115','no_ch1023_o330_ch112_o71_nu116','no_ch1023_o330_ch112_o71_nu114']
        ];

        $answers = [];
        foreach ($attributes as $key=>$value){
            $sum = [];
            $avg = [];

            foreach (Main::$borderWeight as $b_key=>$b_weight){
                $mainList = Main::getMainList($b_key);

                $resultQuery2 = Answer::whereIn('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->select(\DB::raw(" (sum(IF(unique_key='{$value[$hourPerDay]}',answer_numeric,0))* sum(if(unique_key='{$value[$dayPerWeek]}', answer_numeric,0))* {$week})* ({$power[$key]}/1000.0) * sum(if(unique_key='{$value[$amount]}',1,0)) as sumAmount "))
                    ->get();
                $sum[$b_key] = 0.0;
                foreach ($resultQuery2 as $row){
                    $sum[$b_key] += $row->sumAmount;
                }
            }

            $answers[$key] = ($sum[Main::INNER_GROUP_1]*Main::$weight[Main::INNER_GROUP_1]
                + $sum[Main::INNER_GROUP_2]* Main::$weight[Main::INNER_GROUP_2]) / 1000000.0;
            $key3 = str_replace('AL', 'AN', $key);
            $answers[$key3] = ($sum[Main::OUTER_GROUP_1]*Main::$weight[Main::OUTER_GROUP_1]
                + $sum[Main::OUTER_GROUP_2]* Main::$weight[Main::OUTER_GROUP_2]) / 1000000.0;
            //ktoe
            $key2 = str_replace('AL', 'AM', $key);
            $answers[$key2] = $answers[$key] * $ktoe;
            $key4 = str_replace('AL', 'AO', $key);
            $answers[$key4] = $answers[$key3] * $ktoe;

            $key5 = str_replace('AL', 'AP', $key);
            $key6 = str_replace('AL', 'AQ', $key);
            $answers[$key5] = $answers[$key] + $answers[$key3];
            $answers[$key6] = $answers[$key5] * $ktoe;

            $objPHPExcel->getActiveSheet()->setCellValue($key,  round($answers[$key], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key2, round($answers[$key2], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key3, round($answers[$key3], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key4, round($answers[$key4], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key5, round($answers[$key5], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key6, round($answers[$key6], 2));

            $objPHPExcel->getActiveSheet()->getStyle($key)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key2)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key3)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key4)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key5)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key6)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);

            $sumKey[1] = preg_replace('/[0-9]+/', $sumRow, $key);
            if (!isset($sumAll[$sumKey[1]]))
                $sumAll[$sumKey[1]]= 0;
            $sumAll[$sumKey[1]] += $answers[$key];

            $sumKey[3] = preg_replace('/[0-9]+/', $sumRow, $key3);
            if (!isset($sumAll[$sumKey[3]]))
                $sumAll[$sumKey[3]]= 0;
            $sumAll[$sumKey[3]] += $answers[$key3];

            $sumKey[5] = preg_replace('/[0-9]+/', $sumRow, $key5);
            if (!isset($sumAll[$sumKey[5]]))
                $sumAll[$sumKey[5]]= 0;
            $sumAll[$sumKey[5]] += $answers[$key5];
        }

        $sumKey[2] = preg_replace('/[0-9]+/', $sumRow, $key2);
        $sumKey[4] = preg_replace('/[0-9]+/', $sumRow, $key4);
        $sumKey[6] = preg_replace('/[0-9]+/', $sumRow, $key6);
        $sumAll[$sumKey[2]] = $sumAll[$sumKey[1]]*$ktoe;
        $sumAll[$sumKey[4]] = $sumAll[$sumKey[3]]*$ktoe;
        $sumAll[$sumKey[6]] = $sumAll[$sumKey[5]]*$ktoe;

        for ($i=1;$i<=6;$i++){
            $objPHPExcel->getActiveSheet()->setCellValue($sumKey[$i], round($sumAll[$sumKey[$i]], 2));
            $objPHPExcel->getActiveSheet()->getStyle($sumKey[$i])->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
        }

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/sum91.xlsx')));

    }

    public function average2()
    {
        $objPHPExcel = \PHPExcel_IOFactory::load(storage_path('excel/sum91.xlsx'));
        $objPHPExcel->setActiveSheetIndex(0);

        $rows = [
            'BB13'=>'no_ch1023_o329_ch101_o68_nu106',
            'BB14'=>'no_ch1023_o329_ch101_o69_ch102_o72_nu110',
            'BB15'=>'no_ch1023_o329_ch101_o69_ch102_o73_nu110',
            'BB16'=>'no_ch1023_o329_ch101_o69_ch102_o74_nu110',
            'BB17'=>'no_ch1023_o329_ch101_o70_nu106',
            'BB18'=>'no_ch1023_o329_ch101_o70_nu106',
            'BB19'=>'no_ch1023_o330_ch112_o68_nu117',
            'BB20'=>'no_ch1023_o330_ch112_o69_ch113_o72_nu121',
            'BB21'=>'no_ch1023_o330_ch112_o69_ch113_o73_nu121',
            'BB22'=>'no_ch1023_o330_ch112_o69_ch113_o74_nu121',
            'BB23'=>'no_ch1023_o330_ch112_o70_nu117',
            'BB24'=>'no_ch1023_o330_ch112_o71_nu117'
        ];

        $whereIn = [];
        $answers = [];
        $count = [];
        $A = [];
        foreach ($rows as $key=>$value){
            $whereIn[] = $value;

            $p = [];
            $avg = [];

            foreach (Main::$provinceWeight as $p_key=>$p_weight){
                $mainList = Main::getMainList($p_key);

                $count[$p_key] = Answer::where('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->groupBy('main_id')
                    ->get()
                    ->count();

                $avg[$p_key] = Answer::where('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->select(\DB::raw(" AVG(answer_numeric) as average "))
                    ->first()->average;
            }

            foreach (Main::$borderWeight as $b_key=>$b_weight){
                $mainList = Main::getMainList($b_key);

                $count[$b_key] = Answer::where('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->groupBy('main_id')
                    ->get()
                    ->count();

                $avg[$b_key] = Answer::where('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->select(\DB::raw(" AVG(answer_numeric) as average "))
                    ->first()->average;

                $p[$b_key] = $avg[$b_key] * $b_weight;
            }

            $answers[$key] = $p[Main::INNER_GROUP_1] + $p[Main::INNER_GROUP_2];
            $A[Main::INNER_GROUP_1] = (1.0/($count[Main::INNER_GROUP_1]-1))
                * (
                    pow(($avg[Main::CHIANGMAI_INNER] - $avg[Main::INNER_GROUP_1]), 2)
                    +pow(($avg[Main::UTARADIT_INNER] - $avg[Main::INNER_GROUP_1]),2)
                );
            $A[Main::INNER_GROUP_2] = (1.0/($count[Main::INNER_GROUP_2]-1))
                *(
                    pow(($avg[Main::NAN_INNER] - $avg[Main::INNER_GROUP_2]), 2)
                    +pow(($avg[Main::PITSANULOK_INNER] - $avg[Main::INNER_GROUP_2]),2)
                    +pow(($avg[Main::PETCHABUL_INNER] - $avg[Main::INNER_GROUP_2]),2)
                );
            $key2 = str_replace('BB','BC',$key);

            $answers[$key2] = sqrt(
                (
                    Main::$weight[Main::INNER_GROUP_1]
                    * (1.0-($count[Main::INNER_GROUP_1]/($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2])))
                    * ($A[Main::INNER_GROUP_1] / $count[Main::INNER_GROUP_1])
                )
                +
                (
                    Main::$weight[Main::INNER_GROUP_2]
                    * (1.0-($count[Main::INNER_GROUP_2]/($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2])))
                    * ($A[Main::INNER_GROUP_2] / $count[Main::INNER_GROUP_2])
                )
            );
            $key3 = str_replace('BB','BD',$key);
            $answers[$key3] = $p[Main::OUTER_GROUP_1] + $p[Main::OUTER_GROUP_2];
            $A[Main::OUTER_GROUP_1] = (1.0/($count[Main::OUTER_GROUP_1]-1))
                *(
                    pow(($avg[Main::CHIANGMAI_OUTER] - $avg[Main::OUTER_GROUP_1]), 2)
                    +pow(($avg[Main::UTARADIT_OUTER] - $avg[Main::OUTER_GROUP_1]),2)
                );
            $A[Main::OUTER_GROUP_2] = (1.0/($count[Main::OUTER_GROUP_2]-1))
                *(
                    pow(($avg[Main::NAN_OUTER] - $avg[Main::OUTER_GROUP_2]), 2)
                    +pow(($avg[Main::PITSANULOK_OUTER] - $avg[Main::OUTER_GROUP_2]),2)
                    +pow(($avg[Main::PETCHABUL_OUTER] - $avg[Main::OUTER_GROUP_2]),2)
                );
            $key4 = str_replace('BB','BE',$key);
            $answers[$key4] = sqrt(
                (
                    Main::$weight[Main::OUTER_GROUP_1]
                    * (1.0-($count[Main::OUTER_GROUP_1]/($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2])))
                    * ($A[Main::OUTER_GROUP_1] / $count[Main::OUTER_GROUP_1])
                )
                +
                (
                    Main::$weight[Main::OUTER_GROUP_2]
                    * (1.0-($count[Main::OUTER_GROUP_2]/($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2])))
                    * ($A[Main::OUTER_GROUP_2] / $count[Main::OUTER_GROUP_2])
                )
            );

            $objPHPExcel->getActiveSheet()->setCellValue($key,  round($answers[$key], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key2, round($answers[$key2], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key3, round($answers[$key3], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key4, round($answers[$key4], 2));

            $key5= str_replace('BB','BF',$key);
            $key6 = str_replace('BB','BG',$key);
            $objPHPExcel->getActiveSheet()->setCellValue($key5, round(($answers[$key]+$answers[$key3])/2.0, 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key6, round(($answers[$key2]+$answers[$key4])/2.0, 2));

            $objPHPExcel->getActiveSheet()->getStyle($key)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key2)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key3)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key4)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key5)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key6)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
        }

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/sum91.xlsx')));
    }

    public function deleteDuplicate()
    {
        $sqlStr = "SELECT main_id,unique_key FROM `answers` group BY main_id,unique_key HAVING COUNT(*) > 1";
        $result = \DB::select($sqlStr);

        foreach ($result as $row){
            $dupAns = Answer::where('main_id', $row->main_id)->where('unique_key', $row->unique_key)->get();
            if (count($dupAns)>1){
                $count = count($dupAns);
                for ($i=1;$i<$count;$i++){
                    if ( $this->checkDuplicate($dupAns[0], $dupAns[$i])){
                        echo 'D: ' . $dupAns[$i]->main_id . '-' . $dupAns[$i]->unique_key . "\n";
                        $deleteAns = Answer::find($dupAns[$i]->id);
                        $deleteAns->delete();
                    }
                }
            }
        }
    }

    protected function checkDuplicate($first, $second)
    {
        return $first->main_id==$second->main_id &&
        $first->question_id==$second->question_id &&
        $first->answer_numeric==$second->answer_numeric &&
        $first->answer_text==$second->answer_text &&
        $first->updated_at==$second->updated_at;
    }

    public function index()
    {
        $main = Menu::whereNull('parent_id')
            ->orderBy('order')
            ->get();
        return view('summary.index', compact('main'));
    }

    public function testReport()
    {
        app('App\Http\Controllers\SummaryController')->sum();
        app('App\Http\Controllers\SummaryController')->average();
        app('App\Http\Controllers\SummaryController')->usage();
        app('App\Http\Controllers\SummaryController')->average2();

        return response()->download(storage_path('excel/sum91.xlsx'), 'ตารางสรุปหมวดแสงสว่าง.xlsx');
    }
}
