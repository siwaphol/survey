<?php

namespace App\Http\Controllers;

use App\Main;
use App\Menu;
use Illuminate\Http\Request;

use App\Http\Requests;

class FilterExportController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')
            ->with('submenu')
            ->get();

        return view('filter.index', compact('menus'));
    }

    public function exportWithSheetLoop()
    {
        set_time_limit(3600);
        $mainObj = new Main();
        $mainObj->initList();

        $excelNumbers = ['51','52',53,54,55,56];

        foreach ($excelNumbers as $c_number){
            $inputFile = $c_number.".xlsx";

            echo $inputFile . "</br>\n";

            $objPHPExcel = \PHPExcel_IOFactory::load(storage_path('excel/raw_excel/'. $inputFile));
            $outputFile = "";
            $outputFileCell = "A2";
            $uniqueKeyRowStart = 3;

            $i=0;
            foreach ($objPHPExcel->getAllSheets() as $objWorksheet){
                echo "Sheet " . $i . "</br>";

                $uniqueKeyColumnStart = "A";
                $uniqueKeyArr = [];

                if ($i===0){
                    $outputFile = $objWorksheet->getCell($outputFileCell)->getValue();
                }

                while (!empty(trim($objWorksheet->getCell($uniqueKeyColumnStart.$uniqueKeyRowStart)->getValue()))){
                    $uniqueKeyArr[] = trim($objWorksheet->getCell($uniqueKeyColumnStart.$uniqueKeyRowStart)->getValue());

                    $uniqueKeyColumnStart++;
                }

                // เหมาะกับหมวด ก
//                $this->printOutValueToExcelObjectRadioOnlyMainId($uniqueKeyArr, $objWorksheet, $mainObj);
                // หมวด ก แบบที่มี nested radio ด้วย
//                $this->printOutValueToExcelObjectRadioCustom($uniqueKeyArr, $objWorksheet, $mainObj);
                // ก7
//                $this->printOutValueToExcelObjectOneSheetAnswerText($uniqueKeyArr, $objWorksheet, $mainObj);
                // เหมาะกับหมวด ข
                $this->printOutValueToExcelObjectOneSheet($uniqueKeyArr, $objWorksheet, $mainObj);
                // หมวด ข หลอดไฟ
//            $this->printOutValueToExcelObjectOneSheetWhereInUniqueKey($uniqueKeyArr, $objWorksheet, $mainObj);
                //หมวด ข ยานพาหนะที่มีหลายๆคัน
//                $this->printOutValueToExcelObjectOneSheetMultipleVehicle($uniqueKeyArr, $objWorksheet, $mainObj);

                $i++;
            }

            $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
            $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/raw_excel_output/'.$outputFile)));
        }

        echo "success";
    }

    public function export()
    {
        set_time_limit(3600);
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = "3.xlsx";
        $objExcel = \PHPExcel_IOFactory::load(storage_path('excel/raw_excel/'. $inputFile));

        $uniqueKey = [
            ['no_ch1024_o331_ch123_o75_ch124_o78_nu125','no_ch1024_o331_ch123_o75_ch124_o78_nu126','no_ch1024_o331_ch123_o75_ch124_o78_nu127','no_ch1024_o331_ch123_o75_ch124_o78_nu128','no_ch1024_o331_ch123_o75_ch124_o78_nu129'],
            ['no_ch1024_o331_ch123_o75_ch124_o79_nu125','no_ch1024_o331_ch123_o75_ch124_o79_nu126','no_ch1024_o331_ch123_o75_ch124_o79_nu127','no_ch1024_o331_ch123_o75_ch124_o79_nu128','no_ch1024_o331_ch123_o75_ch124_o79_nu129'],
            ['no_ch1024_o331_ch123_o75_ch124_o80_nu125','no_ch1024_o331_ch123_o75_ch124_o80_nu126','no_ch1024_o331_ch123_o75_ch124_o80_nu127','no_ch1024_o331_ch123_o75_ch124_o80_nu128','no_ch1024_o331_ch123_o75_ch124_o80_nu129'],
            ['no_ch1024_o331_ch123_o76_ch1011_o78_nu1012','no_ch1024_o331_ch123_o76_ch1011_o78_nu1013','no_ch1024_o331_ch123_o76_ch1011_o78_nu1014','no_ch1024_o331_ch123_o76_ch1011_o78_nu1015','no_ch1024_o331_ch123_o76_ch1011_o78_nu1016'],
            ['no_ch1024_o331_ch123_o76_ch1011_o79_nu1012','no_ch1024_o331_ch123_o76_ch1011_o79_nu1013','no_ch1024_o331_ch123_o76_ch1011_o79_nu1014','no_ch1024_o331_ch123_o76_ch1011_o79_nu1015','no_ch1024_o331_ch123_o76_ch1011_o79_nu1016'],
            ['no_ch1024_o331_ch123_o77_ch1011_o78_nu1012','no_ch1024_o331_ch123_o77_ch1011_o78_nu1013','no_ch1024_o331_ch123_o77_ch1011_o78_nu1014','no_ch1024_o331_ch123_o77_ch1011_o78_nu1015','no_ch1024_o331_ch123_o77_ch1011_o78_nu1016'],
            ['no_ch1024_o331_ch123_o77_ch1011_o79_nu1012','no_ch1024_o331_ch123_o77_ch1011_o79_nu1013','no_ch1024_o331_ch123_o77_ch1011_o79_nu1014','no_ch1024_o331_ch123_o77_ch1011_o79_nu1015','no_ch1024_o331_ch123_o77_ch1011_o79_nu1016']
        ];

        $this->printOutValueToExcelObjectOneSheet($uniqueKey, $objExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objExcel);
        $outputFile = "3.หม้อหุงข้าวไฟฟ้า.xlsx";
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/raw_excel_output/'.$outputFile)));
        echo 'success';
    }

    /**
     * @param $uniqueKey
     * @param $objExcel
     * @param $mainObj
     */
    protected function printOutValueToExcelObject($uniqueKey, &$objExcel, $mainObj)
    {
        $index = 0;
        foreach ($uniqueKey as $uniqueKeyArr) {
            $objExcel->setActiveSheetIndex($index);
            $startColumn = 'B';
            $startRow = 7;
            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $template = " SUM(IF(unique_key='param',answer_numeric,0)) ";
                $selectSql = "";
                $whereAllNotZero = "";
                for ($i = 0; $i < count($uniqueKeyArr); $i++) {
                    if (empty($selectSql))
                        $selectSql = str_replace("param", $uniqueKeyArr[$i], $template) . " as sum" . ($i + 1);
                    else
                        $selectSql .= ", " . str_replace("param", $uniqueKeyArr[$i], $template) . " as sum" . ($i + 1);

                    if (empty($whereAllNotZero))
                        $whereAllNotZero = "sum" . ($i + 1) . ">0";
                    else
                        $whereAllNotZero .= " OR sum" . ($i + 1) . ">0";
                }

                $whereMainId = implode(",", $mainList);
                $sql = "SELECT * FROM
                    (SELECT main_id,{$selectSql} FROM answers 
                    WHERE main_id IN ({$whereMainId}) GROUP BY main_id) T1 WHERE {$whereAllNotZero} ORDER BY main_id";
                \DB::setFetchMode(\PDO::FETCH_NUM);
                $result = \DB::select($sql);
                \DB::setFetchMode(\PDO::FETCH_CLASS);

                $objExcel->getActiveSheet()->fromArray($result, NULL, ($startColumn . $startRow));
                for ($i = 0; $i < count($uniqueKeyArr); $i++) {
                    $startColumn++;
                }
                $startColumn++;
            }

            $index++;
        }
    }

    protected function printOutValueToExcelObjectOneSheetWhereInUniqueKey($uniqueKeyArr, &$objWorkSheet, $mainObj)
    {
        $startColumn = 'B';
        $startRow = 7;

        foreach (Main::$borderWeight as $b_key => $b_weight) {
            $mainList = $mainObj->filterMain($b_key);

            $template = " SUM(IF(unique_key IN (param),answer_numeric,0)) ";
            $selectSql = "";
            $whereAllNotZero = "";
            for ($i = 0; $i < count($uniqueKeyArr); $i++) {
                if (empty($selectSql))
                    $selectSql = str_replace("param", $uniqueKeyArr[$i], $template) . " as sum" . ($i + 1);
                else
                    $selectSql .= ", " . str_replace("param", $uniqueKeyArr[$i], $template) . " as sum" . ($i + 1);

                if (empty($whereAllNotZero))
                    $whereAllNotZero = "sum" . ($i + 1) . ">0";
                else
                    $whereAllNotZero .= " OR sum" . ($i + 1) . ">0";
            }

            $whereMainId = implode(",", $mainList);
            $sql = "SELECT * FROM
                (SELECT main_id,{$selectSql} FROM answers 
                WHERE main_id IN ({$whereMainId}) GROUP BY main_id) T1 WHERE {$whereAllNotZero} ORDER BY main_id";
            \DB::setFetchMode(\PDO::FETCH_NUM);
            $result = \DB::select($sql);
            \DB::setFetchMode(\PDO::FETCH_CLASS);

            $objWorkSheet->fromArray($result, NULL, ($startColumn . $startRow));
            for ($i = 0; $i < count($uniqueKeyArr); $i++) {
                $startColumn++;
            }
            $startColumn++;
        }

    }

    protected function printOutValueToExcelObjectOneSheet($uniqueKeyArr, &$objWorkSheet, $mainObj)
    {
        $startColumn = 'B';
        $startRow = 7;

        foreach (Main::$borderWeight as $b_key => $b_weight) {
            $mainList = $mainObj->filterMain($b_key);

            $template = " SUM(IF(unique_key='param',answer_numeric,0)) ";
            $selectSql = "";
            $whereAllNotZero = "";
            for ($i = 0; $i < count($uniqueKeyArr); $i++) {
                if (empty($selectSql))
                    $selectSql = str_replace("param", $uniqueKeyArr[$i], $template) . " as sum" . ($i + 1);
                else
                    $selectSql .= ", " . str_replace("param", $uniqueKeyArr[$i], $template) . " as sum" . ($i + 1);

                if (empty($whereAllNotZero))
                    $whereAllNotZero = "sum" . ($i + 1) . ">0";
                else
                    $whereAllNotZero .= " OR sum" . ($i + 1) . ">0";
            }

            $whereMainId = implode(",", $mainList);
            $sql = "SELECT * FROM
                (SELECT main_id,{$selectSql} FROM answers 
                WHERE main_id IN ({$whereMainId}) GROUP BY main_id) T1 WHERE {$whereAllNotZero} ORDER BY main_id";
            \DB::setFetchMode(\PDO::FETCH_NUM);
            $result = \DB::select($sql);
            \DB::setFetchMode(\PDO::FETCH_CLASS);

            $objWorkSheet->fromArray($result, NULL, ($startColumn . $startRow));
            for ($i = 0; $i < count($uniqueKeyArr); $i++) {
                $startColumn++;
            }
            $startColumn++;
        }

    }

    protected function printOutValueToExcelObjectOneSheetAnswerText($uniqueKeyArr, &$objWorkSheet, $mainObj)
    {
        $startColumn = 'B';
        $startRow = 7;

        foreach (Main::$borderWeight as $b_key => $b_weight) {
            $mainList = $mainObj->filterMain($b_key);

            $template = " SUM(IF(unique_key='param',answer_text,0)) ";
            $selectSql = "";
            $whereAllNotZero = "";
            for ($i = 0; $i < count($uniqueKeyArr); $i++) {
                if (empty($selectSql))
                    $selectSql = str_replace("param", $uniqueKeyArr[$i], $template) . " as sum" . ($i + 1);
                else
                    $selectSql .= ", " . str_replace("param", $uniqueKeyArr[$i], $template) . " as sum" . ($i + 1);

                if (empty($whereAllNotZero))
                    $whereAllNotZero = "sum" . ($i + 1) . ">0";
                else
                    $whereAllNotZero .= " OR sum" . ($i + 1) . ">0";
            }

            $whereMainId = implode(",", $mainList);
            $sql = "SELECT * FROM
                (SELECT main_id,{$selectSql} FROM answers 
                WHERE main_id IN ({$whereMainId}) GROUP BY main_id) T1 WHERE {$whereAllNotZero} ORDER BY main_id";
            \DB::setFetchMode(\PDO::FETCH_NUM);
            $result = \DB::select($sql);
            \DB::setFetchMode(\PDO::FETCH_CLASS);

            $objWorkSheet->fromArray($result, NULL, ($startColumn . $startRow));
            for ($i = 0; $i < count($uniqueKeyArr); $i++) {
                $startColumn++;
            }
            $startColumn++;
        }

    }

    protected function printOutValueToExcelObjectRadioOnlyMainId($uniqueKey, &$objWorksheet, $mainObj)
    {
        $index = 0;
        foreach ($uniqueKey as $uniqueKeyArr) {
            $startColumn = 'B';
            $startRow = 7;

            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $whereMainId = implode(",", $mainList);
                $sql = "SELECT * FROM
                    (SELECT main_id FROM answers 
                    WHERE main_id IN ({$whereMainId}) AND {$uniqueKeyArr} GROUP BY main_id) T1 ORDER BY main_id";
                \DB::setFetchMode(\PDO::FETCH_NUM);
                $result = \DB::select($sql);
                \DB::setFetchMode(\PDO::FETCH_CLASS);

                $objWorksheet->fromArray($result, NULL, ($startColumn . $startRow));
                for ($i = 0; $i < count($uniqueKeyArr); $i++) {
                    $startColumn++;
                }
//                $startColumn++;
            }

            $index++;
        }
    }

    protected function printOutValueToExcelObjectRadioCustom($uniqueKey, &$objWorksheet, $mainObj)
    {
//        $index = 0;
        $startColumn = 'B';

        for ($index=0;$index<count($uniqueKey);$index++) {
            $startRow = 7;
            $c_column = $startColumn;

            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $whereMainId = implode(",", $mainList);
                if ($index>0){
                    $sql = "SELECT DISTINCT T1.main_id FROM
                    (SELECT main_id FROM answers 
                    WHERE main_id IN ({$whereMainId}) AND {$uniqueKey[0]} GROUP BY main_id) T1 
                    INNER JOIN (SELECT main_id FROM answers 
                    WHERE main_id IN ({$whereMainId}) AND {$uniqueKey[$index]} GROUP BY main_id) T2
                    ON T1.main_id=T2.main_id
                    ORDER BY T1.main_id";
                }else{
                    $sql = "SELECT * FROM
                    (SELECT main_id FROM answers 
                    WHERE main_id IN ({$whereMainId}) AND {$uniqueKey[0]} GROUP BY main_id) T1 ORDER BY main_id";
                }
                \DB::setFetchMode(\PDO::FETCH_NUM);
                $result = \DB::select($sql);
                \DB::setFetchMode(\PDO::FETCH_CLASS);
                $objWorksheet->fromArray($result, NULL, ($c_column . $startRow));

                // suppose there are 3 more unique keys
//                $startColumn++;
                for ($i = 0; $i < count($uniqueKey); $i++) {
                    $c_column++;
                }
            }
            $startColumn++;

//            $index++;
        }
    }

    // สำหรับรถแต่ละประเภท ที่มีหลายคัน
    protected function printOutValueToExcelObjectOneSheetMultipleVehicle($uniqueKeyArr, &$objWorkSheet, $mainObj)
    {
        $startColumn = 'B';
        $startRow = 7;

        $oilCell = 'A4';

        foreach (Main::$borderWeight as $b_key => $b_weight) {
            $mainList = $mainObj->filterMain($b_key);

            $template = " SUM(IF(unique_key='param',answer_numeric,0)) ";
            $selectSql = "";
            $whereAllNotZero = "";
            for ($i = 0; $i < count($uniqueKeyArr); $i++) {
                // จาก unique_key1,unique_key2,unique_key3 => [0=>unique_key1, 1=>unique_key2, 2=>unique_key3]
                $newUniqueKeyArr = explode(",", $uniqueKeyArr[$i]);

                if (empty($selectSql)){
                    foreach($newUniqueKeyArr as $uKey){
                        $selectSql .= str_replace("param", $uKey, $template) . " + ";
                    }
                    $selectSql .= " 0 as sum" . ($i + 1);
                }
                else{
                    $selectSql .= " , ";
                    foreach($newUniqueKeyArr as $uKey){
                        $selectSql .= str_replace("param", $uKey, $template) . " + ";
                    }
                    $selectSql .= " 0 as sum" . ($i + 1);
                }

                if (empty($whereAllNotZero))
                    $whereAllNotZero = "sum" . ($i + 1) . ">0";
                else
                    $whereAllNotZero .= " OR sum" . ($i + 1) . ">0";

            }

            $whereMainId = implode(",", $mainList);
            $whereOilType = $objWorkSheet->getCell($oilCell)->getValue();
            $oilUseTemplate = "SUM(IF(param,1,0))";
            $selectSql.= ",".str_replace("param", $whereOilType, $oilUseTemplate) . " as useOil ";
            $whereAllNotZero = "(" . $whereAllNotZero . ") AND useOil>0 ";

            $sql = "SELECT * FROM
                (SELECT main_id,{$selectSql} FROM answers 
                WHERE main_id IN ({$whereMainId})  GROUP BY main_id) T1 WHERE {$whereAllNotZero} ORDER BY main_id";
            \DB::setFetchMode(\PDO::FETCH_NUM);
            $result = \DB::select($sql);
            \DB::setFetchMode(\PDO::FETCH_CLASS);

            $objWorkSheet->fromArray($result, NULL, ($startColumn . $startRow));
            for ($i = 0; $i < count($uniqueKeyArr); $i++) {
                $startColumn++;
            }
            $startColumn++;
        }

    }
}
