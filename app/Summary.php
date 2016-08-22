<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    public static function sum($uniqueKeyArr, $startCol, $startRow, $outputFile)
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

        $objPHPExcel = \PHPExcel_IOFactory::load(storage_path('excel/'. $outputFile));
        $objPHPExcel->setActiveSheetIndex(0);

        $rows = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey){
            $rows[$startCol.$rowNumber] = $uniqueKey;
            $rowNumber++;
        }

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
            $col=$startCol;
            $col++;
            $key2 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key2] = ($answers[$key]*100.0)/(float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_INNER])->getValue();
            $col++;
            $key3 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key3] = (int)($p[3] + $p[4]);
            $col++;
            $key4 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key4] = ($answers[$key3]*100.0)/(float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_OUTER])->getValue();
            //รวม
            $col++;
            $key5 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key6 = preg_replace('/[A-Z]+/', $col, $key);
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
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

    public static function average($uniqueKeyArr, $startCol, $startRow, $outputFile)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load(storage_path('excel/'. $outputFile));
        $objPHPExcel->setActiveSheetIndex(0);

        $rows = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey){
            $rows[$startCol.$rowNumber] = $uniqueKey;
            $rowNumber++;
        }

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

            $col = $startCol;
            $col++;
            $key2 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key3 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key4 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key5 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key6 = preg_replace('/[A-Z]+/', $col, $key);

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
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

    public static function usageElectric($uniqueKeyArr, $startCol, $startRow, $outputFile, $sqlSum, $param,$ktoe)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load(storage_path('excel/'.$outputFile));
        $objPHPExcel->setActiveSheetIndex(0);

        $sumAll = [];
        $sumRow = 26;
        $sumKey = [];

        $rows = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey){
            $rows[$startCol.$rowNumber] = $uniqueKey;
            $rowNumber++;
        }

        $answers = [];
        foreach ($rows as $key=>$value){
            echo $key . "\n";
            $sum = [];

            foreach (Main::$borderWeight as $b_key=>$b_weight){
                $mainList = Main::getMainList($b_key);

                $finalSql = $sqlSum;
                foreach ($param as $pKey=>$pValue){
                    $finalSql = str_replace($pKey, $value[$pValue], $finalSql);
                }
                $resultQuery2 = Answer::whereIn('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->select(\DB::raw($finalSql))
                    ->get();
                $sum[$b_key] = 0.0;
                foreach ($resultQuery2 as $row){
                    $sum[$b_key] += $row->sumAmount;
                }
            }

            $col = $startCol;
            $col++;
            $key2 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key3 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key4 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key5 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key6 = preg_replace('/[A-Z]+/', $col, $key);

            $answers[$key] = ($sum[Main::INNER_GROUP_1]*Main::$weight[Main::INNER_GROUP_1]
                    + $sum[Main::INNER_GROUP_2]* Main::$weight[Main::INNER_GROUP_2]) / 1000000.0;
            $answers[$key3] = ($sum[Main::OUTER_GROUP_1]*Main::$weight[Main::OUTER_GROUP_1]
                    + $sum[Main::OUTER_GROUP_2]* Main::$weight[Main::OUTER_GROUP_2]) / 1000000.0;
            //ktoe
            $answers[$key2] = $answers[$key] * $ktoe;
            $answers[$key4] = $answers[$key3] * $ktoe;
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
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

}
