<?php

namespace App\Http\Controllers;

use App\Main;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FilterSelectionController extends Controller
{
    public function exportWithSheetLoop()
    {
        set_time_limit(3600);
        $mainObj = new Main();
        $mainObj->initList();

        $excelNumbers = [1];

        foreach ($excelNumbers as $c_number){
            $inputFile = $c_number.".xlsx";

            echo $inputFile . "</br>\n";

            $objPHPExcel = \PHPExcel_IOFactory::load(storage_path('excel/raw_excel/selection/'. $inputFile));
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

                // เหมาะกับหมวด ข
                $this->printOutDistinctSelection($uniqueKeyArr, $objWorksheet, $mainObj);

                $i++;
            }

            $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
            $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/raw_excel_output/selection/'.$outputFile)));
        }

        echo "success";
    }

    protected function printOutDistinctSelection($uniqueKey, &$objWorksheet, $mainObj)
    {
        $index = 0;
        foreach ($uniqueKey as $uniqueKeyArr) {
            $startColumn = 'B';
            $startRow = 7;

            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $whereMainId = implode(",", $mainList);
                $sql = "SELECT * FROM
                    (SELECT DISTINCT main_id FROM answers 
                    WHERE main_id IN ({$whereMainId}) AND unique_key LIKE '{$uniqueKeyArr}' GROUP BY main_id) T1 ORDER BY main_id";
                \DB::setFetchMode(\PDO::FETCH_NUM);
                $result = \DB::select($sql);
                \DB::setFetchMode(\PDO::FETCH_CLASS);

                $objWorksheet->fromArray($result, NULL, ($startColumn . $startRow));
                for ($i = 0; $i < count($uniqueKeyArr); $i++) {
                    $startColumn++;
                }
            }

            $index++;
        }
    }

}
