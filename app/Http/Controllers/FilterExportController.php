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

        $inputFile = "12.xlsx";
        $objPHPExcel = \PHPExcel_IOFactory::load(storage_path('excel/raw_excel/'. $inputFile));
        $outputFile = "";
        $outputFileCell = "A2";
        $uniqueKeyRowStart = 3;

        $i=0;
//        dd($objPHPExcel->getAllSheets());
        foreach ($objPHPExcel->getAllSheets() as $objWorksheet){
            echo "Sheet " . $i . "</br>";

            $uniqueKeyColumnStart = "A";
            $uniqueKeyArr = [];

//            $objWorksheet = $objPHPExcel->getActiveSheet();

            if ($i===0){
                $outputFile = $objWorksheet->getCell($outputFileCell)->getValue();
            }

            while (!empty(trim($objWorksheet->getCell($uniqueKeyColumnStart.$uniqueKeyRowStart)->getValue()))){
                $uniqueKeyArr[] = trim($objWorksheet->getCell($uniqueKeyColumnStart.$uniqueKeyRowStart)->getValue());

                $uniqueKeyColumnStart++;
            }

            $this->printOutValueToExcelObjectOneSheet($uniqueKeyArr, $objWorksheet, $mainObj);

            $i++;
        }

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/raw_excel_output/'.$outputFile)));
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
}
