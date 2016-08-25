<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    public static function sum($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $isRadio=false)
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
        foreach ($uniqueKeyArr as $uniqueKey){
            $rows[$startCol.$rowNumber] = $uniqueKey;
            $rowNumber++;
        }
//        $start = microtime(true);
        $answerObj = Answer::whereIn('unique_key', $uniqueKeyArr)->get();
//        $time_elapsed_secs = microtime(true) - $start;
//        echo " Answer query : " . $time_elapsed_secs . " seconds</br>";

        $whereIn = [];
        $answers = [];
        foreach ($rows as $key=>$value){
            $whereIn[] = $value;
//            echo $value . "\n";

            $p = [];
            $count = [];

            if ($isRadio){
                for ($i=1; $i<=4; $i++){
                    $mainList = $mainObj->filterMain($i);
                    $whereCondition = "";

                    $idx = 0;
                    foreach ($value as $radioKey=>$radioValue){
                        if ($idx===0)
                            $whereCondition .= " AND ( ";
                        else
                            $whereCondition .= " OR ";
                        $whereCondition .= " (unique_key='$radioKey' AND option_id=$radioValue) ";

                        $idx++;
                    }
                    $whereCondition .= " )";

                    $whereInMainId = implode(",", $mainList);
                    $sql = "SELECT COUNT(*) as count FROM (SELECT main_id FROM answers WHERE main_id IN ($whereInMainId) " . $whereCondition . " GROUP BY main_id) t1";
//                    $start = microtime(true);
                    $count[$i] = \DB::select($sql)[0]->count;
                    $p[$i] = $w[$i] * ((float)$count[$i]/ $s[$i]) * $S[$i];
//                    $time_elapsed_secs = microtime(true) - $start;
//                    echo " Query " . $i . " : " . $time_elapsed_secs . " seconds</br>";
                }
            }
            else {
//                for ($i=1; $i<=4; $i++){
//                    $mainList = $mainObj->filterMain($i);
//
//                    $whereCondition = " AND (unique_key='$value') ";
//                    $whereInMainId = implode(",", $mainList);
//                    $sql = "SELECT COUNT(*) as count FROM (SELECT main_id FROM answers WHERE main_id IN ($whereInMainId) " . $whereCondition . " GROUP BY main_id) t1";
////                    $start = microtime(true);
//                    $count[$i] = \DB::select($sql)[0]->count;
////                    $time_elapsed_secs = microtime(true) - $start;
////                    echo " Query " . $i . " : " . $time_elapsed_secs . " seconds</br>";
//                }
//                var_dump($count);
                for ($i=1; $i<=4; $i++){
                    $mainList = $mainObj->filterMain($i);
                    $dupMainId = [];
//                    $start = microtime(true);
                    $count[$i] = $answerObj->filter(function($item, $key)use($mainList, $value, &$dupMainId){
                        $condition = (!in_array($item->main_id, $dupMainId)) && $item->unique_key===$value
                            && in_array($item->main_id, $mainList);
                        if ($item->unique_key===$value)
                            $dupMainId[] = $item->unique_key;

                        return $condition;
                    })->count();
//                    $time_elapsed_secs = microtime(true) - $start;
//                    echo " Collection " . $i . " : " . $time_elapsed_secs . " seconds</br>";

                    $p[$i] = $w[$i] * ((float)$count[$i]/ $s[$i]) * $S[$i];
                }
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

        return $objPHPExcel;
    }

    public static function average($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj)
    {
        $rows = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey){
            $rows[$startCol.$rowNumber] = $uniqueKey;
            $rowNumber++;
        }

        $allUniqueArr = [];
        foreach ($uniqueKeyArr as $item){
            if (!is_array($item))
                $allUniqueArr[] = $item;
            else{
                foreach ($item as $subItem)
                    $allUniqueArr[] = $subItem;
            }
        }
//        $starttime = microtime(true);
//        $answerObj = Answer::whereIn('unique_key', $allUniqueArr)->get(['main_id','unique_key','answer_numeric']);

        $whereIn = [];
        $answers = [];
        $count = [];
        $A = [];

        foreach ($rows as $key=>$value){
//            $rowTime = microtime(true);
            $whereIn[] = $value;

            $p = [];
            $avg = [];

//            $testtime = microtime(true);
            foreach (Main::$provinceWeight as $p_key=>$p_weight){
//                $rmTime = microtime(true);
                $mainList = $mainObj->filterMain($p_key);
//                echo "Retrive main time: " . ((microtime(true)-$rmTime)) . " seconds </br>";

//                $dupMainId = [];
//                if (is_array($value)){
//                    $count[$p_key] = $answerObj->filter(function($item, $key) use ($value, $mainList, &$dupMainId){
//                        $condition = (!in_array($item->main_id, $dupMainId)) && in_array($item->unique_key, $value)
//                            && in_array($item->main_id, $mainList);
//                        if (in_array($item->unique_key, $value))
//                            $dupMainId[] = $item->main_id;
//                        return $condition;
//                    })->count();

                    //average loop method
//                    $avg[$p_key]=0;
//                    $totalSum = 0;
//                    foreach ($mainList as $mainId){
//                        $totalSum += $answerObj->reduce(function ($l_carry, $l_item) use ($mainId, $value){
//                            if ((int)$l_item->main_id===(int)$mainId && in_array($l_item->unique_key, $value)){
//                                return $l_carry+$l_item->answer_numeric;
//                            }
//                            return $l_carry;
//                        },0);
//                    }
//                    $avg[$p_key] = $totalSum/(float)$count[$p_key];

                    //old2
                    $avg[$p_key]=0;
                    $whereMainId = implode(",", $mainList);
                    if (is_array($value)){
                        $whereUniqueKey = implode("','", $value);
                        $whereUniqueKey = " AND unique_key IN ('" .$whereUniqueKey."') ";
                    }else
                        $whereUniqueKey = " AND unique_key='$value'";

                    $avgSql = "SELECT AVG(sum1) as average, COUNT(*) as countAll FROM
                        (SELECT sum(answer_numeric) AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        ." GROUP BY main_id) T1";
                    $avgResult = \DB::select($avgSql);
                    $avg[$p_key] = $avgResult[0]->average;
                    $count[$p_key] = $avgResult[0]->countAll;
//                    echo " collect count : " . $count[$p_key] . " == " . $avgResult[0]->countAll."</br>";
                    // old1
//                    foreach ($value as $eachValue){
//                        $avg[$p_key] += Answer::where('unique_key', $eachValue)
//                            ->whereIn('main_id', $mainList)
//                            ->select(\DB::raw(" AVG(answer_numeric) as average "))
//                            ->first()->average;
//                    }

//                    $avg[$p_key] = $avg[$p_key]/count($value);
//                }else{
//                    $count[$p_key] = $answerObj->filter(function($item, $key) use ($value, $mainList, &$dupMainId){
//                        $condition = (!in_array($item->main_id, $dupMainId)) && $item->unique_key===$value
//                            && in_array($item->main_id, $mainList);
//                        if ($item->unique_key===$value)
//                            $dupMainId[] = $item->unique_key;
//
//                        return $condition;
//                    })->count();
//
//                    $avg[$p_key] = Answer::where('unique_key', $value)
//                        ->whereIn('main_id', $mainList)
//                        ->select(\DB::raw(" AVG(answer_numeric) as average "))
//                        ->first()->average;
//                }

            }
//            $finaltime = microtime(true)-$testtime;
//            echo "Here is one loop give you in minutes" . ($finaltime/60) ."</br>";

//            $testtime = microtime(true);
            foreach (Main::$borderWeight as $b_key=>$b_weight){
//                $rmTime = microtime(true);
                $mainList = $mainObj->filterMain($b_key);
//                echo "Retrive main time: " . ((microtime(true)-$rmTime)) . " seconds ";

//                $dupMainId = [];
//                if (is_array($value)){
//                                        $countTime = microtime(true);
//                    $count[$b_key] = $answerObj->filter(function($item, $key) use ($value, $mainList, &$dupMainId){
//                        $condition = (!in_array($item->main_id, $dupMainId)) && in_array($item->unique_key, $value)
//                            && in_array($item->main_id, $mainList);
//                        if (in_array($item->unique_key, $value))
//                            $dupMainId[] = $item->main_id;
//                        return $condition;
//                    })->count();

//                    $avg[$b_key]=0;
//                    $totalSum = 0;
//                    foreach ($mainList as $mainId){
//                        $totalSum += $answerObj->reduce(function ($l_carry, $l_item) use ($mainId, $value){
//                            if ((int)$l_item->main_id===(int)$mainId && in_array($l_item->unique_key, $value)){
//                                return $l_carry+$l_item->answer_numeric;
//                            }
//                            return $l_carry;
//                        },0);
//                    }
//                    $avg[$b_key] = $totalSum/(float)$count[$b_key];
                    //old2
                    $avg[$b_key]=0;
                    $whereMainId = implode(",", $mainList);
                    if (is_array($value)){
                        $whereUniqueKey = implode("','", $value);
                        $whereUniqueKey = " AND unique_key IN ('" .$whereUniqueKey."') ";
                    }else
                        $whereUniqueKey = " AND unique_key='$value'";
                    $avgSql = "SELECT AVG(sum1) as average, COUNT(*) as countAll FROM
                        (SELECT sum(answer_numeric) AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id) T1";
                    $avgResult = \DB::select($avgSql);
                    $avg[$b_key] = $avgResult[0]->average;
                    $count[$b_key] = $avgResult[0]->countAll;
//                    echo " collect count : " . $count[$b_key] . " == " . $avgResult[0]->countAll."</br>";
                    //old1
//                    $avg[$b_key]=0;
//                    foreach ($value as $eachValue){
//                        $avg[$b_key] += Answer::where('unique_key', $eachValue)
//                            ->whereIn('main_id', $mainList)
//                            ->select(\DB::raw(" AVG(answer_numeric) as average "))
//                            ->first()->average;
//                    }
////                                        echo "average : " . (microtime(true)-$averageTime) . " seconds</br>";
//                    $avg[$b_key] = $avg[$b_key]/count($value);
//                }else{
//                    $count[$b_key] = $answerObj->filter(function($item, $key) use ($value, $mainList, &$dupMainId){
//                        $condition = (!in_array($item->main_id, $dupMainId)) && $item->unique_key===$value
//                            && in_array($item->main_id, $mainList);
//                        if ($item->unique_key===$value)
//                            $dupMainId[] = $item->unique_key;
//
//                        return $condition;
//                    })->count();
//
//                    $avg[$b_key] = Answer::where('unique_key', $value)
//                        ->whereIn('main_id', $mainList)
//                        ->select(\DB::raw(" AVG(answer_numeric) as average "))
//                        ->first()->average;
//
////                    echo $value . " count: $count[$b_key], average: $avg[$b_key] \n";
//                }

                $p[$b_key] = $avg[$b_key]*$b_weight;
            }

//            $finaltime = microtime(true)-$testtime;
//            echo "After border loop" . ($finaltime/60) ."</br>";

//            $insertTime = microtime(true);
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

            $objPHPExcel->getActiveSheet()->setCellValue($key,  $answers[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, $answers[$key2]);
            $objPHPExcel->getActiveSheet()->setCellValue($key3, $answers[$key3]);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, $answers[$key4]);
            $objPHPExcel->getActiveSheet()->setCellValue($key5, (($answers[$key]+$answers[$key3])/2.0));
            $objPHPExcel->getActiveSheet()->setCellValue($key6, (($answers[$key2]+$answers[$key4])/2.0));

            $objPHPExcel->getActiveSheet()->getStyle($key)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key2)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key3)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key4)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key5)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key6)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
//            echo "INSERT TIME : " . (microtime(true)-$insertTime) . " seconds</br>";

//            echo " ==== unique_key time: " . ((microtime(true)-$rowTime)/60) . " minutes=== </br></br>";
        }

        return $objPHPExcel;
    }

    public static function usageElectric($uniqueKeyArr, $startCol, $startRow, $objPHPExcel,$mainObj, $sqlSum, $param,$ktoe,$gas=false, $ktoeIdx=false)
    {
        $parameterExcel = \PHPExcel_IOFactory::load(storage_path('excel/parameters.xlsx'));
        $parameterExcel->setActiveSheetIndex(2);
        $paramSheet = $parameterExcel->getActiveSheet();
        $population = [];
        $population[Main::NORTHERN_INNER] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_INNER])->getValue();
        $population[Main::NORTHERN_OUTER] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_OUTER])->getValue();

        $allUniqueKey = [];
        foreach ($uniqueKeyArr as $item){
            foreach ($item as $subItem){
                if (is_string($subItem))
                    $allUniqueKey[] = $subItem;
            }
        }
        $answerObj = Answer::whereIn('unique_key', $allUniqueKey)->get();

//        $sumAll = [];
//        $sumRow = 26;
//        $sumKey = [];

        $rows = [];
        $count = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey){
            $rows[$startCol.$rowNumber] = $uniqueKey;
            $rowNumber++;
        }

        $answers = [];
        foreach ($rows as $key=>$value){
            $sum = [];

            foreach (Main::$borderWeight as $b_key=>$b_weight){
                $mainList = $mainObj->filterMain($b_key);

                $finalSql = $sqlSum;
                foreach ($param as $pKey=>$pValue){
                    $finalSql = str_replace($pKey, $value[$pValue], $finalSql);
                }

                $dupMainId = [];
                $count[$b_key] = $answerObj->filter(function($item, $key) use ($value, $mainList, &$dupMainId){
                    $condition = (!in_array($item->main_id, $dupMainId)) && in_array($item->unique_key, $value)
                        && in_array($item->main_id, $mainList);
                    if (in_array($item->unique_key, $value))
                        $dupMainId[] = $item->main_id;

                    return $condition;
                })->count();

                $resultQuery2 = Answer::whereIn('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->groupBy('main_id')
                    ->select(\DB::raw($finalSql))
                    ->get();

                $sum[$b_key] = 0.0;
                foreach ($resultQuery2 as $row){
                    $sum[$b_key] += $row->sumAmount;
                }

//                echo $finalSql ." count: $count[$b_key] , sum: $sum[$b_key] </br>";
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
            $average[Main::INNER_GROUP_1] = $count[Main::INNER_GROUP_1]===0?0:($sum[Main::INNER_GROUP_1]/$count[Main::INNER_GROUP_1]);
            $average[Main::INNER_GROUP_2] = $count[Main::INNER_GROUP_2]===0?0:($sum[Main::INNER_GROUP_2]/$count[Main::INNER_GROUP_2]);
            $average[Main::OUTER_GROUP_1] = $count[Main::OUTER_GROUP_1]===0?0:($sum[Main::OUTER_GROUP_1]/$count[Main::OUTER_GROUP_1]);
            $average[Main::OUTER_GROUP_2] = $count[Main::OUTER_GROUP_2]===0?0:($sum[Main::OUTER_GROUP_2]/$count[Main::OUTER_GROUP_2]);

            $answers[$key] = ($average[Main::INNER_GROUP_1]*Main::$weight[Main::INNER_GROUP_1]
                    + $average[Main::INNER_GROUP_2]* Main::$weight[Main::INNER_GROUP_2]) * $population[Main::NORTHERN_INNER];
            $answers[$key] = $answers[$key]/1000000.0;
            $answers[$key3] = ($average[Main::OUTER_GROUP_1]*Main::$weight[Main::OUTER_GROUP_1]
                    + $average[Main::OUTER_GROUP_2]* Main::$weight[Main::OUTER_GROUP_2]) * $population[Main::NORTHERN_OUTER];
            $answers[$key3] = $answers[$key3]/1000000.0;

            //ktoe
            if ($gas){
                $answers[$key2] = $answers[$key]* 0.00042 * $ktoe;
                $answers[$key4] = $answers[$key3]* 0.00042 * $ktoe;
                $answers[$key5] = $answers[$key]* 0.00042 + $answers[$key3];
                $answers[$key6] = $answers[$key5]* 0.00042 * $ktoe;
            }elseif ($ktoeIdx!==false){
                $answers[$key2] = $answers[$key] * $value[$ktoeIdx];
                $answers[$key4] = $answers[$key3] * $value[$ktoeIdx];
                $answers[$key5] = $answers[$key] + $answers[$key3];
                $answers[$key6] = $answers[$key5] * $value[$ktoeIdx];
            }
            else{
                $answers[$key2] = $answers[$key] * $ktoe;
                $answers[$key4] = $answers[$key3] * $ktoe;
                $answers[$key5] = $answers[$key] + $answers[$key3];
                $answers[$key6] = $answers[$key5] * $ktoe;
            }

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

//            $sumKey[1] = preg_replace('/[0-9]+/', $sumRow, $key);
//            if (!isset($sumAll[$sumKey[1]]))
//                $sumAll[$sumKey[1]]= 0;
//            $sumAll[$sumKey[1]] += $answers[$key];
//
//            $sumKey[3] = preg_replace('/[0-9]+/', $sumRow, $key3);
//            if (!isset($sumAll[$sumKey[3]]))
//                $sumAll[$sumKey[3]]= 0;
//            $sumAll[$sumKey[3]] += $answers[$key3];
//
//            $sumKey[5] = preg_replace('/[0-9]+/', $sumRow, $key5);
//            if (!isset($sumAll[$sumKey[5]]))
//                $sumAll[$sumKey[5]]= 0;
//            $sumAll[$sumKey[5]] += $answers[$key5];
        }

        return $objPHPExcel;
    }

}
