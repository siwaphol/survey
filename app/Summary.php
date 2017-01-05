<?php

namespace App;

use App\Http\Controllers\MainController;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    public static function sum($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $isRadio = false, $isCustomHaving = false, $havingUniqueKey=null, $arraySum = false)
    {
        list($w, $s, $population) = self::getSettingVariables();

        $rows = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey) {
            $rows[$startCol . $rowNumber] = $uniqueKey;
            $rowNumber++;
        }

        if (!$isRadio && !$arraySum)
            $answerObj = Answer::whereIn('unique_key', $uniqueKeyArr)->get();
        else if($arraySum){
            $allUniqueKeys = array_reduce($uniqueKeyArr, 'array_merge', array());
            $answerObj = Answer::whereIn('unique_key', $allUniqueKeys)->get();
        }

        $whereIn = [];
        $answers = [];
        foreach ($rows as $key => $value) {

            // เอาไว้เวลาเว้นแถวที่จะไม่แสดงค่า
            if (empty($value))
                continue;

            $whereIn[] = $value;
            $p = [];
            $count = [];

            if ($isRadio) {
                foreach (Main::$borderWeight as $b_key=>$b_weight){
                    $mainList = $mainObj->filterMain($b_key);

                    $whereCondition = "";

                    $idx = 0;
                    foreach ($value as $radioKey => $radioValue) {
                        if ($idx === 0)
                            $whereCondition .= " AND ( ";
                        else
                            $whereCondition .= " OR ";
                        $whereCondition .= " (unique_key='$radioKey' AND option_id=$radioValue) ";

                        $idx++;
                    }
                    $whereCondition .= " )";

                    $whereInMainId = implode(",", $mainList);
                    $sql = "SELECT COUNT(*) as count FROM (SELECT main_id FROM answers WHERE main_id IN ($whereInMainId) " . $whereCondition . " GROUP BY main_id) t1";
                    $count[$b_key] = \DB::select($sql)[0]->count;

                    $p[$b_key] = $w[$b_key] * ((float)$count[$b_key] / $s[$b_key]);
                }
            } elseif ($isCustomHaving){
                foreach (Main::$borderWeight as $b_key=>$b_weight){
                    $mainList = $mainObj->filterMain($b_key);
                    $whereCondition = $value;

                    $whereInMainId = implode(",", $mainList);
                    $sql = "SELECT COUNT(*) as count FROM (SELECT main_id FROM answers WHERE main_id IN ($whereInMainId) GROUP BY main_id $whereCondition ) t1";
                    $count[$b_key] = \DB::select($sql)[0]->count;
                    $p[$b_key] = $w[$b_key] * ((float)$count[$b_key] / $s[$b_key]);
                }
            } else {
                foreach (Main::$borderWeight as $b_key=>$b_weight){
                    $mainList = $mainObj->filterMain($b_key);
                    $dupMainId = [];

                    $count[$b_key] = $answerObj->filter(function ($b_keytem, $key) use ($mainList, $value, &$dupMainId) {
                        if (is_array($value)){
                            $condition = (!in_array($b_keytem->main_id, $dupMainId)) && in_array($b_keytem->unique_key, $value)
                                && in_array($b_keytem->main_id, $mainList);

                            if (in_array($b_keytem->unique_key, $value))
                                $dupMainId[] = $b_keytem->main_id;
                        }
                        else{
                            $condition = (!in_array($b_keytem->main_id, $dupMainId)) && $b_keytem->unique_key === $value
                                && in_array($b_keytem->main_id, $mainList);

                            if ($b_keytem->unique_key === $value)
                                $dupMainId[] = $b_keytem->main_id;
                        }

                        return $condition;
                    })->count();

                    $p[$b_key] = $w[$b_key] * ((float)$count[$b_key] / $s[$b_key]);
                }
            }

            $percentage1 = $p[Main::INNER_GROUP_1] + $p[Main::INNER_GROUP_2];
            $answers[$key] = $percentage1 * $population[Main::NORTHERN_INNER];
            $col = $startCol;
            $col++;
            $key2 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key2] = $percentage1*100;
            $col++;
            $key3 = preg_replace('/[A-Z]+/', $col, $key);
            $percentage2 = $p[Main::OUTER_GROUP_1] + $p[Main::OUTER_GROUP_2];

            $answers[$key3] = $percentage2* $population[Main::NORTHERN_OUTER];
            $col++;
            $key4 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key4] = $percentage2*100;
            //รวม
            $col++;
            $key5 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key6 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key6] = ($answers[$key2]*$w[Main::NORTHERN_INNER] + $answers[$key4]*$w[Main::NORTHERN_OUTER])/100;
            $answers[$key5] = ($answers[$key6] ) * $population[Main::NORTHERN];
            $answers[$key6] *= 100;

            $objPHPExcel->getActiveSheet()->setCellValue($key, $answers[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, ($answers[$key2]));
            $objPHPExcel->getActiveSheet()->setCellValue($key3, $answers[$key3]);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, ($answers[$key4]));
            $objPHPExcel->getActiveSheet()->setCellValue($key5, $answers[$key5]);
            $objPHPExcel->getActiveSheet()->setCellValue($key6, ($answers[$key6]));

            $objPHPExcel->getActiveSheet()->getStyle($key)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key2)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key3)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key4)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key5)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key6)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
        }

        return $objPHPExcel;
    }

    public static function average($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $isRadio = false, $radioArr = [], $year=false, $multiply=null)
    {
        $rows = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey) {
            $rows[$startCol . $rowNumber] = $uniqueKey;
            $rowNumber++;
        }

        $allUniqueArr = [];
        foreach ($uniqueKeyArr as $item) {
            if (!is_array($item))
                $allUniqueArr[] = $item;
            else {
                foreach ($item as $subItem)
                    $allUniqueArr[] = $subItem;
            }
        }

        $whereIn = [];
        $answers = [];
        $count = [];
        $A = [];

        $level1Counter = 0;
        foreach ($rows as $key => $value) {
            $whereIn[] = $value;
            $p = [];
            $avg = [];

            if (empty($value))
                continue;

            foreach (Main::$provinceWeight as $p_key => $p_weight) {
                $mainList = $mainObj->filterMain($p_key);

                $avg[$p_key] = 0;
                $whereMainId = implode(",", $mainList);

                if ($isRadio) {

                    $newSql = " (IF(SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))>1,1,SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))) * SUM(IF(unique_key='amountKey',answer_numeric,0))) ";
                    $finalSql = "";
                    $idx = 0;
                    $whereUniqueKey = implode("','", $value);
                    $whereUniqueKey = "'" . $whereUniqueKey . "'";
                    foreach ($radioArr[$level1Counter] as $radioKey => $radioValue) {
                        $temp = $newSql;
                        $temp = str_replace('radioKey', $radioKey, $temp);
                        $temp = str_replace('radioValue', $radioValue, $temp);
                        $temp = str_replace('amountKey', $value[$idx], $temp);

                        $finalSql .= $temp . " + ";

                        $whereUniqueKey .= ",'" . $radioKey . "'";
                        $idx++;
                    }
                    $finalSql .= " 0 ";
                    $whereUniqueKey = " AND unique_key IN (" . $whereUniqueKey . ")";
                    $avgSql = "SELECT SUM(sum1)/".Main::$provinceSample[$p_key]." as average, COUNT(*) as countAll FROM
                        (SELECT $finalSql AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id) T1 WHERE sum1>0 ";

                } else {
                    if (is_array($value)) {
                        $whereUniqueKey = implode("','", $value);
                        $tempUniqueKey = $whereUniqueKey;
                        $whereUniqueKey = " AND unique_key IN ('" .$whereUniqueKey."') ";
                        $sumSQL = " SUM(IF(unique_key IN ('$tempUniqueKey'),answer_numeric,0)) ";
                        if (!is_null($multiply))
                            $sumSQL .= " * $multiply ";
                        else if ($year)
                            $sumSQL .= " * 12 ";
                    }else{
                        $whereUniqueKey = " AND unique_key='$value'";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) ";
                        if (!is_null($multiply))
                            $sumSQL .= " * $multiply ";
                        elseif ($year)
                            $sumSQL .= " * 12 ";
                    }

                    $avgSql = "SELECT SUM(sum1)/".Main::$provinceSample[$p_key]." as average, COUNT(*) as countAll FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id) T1";
                }
                $avgResult = \DB::select($avgSql);
                $avg[$p_key] = $avgResult[0]->average;
                $count[$p_key] = $avgResult[0]->countAll;
            }

            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $avg[$b_key] = 0;
                $whereMainId = implode(",", $mainList);
                if ($isRadio) {
                    $newSql = " (IF(SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))>1,1,SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))) * SUM(IF(unique_key='amountKey',answer_numeric,0))) ";
                    $finalSql = "";
                    $idx = 0;
                    $whereUniqueKey = implode("','", $value);
                    $whereUniqueKey = "'" . $whereUniqueKey . "'";
                    foreach ($radioArr[$level1Counter] as $radioKey => $radioValue) {
                        $temp = $newSql;
                        $temp = str_replace('radioKey', $radioKey, $temp);
                        $temp = str_replace('radioValue', $radioValue, $temp);
                        $temp = str_replace('amountKey', $value[$idx], $temp);

                        $finalSql .= $temp . " + ";

                        $whereUniqueKey .= ",'" . $radioKey . "'";
                        $idx++;
                    }
                    $finalSql .= " 0 ";
                    $whereUniqueKey = " AND unique_key IN (" . $whereUniqueKey . ")";
                    $avgSql = "SELECT SUM(sum1)/".Main::$sample[$b_key]." as average, COUNT(*) as countAll FROM
                        (SELECT $finalSql AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id) T1 WHERE sum1>0";

                } else {
                    //old2
                    if (is_array($value)) {
                        $whereUniqueKey = implode("','", $value);
                        $tempUniqueKey = $whereUniqueKey;
                        $whereUniqueKey = " AND unique_key IN ('" .$whereUniqueKey."') ";
                        $sumSQL = " SUM(IF(unique_key IN ('$tempUniqueKey'),answer_numeric,0)) ";
                        if (!is_null($multiply))
                            $sumSQL .= " * $multiply ";
                        else if ($year)
                            $sumSQL .= " * 12 ";
                    }else{
                        $whereUniqueKey = " AND unique_key='$value'";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) ";
                        if (!is_null($multiply))
                            $sumSQL .= " * $multiply ";
                        else if ($year)
                            $sumSQL .= " * 12 ";
                    }

                    $avgSql = "SELECT SUM(sum1)/".Main::$sample[$b_key]." as average, COUNT(*) as countAll FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id) T1";
                }
                $avgResult = \DB::select($avgSql);
                $avg[$b_key] = $avgResult[0]->average;
                $count[$b_key] = $avgResult[0]->countAll;

                $p[$b_key] = $avg[$b_key] * $b_weight;
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
            if ($count[Main::INNER_GROUP_1] - 1 === 0)
                $A[Main::INNER_GROUP_1] = 0;
            else
                $A[Main::INNER_GROUP_1] = (1.0 / ($count[Main::INNER_GROUP_1] - 1))
                    * (
                        pow(($avg[Main::CHIANGMAI_INNER] - $avg[Main::INNER_GROUP_1]), 2)
                        + pow(($avg[Main::UTARADIT_INNER] - $avg[Main::INNER_GROUP_1]), 2)
                    );

            if ($count[Main::INNER_GROUP_2] - 1 === 0)
                $A[Main::INNER_GROUP_2] = 0;
            else
                $A[Main::INNER_GROUP_2] = (1.0 / ($count[Main::INNER_GROUP_2] - 1))
                    * (
                        pow(($avg[Main::NAN_INNER] - $avg[Main::INNER_GROUP_2]), 2)
                        + pow(($avg[Main::PITSANULOK_INNER] - $avg[Main::INNER_GROUP_2]), 2)
                        + pow(($avg[Main::PETCHABUL_INNER] - $avg[Main::INNER_GROUP_2]), 2)
                    );
            if (($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]) === 0)
                $part1 = 0;
            else
                $part1 = $count[Main::INNER_GROUP_1] / ($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]);
            $part2 = ($count[Main::INNER_GROUP_1] === 0) ? 0 : ($A[Main::INNER_GROUP_1] / $count[Main::INNER_GROUP_1]);
            $part3 = ($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]) === 0 ?
                0 : ($count[Main::INNER_GROUP_2] / ($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]));
            $part4 = $count[Main::INNER_GROUP_2] === 0 ? 0 : ($A[Main::INNER_GROUP_2] / $count[Main::INNER_GROUP_2]);

            $answers[$key2] = sqrt(
                (Main::$weight[Main::INNER_GROUP_1] * (1.0 - $part1) * $part2) +
                (Main::$weight[Main::INNER_GROUP_2] * (1.0 - $part3) * $part4)
            );
            $answers[$key3] = $p[Main::OUTER_GROUP_1] + $p[Main::OUTER_GROUP_2];
            if ($count[Main::OUTER_GROUP_1] - 1 === 0)
                $A[Main::OUTER_GROUP_1] = 0;
            else
                $A[Main::OUTER_GROUP_1] = (1.0 / ($count[Main::OUTER_GROUP_1] - 1))
                    * (
                        pow(($avg[Main::CHIANGMAI_OUTER] - $avg[Main::OUTER_GROUP_1]), 2)
                        + pow(($avg[Main::UTARADIT_OUTER] - $avg[Main::OUTER_GROUP_1]), 2)
                    );
            if ($count[Main::OUTER_GROUP_2] - 1 === 0)
                $A[Main::OUTER_GROUP_2] = 0;
            else
                $A[Main::OUTER_GROUP_2] = (1.0 / ($count[Main::OUTER_GROUP_2] - 1))
                    * (
                        pow(($avg[Main::NAN_OUTER] - $avg[Main::OUTER_GROUP_2]), 2)
                        + pow(($avg[Main::PITSANULOK_OUTER] - $avg[Main::OUTER_GROUP_2]), 2)
                        + pow(($avg[Main::PETCHABUL_OUTER] - $avg[Main::OUTER_GROUP_2]), 2)
                    );
            if (($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]) === 0)
                $part1 = 0;
            else
                $part1 = $count[Main::OUTER_GROUP_1] / ($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]);
            $part2 = ($count[Main::OUTER_GROUP_1] === 0) ? 0 : ($A[Main::OUTER_GROUP_1] / $count[Main::OUTER_GROUP_1]);
            $part3 = ($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]) === 0 ?
                0 : ($count[Main::OUTER_GROUP_2] / ($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]));
            $part4 = $count[Main::OUTER_GROUP_2] === 0 ? 0 : ($A[Main::OUTER_GROUP_2] / $count[Main::OUTER_GROUP_2]);

            $answers[$key4] = sqrt(
                (Main::$weight[Main::OUTER_GROUP_1] * (1.0 - $part1) * $part2) +
                (Main::$weight[Main::OUTER_GROUP_2] * (1.0 - $part3) * $part4)
            );

            $objPHPExcel->getActiveSheet()->setCellValue($key, $answers[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, $answers[$key2]);
            $objPHPExcel->getActiveSheet()->setCellValue($key3, $answers[$key3]);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, $answers[$key4]);
            $objPHPExcel->getActiveSheet()->setCellValue($key5, (($answers[$key]*Main::$weight[Main::NORTHERN_INNER] + $answers[$key3] * Main::$weight[Main::NORTHERN_OUTER])));
            $objPHPExcel->getActiveSheet()->setCellValue($key6, (($answers[$key2] + $answers[$key4]) / 2.0));

            $objPHPExcel->getActiveSheet()->getStyle($key)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key2)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key3)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key4)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key5)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key6)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);

            $level1Counter++;
        }

        return $objPHPExcel;
    }

    public static function usageElectric($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $sqlSum, $param, $ktoe, $gas = false, $ktoeIdx = false, $isRadio = false)
    {
        $parameterExcel = \PHPExcel_IOFactory::load(storage_path('excel/parameters.xlsx'));
        $parameterExcel->setActiveSheetIndex(2);
        $paramSheet = $parameterExcel->getActiveSheet();
        $population = [];
        $population[Main::NORTHERN_INNER] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_INNER])->getValue();
        $population[Main::NORTHERN_OUTER] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_OUTER])->getValue();

        $allUniqueKey = [];
        foreach ($uniqueKeyArr as $item) {
            foreach ($item as $subItem) {
                if (is_string($subItem))
                    $allUniqueKey[] = $subItem;
            }
        }

        $rows = [];
//        $count = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey) {
            $rows[$startCol . $rowNumber] = $uniqueKey;
            $rowNumber++;
        }

        $answers = [];
        $level1Index = 0;
        $defects = [];
        foreach ($rows as $key => $value) {
            $sum = [];

            if (empty($value))
                continue;

            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $finalSql = $sqlSum;
                foreach ($param as $pKey => $pValue) {
                    $finalSql = str_replace($pKey, $value[$pValue], $finalSql);
                }

//                $whereMainId = implode(",", $mainList);
//                if (is_array($value)) {
//                    $whereUniqueKey = implode("','", $value);
//                    $whereUniqueKey = " AND unique_key IN ('" . $whereUniqueKey . "') ";
//                } else
//                    $whereUniqueKey = " AND unique_key='$value'";
//                $avgSql = "SELECT COUNT(*) as countAll FROM
//                        (SELECT sum(answer_numeric) AS sum1 FROM answers
//                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
//                    . " GROUP BY main_id) T1";
//                $avgResult = \DB::select($avgSql);
//                $count[$b_key] = $avgResult[0]->countAll;

                $resultQuery2 = Answer::whereIn('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->groupBy('main_id')
                    ->select(\DB::raw($finalSql))
                    ->get();

                $sum[$b_key] = 0.0;
//                $echoParam = 'asdfasfddf';
//                if (str_contains($finalSql, $echoParam))
//                    echo Main::$borderWeightText[$b_key]."</br>";
                foreach ($resultQuery2 as $row) {
                    //test
//                    if (str_contains($finalSql, $echoParam))
//                        echo " main: " . $row->main_id . " , amount: " . $row->amount . " , hourPerDay: " . $row->hourPerDay . " ,dayPerWeek: " . $row->dayPerWeek . ", result: " . $row->sumAmount ." </br>";
                    if ($row->hourPerDay>24 || $row->dayPerWeek>7){
//                        echo Description::$table3Eletric[$level1Index] . "</br>";
//                        echo " ชุดที่: " . $row->main_id . " , จำนวน: " . $row->amount . " , ชั่วโมงต่อวัน: " . $row->hourPerDay . " ,วันต่ออาทิตย์: " . $row->dayPerWeek . ", ผลลัพธ์: " . $row->sumAmount ." </br>";

//                        if (!isset($defects[$row->main_id])){
//                            $defects[$row->main_id] = "";
//                        }
//                        $defects[$row->main_id] .= Description::$table3Eletric[$level1Index]." จำนวน: " . $row->amount . " , ชั่วโมงต่อวัน: " . $row->hourPerDay . " ,วันต่ออาทิตย์: " . $row->dayPerWeek . ", ผลลัพธ์: " . $row->sumAmount ." </br>";
                    }
                    //end test
                    $sum[$b_key] += $row->sumAmount;
                }

//                if (str_contains($finalSql, $echoParam)){
//                    echo " average: " . $sum[$b_key] . "/" . Main::$sample[$b_key] . " = " . ($sum[$b_key] / Main::$sample[$b_key]). "</br>";
//                }
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

            $average = [];
            $average[Main::INNER_GROUP_1] = ($sum[Main::INNER_GROUP_1] / Main::$sample[Main::INNER_GROUP_1]);
            $average[Main::INNER_GROUP_2] = ($sum[Main::INNER_GROUP_2] / Main::$sample[Main::INNER_GROUP_2]);
            $average[Main::OUTER_GROUP_1] = ($sum[Main::OUTER_GROUP_1] / Main::$sample[Main::OUTER_GROUP_1]);
            $average[Main::OUTER_GROUP_2] = ($sum[Main::OUTER_GROUP_2] / Main::$sample[Main::OUTER_GROUP_2]);

            $y = array();
            $y[Main::NORTHERN_INNER] = ($average[Main::INNER_GROUP_1] * Main::$weight[Main::INNER_GROUP_1]
                + $average[Main::INNER_GROUP_2] * Main::$weight[Main::INNER_GROUP_2]);
            $y[Main::NORTHERN_OUTER] = ($average[Main::OUTER_GROUP_1] * Main::$weight[Main::OUTER_GROUP_1]
                + $average[Main::OUTER_GROUP_2] * Main::$weight[Main::OUTER_GROUP_2]);

            $answers[$key] = $y[Main::NORTHERN_INNER] * $population[Main::NORTHERN_INNER];
            $answers[$key] = $answers[$key] / 1000000.0;
            $answers[$key3] = $y[Main::NORTHERN_OUTER] * $population[Main::NORTHERN_OUTER];
            $answers[$key3] = $answers[$key3] / 1000000.0;
//            if (str_contains($finalSql, $echoParam)){
//                echo " ในเขต : ({$average[Main::INNER_GROUP_1]}x".Main::$weight[Main::INNER_GROUP_1]." + {$average[Main::INNER_GROUP_2]}x".Main::$weight[Main::INNER_GROUP_2].")*{$population[Main::NORTHERN_INNER]}/1000000 = ".$answers[$key]."</br>";
//                echo " นอกเขต : ({$average[Main::OUTER_GROUP_1]}x".Main::$weight[Main::OUTER_GROUP_1]." + {$average[Main::OUTER_GROUP_2]}x".Main::$weight[Main::OUTER_GROUP_2].")*{$population[Main::NORTHERN_OUTER]}/1000000 = ".$answers[$key3]."</br>";
//            }

            $answers[$key5] = ($y[Main::NORTHERN_INNER]*Main::$weight[Main::NORTHERN_INNER] +
            $y[Main::NORTHERN_OUTER]*Main::$weight[Main::NORTHERN_OUTER])
            * Main::$population[Main::NORTHERN] / 1000000.0;

            //ktoe
            if ($gas) {
                $answers[$key2] = $answers[$key] * 0.00042 * $ktoe;
                $answers[$key4] = $answers[$key3] * 0.00042 * $ktoe;
                //TODO-nong I don't know why below
//                $answers[$key5] = $answers[$key] * 0.00042 + $answers[$key3];
                $answers[$key6] = $answers[$key5] * 0.00042 * $ktoe;
            } elseif ($ktoeIdx !== false) {
                $answers[$key2] = $answers[$key] * $value[$ktoeIdx];
                $answers[$key4] = $answers[$key3] * $value[$ktoeIdx];
                $answers[$key6] = $answers[$key5] * $value[$ktoeIdx];
            } else {
                $answers[$key2] = $answers[$key] * $ktoe;
                $answers[$key4] = $answers[$key3] * $ktoe;
                $answers[$key6] = $answers[$key5] * $ktoe;
            }

            $objPHPExcel->getActiveSheet()->setCellValue($key, $answers[$key]);
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

            $level1Index++;
        }

        //TODO-nong test for defect echo
//        foreach ($defects as $d_key=>$d_value){
//            echo " ชุดที่ " . $d_key."</br>";
//            echo $d_value . "</br>";
//        }

        return $objPHPExcel;
    }

    public static function specialUsage($uniqueKeyArr, $startCol, $startRow, $objPHPExcel,$mainObj,$ktoe)
    {
        $parameterExcel = \PHPExcel_IOFactory::load(storage_path('excel/parameters.xlsx'));
        $parameterExcel->setActiveSheetIndex(2);
        $paramSheet = $parameterExcel->getActiveSheet();
        $population = [];
        $population[Main::NORTHERN_INNER] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_INNER])->getValue();
        $population[Main::NORTHERN_OUTER] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_OUTER])->getValue();

        $rows = [];
        $count = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey) {
            $rows[$startCol . $rowNumber] = $uniqueKey;
            $rowNumber++;
        }

        $answers = [];
        $level1Counter = 0;
        foreach ($rows as $key=>$value){
            $sum = [];

//            $starttime = microtime(true);
            foreach (Main::$borderWeight as $b_key=>$b_weight){
                $mainList = $mainObj->filterMain($b_key);
                $whereMainId = implode(",", $mainList);
                // สำหรับหมวดคมนาคมอย่างเดียว
//                if ($isRadio){
//                    $newSql = " (IF(SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))>1,1,SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))) * SUM(IF(unique_key='amountKey',answer_numeric,0))) ";
//                    $finalSql = "";
//                    $idx = 0;
//                    $whereUniqueKey = implode("','", $value);
//                    $whereUniqueKey = "'" .$whereUniqueKey."'";
//                    foreach ($radioArr[$level1Counter] as $radioKey=>$radioValue){
//                        $temp = $newSql;
//                        $temp = str_replace('radioKey', $radioKey, $temp);
//                        $temp = str_replace('radioValue', $radioValue, $temp);
//                        $temp = str_replace('amountKey', $value[$idx], $temp);
//
//                        $finalSql .= $temp . " + ";
//
//                        $whereUniqueKey.= ",'" . $radioKey . "'";
//                        $idx++;
//                    }
                    $finalSql = $value;
//                    $whereUniqueKey = " AND unique_key IN (" . $whereUniqueKey . ")";
                    $avgSql = "SELECT SUM(sum1)/".Main::$sample[$b_key]." as average, COUNT(*) as countAll FROM
                        (SELECT $finalSql AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) "
                        ." GROUP BY main_id) T1 WHERE sum1>0";
                    $result = \DB::select($avgSql);

//                }
                $average[$b_key] = $result[0]->average;

//                $finalSql = $sqlSum;
//                foreach ($param as $pKey=>$pValue){
//                    $finalSql = str_replace($pKey, $value[$pValue], $finalSql);
//                }

//                $whereMainId = implode(",", $mainList);
//                if (is_array($value)){
//                    $whereUniqueKey = implode("','", $value);
//                    $whereUniqueKey = " AND unique_key IN ('" .$whereUniqueKey."') ";
//                }else
//                    $whereUniqueKey = " AND unique_key='$value'";
//                $avgSql = "SELECT COUNT(*) as countAll FROM
//                        (SELECT sum(answer_numeric) AS sum1 FROM answers
//                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
//                    . " GROUP BY main_id) T1";
//                $avgResult = \DB::select($avgSql);
//                $count[$b_key] = $avgResult[0]->countAll;
//
//                $resultQuery2 = Answer::whereIn('unique_key', $value)
//                    ->whereIn('main_id', $mainList)
//                    ->groupBy('main_id')
//                    ->select(\DB::raw($finalSql))
//                    ->get();
//
//                $sum[$b_key] = 0.0;
//                foreach ($resultQuery2 as $row){
//                    $sum[$b_key] += $row->sumAmount;
//                }
            }

//            dd(" one query: ", $average);
//            echo " full loop : " . ((microtime(true)-$starttime)/60) . " seconds</br>";
//            dd("stop");

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

//            $average = [];
//            $average[Main::INNER_GROUP_1] = $count[Main::INNER_GROUP_1]===0?0:($sum[Main::INNER_GROUP_1]/$count[Main::INNER_GROUP_1]);
//            $average[Main::INNER_GROUP_2] = $count[Main::INNER_GROUP_2]===0?0:($sum[Main::INNER_GROUP_2]/$count[Main::INNER_GROUP_2]);
//            $average[Main::OUTER_GROUP_1] = $count[Main::OUTER_GROUP_1]===0?0:($sum[Main::OUTER_GROUP_1]/$count[Main::OUTER_GROUP_1]);
//            $average[Main::OUTER_GROUP_2] = $count[Main::OUTER_GROUP_2]===0?0:($sum[Main::OUTER_GROUP_2]/$count[Main::OUTER_GROUP_2]);

            $y = array();
            $y[Main::NORTHERN_INNER] = ($average[Main::INNER_GROUP_1] * Main::$weight[Main::INNER_GROUP_1]
                + $average[Main::INNER_GROUP_2] * Main::$weight[Main::INNER_GROUP_2]);
            $y[Main::NORTHERN_OUTER] = ($average[Main::OUTER_GROUP_1] * Main::$weight[Main::OUTER_GROUP_1]
                + $average[Main::OUTER_GROUP_2] * Main::$weight[Main::OUTER_GROUP_2]);

            $answers[$key] = $y[Main::NORTHERN_INNER] * $population[Main::NORTHERN_INNER];
            $answers[$key] = $answers[$key]/1000000.0;
            $answers[$key3] = $y[Main::NORTHERN_OUTER] * $population[Main::NORTHERN_OUTER];
            $answers[$key3] = $answers[$key3]/1000000.0;

            //ktoe
            $answers[$key2] = $answers[$key] * $ktoe;
            $answers[$key4] = $answers[$key3] * $ktoe;
            $answers[$key5] = ($y[Main::NORTHERN_INNER]*Main::$weight[Main::NORTHERN_INNER] +
                    $y[Main::NORTHERN_OUTER]*Main::$weight[Main::NORTHERN_OUTER])
                * Main::$population[Main::NORTHERN] / 1000000.0;
            $answers[$key6] = $answers[$key5] * $ktoe;

            $objPHPExcel->getActiveSheet()->setCellValue($key, $answers[$key]);
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

            $level1Counter++;
        }

        return $objPHPExcel;
    }

    public static function sum13($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $changeUnique, $notSure, $notInNotSure, $mainUnique, $uniqueVal)
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

        $rows = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey) {
            $rows[$startCol . $rowNumber] = $uniqueKey;
            $rowNumber++;
        }
//        $start = microtime(true);
        $answerObj = Answer::whereIn('unique_key', $uniqueKeyArr)->get();
//        $time_elapsed_secs = microtime(true) - $start;
//        echo " Answer query : " . $time_elapsed_secs . " seconds</br>";

        $whereIn = [];
        $answers = [];
        foreach ($rows as $key => $value) {
            $whereIn[] = $value;
//            echo $value . "\n";
            $p = [];
            $count = [];
            for ($i = 1; $i <= 4; $i++) { //กลุ่มจังหวัด
                $mainList = $mainObj->filterMain($i);
                $whereCondition1 = "";
                $whereCondition3 = "";
                $idx = 0;
                foreach ($value as $radioKey => $radioValue) {
                    if ($idx === 0) {
                        $whereCondition1 .= " AND ( ";
                        $whereCondition3 .= " AND ( ";
                    } else {
                        $whereCondition1 .= " OR ";
                        $whereCondition3 .= " OR ";
                    }
                    //$whereCondition .= " (unique_key='{$mainUnique}' AND option_id = {$uniqueVal}) and (unique_key='$changeUnique' AND option_id = $radioValue)  ";
                    //$whereCondition2 = " AND (unique_key='$notSure' AND option_id = {$uniqueVal}) and (unique_key='$notInNotSure' AND option_id = $radioValue) ";
                    $whereCondition1 .= " (unique_key = '$mainUnique' AND option_id = {$uniqueVal})  ";
                    $whereCondition2 = " AND (unique_key='$changeUnique' AND option_id = $radioValue)  ";
                    $whereCondition3 .= " (unique_key='{$notSure}' AND option_id = {$uniqueVal})  ";
                    $whereCondition4 = " AND (unique_key='{$notInNotSure}' AND option_id = $radioValue)  ";
                    $idx++;
                }
                $whereCondition1 .= " )";
                $whereCondition3 .= " )";

                $whereInMainId = implode(", ", $mainList);
                $sql = "SELECT COUNT(*) as count FROM (SELECT main_id FROM answers WHERE main_id IN ($whereInMainId) " . $whereCondition1 . " )  t1 ";
                $sql .= " inner join (SELECT main_id FROM answers WHERE main_id IN ($whereInMainId) " . $whereCondition2 . " ) t2 on t1.main_id = t2.main_id GROUP BY t2.main_id";
                $sql2 = "SELECT COUNT(*) as count FROM (SELECT main_id FROM answers WHERE main_id IN ($whereInMainId) " . $whereCondition3 . " )  t1 ";
                $sql2 .= " inner join (SELECT main_id FROM answers WHERE main_id IN ($whereInMainId) " . $whereCondition4 . " ) t2 on t1.main_id = t2.main_id GROUP BY t2.main_id";
                //echo $sql;
                $result1 = \DB::select($sql);
                $result2 = \DB::select($sql2);
                $count[$i] = count($result1)==0?0:$result1[0]->count;
                $count[$i] += count($result2)==0?0:$result2[0]->count;
                $p[$i] = $w[$i] * ((float)$count[$i] / $s[$i]);
                //echo $w[$i]." / ".$count[$i]." / ". $s[$i]." / ".$p[$i]."<br><br>";
            }

            $percentage = $p[1] + $p[2];
            $answers[$key] = $percentage*$S[1];
            $col = $startCol;
            $col++;
            $key2 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key2] = $percentage*100;
            $col++;
            $key3 = preg_replace('/[A-Z]+/', $col, $key);

            $percentage = $p[3] + $p[4];


            $answers[$key3] = $percentage*$S[3];
            $col++;
            $key4 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key4] = $percentage*100;
            //รวม
            $col++;
            $key5 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key6 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key6] = ($answers[$key2]*Main::$weight[Main::NORTHERN_INNER] + $answers[$key4]*Main::$weight[Main::NORTHERN_OUTER])/100;
            $answers[$key5] = ($answers[$key6]) * (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN])->getValue();
			$answers[$key6] *= 100;

            $objPHPExcel->getActiveSheet()->setCellValue($key, $answers[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, ($answers[$key2]));
            $objPHPExcel->getActiveSheet()->setCellValue($key3, $answers[$key3]);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, ($answers[$key4]));
            $objPHPExcel->getActiveSheet()->setCellValue($key5, $answers[$key5]);
            $objPHPExcel->getActiveSheet()->setCellValue($key6, ($answers[$key6]));

            $objPHPExcel->getActiveSheet()->getStyle($key)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key2)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key3)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key4)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key5)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key6)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);

        }

        return $objPHPExcel;
    }

    /**
     * @param $settings
     * @return array
     */
    protected static function getSettingVariables()
    {
        $settings = Setting::all();

        $weight = [];
        $weight[1] = $settings->where('code', Setting::NORTHERN_INNER_GROUP_1_WEIGHT_CODE)->first()->value;
        $weight[2] = $settings->where('code', Setting::NORTHERN_INNER_GROUP_2_WEIGHT_CODE)->first()->value;
        $weight[3] = $settings->where('code', Setting::NORTHERN_OUTER_GROUP_1_WEIGHT_CODE)->first()->value;
        $weight[4] = $settings->where('code', Setting::NORTHERN_OUTER_GROUP_2_WEIGHT_CODE)->first()->value;

        $weight[Main::NORTHERN_INNER] = $settings->where('code', Setting::NORTHERN_INNER_WEIGHT_CODE)->first()->value;
        $weight[Main::NORTHERN_OUTER] = $settings->where('code', Setting::NORTHERN_OUTER_WEIGHT_CODE)->first()->value;

        $realSample = [];
        $realSample[1] = $settings->where('code', Setting::NORTHERN_INNER_GROUP_1_SAMPLE_CODE)->first()->value;
        $realSample[2] = $settings->where('code', Setting::NORTHERN_INNER_GROUP_2_SAMPLE_CODE)->first()->value;
        $realSample[3] = $settings->where('code', Setting::NORTHERN_OUTER_GROUP_1_SAMPLE_CODE)->first()->value;
        $realSample[4] = $settings->where('code', Setting::NORTHERN_OUTER_GROUP_2_SAMPLE_CODE)->first()->value;

        $population = [];
        $population[Main::NORTHERN_INNER] = (float)$settings->where('code', Setting::NORTHERN_INNER_POPULATION_CODE)->first()->value;
        $population[Main::NORTHERN_OUTER] = (float)$settings->where('code', Setting::NORTHERN_OUTER_POPULATION_CODE)->first()->value;
        $population[Main::NORTHERN] = (float)$settings->where('code', Setting::NORTHERN_POPULATION_CODE)->first()->value;
        return array($weight, $realSample, $population);
    }

}
