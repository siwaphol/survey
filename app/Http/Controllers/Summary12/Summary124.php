<?php

namespace App\Http\Controllers\Summary12;

use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary124 extends Controller
{

    public static function report124()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary12.xlsx';
        $inputSheet = '12.4';
        $outputFile = 'sum124.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/' . $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        //ตารางที่ 12.15 จำนวนและร้อยละของครัวเรือนที่มีการผลิตไฟฟ้าด้วยเซลล์แสงอาทิตย์จำแนกตามเขตปกครอง
        $table1 = [
            ['no_ra735' => 82],
            ['no_ra735' => 81],
        ];

        //ตารางที่ 12.16 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของกิจกรรมการใช้พลังงานแสงอาทิตย์ในการผลิต
        $table2 = [
            'no_ra735_o81_ch736_o254_ti737_nu738',
            'no_ra735_o81_ch736_o254_ti737_nu739',
            'no_ra735_o81_ch736_o254_ti737_nu740',
            'no_ra735_o81_ch736_o254_ti737_nu742',
            'no_ra735_o81_ch736_o254_nu744',
            'no_ra735_o81_ch736_o254_ti745_nu746',
        ];

        //ตารางที่ 12.17 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของกิจกรรมการใช้พลังงานแสงอาทิตย์ในการอบแห้ง
        $table3 = [
            'no_ra735_o81_ch736_o255_ch749_o260_nu750',
            'no_ra735_o81_ch736_o255_ch749_o260_nu751',
            'no_ra735_o81_ch736_o255_ch749_o260_nu752',
            'no_ra735_o81_ch736_o255_ch749_o261_nu753',
            'no_ra735_o81_ch736_o255_ch749_o261_nu754',
            'no_ra735_o81_ch736_o255_ch749_o261_nu755',
            'no_ra735_o81_ch736_o255_ch749_o262_nu756',
            'no_ra735_o81_ch736_o255_ch749_o262_nu757',
            'no_ra735_o81_ch736_o255_ch749_o262_nu758',
        ];




        $isRadio = true;
        $startColumn = 'C';
        $startRow = 11;
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj, $isRadio);

        $startColumn = 'R';
        $startRow = 11;
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $startColumn = 'AG';
        $startRow = 12;
        $objPHPExcel = Summary124::average($table3, $startColumn, $startRow, $objPHPExcel, $mainObj);


        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/' . $outputFile)));
    }

    public static function average($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $isRadio = false, $radioArr = [], $year=false)
    {
        $rows = [];
        $rowNumber = $startRow;

        $rr = 0;
        foreach ($uniqueKeyArr as $uniqueKey) {
            if($rr == 3){$rowNumber++; $rr = 0;}
            $rows[$startCol . $rowNumber] = $uniqueKey;
            $rr++;
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
                        if ($year)
                            $sumSQL .= " * 12 ";
                    }else{
                        $whereUniqueKey = " AND unique_key='$value'";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) ";
                        if ($year)
                            $sumSQL .= " * 12 ";
                    }

                    $avgSql = "SELECT AVG(sum1) as average, COUNT(*) as countAll FROM
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
                        if ($year)
                            $sumSQL .= " * 12 ";
                    }else{
                        $whereUniqueKey = " AND unique_key='$value'";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) ";
                        if ($year)
                            $sumSQL .= " * 12 ";
                    }

                    $avgSql = "SELECT AVG(sum1) as average, COUNT(*) as countAll FROM
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
