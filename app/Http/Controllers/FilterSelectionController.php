<?php

namespace App\Http\Controllers;

use App\Main;
use App\Question;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FilterSelectionController extends Controller
{
    public function testExport($section_id=5, $sub_section_id=6)
    {
        $section = $section_id;
        $sub_section = $sub_section_id;
        $whereSubSql = ' and t1.sub_section_id ';
        if ($sub_section_id){
            $whereSubSql .= ' = ' . $sub_section_id;
        }
        else{
            $whereSubSql .= ' IS NULL ';
        }

        $str = "select 
        t1.id,
        t1.parent_id,
        t1.sibling_order,
        t1.section,
        t1.input_type,
        t1.text,
        t1.required,
        t1.dependent_parent_option_id,
        t1.unit_of_measure,
        t3.id as option_id,
        t3.name as option_name,
        t2.order as option_order,
        t2.id as option_question_id
        from questions t1
        LEFT JOIN option_questions t2
        on t1.id=t2.question_id
        LEFT JOIN options t3
        on t2.option_id=t3.id
        WHERE t1.section_id={$section} " . $whereSubSql .
            " ORDER BY t1.parent_id,t1.sibling_order,t2.id ";

        $result = \DB::select($str);

        if (count($result)<=0)
            return abort(404);

        $grouped = QuestionController::createQuestionGroup($result);

        // พอได้ grouped แล้วก็สามารถดึงค่าตัวแปร unique_key ง่ายขึ้น
        $columnHead = array();
        $filterTest = array();
        $uniqueKeys = $this->generateUniqueKeyFilterArray($grouped,'question.no', false, null, $filterTest, $columnHead);

        $selectSql = "main_id, " . implode(",", $uniqueKeys);
        $sqlStr = "SELECT {$selectSql} FROM answers WHERE main_id<=2500 GROUP BY main_id ORDER BY main_id";
        \DB::setFetchMode(\PDO::FETCH_NUM);
        $sqlResult = \DB::select($sqlStr);
        \DB::setFetchMode(\PDO::FETCH_CLASS);

        $sqlResult = array($columnHead) + $sqlResult;
        dd($sqlResult);
    }

    public function generateUniqueKeyFilterArray(&$questionArr,$key='question.no', $hideable=false, $condition=null, &$filterTest=[], &$columnHead = [])
    {
        $list = [];
        foreach ($questionArr as $aQuestion){
            $myObj = clone $aQuestion;
            if ($aQuestion->input_type===Question::TYPE_NUMBER){
                $qKey = $key .'_'. 'nu'.$aQuestion->id;
                $myObj->{"unique_key"} = $qKey;

                $tempKey = str_replace("question.","", $qKey);
                $filterTest[] = "sum(if(unique_key='{$tempKey}', answer_numeric,0)) as \"" . $aQuestion->name . "\"";
                $columnHead[] = $aQuestion->name;
            }elseif ($aQuestion->input_type===Question::TYPE_TEXT){
                $qKey = $key .'_'. 'te'.$aQuestion->id;
                $myObj->{"unique_key"} = $qKey;

            }elseif ($aQuestion->input_type===Question::TYPE_CHECKBOX){
                $qKey = $key .'_'. 'ch'.$aQuestion->id ;
                $myObj->{"unique_key"} = $qKey;
                $i = 0;
                foreach ($aQuestion as $option){
                    $optionKey = $qKey . '_o' .$option->option_id;
                    $myObj[$i] = clone $option;
                    $myObj[$i]->{"unique_key"} = $optionKey;

                    //TODO-nong test filter
                    $tempKey = str_replace("question.","", $qKey);
                    $filterTest[] = "if(sum(if(unique_key='{$tempKey}', 1,0))>1, 1,0) as \"" . $option->option_name . "\"";
                    $columnHead[] = $option->option_name;
                    //end to-do nong test filter

                    if (isset($option->children)){
                        //End test descriptions table
                        $myObj[$i]->children = $this->generateUniqueKeyFilterArray($option->children,$optionKey, true,$optionKey, $filterTest, $columnHead);
                    }
                    $i++;
                }
            }elseif ($aQuestion->input_type===Question::TYPE_RADIO){
                $qKey = $key . '_ra'.$aQuestion->id;
                $myObj->{"unique_key"} = $qKey;
                $i = 0;

                //test filter
                $matchingOptionWithText = "''";

                foreach ($aQuestion as $option){
                    $optionKey = $qKey . '_o' .$option->option_id;
                    $myObj[$i] = clone $option;
                    $myObj[$i]->{"unique_key"} = $optionKey;

                    //test filter
                    $tempKey = str_replace("question.","", $qKey);
                    $matchingOptionWithText .= ",if(sum(if(unique_key='{$tempKey}' and option_id={$option->option_id}, 1 , 0))>0, '{$option->option_name}','')";

                    if (isset($option->children)){
                        $optionCon = $qKey . "==" . $option->option_id;
                        $myObj[$i]->children = $this->generateUniqueKeyFilterArray($option->children,$optionKey, true,$optionCon,$filterTest, $columnHead);
                    }
                    $i++;
                }

                //test filter
                $matchingOptionWithText = str_replace("param", "''", $matchingOptionWithText);
                $filterTest[] = "CONCAT(" . $matchingOptionWithText . ") as \"" . $aQuestion->name . "\" ";
                $columnHead[] = $aQuestion->name;
            }

            if ($hideable && !is_null($condition)){
                $myObj->{"ngIf"} = $condition;
            }

            if (isset($aQuestion->children)){
                if ($aQuestion->input_type===Question::TYPE_RADIO){
                    $myObj->children =  $this->generateUniqueKeyFilterArray($aQuestion->children,$qKey, true, $qKey,$filterTest, $columnHead);
                }else{
                    $myObj->children = $this->generateUniqueKeyFilterArray($aQuestion->children,$qKey, $hideable, $condition,$filterTest, $columnHead);
                }
            }

            $list[] = $myObj;
        }

        return $filterTest;
    }

    
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
