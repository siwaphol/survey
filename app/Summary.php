<?php

namespace App;

use App\Http\Controllers\MainController;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    public static function sum($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $isRadio = false, $isCustomHaving = false, $havingUniqueKey=null, $arraySum = false, $radioCondition = 'OR', $colSkip=0)
    {
        list($w, $s, $population) = self::getSettingVariables();

        $rows = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey) {
            $rows[$startCol . $rowNumber] = $uniqueKey;
            $rowNumber++;
        }

        if (!$isRadio && !$arraySum){
            $uniqueKeyArrWithoutEmptyValue = array_filter($uniqueKeyArr, function($var){
                return !empty($var) && is_string($var);
            });

            foreach ($uniqueKeyArr as $item){
                if (!empty($item) && is_array($item)){
                    foreach ($item as $sub_unique_key){
                        if (!in_array($sub_unique_key,$uniqueKeyArrWithoutEmptyValue))
                            $uniqueKeyArrWithoutEmptyValue[] = $sub_unique_key;
                    }
                }
            }

            $answerObj = Answer::whereIn('unique_key', $uniqueKeyArrWithoutEmptyValue)->get();
        }
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
                            $whereCondition .= " {$radioCondition} ";
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
            for($i=0;$i<$colSkip;$i++){
                $col++;
            }
            $key3 = preg_replace('/[A-Z]+/', $col, $key);
            $percentage2 = $p[Main::OUTER_GROUP_1] + $p[Main::OUTER_GROUP_2];
            $answers[$key3] = $percentage2* $population[Main::NORTHERN_OUTER];
            $col++;
            $key4 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key4] = $percentage2*100;
            //รวม
            $col++;
            for($i=0;$i<$colSkip;$i++){
                $col++;
            }
            $key5 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key6 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key6] = ($answers[$key2]*$w[Main::NORTHERN_INNER] + $answers[$key4]*$w[Main::NORTHERN_OUTER])/100;
            $answers[$key5] = ($answers[$key6] ) * $population[Main::NORTHERN];
            $answers[$key6] *= 100;

            $objPHPExcel->getActiveSheet()->setCellValue($key, (int)$answers[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, ($answers[$key2]));
            $objPHPExcel->getActiveSheet()->setCellValue($key3, (int)$answers[$key3]);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, ($answers[$key4]));
            $objPHPExcel->getActiveSheet()->setCellValue($key5, (int)$answers[$key5]);
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

    public static function average($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $isRadio = false, $radioArr = [], $year=false, $multiply=null, $customSql=false,$customWhere=false, $useSum = false)
    {
        list($weight, $sample, $population) = self::getSettingVariables();

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

            if (empty($value)){
                $level1Counter++;
                continue;
            }

            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $avg[$b_key] = 0;
                $stddev[$b_key] = 0;

                $whereMainId = implode(",", $mainList);
                if ($isRadio) {
                    $newSql = " (IF(SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))>0,1,0) * SUM(IF(unique_key='amountKey',answer_numeric,0))) ";
                    $finalSql = "";
                    $idx = 0;
                    $whereUniqueKey = implode("','", $value);
                    $whereUniqueKey = "'" . $whereUniqueKey . "'";
                    foreach ($radioArr[$level1Counter] as $radioKey => $radioValue) {
                        $temp = $newSql;
                        $removedCharRadioKey = str_replace('@', '' ,$radioKey);
                        $temp = str_replace('radioKey', $removedCharRadioKey, $temp);
                        $temp = str_replace('radioValue', $radioValue, $temp);
                        $temp = str_replace('amountKey', $value[$idx], $temp);

                        $finalSql .= $temp . " + ";

                        $whereUniqueKey .= ",'" . $removedCharRadioKey . "'";
                        $idx++;
                    }
                    $finalSql .= " 0 ";
                    $whereUniqueKey = " AND unique_key IN (" . $whereUniqueKey . ")";

                    $sumSQL = $finalSql;

                    $avgSql = "SELECT SUM(sum1) as a_sum, COUNT(*) as countAll FROM
                        (SELECT $finalSql AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id) T1";

                }
                else if($customSql){
                    $sumSQL = $customSql[$level1Counter];
                    $whereSql = $customWhere[$level1Counter];

                    $avgSql = "SELECT SUM(sum1) as a_sum, COUNT(*) as countAll FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereSql
                        . " GROUP BY main_id) T1";
                }
                else {
                    //old2
                    if (is_array($value)) {
                        $whereUniqueKey = implode("','", $value);
                        $tempUniqueKey = $whereUniqueKey;
                        $whereUniqueKey = " AND unique_key IN ('" .$whereUniqueKey."') ";
                        $sumSQL = " SUM(IF(unique_key IN ('$tempUniqueKey'),answer_numeric,0)) ";
                    }else{
                        $whereUniqueKey = " AND unique_key='$value'";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) ";
                    }

                    $avgSql = "SELECT SUM(sum1) as a_sum, COUNT(*) as countAll FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id) T1";
                }
                $avgResult = \DB::select($avgSql);

                if (!is_null($multiply))
                    $avg[$b_key] = ($avgResult[0]->a_sum*(float)$multiply)/$sample[$b_key];
                else if ($year)
                    $avg[$b_key] = ($avgResult[0]->a_sum*12.0)/$sample[$b_key];
                else
                    $avg[$b_key] = $avgResult[0]->a_sum/$sample[$b_key];

                if ($useSum){
                    $count[$b_key] = $avgResult[0]->a_sum;
                }else{
                    $count[$b_key] = $avgResult[0]->countAll;
                }

                $p[$b_key] = $avg[$b_key] * $weight[$b_key];
            }

            $outerNorthernMain = array_merge($mainObj->filterMain(Main::OUTER_GROUP_1), $mainObj->filterMain(Main::OUTER_GROUP_2));
            $outerNorthernMain = implode(",",$outerNorthernMain);
            $innerNorthernMain = array_merge($mainObj->filterMain(Main::INNER_GROUP_1), $mainObj->filterMain(Main::INNER_GROUP_2));
            $innerNorthernMain = implode(",", $innerNorthernMain);

            if ($isRadio){
                $stddevForEachRadio = array(Main::NORTHERN_OUTER=>[], Main::NORTHERN_INNER=>[]);
                $newSql = " (IF(SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))>0,1,0) * SUM(IF(unique_key='amountKey',answer_numeric,0))) ";
                $idx = 0;
                $whereUniqueKey = implode("','", $value);
                $whereUniqueKey = "'" . $whereUniqueKey . "'";

                foreach ($radioArr[$level1Counter] as $radioKey => $radioValue) {
                    $temp = $newSql;
                    $removedCharRadioKey = str_replace('@', '' ,$radioKey);
                    $temp = str_replace('radioKey', $removedCharRadioKey, $temp);
                    $temp = str_replace('radioValue', $radioValue, $temp);
                    $temp = str_replace('amountKey', $value[$idx], $temp);

                    $sumSQL = $temp;
                    $whereUniqueKey .= ",'" . $removedCharRadioKey . "'";

                    $outerSTDDEVSql = "SELECT STDDEV(sum1) as a_stddev FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($outerNorthernMain)"
                        . " GROUP BY main_id) T1 where sum1>0";
                    $result = \DB::select($outerSTDDEVSql);
                    $stddevForEachRadio[Main::NORTHERN_OUTER][] = $result[0]->a_stddev * $result[0]->a_stddev;

                    $innerSTDDEVSql = "SELECT STDDEV(sum1) as a_stddev FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($innerNorthernMain)"
                        . " GROUP BY main_id) T1 where sum1>0";
                    $result = \DB::select($innerSTDDEVSql);
                    $stddevForEachRadio[Main::NORTHERN_INNER][] = $result[0]->a_stddev * $result[0]->a_stddev;

                    $idx++;
                }

                $stddev[Main::NORTHERN_OUTER] = $stddevForEachRadio[Main::NORTHERN_OUTER]?
                    sqrt(array_sum($stddevForEachRadio[Main::NORTHERN_OUTER])/count($stddevForEachRadio[Main::NORTHERN_OUTER]))
                    :0;
                $stddev[Main::NORTHERN_INNER] = $stddevForEachRadio[Main::NORTHERN_INNER]?
                    sqrt(array_sum($stddevForEachRadio[Main::NORTHERN_INNER])/count($stddevForEachRadio[Main::NORTHERN_INNER]))
                    :0;
            }else{
                $outerSTDDEVSql = "SELECT STDDEV(sum1) as a_stddev FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($outerNorthernMain) "
                    . " GROUP BY main_id) T1";
                $result = \DB::select($outerSTDDEVSql);
                $stddev[Main::NORTHERN_OUTER] = $result[0]->a_stddev;

                $innerSTDDEVSql = "SELECT STDDEV(sum1) as a_stddev FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($innerNorthernMain) "
                    . " GROUP BY main_id) T1";
                $result = \DB::select($innerSTDDEVSql);
                $stddev[Main::NORTHERN_INNER] = $result[0]->a_stddev;
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
            $sqrtInnerCount = sqrt($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]);
            $answers[$key2] = $sqrtInnerCount?(($stddev[Main::NORTHERN_INNER]) / $sqrtInnerCount):0;
//            $answers[$key2] = (($stddev[Main::INNER_GROUP_1]*$weight[Main::INNER_GROUP_1] + $stddev[Main::INNER_GROUP_2]*$weight[Main::INNER_GROUP_2])/2.0)
//                / sqrt($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]);
//            $answers[$key2] = (($stddev[Main::INNER_GROUP_1] + $stddev[Main::INNER_GROUP_2])/2.0)
//                / sqrt($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]);

            $answers[$key3] = $p[Main::OUTER_GROUP_1] + $p[Main::OUTER_GROUP_2];
            $sqrtOuterCount = sqrt($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]);
            $answers[$key4] = $sqrtOuterCount?($stddev[Main::NORTHERN_OUTER] / $sqrtOuterCount):0;
//            $answers[$key4] = (($stddev[Main::OUTER_GROUP_1]*$weight[Main::OUTER_GROUP_1] + $stddev[Main::OUTER_GROUP_2]*$weight[Main::OUTER_GROUP_2])/2.0)
//                / sqrt($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]);
//            $answers[$key4] = (($stddev[Main::OUTER_GROUP_1] + $stddev[Main::OUTER_GROUP_2])/2.0)
//                / sqrt($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]);

            $objPHPExcel->getActiveSheet()->setCellValue($key, $answers[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, $answers[$key2]);
            $objPHPExcel->getActiveSheet()->setCellValue($key3, $answers[$key3]);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, $answers[$key4]);
            $objPHPExcel->getActiveSheet()->setCellValue($key5, (($answers[$key]*$weight[Main::NORTHERN_INNER] + $answers[$key3] * $weight[Main::NORTHERN_OUTER])));
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
        list($weight, $sample, $population) = self::getSettingVariables();

        $allUniqueKey = [];
        foreach ($uniqueKeyArr as $item) {
            foreach ($item as $subItem) {
                if (is_string($subItem))
                    $allUniqueKey[] = $subItem;
            }
        }

        $rows = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey) {
            $rows[$startCol . $rowNumber] = $uniqueKey;
            $rowNumber++;
        }

        $answers = [];
        $level1Index = 0;
        foreach ($rows as $key => $value) {
            $sum = [];

            if (empty($value)){
                $level1Index++;
                continue;
            }

            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $finalSql = $sqlSum;
                foreach ($param as $pKey => $pValue) {
                    $finalSql = str_replace($pKey, $value[$pValue], $finalSql);
                }

                $resultQuery2 = Answer::whereIn('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->groupBy('main_id')
                    ->select(\DB::raw($finalSql))
                    ->get();

                $sum[$b_key] = 0.0;
                foreach ($resultQuery2 as $row) {
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

            $sumUsageHourPerYear = [];
            $sumUsageHourPerYear[Main::INNER_GROUP_1] = ($sum[Main::INNER_GROUP_1] / $sample[Main::INNER_GROUP_1]) * $weight[Main::INNER_GROUP_1];
            $sumUsageHourPerYear[Main::INNER_GROUP_2] = ($sum[Main::INNER_GROUP_2] / $sample[Main::INNER_GROUP_2]) * $weight[Main::INNER_GROUP_2];
            $sumUsageHourPerYear[Main::OUTER_GROUP_1] = ($sum[Main::OUTER_GROUP_1] / $sample[Main::OUTER_GROUP_1]) * $weight[Main::OUTER_GROUP_1];
            $sumUsageHourPerYear[Main::OUTER_GROUP_2] = ($sum[Main::OUTER_GROUP_2] / $sample[Main::OUTER_GROUP_2]) * $weight[Main::OUTER_GROUP_2];

            $y = array();
            $y[Main::NORTHERN_INNER] = ($sumUsageHourPerYear[Main::INNER_GROUP_1] + $sumUsageHourPerYear[Main::INNER_GROUP_2]);
            $y[Main::NORTHERN_OUTER] = ($sumUsageHourPerYear[Main::OUTER_GROUP_1] + $sumUsageHourPerYear[Main::OUTER_GROUP_2]);

            $answers[$key] = $y[Main::NORTHERN_INNER] * $population[Main::NORTHERN_INNER];
            $answers[$key] = $answers[$key] / 1000000.0;
            $answers[$key3] = $y[Main::NORTHERN_OUTER] * $population[Main::NORTHERN_OUTER];
            $answers[$key3] = $answers[$key3] / 1000000.0;

            $answers[$key5] = ($y[Main::NORTHERN_INNER]*$weight[Main::NORTHERN_INNER] +
            $y[Main::NORTHERN_OUTER]*$weight[Main::NORTHERN_OUTER])
            * $population[Main::NORTHERN] / 1000000.0;

            //ktoe
            if ($ktoeIdx !== false) {
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

        return $objPHPExcel;
    }

    public static function averageLifetime($uniqueKeyArr,$uniqueKeyArrAmount, $startCol, $startRow, $objPHPExcel, $mainObj, $isRadio = false, $radioArr = [], $year=false, $multiply=null, $amountColumn='answer_numeric')
    {
        list($weight, $sample, $population) = self::getSettingVariables();

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
        $amount = []; // จำนวณอุปกรณ์

        $level1Counter = 0;
        foreach ($rows as $key => $value) {
            $whereIn[] = $value;
            $p = [];
            $avg = [];

            if (empty($value)){
                $level1Counter++;
                continue;
            }

            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $avg[$b_key] = 0;
                $stddev[$b_key] = 0;

                $whereMainId = implode(",", $mainList);
                if ($isRadio) {
                    $newSql = " (IF(SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))>0,1,0) * SUM(IF(unique_key='amountKey',answer_numeric,0))) ";

                    $finalSql = "";
                    $amountSql = "";
                    $idx = 0;
                    $whereUniqueKey = implode("','", $value);
                    $whereAmountUniqueKey = implode("','", $uniqueKeyArrAmount[$level1Counter]);
                    $whereUniqueKey = "'" . $whereUniqueKey . "','" .$whereAmountUniqueKey. "'";

                    foreach ($radioArr[$level1Counter] as $radioKey => $radioValue) {
                        $temp = $newSql;
                        $removedCharRadioKey = str_replace('@', '' ,$radioKey);

                        $temp = str_replace('radioKey', $removedCharRadioKey, $temp);
                        $temp = str_replace('radioValue', $radioValue, $temp);
                        $temp = str_replace('amountKey', $value[$idx], $temp); // อายุการใช้งาน

                        $tempAmount = $newSql;
                        $tempAmount = str_replace('radioKey', $removedCharRadioKey, $tempAmount);
                        $tempAmount = str_replace('radioValue', $radioValue, $tempAmount);
                        $tempAmount = str_replace('amountKey', $uniqueKeyArrAmount[$level1Counter][$idx], $tempAmount); // จำนวน

                        $finalSql .= $temp . " + ";
                        $amountSql .= $tempAmount . " + ";

                        $whereUniqueKey .= ",'" . $removedCharRadioKey . "'";
                        $idx++;
                    }
                    $finalSql .= " 0 ";
                    $amountSql .= " 0 ";
                    $whereUniqueKey = " AND unique_key IN (" . $whereUniqueKey . ")";

                    $sumSQL = $finalSql;

                    $avgSql = "SELECT SUM(sum1) as a_sum, COUNT(*) as countAll, SUM(sumAmount1) as amount_sum FROM
                        (SELECT $finalSql AS sum1, $amountSql AS sumAmount1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id) T1";
                } else {
                    if (is_array($value)) {
                        $whereUniqueKey = implode("','", $value);
                        $tempUniqueKey = $whereUniqueKey;

                        $whereAmountUniqueKey = implode("','", $uniqueKeyArrAmount[$level1Counter]);
                        $tempAmountUniqueKey = $whereAmountUniqueKey;

                        $whereUniqueKey = " AND (unique_key IN ('" .$whereUniqueKey."') OR unique_key IN ('$whereAmountUniqueKey')) ";
                        $sumSQL = " SUM(IF(unique_key IN ('$tempUniqueKey'),answer_numeric,0)) ";

                        $sumAmountSQL = " SUM(IF(unique_key IN ('$tempAmountUniqueKey'), {$amountColumn},0)) ";
                    }else{
                        $whereUniqueKey = " AND (unique_key='$value' OR unique_key='$uniqueKeyArrAmount[$level1Counter]') ";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) ";
                        $sumAmountSQL = " SUM(IF(unique_key='$uniqueKeyArrAmount[$level1Counter]', {$amountColumn},0)) ";
                    }

                    $avgSql = "SELECT SUM(sum1) as a_sum, COUNT(*) as countAll, SUM(sumAmount1) as amount_sum FROM
                         ( SELECT $sumSQL AS sum1, $sumAmountSQL AS sumAmount1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id ) T1";

//                    $avgSql = "SELECT SUM(sum1) as a_sum, COUNT(*) as countAll FROM
//                        (SELECT $sumSQL AS sum1 FROM answers
//                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
//                        . " GROUP BY main_id) T1";

                }

                $avgResult = \DB::select($avgSql);

                if ((int)$avgResult[0]->amount_sum===0)
                    $avg[$b_key] = 0;
                else if (!is_null($multiply))
                    $avg[$b_key] = ($avgResult[0]->a_sum*(float)$multiply)/$avgResult[0]->amount_sum;
                else if ($year)
                    $avg[$b_key] = ($avgResult[0]->a_sum*12.0)/$avgResult[0]->amount_sum;
                else
                    $avg[$b_key] = $avgResult[0]->a_sum/$avgResult[0]->amount_sum;

//                $count[$b_key] = $avgResult[0]->countAll;
                $amount[$b_key] = $avgResult[0]->amount_sum;

                $p[$b_key] = $avg[$b_key] * $weight[$b_key];
            }

            $outerNorthernMain = array_merge($mainObj->filterMain(Main::OUTER_GROUP_1), $mainObj->filterMain(Main::OUTER_GROUP_2));
            $outerNorthernMain = implode(",",$outerNorthernMain);
            $innerNorthernMain = array_merge($mainObj->filterMain(Main::INNER_GROUP_1), $mainObj->filterMain(Main::INNER_GROUP_2));
            $innerNorthernMain = implode(",", $innerNorthernMain);

            if ($isRadio){
                $stddevForEachRadio = array(Main::NORTHERN_OUTER=>[], Main::NORTHERN_INNER=>[]);
                $newSql = " (IF(SUM(IF(unique_key='radioKey' AND option_id=radioValue,1,0))>0,1,0) * SUM(IF(unique_key='amountKey',answer_numeric,0))) ";
                $idx = 0;
                $whereUniqueKey = implode("','", $value);
                $whereUniqueKey = "'" . $whereUniqueKey . "'";

                foreach ($radioArr[$level1Counter] as $radioKey => $radioValue) {
                    $temp = $newSql;
                    $removedCharRadioKey = str_replace('@', '' ,$radioKey);
                    $temp = str_replace('radioKey', $removedCharRadioKey, $temp);
                    $temp = str_replace('radioValue', $radioValue, $temp);
                    $temp = str_replace('amountKey', $value[$idx], $temp);

                    $sumSQL = $temp;
                    $whereUniqueKey .= ",'" . $removedCharRadioKey . "'";

                    $outerSTDDEVSql = "SELECT STDDEV(sum1) as a_stddev FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($outerNorthernMain) "
                        . " GROUP BY main_id) T1 where sum1>0";
                    $result = \DB::select($outerSTDDEVSql);
                    $stddevForEachRadio[Main::NORTHERN_OUTER][] = $result[0]->a_stddev * $result[0]->a_stddev;

                    $innerSTDDEVSql = "SELECT STDDEV(sum1) as a_stddev FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($innerNorthernMain) "
                        . " GROUP BY main_id) T1 where sum1>0";
                    $result = \DB::select($innerSTDDEVSql);
                    $stddevForEachRadio[Main::NORTHERN_INNER][] = $result[0]->a_stddev * $result[0]->a_stddev;

                    $idx++;
                }

                $stddev[Main::NORTHERN_OUTER] = $stddevForEachRadio[Main::NORTHERN_OUTER]?
                    sqrt(array_sum($stddevForEachRadio[Main::NORTHERN_OUTER])/count($stddevForEachRadio[Main::NORTHERN_OUTER]))
                    :0;
                $stddev[Main::NORTHERN_INNER] = $stddevForEachRadio[Main::NORTHERN_INNER]?
                    sqrt(array_sum($stddevForEachRadio[Main::NORTHERN_INNER])/count($stddevForEachRadio[Main::NORTHERN_INNER]))
                    :0;
            }else{
                $outerSTDDEVSql = "SELECT STDDEV(sum1) as a_stddev FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($outerNorthernMain) "
                    . " GROUP BY main_id) T1";
                $result = \DB::select($outerSTDDEVSql);
                $stddev[Main::NORTHERN_OUTER] = $result[0]->a_stddev;

                $innerSTDDEVSql = "SELECT STDDEV(sum1) as a_stddev FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($innerNorthernMain) "
                    . " GROUP BY main_id) T1";
                $result = \DB::select($innerSTDDEVSql);
                $stddev[Main::NORTHERN_INNER] = $result[0]->a_stddev;
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
            $sqrtInnerCount = sqrt($amount[Main::INNER_GROUP_1] + $amount[Main::INNER_GROUP_2]);
            $answers[$key2] = $sqrtInnerCount?($stddev[Main::NORTHERN_INNER]/ $sqrtInnerCount):0;

            $answers[$key3] = $p[Main::OUTER_GROUP_1] + $p[Main::OUTER_GROUP_2];
            $sqrtOuterCount = sqrt($amount[Main::OUTER_GROUP_1] + $amount[Main::OUTER_GROUP_2]);
            $answers[$key4] = $sqrtOuterCount?($stddev[Main::NORTHERN_OUTER]/ $sqrtOuterCount):0;

            $objPHPExcel->getActiveSheet()->setCellValue($key, $answers[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, $answers[$key2]);
            $objPHPExcel->getActiveSheet()->setCellValue($key3, $answers[$key3]);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, $answers[$key4]);
            $objPHPExcel->getActiveSheet()->setCellValue($key5, (($answers[$key]*$weight[Main::NORTHERN_INNER] + $answers[$key3] * $weight[Main::NORTHERN_OUTER])));
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

    public static function sum13($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $changeUnique, $notSure, $notInNotSure, $mainUnique, $uniqueVal, $changeAnswerUnique, $changeAnswerValue)
    {
        list($weight, $sample, $population) = self::getSettingVariables();

        $rows = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey) {
            $rows[$startCol . $rowNumber] = $uniqueKey;
            $rowNumber++;
        }

        $whereIn = [];
        $answers = [];
        foreach ($rows as $key => $value) {
            $whereIn[] = $value;
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
                $sql .= " inner join (SELECT main_id FROM answers WHERE main_id IN ($whereInMainId) " . $whereCondition2 . " ) t2 on t1.main_id = t2.main_id ";

                $newSql = " SELECT COUNT(*) as count FROM 
                  (
                  SELECT SUM(IF(unique_key='{$mainUnique}' AND option_id='{$uniqueVal}',1,0)) * SUM(IF(unique_key='{$changeUnique}' AND option_id='{$radioValue}',1,0)) 
                  * SUM(IF(unique_key='{$changeAnswerUnique}' AND option_id='{$changeAnswerValue}',1,0)) as sumAmount
                  FROM answers where main_id IN ($whereInMainId) GROUP BY main_id ) t1
                  WHERE sumAmount>0 ";

//                $sql2 = "SELECT COUNT(*) as count FROM (SELECT main_id FROM answers WHERE main_id IN ($whereInMainId) " . $whereCondition3 . " )  t1 ";
//                $sql2 .= " inner join (SELECT main_id FROM answers WHERE main_id IN ($whereInMainId) " . $whereCondition4 . " ) t2 on t1.main_id = t2.main_id ";
                //echo $sql;
                $result1 = \DB::select($newSql);
//                $result2 = \DB::select($sql2);
                $count[$i] = count($result1)==0?0:$result1[0]->count;
//                $count[$i] += count($result2)==0?0:$result2[0]->count;
                $p[$i] = $weight[$i] * ((float)$count[$i] / $sample[$i]);
            }

            $percentage = $p[1] + $p[2];
            $answers[$key] = $percentage*$population[Main::NORTHERN_INNER];
            $col = $startCol;
            $col++;
            $key2 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key2] = $percentage*100;
            $col++;
            $key3 = preg_replace('/[A-Z]+/', $col, $key);

            $percentage = $p[3] + $p[4];

            $answers[$key3] = $percentage*$population[Main::NORTHERN_OUTER];
            $col++;
            $key4 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key4] = $percentage*100;
            //รวม
            $col++;
            $key5 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key6 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key6] = ($answers[$key2]*$weight[Main::NORTHERN_INNER] + $answers[$key4]*$weight[Main::NORTHERN_OUTER])/100;
            $answers[$key5] = ($answers[$key6]) * $population[Main::NORTHERN];
			$answers[$key6] *= 100;

            $objPHPExcel->getActiveSheet()->setCellValue($key, ceil($answers[$key]));
            $objPHPExcel->getActiveSheet()->setCellValue($key2, ($answers[$key2]));
            $objPHPExcel->getActiveSheet()->setCellValue($key3, ceil($answers[$key3]));
            $objPHPExcel->getActiveSheet()->setCellValue($key4, ($answers[$key4]));
            $objPHPExcel->getActiveSheet()->setCellValue($key5, ceil($answers[$key5]));
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

    // สำหรับหมวด ค
    public static function sum11($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj)
    {
        list($weight, $sample, $population) = self::getSettingVariables();

        $w = [];
        $w[1] = $weight[Main::INNER_GROUP_1];
        $w[2] = $weight[Main::INNER_GROUP_2];
        $w[3] = $weight[Main::OUTER_GROUP_1];
        $w[4] = $weight[Main::OUTER_GROUP_2];

        $s = [];
        $s[1] = $sample[Main::INNER_GROUP_1];
        $s[2] = $sample[Main::INNER_GROUP_2];
        $s[3] = $sample[Main::OUTER_GROUP_1];
        $s[4] = $sample[Main::OUTER_GROUP_2];

//        $parameterExcel = \PHPExcel_IOFactory::load(storage_path('excel/parameters.xlsx'));
//        $parameterExcel->setActiveSheetIndex(2);
//        $paramSheet = $parameterExcel->getActiveSheet();
        $S = [];
        $S[1] = (float)$population[Main::NORTHERN_INNER];
        $S[2] = (float)$population[Main::NORTHERN_INNER];
        $S[3] = (float)$population[Main::NORTHERN_OUTER];
        $S[4] = (float)$population[Main::NORTHERN_OUTER];

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
            if (empty($options)){
                $startRow++;
                continue;
            }
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

                $answers[Main::NORTHERN_INNER][$attr] = $percents[Main::NORTHERN_INNER][$attr]*$population[Main::NORTHERN_INNER];
                $answers[Main::NORTHERN_OUTER][$attr] = $percents[Main::NORTHERN_OUTER][$attr]*$population[Main::NORTHERN_OUTER];

                $percents[Main::NORTHERN][$attr] = $percents[Main::NORTHERN_INNER][$attr]*$weight[Main::NORTHERN_INNER]
                    + $percents[Main::NORTHERN_OUTER][$attr]*$weight[Main::NORTHERN_OUTER];
                $answers[Main::NORTHERN][$attr] = $percents[Main::NORTHERN][$attr]*$population[Main::NORTHERN];

                $l_loop = [Main::NORTHERN,Main::NORTHERN_OUTER, Main::NORTHERN_INNER];
                foreach ($l_loop as $l_key){
                    $objPHPExcel->getActiveSheet()->setCellValue($tempCol[$l_key].$startRow, (int)$answers[$l_key][$attr]);
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

    public static function average11($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $multiplier,$year = false, $customSql=false)
    {
        list($weight, $sample, $population) = self::getSettingVariables();

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

            if (empty($value)){
                $level1Counter++;
                continue;
            }

            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $avg[$b_key] = 0;
                $stddev[$b_key] = 0;

                $whereMainId = implode(",", $mainList);

                if ($customSql){
                    $sumSQL = $value;

                    $avgSql = "SELECT SUM(sum1) as a_sum, COUNT(*) as countAll FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) "
                        . " GROUP BY main_id) T1 WHERE sum1>0";
                }else{
                    if (is_array($value)) {
                        $whereUniqueKey = implode("','", $value);
                        $tempUniqueKey = $whereUniqueKey;
                        $whereUniqueKey = " AND unique_key IN ('" .$whereUniqueKey."','$multiplier[$level1Counter]') ";
                        $sumSQL = " SUM(IF(unique_key IN ('$tempUniqueKey'),answer_numeric,0)) * 
                    SUM(IF(unique_key='$multiplier[$level1Counter]',answer_numeric,0)) ";
                    }else{
                        $whereUniqueKey = " AND (unique_key='$value' OR unique_key='$multiplier[$level1Counter]') ";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) * 
                    SUM(IF(unique_key='$multiplier[$level1Counter]',answer_numeric,0)) ";
                    }

                    $avgSql = "SELECT SUM(sum1) as a_sum, COUNT(*) as countAll FROM
                    (SELECT $sumSQL AS sum1 FROM answers
                    WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id) T1";
                }

                $avgResult = \DB::select($avgSql);
                $avg[$b_key] = $avgResult[0]->a_sum/$sample[$b_key];

                $count[$b_key] = $avgResult[0]->countAll;

                $p[$b_key] = $avg[$b_key] * $weight[$b_key];
            }

            $outerNorthernMain = array_merge($mainObj->filterMain(Main::OUTER_GROUP_1), $mainObj->filterMain(Main::OUTER_GROUP_2));
            $outerNorthernMain = implode(",",$outerNorthernMain);
            $innerNorthernMain = array_merge($mainObj->filterMain(Main::INNER_GROUP_1), $mainObj->filterMain(Main::INNER_GROUP_2));
            $innerNorthernMain = implode(",", $innerNorthernMain);

            $outerSTDDEVSql = "SELECT STDDEV(sum1) as a_stddev FROM
                    (SELECT $sumSQL AS sum1 FROM answers
                    WHERE main_id IN ($outerNorthernMain) "
                . " GROUP BY main_id) T1";
            $result = \DB::select($outerSTDDEVSql);
            $stddev[Main::NORTHERN_OUTER] = $result[0]->a_stddev;

            $innerSTDDEVSql = "SELECT STDDEV(sum1) as a_stddev FROM
                    (SELECT $sumSQL AS sum1 FROM answers
                    WHERE main_id IN ($innerNorthernMain) "
                . " GROUP BY main_id) T1";
            $result = \DB::select($innerSTDDEVSql);
            $stddev[Main::NORTHERN_INNER] = $result[0]->a_stddev;

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
            $sqrtInnerCount = sqrt($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]);
            $answers[$key2] = $sqrtInnerCount?(($stddev[Main::NORTHERN_INNER]) / $sqrtInnerCount):0;
//            $answers[$key2] = (($stddev[Main::INNER_GROUP_1]*$weight[Main::INNER_GROUP_1] + $stddev[Main::INNER_GROUP_2]*$weight[Main::INNER_GROUP_2])/2.0)
//                / sqrt($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]);
//            $answers[$key2] = (($stddev[Main::INNER_GROUP_1] + $stddev[Main::INNER_GROUP_2])/2.0)
//                / sqrt($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]);

            $answers[$key3] = $p[Main::OUTER_GROUP_1] + $p[Main::OUTER_GROUP_2];
            $sqrtOuterCount = sqrt($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]);
            $answers[$key4] = $sqrtOuterCount?($stddev[Main::NORTHERN_OUTER] / $sqrtOuterCount):0;
//            $answers[$key4] = (($stddev[Main::OUTER_GROUP_1]*$weight[Main::OUTER_GROUP_1] + $stddev[Main::OUTER_GROUP_2]*$weight[Main::OUTER_GROUP_2])/2.0)
//                / sqrt($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]);
//            $answers[$key4] = (($stddev[Main::OUTER_GROUP_1] + $stddev[Main::OUTER_GROUP_2])/2.0)
//                / sqrt($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]);

            $objPHPExcel->getActiveSheet()->setCellValue($key, $answers[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, $answers[$key2]);
            $objPHPExcel->getActiveSheet()->setCellValue($key3, $answers[$key3]);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, $answers[$key4]);
            $objPHPExcel->getActiveSheet()->setCellValue($key5, (($answers[$key]*$weight[Main::NORTHERN_INNER] + $answers[$key3] * $weight[Main::NORTHERN_OUTER])));
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


    /**
     * @param $settings
     * @return array
     */
    public static function getSettingVariables()
    {
        $settings = Setting::all();

        $weight = [];
        $weight[Main::INNER_GROUP_1] = $settings->where('code', Setting::NORTHERN_INNER_GROUP_1_WEIGHT_CODE)->first()->value;
        $weight[Main::INNER_GROUP_2] = $settings->where('code', Setting::NORTHERN_INNER_GROUP_2_WEIGHT_CODE)->first()->value;
        $weight[Main::OUTER_GROUP_1] = $settings->where('code', Setting::NORTHERN_OUTER_GROUP_1_WEIGHT_CODE)->first()->value;
        $weight[Main::OUTER_GROUP_2] = $settings->where('code', Setting::NORTHERN_OUTER_GROUP_2_WEIGHT_CODE)->first()->value;

        $weight[Main::NORTHERN_INNER] = $settings->where('code', Setting::NORTHERN_INNER_WEIGHT_CODE)->first()->value;
        $weight[Main::NORTHERN_OUTER] = $settings->where('code', Setting::NORTHERN_OUTER_WEIGHT_CODE)->first()->value;

        $realSample = [];
        $realSample[Main::INNER_GROUP_1] = $settings->where('code', Setting::NORTHERN_INNER_GROUP_1_SAMPLE_CODE)->first()->value;
        $realSample[Main::INNER_GROUP_2] = $settings->where('code', Setting::NORTHERN_INNER_GROUP_2_SAMPLE_CODE)->first()->value;
        $realSample[Main::OUTER_GROUP_1] = $settings->where('code', Setting::NORTHERN_OUTER_GROUP_1_SAMPLE_CODE)->first()->value;
        $realSample[Main::OUTER_GROUP_2] = $settings->where('code', Setting::NORTHERN_OUTER_GROUP_2_SAMPLE_CODE)->first()->value;

        $population = [];
        $population[Main::NORTHERN_INNER] = (float)$settings->where('code', Setting::NORTHERN_INNER_POPULATION_CODE)->first()->value;
        $population[Main::NORTHERN_OUTER] = (float)$settings->where('code', Setting::NORTHERN_OUTER_POPULATION_CODE)->first()->value;
        $population[Main::NORTHERN] = (float)$settings->where('code', Setting::NORTHERN_POPULATION_CODE)->first()->value;
        return array($weight, $realSample, $population);
    }

}
