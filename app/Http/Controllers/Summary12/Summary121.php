<?php

namespace App\Http\Controllers\Summary12;

use App\Main;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary121 extends Controller
{
    public static function report121()
    {
        set_time_limit(1200);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary12.xlsx';
        $inputSheet = '12.1';
        $outputFile = 'sum121.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/' . $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        //ตารางที่ 12.1 จำนวนและร้อยละของครัวเรือนที่มีการเผาถ่านจำแนกตามเขตปกครอง
        $table12_1 = [
            ['no_ra700' => 82],
            ['no_ra700' => 81],
        ];
        //ตารางที่ 12.2 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของการใช้วัตถุดิบในการผลิตถ่านของครัวเรือนจำแนกตามเขตปกครอง
        $table12_2 = [
            'no_ra700_o81_ch701_o237_nu702',
            'no_ra700_o81_ch701_o238_nu703',
            'no_ra700_o81_ch701_o239_nu704',
            'no_ra700_o81_ch701_o1_nu705'
        ];
        //ตารางที่ 12.3 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของจำนวนครั้งในการเผาถ่าน ปริมาณถ่านที่ได้และการจำหน่ายของครัวเรือนจำแนกตามเขตปกครอง
        $table12_3 = [
            'no_ra700_o81_nu706',
            'no_ra700_o81_nu707',
            'no_ra700_o81_nu708'
        ];

        //ตารางที่ 12.4 จำนวนและร้อยละของประเภทเตาที่ใช้ในการผลิตถ่าจำแนกตามเขตปกครอง
        $table12_4 = [
            'no_ra700_o81_ch710_o240',
            'no_ra700_o81_ch710_o241',
            'no_ra700_o81_ch710_o242',
            'no_ra700_o81_ch710_o243'
        ];
        //ตารางที่ 12.5 ปริมาณเชือวัสดุที่ใช้ในการผลิตถ่านต่อครั้งของครัวเรือนจำแนกตามเขตปกครอง
        $table12_5 = [
            'no_ra700_o81_ch711_o266_nu712',
            'no_ra700_o81_ch711_o268_nu713',
            'no_ra700_o81_ch711_o286_nu714',
            'no_ra700_o81_ch711_o1_nu715'
        ];
        //ตารางที่ 12.6 ค่าเฉลี่ยและค่าความคลาดเคลื่อนมาตรฐานของราคาจำหน่ายถ่านของครัวเรือนจำแนกตามเขตปกครอง
        $table12_6 =[
            " IF(SUM(IF(unique_key='no_ra700_o81_ch701_o237',1,0))>=1,1,0) * SUM(IF(unique_key='no_ra700_o81_nu709',answer_numeric,0)) ",
            " IF(SUM(IF(unique_key='no_ra700_o81_ch701_o238',1,0))>=1,1,0) * SUM(IF(unique_key='no_ra700_o81_nu709',answer_numeric,0)) ",
            " IF(SUM(IF(unique_key='no_ra700_o81_ch701_o239',1,0))>=1,1,0) * SUM(IF(unique_key='no_ra700_o81_nu709',answer_numeric,0)) ",
            " IF(SUM(IF(unique_key='no_ra700_o81_ch701_o1',1,0))>=1,1,0) * SUM(IF(unique_key='no_ra700_o81_nu709',answer_numeric,0)) ",
        ];

        $isRadio = true;
        $startColumn = 'C';
        $startRow = 11;
        $objPHPExcel = Summary::sum($table12_1, $startColumn, $startRow, $objPHPExcel, $mainObj, $isRadio);

        $startColumn = 'Q';
        $startRow = 11;
        $objPHPExcel = Summary::average($table12_2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $startColumn = 'AF';
        $startRow = 11;
        $objPHPExcel = Summary::average($table12_3,$startColumn, $startRow,$objPHPExcel,$mainObj);

        $startColumn = 'AT';
        $startRow = 11;
        $objPHPExcel = Summary::sum($table12_4, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $startColumn = 'BH';
        $startRow = 11;
        $objPHPExcel = Summary::average($table12_5, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $startColumn = "BV";
        $startRow = 11;
        $objPHPExcel = Summary121::average($table12_6, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/' . $outputFile)));
    }

    public static function average($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj)
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
                $whereMainId = implode(",", $mainList);

                $avgSql = "SELECT SUM(sum1)/".Main::$provinceSample[$p_key]." as average, COUNT(*) as countAll FROM
                    (SELECT $value AS sum1 FROM answers
                    WHERE main_id IN ($whereMainId) "
                    . " GROUP BY main_id) T1 WHERE sum1 > 0";

                $avgResult = \DB::select($avgSql);
                $avg[$p_key] = $avgResult[0]->average;
                $count[$p_key] = $avgResult[0]->countAll;
            }

            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $avg[$b_key] = 0;
                $whereMainId = implode(",", $mainList);

                $avgSql = "SELECT SUM(sum1)/".Main::$sample[$b_key]." as average, COUNT(*) as countAll FROM
                    (SELECT $value AS sum1 FROM answers
                    WHERE main_id IN ($whereMainId) "
                    . " GROUP BY main_id) T1 WHERE sum1 > 0";

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
