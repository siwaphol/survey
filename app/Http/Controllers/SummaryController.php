<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Main;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SummaryController extends Controller
{
    public function sum()
    {
        $w = [];
        $w[1] = 0.66;
        $w[2] = 0.34;
        $w[3] = 0.5;
        $w[4] = 0.5;

        $s = [];
        $s[1] = 526.00;
        $s[2] = 274.00;
        $s[3] = 850.00;
        $s[4] = 850.00;

        $S = [];
        $S[1] = 1432284.00;
        $S[2] = 1432284.00;
        $S[3] = 3034793.00;
        $S[4] = 3034793.00;

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
        $answerIn = [];
        $answerOut = [];
        $percentIn = [];
        $percentOut = [];
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
            $answerIn[$key] = (int)($p[1] + $p[2]);
            $percentIn[str_replace('E','F',$key)] = ($answerIn[$key]*100)/$S[1];
            $answerOut[str_replace('E','G',$key)] = (int)($p[3] + $p[4]);
            $percentOut[str_replace('E','H',$key)] = ($answerOut[str_replace('E','G',$key)]*100)/$S[3];

            $objPHPExcel->getActiveSheet()->setCellValue($key, (int)($p[1] + $p[2]));
            $objPHPExcel->getActiveSheet()->setCellValue(str_replace('E','F',$key), round(($answerIn[$key]*100)/$S[1], 2));
            $objPHPExcel->getActiveSheet()->setCellValue(str_replace('E','G',$key), (int)($p[3] + $p[4]));
            $objPHPExcel->getActiveSheet()->setCellValue(str_replace('E','H',$key), round(($answerOut[str_replace('E','G',$key)]*100)/$S[3], 2));
        }

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/หมวดแสงสว่าง.xlsx')));
        echo "success";
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

            $objPHPExcel->getActiveSheet()->setCellValue($key,  round($answers[$key]), 2);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, round($answers[$key2]), 2);
            $objPHPExcel->getActiveSheet()->setCellValue($key3, round($answers[$key3]), 2);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, round($answers[$key4]), 2);
        }

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/หมวดแสงสว่าง2.xlsx')));
        echo "success";
    }

    public function usage()
    {
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
    }
}
