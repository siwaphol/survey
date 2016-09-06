<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EchoSummary extends Model
{
    public static function sum($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $isRadio = false, $isCustomHaving = false, $havingUniqueKey=null)
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

        if (!$isRadio)
            $answerObj = Answer::whereIn('unique_key', $uniqueKeyArr)->get();

        $whereIn = [];
        $answers = [];
        foreach ($rows as $key => $value) {

            if (empty($value))
                continue;

            $whereIn[] = $value;
            $p = [];
            $count = [];

            echo "หา จำนวน ของแต่ละกลุ่มจังหวัด </br>";

            if ($isRadio) {
                for ($i = 1; $i <= 4; $i++) {
                    $mainList = $mainObj->filterMain($i);
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
                    $count[$i] = \DB::select($sql)[0]->count;
                    $p[$i] = $w[$i] * ((float)$count[$i] / $s[$i]);
                }
            }
            elseif ($isCustomHaving){
                for ($i = 1; $i <= 4; $i++) {
                    $mainList = $mainObj->filterMain($i);
                    $whereCondition = $value;

                    $whereInMainId = implode(",", $mainList);
                    $sql = "SELECT COUNT(*) as count FROM (SELECT main_id FROM answers WHERE main_id IN ($whereInMainId) GROUP BY main_id $whereCondition ) t1";
                    $count[$i] = \DB::select($sql)[0]->count;
                    $p[$i] = $w[$i] * ((float)$count[$i] / $s[$i]);
                }
            }
            else {
                for ($i = 1; $i <= 4; $i++) {
                    $mainList = $mainObj->filterMain($i);

                    $dupMainId = [];
                    $count[$i] = $answerObj->filter(function ($item, $key) use ($mainList, $value, &$dupMainId) {
                        $condition = (!in_array($item->main_id, $dupMainId)) && $item->unique_key === $value
                            && in_array($item->main_id, $mainList);
                        if ($item->unique_key === $value)
                            $dupMainId[] = $item->unique_key;

                        return $condition;
                    })->count();

                    echo Main::$borderWeightText[$i] . " นับรวมได้ " . $count[$i] . "</br>";

                    $p[$i] = $w[$i] * ((float)$count[$i] / $s[$i]);
                    echo " ร้อยละของ " . Main::$borderWeightText[$i] . " = weight x (จำนวนประชากรกลุ่ม/ จำนวนประชากรในเขตหรือนอกเขต)</br>";
                    echo $p[$i] . " = ".$w[$i]." x ({$count[$i]}/ {$s[$i]})</br>";
                }
            }
            $percentage1 = $p[1] + $p[2];
            echo " ร้อยละในเขตได้ = (" . $p[1] . " + " . $p[2] . ")x100 = ".($percentage1*100)."</br>";
            $answers[$key] = $percentage1*$S[1];
            echo " จำนวนในเขต = ร้อยละในเขต x จำนวนประชากรทั้งหมดในเขต</br>";
            echo " {$answers[$key]} = {$percentage1} x {$S[1]}</br>";
            $col = $startCol;
            $col++;
            $key2 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key2] = $percentage1*100;
            $col++;
            $key3 = preg_replace('/[A-Z]+/', $col, $key);
            
            $percentage2 = $p[3] + $p[4];
            echo " ร้อยละนอกเขตได้ = (" . $p[3] . " + " . $p[4] . ")x100 = ".($percentage2*100)."</br>";
            $answers[$key3] = $percentage2*$S[3];
            echo " จำนวนนอกเขต = ร้อยละนอกเขต x จำนวนประชากรทั้งหมดนอกเขต</br>";
            echo " {$answers[$key3]} = {$percentage2} x {$S[3]}</br>";
            $col++;
            $key4 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key4] = $percentage2*100;
            //รวม
            $col++;
            $key5 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key6 = preg_replace('/[A-Z]+/', $col, $key);
            echo " ร้อยละรวม = ร้อยละในเขต x weightในเขต + ร้อยละนอกเขต x weightนอกเขต</br>";
            echo $answers[$key6] ." = " . $answers[$key2] ." x ".Main::$weight[Main::NORTHERN_INNER] . " + " . $answers[$key4] ." x ". Main::$weight[Main::NORTHERN_OUTER]."</br>";
            $answers[$key6] = ($answers[$key2]*Main::$weight[Main::NORTHERN_INNER] + $answers[$key4]*Main::$weight[Main::NORTHERN_OUTER])/100;
            $answers[$key5] = ($answers[$key6] ) * (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN])->getValue();
            echo " ประชากรรวม " . $answers[$key5] . " = " . $answers[$key6] . " x " . $paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN])->getValue(). "</br>";
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
            echo "==============</br></br>";
        }

        return $objPHPExcel;
    }

    public static function averageWithDetails($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $isRadio = false, $radioArr = [], $detailsColumn = [])
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

            echo $detailsColumn[$level1Counter] . "</br>";
            echo "หา average ของแต่ละจังหวัด </br>";

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
                    $avgSql = "SELECT AVG(sum1) as average, COUNT(*) as countAll FROM
                        (SELECT $finalSql AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id) T1 WHERE sum1>0";

                } else {
                    if (is_array($value)) {
                        $whereUniqueKey = implode("','", $value);
                        $tempUniqueKey = $whereUniqueKey;
                        $whereUniqueKey = " AND unique_key IN ('" .$whereUniqueKey."') ";
                        $sumSQL = " SUM(IF(unique_key IN ('$tempUniqueKey'),answer_numeric,0)) ";
                    }else{
                        $whereUniqueKey = " AND unique_key='$value'";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) ";
                    }

                    $avgSql = "SELECT AVG(sum1) as average, COUNT(*) as countAll FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) "
                        . " GROUP BY main_id) T1";
                }
                $avgResult = \DB::select($avgSql);
                $avg[$p_key] = $avgResult[0]->average;
                $count[$p_key] = $avgResult[0]->countAll;

                echo Main::$provinceWeightText[$p_key] . " เลือก " . $count[$p_key] . " ชุด " . " เฉลี่ย " . $avg[$p_key] . " </br>";
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
                    $avgSql = "SELECT AVG(sum1) as average, COUNT(*) as countAll FROM
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
                    }else{
                        $whereUniqueKey = " AND unique_key='$value'";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) ";
                    }

                    $avgSql = "SELECT AVG(sum1) as average, COUNT(*) as countAll FROM
                        (SELECT $sumSQL AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) "
                        . " GROUP BY main_id) T1";
                }
                $avgResult = \DB::select($avgSql);
                $avg[$b_key] = $avgResult[0]->average;
                $count[$b_key] = $avgResult[0]->countAll;

                echo Main::$borderWeightText[$b_key] . " เลือก " . $count[$b_key] . " ชุด " . " เฉลี่ย " . $avg[$b_key] . " </br>";
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
            $weight1 = Main::$borderWeight[Main::INNER_GROUP_1];
            $weight2 = Main::$borderWeight[Main::INNER_GROUP_2];
            echo " ค่าเฉลี่ยในเขต $answers[$key] = {$avg[Main::INNER_GROUP_1]} x {$weight1} + {$avg[Main::INNER_GROUP_2]} x {$weight2} </br>";

            if ($count[Main::INNER_GROUP_1] - 1 === 0)
                $A[Main::INNER_GROUP_1] = 0;
            else
                $A[Main::INNER_GROUP_1] = (1.0 / ($count[Main::INNER_GROUP_1] - 1))
                    * (
                        pow(($avg[Main::CHIANGMAI_INNER] - $avg[Main::INNER_GROUP_1]), 2)
                        + pow(($avg[Main::UTARADIT_INNER] - $avg[Main::INNER_GROUP_1]), 2)
                    );
            echo "เริ่มหาค่า S.E. ในเขต</br>";
            echo "หาค่า A ของในเขตกลุ่ม 1 = ( 1 / จำนวนครัวเรือนที่เลือก - 1) x ((จำนวนเฉลี่ยเชียงใหม่ในเขต - ค่าเฉลี่ยในเขตกลุ่มจังหวัด 1)^2 + (จำนวนเฉลี่ยอุตรดิตในเขต - ค่าเฉลี่ยในเขตกลุ่มจังหวัด 1)^2) </br>";
            $aTemp = $A[Main::INNER_GROUP_1];
            echo " {$aTemp} = ( 1 / {$count[Main::INNER_GROUP_1]} -1 ) x (({$avg[Main::CHIANGMAI_INNER]}-{$avg[Main::INNER_GROUP_1]})^2 + ({$avg[Main::UTARADIT_INNER]}-{$avg[Main::INNER_GROUP_1]})^2) </br>";

            if ($count[Main::INNER_GROUP_2] - 1 === 0)
                $A[Main::INNER_GROUP_2] = 0;
            else
                $A[Main::INNER_GROUP_2] = (1.0 / ($count[Main::INNER_GROUP_2] - 1))
                    * (
                        pow(($avg[Main::NAN_INNER] - $avg[Main::INNER_GROUP_2]), 2)
                        + pow(($avg[Main::PITSANULOK_INNER] - $avg[Main::INNER_GROUP_2]), 2)
                        + pow(($avg[Main::PETCHABUL_INNER] - $avg[Main::INNER_GROUP_2]), 2)
                    );
            echo "หาค่า A ของในเขตกลุ่ม 2 = ( 1 / จำนวนครัวเรือนที่เลือก - 1) x ((จำนวนเฉลี่ยน่านในเขต - ค่าเฉลี่ยในเขตกลุ่มจังหวัด 2)^2 + (จำนวนเฉลี่ยพิษณุโลกในเขต - ค่าเฉลี่ยในเขตกลุ่มจังหวัด 2)^2 + (จำนวนเฉลี่ยเพชรบูรณ์ในเขต - ค่าเฉลี่ยในเขตกลุ่มจังหวัด 2)^2) </br>";
            $aTemp = $A[Main::INNER_GROUP_2];
            echo " {$aTemp} = ( 1 / {$count[Main::INNER_GROUP_2]} -1 ) x (({$avg[Main::NAN_INNER]}-{$avg[Main::INNER_GROUP_2]})^2 + ({$avg[Main::PITSANULOK_INNER]}-{$avg[Main::INNER_GROUP_2]})^2 + ({$avg[Main::PETCHABUL_INNER]}-{$avg[Main::INNER_GROUP_2]})^2)";

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
            echo "S.E ในเขต</br>";
            echo " S.E. ในเขต  =  sqr( (weight ในเขตกลุ่ม 1 x (1 - จำนวนที่เลือกในเขตกลุ่ม 1 / จำนวนที่เลือกในเขต) x (ค่า A ในเขตกลุ่มจังหวัด 1 / จำนวนที่เลือกของกลุ่มจังหวัด 1))  + (weight ในเขตกลุ่ม 2 x (1 - จำนวนที่เลือกในเขตกลุ่ม 2 / จำนวนที่เลือกในเขต) x (ค่า A ในเขตกลุ่มจังหวัด 2 / จำนวนที่เลือกของกลุ่มจังหวัด 2)) ) </br>";
            $temp2 = Main::$weight[Main::INNER_GROUP_1];
            echo " ".$answers[$key2]."  =  sqr( ($temp2 x (1 - {$count[Main::INNER_GROUP_1]} / ".($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2])
                .") x (".$A[Main::INNER_GROUP_1]." / ".$count[Main::INNER_GROUP_1]."))  + (".Main::$weight[Main::INNER_GROUP_2]
                ." x (1 - ".$count[Main::INNER_GROUP_2]." / ".($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2])
                .") x (".$A[Main::INNER_GROUP_2]." / ".$count[Main::INNER_GROUP_2].")) ) </br>";

            // นอกเขต
            $answers[$key3] = $p[Main::OUTER_GROUP_1] + $p[Main::OUTER_GROUP_2];
            $weight1 = Main::$borderWeight[Main::OUTER_GROUP_1];
            $weight2 = Main::$borderWeight[Main::OUTER_GROUP_2];
            echo " ค่าเฉลี่ยนอกเขต $answers[$key3] = {$avg[Main::OUTER_GROUP_1]} x {$weight1} + {$avg[Main::OUTER_GROUP_2]} x {$weight2} </br>";

            if ($count[Main::OUTER_GROUP_1] - 1 === 0)
                $A[Main::OUTER_GROUP_1] = 0;
            else
                $A[Main::OUTER_GROUP_1] = (1.0 / ($count[Main::OUTER_GROUP_1] - 1))
                    * (
                        pow(($avg[Main::CHIANGMAI_OUTER] - $avg[Main::OUTER_GROUP_1]), 2)
                        + pow(($avg[Main::UTARADIT_OUTER] - $avg[Main::OUTER_GROUP_1]), 2)
                    );
            echo "หาค่า S.E. นอกเขต</br>";
            echo "หาค่า A ของนอกเขตกลุ่ม 1 = ( 1 / จำนวนครัวเรือนที่เลือก - 1) x ((จำนวนเฉลี่ยเชียงใหม่นอกเขต - ค่าเฉลี่ยนอกเขตกลุ่มจังหวัด 1)^2 + (จำนวนเฉลี่ยอุตรดิตนอกเขต - ค่าเฉลี่ยนอกเขตกลุ่มจังหวัด 1)^2) </br>";
            $aTemp = $A[Main::OUTER_GROUP_1];
            echo " {$aTemp} = ( 1 / {$count[Main::OUTER_GROUP_1]} -1 ) x (({$avg[Main::CHIANGMAI_OUTER]}-{$avg[Main::OUTER_GROUP_1]})^2 + ({$avg[Main::UTARADIT_OUTER]}-{$avg[Main::OUTER_GROUP_1]})^2) </br>";

            if ($count[Main::OUTER_GROUP_2] - 1 === 0)
                $A[Main::OUTER_GROUP_2] = 0;
            else
                $A[Main::OUTER_GROUP_2] = (1.0 / ($count[Main::OUTER_GROUP_2] - 1))
                    * (
                        pow(($avg[Main::NAN_OUTER] - $avg[Main::OUTER_GROUP_2]), 2)
                        + pow(($avg[Main::PITSANULOK_OUTER] - $avg[Main::OUTER_GROUP_2]), 2)
                        + pow(($avg[Main::PETCHABUL_OUTER] - $avg[Main::OUTER_GROUP_2]), 2)
                    );
            echo "หาค่า A ของนอกเขตกลุ่ม 2 = ( 1 / จำนวนครัวเรือนที่เลือก - 1) x ((จำนวนเฉลี่ยน่านนอกเขต - ค่าเฉลี่ยนอกเขตกลุ่มจังหวัด 2)^2 + (จำนวนเฉลี่ยพิษณุโลกนอกเขต - ค่าเฉลี่ยนอกเขตกลุ่มจังหวัด 2)^2 + (จำนวนเฉลี่ยเพชรบูรณ์นอกเขต - ค่าเฉลี่ยนอกเขตกลุ่มจังหวัด 2)^2) </br>";
            $aTemp = $A[Main::OUTER_GROUP_2];
            echo " {$aTemp} = ( 1 / {$count[Main::OUTER_GROUP_2]} -1 ) x (({$avg[Main::NAN_OUTER]}-{$avg[Main::OUTER_GROUP_2]})^2 + ({$avg[Main::PITSANULOK_OUTER]}-{$avg[Main::OUTER_GROUP_2]})^2 + ({$avg[Main::PETCHABUL_OUTER]}-{$avg[Main::OUTER_GROUP_2]})^2)";


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
            echo "S.E นอกเขต</br>";
            echo " S.E. นอกเขต  =  sqr( (weight นอกเขตกลุ่ม 1 x (1 - จำนวนที่เลือกนอกเขตกลุ่ม 1 / จำนวนที่เลือกนอกเขต) x (ค่า A นอกเขตกลุ่มจังหวัด 1 / จำนวนที่เลือกของกลุ่มจังหวัด 1))  + (weight นอกเขตกลุ่ม 2 x (1 - จำนวนที่เลือกนอกเขตกลุ่ม 2 / จำนวนที่เลือกนอกเขต) x (ค่า A นอกเขตกลุ่มจังหวัด 2 / จำนวนที่เลือกของกลุ่มจังหวัด 2)) ) </br>";
            $temp2 = Main::$weight[Main::OUTER_GROUP_1];
            echo " ".$answers[$key4]."  =  sqr( ($temp2 x (1 - {$count[Main::OUTER_GROUP_1]} / ".($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2])
                .") x (".$A[Main::OUTER_GROUP_1]." / ".$count[Main::OUTER_GROUP_1]."))  + (".Main::$weight[Main::OUTER_GROUP_2]
                ." x (1 - ".$count[Main::OUTER_GROUP_2]." / ".($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2])
                .") x (".$A[Main::OUTER_GROUP_2]." / ".$count[Main::OUTER_GROUP_2].")) ) </br>";

            $objPHPExcel->getActiveSheet()->setCellValue($key, $answers[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, $answers[$key2]);
            $objPHPExcel->getActiveSheet()->setCellValue($key3, $answers[$key3]);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, $answers[$key4]);
            $objPHPExcel->getActiveSheet()->setCellValue($key5, (($answers[$key] + $answers[$key3]) / 2.0));
            $objPHPExcel->getActiveSheet()->setCellValue($key6, (($answers[$key2] + $answers[$key4]) / 2.0));

            $objPHPExcel->getActiveSheet()->getStyle($key)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key2)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key3)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key4)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key5)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key6)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);

            $level1Counter++;
            echo "======= </br></br>";
        }

        return $objPHPExcel;
    }

    public static function usageElectric($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $sqlSum, $param, $ktoe, $gas = false, $ktoeIdx = false, $isRadio = false, $sqlDetails)
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
        $count = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey) {
            $rows[$startCol . $rowNumber] = $uniqueKey;
            $rowNumber++;
        }

        $answers = [];
        foreach ($rows as $key => $value) {
            $sum = [];

            echo " หาปริมาณใช้งานของแต่ละกลุ่มจังหวัด </br>";
            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $finalSql = $sqlSum;
                foreach ($param as $pKey => $pValue) {
                    $finalSql = str_replace($pKey, $value[$pValue], $finalSql);
                }

                $whereMainId = implode(",", $mainList);
                if (is_array($value)) {
                    $whereUniqueKey = implode("','", $value);
                    $whereUniqueKey = " AND unique_key IN ('" . $whereUniqueKey . "') ";
                } else
                    $whereUniqueKey = " AND unique_key='$value'";
                $avgSql = "SELECT COUNT(*) as countAll FROM
                        (SELECT sum(answer_numeric) AS sum1 FROM answers
                        WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                    . " GROUP BY main_id) T1";
                $avgResult = \DB::select($avgSql);
                $count[$b_key] = $avgResult[0]->countAll;

                echo Main::$borderWeightText[$b_key] . " เลือกทั้งหมด " . $count[$b_key] . " ครัวเรือน</br>";

                $resultQuery2 = Answer::whereIn('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->groupBy('main_id')
                    ->select(\DB::raw($finalSql))
                    ->get();
                echo $sqlDetails . "</br>";
//                foreach ($mainList as $mainId){
//                    echo " ชุดที่ " . $mainId. " ได้ " . ();
//                }

                $sum[$b_key] = 0.0;
                foreach ($resultQuery2 as $row) {
                    $sum[$b_key] += $row->sumAmount;
                }
                echo Main::$borderWeightText[$b_key] . " ใช้ทั้งหมด " . $sum[$b_key]." กิโลวัตต่อปี</br>";
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
            $average[Main::INNER_GROUP_1] = $count[Main::INNER_GROUP_1] === 0 ? 0 : ($sum[Main::INNER_GROUP_1] / $count[Main::INNER_GROUP_1]);
            echo " เฉลี่ยใช้งาน " .
            $average[Main::INNER_GROUP_2] = $count[Main::INNER_GROUP_2] === 0 ? 0 : ($sum[Main::INNER_GROUP_2] / $count[Main::INNER_GROUP_2]);
            $average[Main::OUTER_GROUP_1] = $count[Main::OUTER_GROUP_1] === 0 ? 0 : ($sum[Main::OUTER_GROUP_1] / $count[Main::OUTER_GROUP_1]);
            $average[Main::OUTER_GROUP_2] = $count[Main::OUTER_GROUP_2] === 0 ? 0 : ($sum[Main::OUTER_GROUP_2] / $count[Main::OUTER_GROUP_2]);

            $answers[$key] = ($average[Main::INNER_GROUP_1] * Main::$weight[Main::INNER_GROUP_1]
                    + $average[Main::INNER_GROUP_2] * Main::$weight[Main::INNER_GROUP_2]) * $population[Main::NORTHERN_INNER];
            echo " จำนวนใช้งานในเขต  = (ค่าเฉลี่ยในเขตกลุ่ม1 x weight + ค่าเฉลี่ยในเขตกลุ่ม2 x weight) x จำนวนประชากรในเขตทั้งหมด / 1ล้าน</br>";
            $answers[$key] = $answers[$key] / 1000000.0;
            echo $answers[$key] . " = " . $average[Main::INNER_GROUP_1] . "x" . Main::$weight[Main::INNER_GROUP_1]
                . " + " . $average[Main::INNER_GROUP_2] ."x". Main::$weight[Main::INNER_GROUP_2]
                . " x " .$population[Main::NORTHERN_INNER] . "/1000000</br>";
            $answers[$key3] = ($average[Main::OUTER_GROUP_1] * Main::$weight[Main::OUTER_GROUP_1]
                    + $average[Main::OUTER_GROUP_2] * Main::$weight[Main::OUTER_GROUP_2]) * $population[Main::NORTHERN_OUTER];
            $answers[$key3] = $answers[$key3] / 1000000.0;
            echo " จำนวนใช้งานนอกเขต  = (ค่าเฉลี่ยนอกเขตกลุ่ม1 x weight + ค่าเฉลี่ยนอกเขตกลุ่ม2 x weight) x จำนวนประชากรนอกเขตทั้งหมด / 1ล้าน</br>";
            echo $answers[$key] . " = " . $average[Main::OUTER_GROUP_1] . "x" . Main::$weight[Main::OUTER_GROUP_1]
                . " + " . $average[Main::OUTER_GROUP_2] ."x". Main::$weight[Main::OUTER_GROUP_2]
                . " x " .$population[Main::NORTHERN_OUTER] . "/1000000</br>";

            //ktoe
            if ($gas) {
                $answers[$key2] = $answers[$key] * 0.00042 * $ktoe;
                $answers[$key4] = $answers[$key3] * 0.00042 * $ktoe;
                $answers[$key5] = $answers[$key] * 0.00042 + $answers[$key3];
                $answers[$key6] = $answers[$key5] * 0.00042 * $ktoe;
            } elseif ($ktoeIdx !== false) {
                $answers[$key2] = $answers[$key] * $value[$ktoeIdx];
                $answers[$key4] = $answers[$key3] * $value[$ktoeIdx];
                $answers[$key5] = $answers[$key] + $answers[$key3];
                $answers[$key6] = $answers[$key5] * $value[$ktoeIdx];
            } else {
                $answers[$key2] = $answers[$key] * $ktoe;
                echo " ktoe ในเขต " . $answers[$key2] . " = " . $answers[$key] . "x".$ktoe."</br>";
                $answers[$key4] = $answers[$key3] * $ktoe;
                echo " ktoe นอกเขต " . $answers[$key4] . " = " . $answers[$key3] . "x".$ktoe."</br>";
                $answers[$key5] = $answers[$key] + $answers[$key3];
                echo " ใช้งานรวม " . $answers[$key5] ." = " . $answers[$key] ."+". $answers[$key3]."</br>";
                $answers[$key6] = $answers[$key5] * $ktoe;
                echo " ktoeรวม ".$answers[$key6] . " = ".$answers[$key5] ."x". $ktoe."</br>";
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
            echo "=========</br></br>";
        }

        return $objPHPExcel;
    }

    public static function report911Test()
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
            'no_ch1023_o329_ch101_o71',
            'no_ch1023_o330_ch112_o71'
        ];
        $startColumn = 'E';
//        $objPHPExcel = EchoSummary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1023_o329_ch101_o71_nu103',
            'no_ch1023_o330_ch112_o71_nu114'
        ];
        $detailsColumns = [
            'หลอดไฟ (ในบ้าน) หลอดไส้',
            'หลอดไฟ (ในบ้าน) หลอดฟลูออเรสเซนต์ ชนิดกลม',
            'หลอดไฟ (ในบ้าน) หลอดฟลูออเรสเซนต์ ชนิดตรง ขนาดยาว',
            'หลอดไฟ (ในบ้าน) หลอดฟลูออเรสเซนต์ ชนิดตรง ขนาดสั้น',
            'หลอดไฟ (ในบ้าน) หลอดคอมแพคฟลูออเรสเซนต์',
            'หลอดไฟ (ในบ้าน) หลอดแอลอีดี',
            'หลอดไฟ (นอกบ้าน) หลอดไส้',
            'หลอดไฟ (นอกบ้าน) หลอดฟลูออเรสเซนต์ ชนิดกลม',
            'หลอดไฟ (นอกบ้าน) หลอดฟลูออเรสเซนต์ ชนิดตรง ขนาดยาว',
            'หลอดไฟ (นอกบ้าน) หลอดฟลูออเรสเซนต์ ชนิดตรง ขนาดสั้น',
            'หลอดไฟ (นอกบ้าน) หลอดคอมแพคฟลูออเรสเซนต์',
            'หลอดไฟ (นอกบ้าน) หลอดแอลอีดี',
        ];
        $startColumn = 'U';
        //withDetails
//        Summary::averageWithDetails($table2, $startColumn, $startRow, $objPHPExcel, $mainObj, false,[], $detailsColumns);
//        dd();
//        $objPHPExcel = EchoSummary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table3 = [
            ['no_ch1023_o329_ch101_o71_nu104','no_ch1023_o329_ch101_o71_nu105','no_ch1023_o329_ch101_o71_nu103',0.010],
            ['no_ch1023_o330_ch112_o71_nu115','no_ch1023_o330_ch112_o71_nu116','no_ch1023_o330_ch112_o71_nu114',0.010]
        ];
        $startColumn = 'AL';
        $ktoe = 0.08521;
        $week = Parameter::WEEK_PER_YEAR;
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0))* sum(if(unique_key='param2', answer_numeric,0))* {$week})* (param4) * sum(if(unique_key='param3',1,0)) as sumAmount ";

        $sumAmountSQL2 = " (sum(IF(unique_key='param1',answer_numeric,0))* sum(if(unique_key='param2', answer_numeric,0))* {$week})* (param4) * sum(if(unique_key='param3',1,0)) as sumAmount,main_id ";
        $sumAmountSQLDesc = " อัตราการใช้ชั่วโมงต่อวัน x อัตราการใช้วันต่อสัปดาห์ x {$week}) x กำลังไฟฟ้าหน่วน kWh x จำนวนหลอด </br>";

        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2,
            'param4'=>3
        ];
        $objPHPExcel = EchoSummary::usageElectric($table3, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL,$params,$ktoe,false,false,false,$sumAmountSQLDesc);
        dd();
        $table4 = [
            'no_ch1023_o329_ch101_o71_nu106',
            'no_ch1023_o330_ch112_o71_nu117'
        ];
        $startColumn = 'BB';
        $objPHPExcel = EchoSummary::average($table4, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

}
