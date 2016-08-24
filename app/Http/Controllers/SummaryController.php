<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Main;
use App\Menu;
use App\Parameter;
use App\Summary;
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

            $key2 = str_replace('U','V',$key);
            $key3 = str_replace('U','W',$key);
            $key4 = str_replace('U','X',$key);

            $answers[$key] = $p[Main::INNER_GROUP_1] + $p[Main::INNER_GROUP_2];
            if ($count[Main::INNER_GROUP_1]-1===0)
                $A[Main::INNER_GROUP_1] = 0;
            else
                $A[Main::INNER_GROUP_1] = (1.0/($count[Main::INNER_GROUP_1]-1))
                    * (
                        pow(($avg[Main::CHIANGMAI_INNER] - $avg[Main::INNER_GROUP_1]), 2)
                        +pow(($avg[Main::UTARADIT_INNER] - $avg[Main::INNER_GROUP_1]),2)
                    );

            if ($count[Main::INNER_GROUP_2]-1===0)
                $A[Main::INNER_GROUP_2] = 0;
            else
                $A[Main::INNER_GROUP_2] = (1.0/($count[Main::INNER_GROUP_2]-1))
                    *(
                        pow(($avg[Main::NAN_INNER] - $avg[Main::INNER_GROUP_2]), 2)
                        +pow(($avg[Main::PITSANULOK_INNER] - $avg[Main::INNER_GROUP_2]),2)
                        +pow(($avg[Main::PETCHABUL_INNER] - $avg[Main::INNER_GROUP_2]),2)
                    );
            if (($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2])===0)
                $part1 = 0;
            else
                $part1 = $count[Main::INNER_GROUP_1]/($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]);
            $part2 = ($count[Main::INNER_GROUP_1]===0)?0:($A[Main::INNER_GROUP_1] / $count[Main::INNER_GROUP_1]);
            $part3 = ($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2])===0?
                0:($count[Main::INNER_GROUP_2]/($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]));
            $part4 = $count[Main::INNER_GROUP_2]===0?0:($A[Main::INNER_GROUP_2] / $count[Main::INNER_GROUP_2]);

            $answers[$key2] = sqrt(
                (Main::$weight[Main::INNER_GROUP_1] * (1.0-$part1) * $part2) +
                (Main::$weight[Main::INNER_GROUP_2] * (1.0-$part3) * $part4)
            );
            $answers[$key3] = $p[Main::OUTER_GROUP_1] + $p[Main::OUTER_GROUP_2];
            if ($count[Main::OUTER_GROUP_1]-1===0)
                $A[Main::OUTER_GROUP_1] = 0;
            else
                $A[Main::OUTER_GROUP_1] = (1.0/($count[Main::OUTER_GROUP_1]-1))
                *(
                    pow(($avg[Main::CHIANGMAI_OUTER] - $avg[Main::OUTER_GROUP_1]), 2)
                    +pow(($avg[Main::UTARADIT_OUTER] - $avg[Main::OUTER_GROUP_1]),2)
                );
            if ($count[Main::OUTER_GROUP_2]-1===0)
                $A[Main::OUTER_GROUP_2] = 0;
            else
                $A[Main::OUTER_GROUP_2] = (1.0/($count[Main::OUTER_GROUP_2]-1))
                    *(
                        pow(($avg[Main::NAN_OUTER] - $avg[Main::OUTER_GROUP_2]), 2)
                        +pow(($avg[Main::PITSANULOK_OUTER] - $avg[Main::OUTER_GROUP_2]),2)
                        +pow(($avg[Main::PETCHABUL_OUTER] - $avg[Main::OUTER_GROUP_2]),2)
                    );
            if (($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2])===0)
                $part1 = 0;
            else
                $part1 = $count[Main::OUTER_GROUP_1]/($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]);
            $part2 = ($count[Main::OUTER_GROUP_1]===0)?0:($A[Main::OUTER_GROUP_1] / $count[Main::OUTER_GROUP_1]);
            $part3 = ($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2])===0?
                0:($count[Main::OUTER_GROUP_2]/($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]));
            $part4 = $count[Main::OUTER_GROUP_2]===0?0:($A[Main::OUTER_GROUP_2] / $count[Main::OUTER_GROUP_2]);

            $answers[$key4] = sqrt(
                (Main::$weight[Main::OUTER_GROUP_1] * (1.0-$part1) * $part2) +
                (Main::$weight[Main::OUTER_GROUP_2] * (1.0-$part3) * $part4)
            );

            $objPHPExcel->getActiveSheet()->setCellValue($key,  round($answers[$key], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key2, round($answers[$key2], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key3, round($answers[$key3], 2));
            $objPHPExcel->getActiveSheet()->setCellValue($key4, round($answers[$key4], 2));
            $key5 = str_replace('U','Y',$key);
            $key6 = str_replace('U','Z',$key);
            $objPHPExcel->getActiveSheet()->setCellValue($key5, ($answers[$key]+$answers[$key3])/2.0, 2);
            $objPHPExcel->getActiveSheet()->setCellValue($key6, ($answers[$key2]+$answers[$key4])/2.0, 2);

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
        $parameterExcel = \PHPExcel_IOFactory::load(storage_path('excel/parameters.xlsx'));
        $parameterExcel->setActiveSheetIndex(2);
        $paramSheet = $parameterExcel->getActiveSheet();
        $population = [];
        $population[Main::NORTHERN_INNER] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_INNER])->getValue();
        $population[Main::NORTHERN_OUTER] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_OUTER])->getValue();

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
            $count = [];

            foreach (Main::$borderWeight as $b_key=>$b_weight){
                $mainList = Main::getMainList($b_key);

                $count[$b_key] = Answer::whereIn('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->groupBy('main_id')
                    ->get()
                    ->count();

                $resultQuery2 = Answer::whereIn('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->select(\DB::raw(" (sum(IF(unique_key='{$value[$hourPerDay]}',answer_numeric,0))* sum(if(unique_key='{$value[$dayPerWeek]}', answer_numeric,0))* {$week})* ({$power[$key]}/1000.0) * sum(if(unique_key='{$value[$amount]}',1,0)) as sumAmount "))
                    ->groupBy('main_id')
                    ->get();
                $sum[$b_key] = 0.0;
                foreach ($resultQuery2 as $row){
                    $sum[$b_key] += $row->sumAmount;
                }
            }

            $average = [];
            $average[Main::INNER_GROUP_1] = $count[Main::INNER_GROUP_1]===0?0:($sum[Main::INNER_GROUP_1]/$count[Main::INNER_GROUP_1]);
            $average[Main::INNER_GROUP_2] = $count[Main::INNER_GROUP_2]===0?0:($sum[Main::INNER_GROUP_2]/$count[Main::INNER_GROUP_2]);
            $average[Main::OUTER_GROUP_1] = $count[Main::OUTER_GROUP_1]===0?0:($sum[Main::OUTER_GROUP_1]/$count[Main::OUTER_GROUP_1]);
            $average[Main::OUTER_GROUP_2] = $count[Main::OUTER_GROUP_2]===0?0:($sum[Main::OUTER_GROUP_2]/$count[Main::OUTER_GROUP_2]);

            $key3 = str_replace('AL', 'AN', $key);
            $answers[$key] = ($average[Main::INNER_GROUP_1]*Main::$weight[Main::INNER_GROUP_1]
                    + $average[Main::INNER_GROUP_2]* Main::$weight[Main::INNER_GROUP_2]) * $population[Main::NORTHERN_INNER];
            $answers[$key] = $answers[$key]/1000000.0;
            $answers[$key3] = ($average[Main::OUTER_GROUP_1]*Main::$weight[Main::OUTER_GROUP_1]
                    + $average[Main::OUTER_GROUP_2]* Main::$weight[Main::OUTER_GROUP_2]) * $population[Main::NORTHERN_OUTER];
            $answers[$key3] = $answers[$key3]/1000000.0;
            //ktoe
            $key2 = str_replace('AL', 'AM', $key);
            $answers[$key2] = $answers[$key] * $ktoe;
            $key4 = str_replace('AL', 'AO', $key);
            $answers[$key4] = $answers[$key3] * $ktoe;

            $key5 = str_replace('AL', 'AP', $key);
            $key6 = str_replace('AL', 'AQ', $key);
            $answers[$key5] = $answers[$key] + $answers[$key3];
            $answers[$key6] = $answers[$key5] * $ktoe;

            $objPHPExcel->getActiveSheet()->setCellValue($key,  $answers[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, $answers[$key2]);
            $objPHPExcel->getActiveSheet()->setCellValue($key3, $answers[$key3]);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, $answers[$key4]);
            $objPHPExcel->getActiveSheet()->setCellValue($key5, $answers[$key5]);
            $objPHPExcel->getActiveSheet()->setCellValue($key6, $answers[$key6]);

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

    public function test2()
    {
        $mainObj = new Main();
        $mainObj->initList();

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
            'no_ch1025_o341_ch202_o94_nu203',
            'no_ch1025_o341_ch202_o95_nu203',
            'no_ch1025_o341_ch202_o96_nu203',
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
            ['no_ch1024_o331_ch123_o75_ch124_o78_nu126', 'no_ch1024_o331_ch123_o75_ch124_o78_nu127','no_ch1024_o331_ch123_o75_ch124_o78_nu128',0.4,'no_ch1024_o331_ch123_o75_ch124_o78_nu125'],
            ['no_ch1024_o331_ch123_o75_ch124_o79_nu126', 'no_ch1024_o331_ch123_o75_ch124_o79_nu127','no_ch1024_o331_ch123_o75_ch124_o79_nu128',0.7,'no_ch1024_o331_ch123_o75_ch124_o79_nu125'],
            ['no_ch1024_o331_ch123_o75_ch124_o80_nu126', 'no_ch1024_o331_ch123_o75_ch124_o80_nu127','no_ch1024_o331_ch123_o75_ch124_o80_nu128',1.3,'no_ch1024_o331_ch123_o75_ch124_o80_nu125'],
            ['no_ch1024_o331_ch123_o76_ch1011_o78_nu1013', 'no_ch1024_o331_ch123_o76_ch1011_o78_nu1014','no_ch1024_o331_ch123_o76_ch1011_o78_nu1015',0.4,'no_ch1024_o331_ch123_o76_ch1011_o78_nu1012'],
            ['no_ch1024_o331_ch123_o76_ch1011_o79_nu1013', 'no_ch1024_o331_ch123_o76_ch1011_o79_nu1014','no_ch1024_o331_ch123_o76_ch1011_o79_nu1015',0.7,'no_ch1024_o331_ch123_o76_ch1011_o79_nu1012'],
            ['no_ch1024_o331_ch123_o77_ch1011_o78_nu1013', 'no_ch1024_o331_ch123_o77_ch1011_o78_nu1014','no_ch1024_o331_ch123_o77_ch1011_o78_nu1015',0.7,'no_ch1024_o331_ch123_o77_ch1011_o78_nu1012'],
            ['no_ch1024_o331_ch123_o77_ch1011_o79_nu1013', 'no_ch1024_o331_ch123_o77_ch1011_o79_nu1014','no_ch1024_o331_ch123_o77_ch1011_o79_nu1015',0.8,'no_ch1024_o331_ch123_o77_ch1011_o79_nu1012'],
            ['no_ch1024_o332_ch132_o83_nu134', 'no_ch1024_o332_ch132_o83_nu135','no_ch1024_o332_ch132_o83_nu136',1.5,'no_ch1024_o332_ch132_o83_nu133'],
            ['no_ch1024_o332_ch132_o84_nu134', 'no_ch1024_o332_ch132_o84_nu135','no_ch1024_o332_ch132_o84_nu136',1.8,'no_ch1024_o332_ch132_o84_nu133'],
            ['no_ch1024_o332_ch132_o85_nu134', 'no_ch1024_o332_ch132_o85_nu135','no_ch1024_o332_ch132_o85_nu136',2,'no_ch1024_o332_ch132_o85_nu133'],
            ['no_ch1024_o333_nu142', 'no_ch1024_o333_nu143','no_ch1024_o333_nu144',0.8,'no_ch1024_o333_nu141'],
            ['no_ch1024_o334_nu150', 'no_ch1024_o334_nu151','no_ch1024_o334_nu152',1.2,'no_ch1024_o334_nu149'],
            ['no_ch1024_o335_ch156_o287_nu158', 'no_ch1024_o335_ch156_o287_nu159','no_ch1024_o335_ch156_o287_nu160',0.6,'no_ch1024_o335_ch156_o287_nu157'],
            ['no_ch1024_o335_ch156_o288_nu158', 'no_ch1024_o335_ch156_o288_nu159','no_ch1024_o335_ch156_o288_nu160',0.7,'no_ch1024_o335_ch156_o288_nu157'],
            ['no_ch1024_o336_nu167', 'no_ch1024_o336_nu168','no_ch1024_o336_nu169',1.5,'no_ch1024_o336_nu166'],
            ['no_ch1024_o337_nu175', 'no_ch1024_o337_nu176','no_ch1024_o337_nu177',1,'no_ch1024_o337_nu174'],
            ['no_ch1024_o338_nu183', 'no_ch1024_o338_nu184','no_ch1024_o338_nu185',0.8,'no_ch1024_o338_nu182'],
            ['no_ch1024_o339_nu190', 'no_ch1024_o339_nu191','no_ch1024_o339_nu192',0.7,'no_ch1024_o339_nu189'],
            ['no_ch1024_o340_nu197', 'no_ch1024_o340_nu198','no_ch1024_o340_nu199',2,'no_ch1024_o340_nu196']
        ];
        $week = 52.14;
        $ktoe = 0.08521;
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
        $usage2 = [
            [4,'no_ch1025_o341_ch202_o94_ch204_o97_nu205','no_ch1025_o341_ch202_o94_ch204_o97_nu206'],
            [15,'no_ch1025_o341_ch202_o94_ch204_o98_nu205','no_ch1025_o341_ch202_o94_ch204_o98_nu206'],
            [48,'no_ch1025_o341_ch202_o94_ch204_o99_nu205','no_ch1025_o341_ch202_o94_ch204_o99_nu206'],
            [4,'no_ch1025_o341_ch202_o95_ch204_o97_nu205','no_ch1025_o341_ch202_o95_ch204_o97_nu206'],
            [15,'no_ch1025_o341_ch202_o95_ch204_o98_nu205','no_ch1025_o341_ch202_o95_ch204_o98_nu206'],
            [48,'no_ch1025_o341_ch202_o95_ch204_o99_nu205','no_ch1025_o341_ch202_o95_ch204_o99_nu206'],
            [4,'no_ch1025_o341_ch202_o96_ch1018_o97_nu1019','no_ch1025_o341_ch202_o96_ch1018_o97_nu1020']
        ];
        $params = [
          'param1'=>0,
            'param2'=>1,
            'param3'=>2
        ];
        $ktoe =0.024;
        $startRow = 32;
        $sumAmountSQL = " param1 * sum(IF(unique_key='param2',answer_numeric,0)) * sum(if(unique_key='param3', answer_numeric,0)) as sumAmount ";
        $objPHPExcel = Summary::usageElectric($usage2, $startColumn, $startRow, $objPHPExcel,$mainObj, $sumAmountSQL, $params,$ktoe,true);
        $usage3 = [
          ['no_ch1026_o342_ch210_o100_nu211','no_ch1026_o342_ch210_o100_nu212','no_ch1026_o342_ch210_o100_nu213',0.378 ],
          ['no_ch1026_o342_ch210_o101_nu211','no_ch1026_o342_ch210_o101_nu212','no_ch1026_o342_ch210_o101_nu213',0.684 ],
          ['no_ch1026_o342_ch210_o102_nu211','no_ch1026_o342_ch210_o102_nu212','no_ch1026_o342_ch210_o102_nu213',0.341 ],
          ['no_ch1026_o342_ch210_o103_nu211','no_ch1026_o342_ch210_o103_nu212','no_ch1026_o342_ch210_o103_nu213',0.3 ],
          ['no_ch1026_o343_ch216_o100_nu217','no_ch1026_o343_ch216_o100_nu218','no_ch1026_o343_ch216_o100_nu219',0.378 ],
          ['no_ch1026_o343_ch216_o101_nu217','no_ch1026_o343_ch216_o101_nu218','no_ch1026_o343_ch216_o101_nu219',0.684 ],
          ['no_ch1026_o343_ch216_o102_nu217','no_ch1026_o343_ch216_o102_nu218','no_ch1026_o343_ch216_o102_nu219',0.341 ],
          ['no_ch1026_o343_ch216_o103_nu217','no_ch1026_o343_ch216_o103_nu218','no_ch1026_o343_ch216_o103_nu219',0.3 ],
          ['no_ch1026_o344_ch222_o100_nu223','no_ch1026_o344_ch222_o100_nu224','no_ch1026_o344_ch222_o100_nu225',0.378 ],
          ['no_ch1026_o344_ch222_o101_nu223','no_ch1026_o344_ch222_o101_nu224','no_ch1026_o344_ch222_o101_nu225',0.684 ],
          ['no_ch1026_o344_ch222_o102_nu223','no_ch1026_o344_ch222_o102_nu224','no_ch1026_o344_ch222_o102_nu225',0.341 ],
          ['no_ch1026_o344_ch222_o103_nu223','no_ch1026_o344_ch222_o103_nu224','no_ch1026_o344_ch222_o103_nu225',0.3 ],
          ['no_ch1026_o345_ch228_o100_nu229','no_ch1026_o345_ch228_o100_nu230','no_ch1026_o345_ch228_o100_nu231',0.378 ],
          ['no_ch1026_o345_ch228_o101_nu229','no_ch1026_o345_ch228_o101_nu230','no_ch1026_o345_ch228_o101_nu231',0.684 ],
          ['no_ch1026_o345_ch228_o102_nu229','no_ch1026_o345_ch228_o102_nu230','no_ch1026_o345_ch228_o102_nu231',0.341 ],
          ['no_ch1026_o345_ch228_o103_nu229','no_ch1026_o345_ch228_o103_nu230','no_ch1026_o345_ch228_o103_nu231',0.3 ],
          ['no_ch1026_o346_ch234_o100_nu235','no_ch1026_o346_ch234_o100_nu236','no_ch1026_o346_ch234_o100_nu237',0.378 ],
          ['no_ch1026_o346_ch234_o101_nu235','no_ch1026_o346_ch234_o101_nu236','no_ch1026_o346_ch234_o101_nu237',0.684 ],
          ['no_ch1026_o346_ch234_o102_nu235','no_ch1026_o346_ch234_o102_nu236','no_ch1026_o346_ch234_o102_nu237',0.341 ],
          ['no_ch1026_o346_ch234_o103_nu235','no_ch1026_o346_ch234_o103_nu236','no_ch1026_o346_ch234_o103_nu237',0.3 ],
        ];
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

        return response()->download(storage_path('excel/'.$inputFile), 'ตารางสรุปหมวดประกอบอาหาร.xlsx');
    }

    //หมวดความสะดวกสบาย
    public function report913()
    {
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.3';
        $startRow = 13;
        $outputFile = 'sum913.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1027_o347_ch240_o104_ch241_o108',
            'no_ch1027_o347_ch240_o104_ch241_o109',
            'no_ch1027_o347_ch240_o104_ch241_o110',
            'no_ch1027_o347_ch240_o104_ch241_o111',
            'no_ch1027_o347_ch240_o104_ch241_o112',
            'no_ch1027_o347_ch240_o104_ch241_o113',
            'no_ch1027_o347_ch240_o104_ch241_o114',
            'no_ch1027_o347_ch240_o105_ch248_o115',
            'no_ch1027_o347_ch240_o105_ch248_o116',
            'no_ch1027_o347_ch240_o105_ch248_o117',
            'no_ch1027_o347_ch240_o105_ch248_o118',
            'no_ch1027_o347_ch240_o105_ch248_o119',
            'no_ch1027_o347_ch240_o106_ch254_o108',
            'no_ch1027_o347_ch240_o106_ch254_o109',
            'no_ch1027_o347_ch240_o106_ch254_o110',
            'no_ch1027_o347_ch240_o106_ch254_o111',
            'no_ch1027_o347_ch240_o106_ch254_o112',
            'no_ch1027_o347_ch240_o106_ch254_o113',
            'no_ch1027_o347_ch240_o106_ch254_o114',
            'no_ch1027_o347_ch240_o106_ch254_o115',
            'no_ch1027_o347_ch240_o106_ch254_o116',
            'no_ch1027_o347_ch240_o106_ch254_o117',
            'no_ch1027_o347_ch240_o106_ch254_o118',
            'no_ch1027_o347_ch240_o106_ch254_o119',
            'no_ch1027_o347_ch240_o107_ch260_o108',
            'no_ch1027_o347_ch240_o107_ch260_o109',
            'no_ch1027_o347_ch240_o107_ch260_o110',
            'no_ch1027_o347_ch240_o107_ch260_o111',
            'no_ch1027_o347_ch240_o107_ch260_o112',
            'no_ch1027_o347_ch240_o107_ch260_o113',
            'no_ch1027_o347_ch240_o107_ch260_o114',
            'no_ch1027_o347_ch240_o107_ch260_o115',
            'no_ch1027_o347_ch240_o107_ch260_o116',
            'no_ch1027_o347_ch240_o107_ch260_o117',
            'no_ch1027_o347_ch240_o107_ch260_o118',
            'no_ch1027_o347_ch240_o107_ch260_o119',
            'no_ch1027_o348_ch267_o122',
            'no_ch1027_o348_ch267_o123',
            'no_ch1027_o348_ch267_o124',
            'no_ch1027_o349',
            'no_ch1027_o350',
            'no_ch1027_o351',
            'no_ch1027_o352_ch291_o128_ch293_o134',
            'no_ch1027_o352_ch291_o128_ch293_o135',
            'no_ch1027_o352_ch291_o128_ch293_o136',
            'no_ch1027_o352_ch291_o128_ch293_o137',
            'no_ch1027_o352_ch291_o128_ch293_o138',
            'no_ch1027_o352_ch291_o129_ch298_o132',
            'no_ch1027_o352_ch291_o129_ch298_o133',
            'no_ch1027_o352_ch291_o129_ch298_o134',
            'no_ch1027_o353_ch304_o139',
            'no_ch1027_o353_ch304_o140',
            'no_ch1027_o353_ch304_o141',
            'no_ch1027_o354_ch310_o142',
            'no_ch1027_o354_ch310_o143',
            'no_ch1027_o354_ch310_o144',
            'no_ch1027_o355_ch317_o146',
            'no_ch1027_o355_ch317_o147',
            'no_ch1027_o355_ch317_o148',
        ];
        $startColumn = 'E';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1027_o347_ch240_o104_ch241_o108_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o109_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o110_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o111_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o112_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o113_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o114_nu242',
            'no_ch1027_o347_ch240_o105_ch248_o115_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o116_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o117_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o118_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o119_nu249',
            'no_ch1027_o347_ch240_o106_ch254_o108_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o109_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o110_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o111_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o112_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o113_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o114_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o115_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o116_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o117_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o118_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o119_nu255',
            'no_ch1027_o347_ch240_o107_ch260_o108_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o109_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o110_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o111_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o112_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o113_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o114_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o115_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o116_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o117_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o118_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o119_nu261',
            'no_ch1027_o348_ch267_o122_nu268',
            'no_ch1027_o348_ch267_o123_nu268',
            'no_ch1027_o348_ch267_o124_nu268',
            'no_ch1027_o349_nu274',
            'no_ch1027_o350_nu280',
            'no_ch1027_o351_nu286',
            'no_ch1027_o352_ch291_o128_ch293_o134_nu294',
            'no_ch1027_o352_ch291_o128_ch293_o135_nu294',
            'no_ch1027_o352_ch291_o128_ch293_o136_nu294',
            'no_ch1027_o352_ch291_o128_ch293_o137_nu294',
            'no_ch1027_o352_ch291_o128_ch293_o138_nu294',
            'no_ch1027_o352_ch291_o129_ch298_o132_nu299',
            'no_ch1027_o352_ch291_o129_ch298_o133_nu299',
            'no_ch1027_o352_ch291_o129_ch298_o134_nu299',
            'no_ch1027_o353_ch304_o139_nu305',
            'no_ch1027_o353_ch304_o140_nu305',
            'no_ch1027_o353_ch304_o141_nu305',
            'no_ch1027_o354_ch310_o142_nu311',
            'no_ch1027_o354_ch310_o143_nu311',
            'no_ch1027_o354_ch310_o144_nu311',
            'no_ch1027_o355_ch317_o146_nu318',
            'no_ch1027_o355_ch317_o147_nu318',
            'no_ch1027_o355_ch317_o148_nu318',
        ];
        $startColumn = 'U';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        //table 3
        $table3 = [
            ['no_ch1027_o347_ch240_o104_ch241_o108_nu242','no_ch1027_o347_ch240_o104_ch241_o108_nu243','no_ch1027_o347_ch240_o104_ch241_o108_nu244',0.04],
            ['no_ch1027_o347_ch240_o104_ch241_o109_nu242','no_ch1027_o347_ch240_o104_ch241_o109_nu243','no_ch1027_o347_ch240_o104_ch241_o109_nu244',0.05],
            ['no_ch1027_o347_ch240_o104_ch241_o110_nu242','no_ch1027_o347_ch240_o104_ch241_o110_nu243','no_ch1027_o347_ch240_o104_ch241_o110_nu244',0.078],
            ['no_ch1027_o347_ch240_o104_ch241_o111_nu242','no_ch1027_o347_ch240_o104_ch241_o111_nu243','no_ch1027_o347_ch240_o104_ch241_o111_nu244',0.04],
            ['no_ch1027_o347_ch240_o104_ch241_o112_nu242','no_ch1027_o347_ch240_o104_ch241_o112_nu243','no_ch1027_o347_ch240_o104_ch241_o112_nu244',0.05],
            ['no_ch1027_o347_ch240_o104_ch241_o113_nu242','no_ch1027_o347_ch240_o104_ch241_o113_nu243','no_ch1027_o347_ch240_o104_ch241_o113_nu244',0.078],
            ['no_ch1027_o347_ch240_o104_ch241_o114_nu242','no_ch1027_o347_ch240_o104_ch241_o114_nu243','no_ch1027_o347_ch240_o104_ch241_o114_nu244',0.04],
            ['no_ch1027_o347_ch240_o105_ch248_o115_nu249','no_ch1027_o347_ch240_o105_ch248_o115_nu250','no_ch1027_o347_ch240_o105_ch248_o115_nu251',0.05],
            ['no_ch1027_o347_ch240_o105_ch248_o116_nu249','no_ch1027_o347_ch240_o105_ch248_o116_nu250','no_ch1027_o347_ch240_o105_ch248_o116_nu251',0.078],
            ['no_ch1027_o347_ch240_o105_ch248_o117_nu249','no_ch1027_o347_ch240_o105_ch248_o117_nu250','no_ch1027_o347_ch240_o105_ch248_o117_nu251',0.05],
            ['no_ch1027_o347_ch240_o105_ch248_o118_nu249','no_ch1027_o347_ch240_o105_ch248_o118_nu250','no_ch1027_o347_ch240_o105_ch248_o118_nu251',0.095],
            ['no_ch1027_o347_ch240_o105_ch248_o119_nu249','no_ch1027_o347_ch240_o105_ch248_o119_nu250','no_ch1027_o347_ch240_o105_ch248_o119_nu251',0.125],
            ['no_ch1027_o347_ch240_o106_ch254_o108_nu255','no_ch1027_o347_ch240_o106_ch254_o108_nu256','no_ch1027_o347_ch240_o106_ch254_o108_nu257',0.2],
            ['no_ch1027_o347_ch240_o106_ch254_o109_nu255','no_ch1027_o347_ch240_o106_ch254_o109_nu256','no_ch1027_o347_ch240_o106_ch254_o109_nu257',0.225],
            ['no_ch1027_o347_ch240_o106_ch254_o110_nu255','no_ch1027_o347_ch240_o106_ch254_o110_nu256','no_ch1027_o347_ch240_o106_ch254_o110_nu257',0.045],
            ['no_ch1027_o347_ch240_o106_ch254_o111_nu255','no_ch1027_o347_ch240_o106_ch254_o111_nu256','no_ch1027_o347_ch240_o106_ch254_o111_nu257',0.1],
            ['no_ch1027_o347_ch240_o106_ch254_o112_nu255','no_ch1027_o347_ch240_o106_ch254_o112_nu256','no_ch1027_o347_ch240_o106_ch254_o112_nu257',0.03],
            ['no_ch1027_o347_ch240_o106_ch254_o113_nu255','no_ch1027_o347_ch240_o106_ch254_o113_nu256','no_ch1027_o347_ch240_o106_ch254_o113_nu257',0.035],
            ['no_ch1027_o347_ch240_o106_ch254_o114_nu255','no_ch1027_o347_ch240_o106_ch254_o114_nu256','no_ch1027_o347_ch240_o106_ch254_o114_nu257',0.042],
            ['no_ch1027_o347_ch240_o106_ch254_o115_nu255','no_ch1027_o347_ch240_o106_ch254_o115_nu256','no_ch1027_o347_ch240_o106_ch254_o115_nu257',0.05],
            ['no_ch1027_o347_ch240_o106_ch254_o116_nu255','no_ch1027_o347_ch240_o106_ch254_o116_nu256','no_ch1027_o347_ch240_o106_ch254_o116_nu257',3.5],
            ['no_ch1027_o347_ch240_o106_ch254_o117_nu255','no_ch1027_o347_ch240_o106_ch254_o117_nu256','no_ch1027_o347_ch240_o106_ch254_o117_nu257',1.6],
            ['no_ch1027_o347_ch240_o106_ch254_o118_nu255','no_ch1027_o347_ch240_o106_ch254_o118_nu256','no_ch1027_o347_ch240_o106_ch254_o118_nu257',0.025],
            ['no_ch1027_o347_ch240_o106_ch254_o119_nu255','no_ch1027_o347_ch240_o106_ch254_o119_nu256','no_ch1027_o347_ch240_o106_ch254_o119_nu257',0.8],
            ['no_ch1027_o347_ch240_o107_ch260_o108_nu261','no_ch1027_o347_ch240_o107_ch260_o108_nu262','no_ch1027_o347_ch240_o107_ch260_o108_nu261',1],
            ['no_ch1027_o347_ch240_o107_ch260_o109_nu261','no_ch1027_o347_ch240_o107_ch260_o109_nu262','no_ch1027_o347_ch240_o107_ch260_o109_nu261',1.2],
            ['no_ch1027_o347_ch240_o107_ch260_o110_nu261','no_ch1027_o347_ch240_o107_ch260_o110_nu262','no_ch1027_o347_ch240_o107_ch260_o110_nu261',2.4],
            ['no_ch1027_o347_ch240_o107_ch260_o111_nu261','no_ch1027_o347_ch240_o107_ch260_o111_nu262','no_ch1027_o347_ch240_o107_ch260_o111_nu263',1.6],
            ['no_ch1027_o347_ch240_o107_ch260_o112_nu261','no_ch1027_o347_ch240_o107_ch260_o112_nu262','no_ch1027_o347_ch240_o107_ch260_o112_nu263',1.5],
            ['no_ch1027_o347_ch240_o107_ch260_o113_nu261','no_ch1027_o347_ch240_o107_ch260_o113_nu262','no_ch1027_o347_ch240_o107_ch260_o113_nu263',0.06],
            ['no_ch1027_o347_ch240_o107_ch260_o114_nu261','no_ch1027_o347_ch240_o107_ch260_o114_nu262','no_ch1027_o347_ch240_o107_ch260_o114_nu263',0.075],
            ['no_ch1027_o347_ch240_o107_ch260_o115_nu261','no_ch1027_o347_ch240_o107_ch260_o115_nu262','no_ch1027_o347_ch240_o107_ch260_o115_nu263',0.09],
            ['no_ch1027_o347_ch240_o107_ch260_o116_nu261','no_ch1027_o347_ch240_o107_ch260_o116_nu262','no_ch1027_o347_ch240_o107_ch260_o116_nu263',0.25],
            ['no_ch1027_o347_ch240_o107_ch260_o117_nu261','no_ch1027_o347_ch240_o107_ch260_o117_nu262','no_ch1027_o347_ch240_o107_ch260_o117_nu263',0.6],
            ['no_ch1027_o347_ch240_o107_ch260_o118_nu261','no_ch1027_o347_ch240_o107_ch260_o118_nu262','no_ch1027_o347_ch240_o107_ch260_o118_nu263',0.95],
            ['no_ch1027_o347_ch240_o107_ch260_o119_nu261','no_ch1027_o347_ch240_o107_ch260_o119_nu262','no_ch1027_o347_ch240_o107_ch260_o119_nu263',1.3],
            ['no_ch1027_o348_ch267_o122_nu268','no_ch1027_o348_ch267_o122_nu269','no_ch1027_o348_ch267_o122_nu270',1.6],
            ['no_ch1027_o348_ch267_o123_nu268','no_ch1027_o348_ch267_o123_nu269','no_ch1027_o348_ch267_o123_nu270',2],
            ['no_ch1027_o348_ch267_o124_nu268','no_ch1027_o348_ch267_o124_nu269','no_ch1027_o348_ch267_o124_nu270',2.3],
            ['no_ch1027_o349_nu274','no_ch1027_o349_nu275','no_ch1027_o349_nu276',1.55],
            ['no_ch1027_o350_nu280','no_ch1027_o350_nu281','no_ch1027_o350_nu282',1.75],
            ['no_ch1027_o351_nu286','no_ch1027_o351_nu287','no_ch1027_o351_nu288',2.15],
            ['no_ch1027_o352_ch291_o128_ch293_o134_nu294','no_ch1027_o352_ch291_o128_ch293_o134_nu295','no_ch1027_o352_ch291_o128_ch293_o134_nu296',2.3],
            ['no_ch1027_o352_ch291_o128_ch293_o135_nu294','no_ch1027_o352_ch291_o128_ch293_o135_nu295','no_ch1027_o352_ch291_o128_ch293_o135_nu296',3],
            ['no_ch1027_o352_ch291_o128_ch293_o136_nu294','no_ch1027_o352_ch291_o128_ch293_o136_nu295','no_ch1027_o352_ch291_o128_ch293_o136_nu296',3.5],
            ['no_ch1027_o352_ch291_o128_ch293_o137_nu294','no_ch1027_o352_ch291_o128_ch293_o137_nu295','no_ch1027_o352_ch291_o128_ch293_o137_nu296',5.3],
            ['no_ch1027_o352_ch291_o128_ch293_o138_nu294','no_ch1027_o352_ch291_o128_ch293_o138_nu295','no_ch1027_o352_ch291_o128_ch293_o138_nu296',2],
            ['no_ch1027_o352_ch291_o129_ch298_o132_nu299','no_ch1027_o352_ch291_o129_ch298_o132_nu300','no_ch1027_o352_ch291_o129_ch298_o132_nu301',2.2],
            ['no_ch1027_o352_ch291_o129_ch298_o133_nu299','no_ch1027_o352_ch291_o129_ch298_o133_nu300','no_ch1027_o352_ch291_o129_ch298_o133_nu301',2.2],
            ['no_ch1027_o352_ch291_o129_ch298_o134_nu299','no_ch1027_o352_ch291_o129_ch298_o134_nu300','no_ch1027_o352_ch291_o129_ch298_o134_nu301',2.3],
            ['no_ch1027_o353_ch304_o139_nu305','no_ch1027_o353_ch304_o139_nu306','no_ch1027_o353_ch304_o139_nu307',0.35],
            ['no_ch1027_o353_ch304_o140_nu305','no_ch1027_o353_ch304_o140_nu306','no_ch1027_o353_ch304_o140_nu307',0.4],
            ['no_ch1027_o353_ch304_o141_nu305','no_ch1027_o353_ch304_o141_nu306','no_ch1027_o353_ch304_o141_nu307',0.5],
            ['no_ch1027_o354_ch310_o142_nu311','no_ch1027_o354_ch310_o142_nu312','no_ch1027_o354_ch310_o142_nu313',0.55],
            ['no_ch1027_o354_ch310_o143_nu311','no_ch1027_o354_ch310_o143_nu312','no_ch1027_o354_ch310_o143_nu313',0.3],
            ['no_ch1027_o354_ch310_o144_nu311','no_ch1027_o354_ch310_o144_nu312','no_ch1027_o354_ch310_o144_nu313',0.35],
            ['no_ch1027_o355_ch317_o146_nu318','no_ch1027_o355_ch317_o146_nu319','no_ch1027_o355_ch317_o146_nu320',0.4],
            ['no_ch1027_o355_ch317_o147_nu318','no_ch1027_o355_ch317_o147_nu319','no_ch1027_o355_ch317_o147_nu320',0.45],
            ['no_ch1027_o355_ch317_o148_nu318','no_ch1027_o355_ch317_o148_nu319','no_ch1027_o355_ch317_o148_nu320',1.1],
        ];
        $startColumn = 'AL';
        $ktoe = 0.08521;
        $week = Parameter::WEEK_PER_YEAR;
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) * sum(if(unique_key='param2', answer_numeric,0)) * sum(if(unique_key='param3', answer_numeric,0))) * {$week}  * (param4) as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2,
            'param4'=>3
        ];
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL,$params,$ktoe);

        // Table 4
        $table4 = [
            'no_ch1027_o347_ch240_o104_ch241_o108_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o109_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o110_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o111_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o112_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o113_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o114_nu245',
            'no_ch1027_o347_ch240_o105_ch248_o115_nu252',
            'no_ch1027_o347_ch240_o105_ch248_o116_nu252',
            'no_ch1027_o347_ch240_o105_ch248_o117_nu252',
            'no_ch1027_o347_ch240_o105_ch248_o118_nu252',
            'no_ch1027_o347_ch240_o105_ch248_o119_nu252',
            'no_ch1027_o347_ch240_o106_ch254_o108_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o109_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o110_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o111_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o112_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o113_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o114_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o115_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o116_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o117_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o118_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o119_nu258',
            'no_ch1027_o347_ch240_o107_ch260_o108_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o109_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o110_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o111_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o112_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o113_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o114_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o115_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o116_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o117_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o118_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o119_nu264',
            'no_ch1027_o348_ch267_o122_nu271',
            'no_ch1027_o348_ch267_o123_nu271',
            'no_ch1027_o348_ch267_o124_nu271',
            'no_ch1027_o349_nu277',
            'no_ch1027_o350_nu283',
            'no_ch1027_o351_nu289',
            'no_ch1027_o352_ch291_o128_ch293_o134_nu297',
            'no_ch1027_o352_ch291_o128_ch293_o135_nu297',
            'no_ch1027_o352_ch291_o128_ch293_o136_nu297',
            'no_ch1027_o352_ch291_o128_ch293_o137_nu297',
            'no_ch1027_o352_ch291_o128_ch293_o138_nu297',
            'no_ch1027_o352_ch291_o129_ch298_o132_nu302',
            'no_ch1027_o352_ch291_o129_ch298_o133_nu302',
            'no_ch1027_o352_ch291_o129_ch298_o134_nu302',
            'no_ch1027_o353_ch304_o139_nu308',
            'no_ch1027_o353_ch304_o140_nu308',
            'no_ch1027_o353_ch304_o141_nu308',
            'no_ch1027_o354_ch310_o142_nu314',
            'no_ch1027_o354_ch310_o143_nu314',
            'no_ch1027_o354_ch310_o144_nu314',
            'no_ch1027_o355_ch317_o146_nu321',
            'no_ch1027_o355_ch317_o147_nu321',
            'no_ch1027_o355_ch317_o148_nu321',
        ];
        $startColumn = 'BB';
        $objPHPExcel = Summary::average($table4, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return response()->download(storage_path('excel/'.$inputFile), 'ตารางสรุปหมวดข่าวสารบันเทิง.xlsx');
    }
}
