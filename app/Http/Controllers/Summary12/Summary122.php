<?php

namespace App\Http\Controllers\Summary12;

use App\Answer;
use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary122 extends Controller
{

    public static function report122()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary12.xlsx';
        $inputSheet = '12.2';
        $outputFile = 'sum122.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/' . $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);


        //ตารางที่ 12.7 จำนวนและร้อยละของครัวเรือนที่มีการผลิตก๊าซชีวภาพจำแนกตามเขตปกครอง
        $table1 = [
            ['no_ra716' => 244],
            ['no_ra716' => 245],
        ];
        //ตารางที่ 12.8 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของครัวเรือนที่มีการใช้ก๊าซชีวภาพในแต่ละกิจกรรมจำแนกตามเขตปกครอง
        $table12_8 = [
            ['no_ra716_o245_ch718_o246_nu719'],
            ['no_ra716_o245_ch718_o247_nu719'],
        ];
        $multiplier12_8_1 = [
            ['no_ra716_o245_ch718_o246_nu720'],
            ['no_ra716_o245_ch718_o247_nu720'],
        ];
        $multiplier12_8_2 = [
            ['no_ra716_o245_ch718_o246_nu721'],
            ['no_ra716_o245_ch718_o247_nu721'],
        ];
        //ตารางที่ 12.9 จำนวนและร้อยละของครัวเรือนที่มีแนวโน้มจะใช้ก๊าซชีวภาพในอนาคตในแต่ละกิจกรรมจำแนกตามเขตปกครอง
        $table3 = [
            ['no_ra716_o245_ch718_o246_ra722' => 291],
            ['no_ra716_o245_ch718_o246_ra722' => 292],
            ['no_ra716_o245_ch718_o246_ra722' => 293],
            ['no_ra716_o245_ch718_o247_ra722' => 291],
            ['no_ra716_o245_ch718_o247_ra722' => 292],
            ['no_ra716_o245_ch718_o247_ra722' => 293],
            ['no_ra716_o245_ch718_o1_ra722' => 291],
            ['no_ra716_o245_ch718_o1_ra722' => 292],
            ['no_ra716_o245_ch718_o1_ra722' => 293],
        ];

        //ตารางที่ 12.10 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของจำนวนสัตว์ที่ใช้ในการผลิตก๊าซชีวภาพของครัวเรือนจำแนกตามเขตปกครอง
        $table4 = [
            'no_ra716_o245_ch723_o248_nu724',
            'no_ra716_o245_ch723_o249_nu725'
        ];
        //ตารางที่ 12.11 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของกิจกรรมการผลิตก๊าซชีวภาพของครัวเรือนจำแนกตามเขตปกครอง
        $table5 = [
            'no_ra716_o245_ti726_nu727',
            'no_ra716_o245_ti726_nu728'
        ];


        $isRadio = true;

        $startColumn = 'C';
        $startRow = 11;
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj, $isRadio);

        $startColumn = 'Q';
        $startRow = 11;
        $table12_8_final = [];
        $level = 0;
        foreach ($table12_8 as $row) {
            $finalSql = " ( 0 ";
            $level2 = 0;
            foreach ($row as $row2) {
                $sql = " SUM(IF(unique_key='param1',answer_numeric,0)) * SUM(IF(unique_key='param2',answer_numeric,0)) * SUM(IF(unique_key='param3',answer_numeric,0)) ";
                $temp = str_replace("param1", $row2, $sql);
                $temp = str_replace("param2", $multiplier12_8_1[$level][$level2], $temp);
                $temp = str_replace("param3", $multiplier12_8_2[$level][$level2], $temp);
                $finalSql .= " + " . $temp;
                $finalSql .= " ) ";
                $level2++;
            }
            $table12_8_final[] = $finalSql;
            $level++;
        }
        $objPHPExcel = Summary122::average($table12_8_final, $startColumn, $startRow, $objPHPExcel, $mainObj, null, false, true);

        $startColumn = 'AE';
        $startRow = 12;
        $objPHPExcel = Summary122::sum($table3,$startColumn, $startRow,$objPHPExcel,$mainObj,$isRadio);

        $startColumn = 'AS';
        $startRow = 11;
        $objPHPExcel = Summary::average($table4, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $startColumn = 'BH';
        $startRow = 11;
        $objPHPExcel = Summary::average($table5, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/' . $outputFile)));
    }

    public static function sum($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $isRadio = false)
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
        $rr = 0;
        foreach ($uniqueKeyArr as $uniqueKey) {
            if($rr == 3){$rowNumber++; $rr = 0;}
            $rows[$startCol . $rowNumber] = $uniqueKey;
            $rr++;
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

                    //echo $w[$i]." / ".$count[$i]." / ". $s[$i]." / ".$p[$i]."<br><br>";

                }
            } else {
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

                    $p[$i] = $w[$i] * ((float)$count[$i] / $s[$i]);

                }
            }
            $percentage1 = $p[1] + $p[2];
            $answers[$key] = $percentage1 * $S[1];
            $col = $startCol;
            $col++;
            $key2 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key2] = $percentage1 * 100;
            $col++;
            $key3 = preg_replace('/[A-Z]+/', $col, $key);
            $percentage2 = $p[3] + $p[4];

            $answers[$key3] = $percentage2 * $S[3];
            $col++;
            $key4 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key4] = $percentage2 * 100;
            //รวม
            $col++;
            $key5 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key6 = preg_replace('/[A-Z]+/', $col, $key);
            $answers[$key6] = ($answers[$key2] * Main::$weight[Main::NORTHERN_INNER] + $answers[$key4] * Main::$weight[Main::NORTHERN_OUTER]) / 100;
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

    public static function average($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $multiplier, $year = false, $customSql = false)
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

            foreach (Main::$provinceWeight as $p_key => $p_weight) {
                $mainList = $mainObj->filterMain($p_key);

                $avg[$p_key] = 0;
                $whereMainId = implode(", ", $mainList);

                if ($customSql) {
                    $avgSql = "SELECT SUM(sum1)/".Main::$provinceSample[$p_key]." as average, COUNT(*) as countAll FROM
                    (SELECT $value AS sum1 FROM answers
                    WHERE main_id IN ($whereMainId) "
                        . " GROUP BY main_id) T1";
                } else {
                    if (is_array($value)) {
                        $whereUniqueKey = implode("','", $value);
                        $tempUniqueKey = $whereUniqueKey;
                        $whereUniqueKey = " AND unique_key IN ('" . $whereUniqueKey . "','$multiplier[$level1Counter]') ";
                        $sumSQL = " SUM(IF(unique_key IN ('$tempUniqueKey'),answer_numeric,0)) * 
                    SUM(IF(unique_key='$multiplier[$level1Counter]',answer_numeric,0)) ";
                    } else {
                        $whereUniqueKey = " AND (unique_key='$value' OR unique_key='$multiplier[$level1Counter]') ";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) * 
                    SUM(IF(unique_key='$multiplier[$level1Counter]',answer_numeric,0)) ";
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
                //old2
                if ($customSql) {
                    $avgSql = "SELECT SUM(sum1)/".Main::$sample[$b_key]." as average, COUNT(*) as countAll FROM
                    (SELECT $value AS sum1 FROM answers
                    WHERE main_id IN ($whereMainId) "
                        . " GROUP BY main_id) T1";
                } else {
                    if (is_array($value)) {
                        $whereUniqueKey = implode("','", $value);
                        $tempUniqueKey = $whereUniqueKey;
                        $whereUniqueKey = " AND unique_key IN ('" . $whereUniqueKey . "','$multiplier[$level1Counter]') ";
                        $sumSQL = " SUM(IF(unique_key IN ('$tempUniqueKey'),answer_numeric,0)) * 
                    SUM(IF(unique_key='$multiplier[$level1Counter]',answer_numeric,0)) ";
                    } else {
                        $whereUniqueKey = " AND (unique_key='$value' OR unique_key='$multiplier[$level1Counter]') ";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) * 
                    SUM(IF(unique_key='$multiplier[$level1Counter]',answer_numeric,0)) ";
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
            $objPHPExcel->getActiveSheet()->setCellValue($key5, (($answers[$key] + $answers[$key3]) / 2.0));
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
}
