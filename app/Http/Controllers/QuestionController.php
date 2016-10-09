<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Description;
use App\Menu;
use App\Option;
use App\OptionQuestion;
use App\Question;
use Illuminate\Http\Request;

use App\Http\Requests;

class QuestionController extends Controller
{
    public function importExcelQuestion()
    {
        $filename = "SurveyDB_v1.xlsx";
        $path = storage_path("excel\\" . $filename);

        if(explode('.', $filename)[1] === 'xls'){
            $objReader = \PHPExcel_IOFactory::createReader("Excel5");
        }
        else if(explode('.', $filename)[1] === 'xlsx')
        {
            $objReader = \PHPExcel_IOFactory::createReader("Excel2007");
        }

        $objReader->setReadDataOnly(true);
        $worksheetData = $objReader->listWorksheetInfo($path);

        $chunkSize = 10000;
        $chunkFilter = new \App\Custom\ChunkReadFilter();
        $objReader->setReadFilter($chunkFilter);

        $optionSheetNo = 0;

        // sheet 0 option
        \DB::transaction(function () use ($worksheetData,$chunkFilter,$objReader,$chunkSize,$path,$optionSheetNo) {
            $totalRows = $worksheetData[$optionSheetNo]['totalRows'];

            \DB::statement("SET FOREIGN_KEY_CHECKS = 0");
            \DB::table('options')->truncate();
            \DB::statement("SET FOREIGN_KEY_CHECKS = 1");

            for ($startRow = 2; $startRow <= $totalRows; $startRow += $chunkSize) {
                $chunkFilter->setRows($startRow,$chunkSize);

                $objPHPExcel = $objReader->load($path);

                $sheetData = $objPHPExcel
                    ->getSheet($optionSheetNo)
                    ->toArray(null,true,true,true);

                for($i=$startRow; $i <= ($startRow+$chunkSize-1); $i++) {
                    if ($i > $totalRows) {
                        break;
                    }
                    $option = Option::findOrNew((int)$sheetData[$i]["A"]);
                    $option->id = (int)$sheetData[$i]["A"];
                    $option->name = is_null($sheetData[$i]["B"])?'':$sheetData[$i]["B"];
                    $option->save();
                }
            }
        });
        \DB::statement("SET FOREIGN_KEY_CHECKS = 0");
        \DB::table('option_questions')->truncate();
        \DB::table('questions')->truncate();
        \DB::statement("SET FOREIGN_KEY_CHECKS = 1");

        for($sheetNo=1; $sheetNo <=10; $sheetNo++){
            echo ' sheet ' . $sheetNo;
            if($sheetNo%2){
                echo ' (odd)';
                // questions sheet
//                \DB::transaction(function ()use($worksheetData,$chunkFilter,$objReader,$chunkSize,$path,$sheetNo) {
                    $totalRows = $worksheetData[$sheetNo]['totalRows'];

                    for ($startRow = 2; $startRow <= $totalRows; $startRow += $chunkSize) {
                        $chunkFilter->setRows($startRow,$chunkSize);

                        $objPHPExcel = $objReader->load($path);

                        $sheetData = $objPHPExcel
                            ->getSheet($sheetNo)
                            ->toArray(null,true,true,true);

                        for($i=$startRow; $i <= ($startRow+$chunkSize-1); $i++) {
                            if ($i > $totalRows) {
                                break;
                            }
                            $question = Question::findOrNew((int)$sheetData[$i]["A"]);
                            $question->id = (int)$sheetData[$i]["A"];
                            $question->parent_id = $sheetData[$i]["B"]==='NULL'?null:(int)$sheetData[$i]["B"];
                            $question->sibling_order = (int)$sheetData[$i]["C"];
                            $question->dependent_parent_option_id = $sheetData[$i]["D"]==='NULL'?null:$sheetData[$i]["D"];
                            $question->section = $sheetData[$i]["E"];
                            $question->sub_section = $sheetData[$i]["F"];
                            $question->input_type = $sheetData[$i]["G"];
                            $question->text = $sheetData[$i]["H"];
                            $question->unit_of_measure = $sheetData[$i]["I"]==='NULL'?null:$sheetData[$i]["I"];
                            $question->save();
                        }
                    }
//                });
                continue;
            }
            echo ' (even)';
            // question_option sheet
            \DB::transaction(function () use ($worksheetData,$chunkFilter,$objReader,$chunkSize,$path,$sheetNo) {
                $totalRows = $worksheetData[$sheetNo]['totalRows'];

                for ($startRow = 2; $startRow <= $totalRows; $startRow += $chunkSize) {
                    $chunkFilter->setRows($startRow,$chunkSize);

                    $objPHPExcel = $objReader->load($path);

                    $sheetData = $objPHPExcel
                        ->getSheet($sheetNo)
                        ->toArray(null,true,true,true);

                    for($i=$startRow; $i <= ($startRow+$chunkSize-1); $i++) {
                        if ($i > $totalRows) {
                            break;
                        }

                        if (is_null(Question::find((int)$sheetData[$i]["A"])))
                            continue;
                        \DB::table('option_questions')
                            ->insert([
                                'question_id'=>(int)$sheetData[$i]["A"],
                                'option_id'=>(int)$sheetData[$i]["B"],
                                'order'=>(int)$sheetData[$i]["C"],
                            ]);
                    }
                }
            });
        }

        dd("complete");

    }

    public function htmlLoop($id, $sub=null)
    {
        $main_id = \Session::get('main_id');
        if (is_null($main_id) || empty($main_id))
            return redirect('main');
        $main_id = (int)$main_id;

        $section = $id;
        $sub_section = $sub;
        $whereSubSql = ' and t1.sub_section_id ';
        if ($sub){
            $whereSubSql .= ' = ' . $sub;
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

        $scope = [];
        $edited = false;
        $answers = Answer::where('section_id', $section)
            ->where('sub_section_id', $sub_section)
            ->where('main_id', $main_id)
            ->get();
        if (count($answers)>0)
            $edited = true;

        $testfilter = [];
        $new = $this->generateUniqueKey($grouped, $scope, $answers,'question.no', false, null, null, $testfilter);

        //TODO-nong descriptions table test
        dd($testfilter);

        $sectionName = Menu::find($section)->name;
        $hasSub = Menu::find($sub_section);
        $subSectionName = null;
        if ($hasSub)
            $subSectionName = $hasSub->name;
        return view('angular_material_main2',
            compact('grouped','section','sub_section', 'main_id','scope','new', 'sectionName', 'subSectionName','edited'));
    }

    function generateUniqueKey(&$questionArr, &$scope, $answers,$key='question.no', $hideable=false, $condition=null, $parentText = null, &$filterTest){
        $list = [];
        foreach ($questionArr as $aQuestion){
            $myObj = clone $aQuestion;
            if ($aQuestion->input_type===Question::TYPE_TITLE){
                $qKey = $key .'_'. 'ti'.$aQuestion->id;
                $myObj->{"unique_key"} = $qKey;
            }elseif ($aQuestion->input_type===Question::TYPE_NUMBER){
                $qKey = $key .'_'. 'nu'.$aQuestion->id;
                $myObj->{"unique_key"} = $qKey;

                // TODO-nong test descriptions table
//                $desc = Description::where('unique_key', str_replace('question.',"",$qKey))
//                    ->first();
//                if (is_null($desc))
//                    Description::create([
//                        'title'=>$aQuestion->name,
//                        'parent_title'=>$parentText,
//                        'unique_key'=>str_replace('question.',"",$qKey)
//                    ]);
//                else{
//                    $desc->title = $aQuestion->name;
//                    $desc->parent_title = $parentText;
//                    $desc->save();
//                }
                $tempKey = str_replace("question.","", $qKey);
                $filterTest[] = "sum(if(unique_key='{$tempKey}', answer_numeric,0)) as \"" . $aQuestion->name . "\"";
                // end test descriptions table

                $answer = $answers->where('unique_key', str_replace("question.", "", $qKey))
                    ->first();

                $scope[] = '$scope.' . $qKey . ' = '.(is_null($answer)?'0':$answer->answer_numeric).';';
            }elseif ($aQuestion->input_type===Question::TYPE_TEXT){
                $qKey = $key .'_'. 'te'.$aQuestion->id;
                $myObj->{"unique_key"} = $qKey;

                $answer = $answers->where('unique_key', str_replace("question.", "", $qKey))
                    ->first();
                $scope[] = '$scope.' . $qKey . ' = '.(is_null($answer)?'""':'"'.$answer->answer_text.'"').';';
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
                    //end to-do nong test filter

                    $answer = $answers->where('unique_key', str_replace("question.", "", $optionKey))
                        ->first();
                    $scopeAnswer = 'false';
                    if ($answer){
                        $scopeAnswer = 'true';
                        if (!empty($answer->other_text))
                            $scope[] ='$scope.' . str_replace("no","other",$optionKey) . ' = "'.$answer->other_text.'";';
                    }
                    $scope[] = '$scope.' . $optionKey . ' = '.$scopeAnswer.';';

                    if (isset($option->children)){
                        //TODO-nong test descriptions table
                        $echoName = ($parentText?$parentText . "/":"") . $option->option_name;
//                        echo $echoName . " - $optionKey - " . " </br>";
                        //End test descriptions table
                        $myObj[$i]->children = $this->generateUniqueKey($option->children, $scope, $answers,$optionKey, true,$optionKey, $echoName, $filterTest);
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
                        $myObj[$i]->children = $this->generateUniqueKey($option->children, $scope, $answers,$optionKey, true,$optionCon,null,$filterTest);
                    }
                    $i++;
                }

                //test filter
                $matchingOptionWithText = str_replace("param", "''", $matchingOptionWithText);
//                $matchingOptionWithText .= " as \"" . $aQuestion->name . "\"";
                $filterTest[] = "CONCAT(" . $matchingOptionWithText . ") as \"" . $aQuestion->name . "\" ";

                $answer = $answers->where('unique_key', str_replace("question.", "", $qKey))
                    ->first();
                if ($answer){
                    $optionId = $answer->option_id;
                    if (!empty($answer->other_text))
                        $scope[] ='$scope.' . str_replace("no","other",$qKey) . ' = "'.$answer->other_text.'";';
                }
                //TODO-nong test filter
//                $filterTest[] = " if(unique_key='{$optionKey}') ";

                $scope[] = '$scope.' . $qKey . ' = '.(is_null($answer)?'""':'"'.$optionId.'"').';';
            }

            if ($hideable && !is_null($condition)){
                $myObj->{"ngIf"} = $condition;
            }

            if (isset($aQuestion->children)){
                if ($aQuestion->input_type===Question::TYPE_RADIO){
                    $myObj->children =  $this->generateUniqueKey($aQuestion->children, $scope, $answers,$qKey, true, $qKey,null,$filterTest);
                }else{
                    $myObj->children = $this->generateUniqueKey($aQuestion->children, $scope, $answers,$qKey, $hideable, $condition,null,$filterTest);
                }
            }

            $list[] = $myObj;
        }

        return $list;
    }

    /**
     * @param $result
     * @return static
     */
    public static function createQuestionGroup($result)
    {
        $t = collect($result);
        $grouped = $t->groupBy('id');

        $forgetList = [];
        foreach ($grouped as $aQuestion) {

            $aQuestion->{"input_type"} = $aQuestion[0]->input_type;
            $aQuestion->{"id"} = $aQuestion[0]->id;
            $aQuestion->{"parent_id"} = $aQuestion[0]->parent_id;
            $aQuestion->{"name"} = $aQuestion[0]->text;
            $aQuestion->{"subtext"} = null;
            $aQuestion->{"dependent_parent_option_id"} = $aQuestion[0]->dependent_parent_option_id;

            $aQuestion->{"class"} = "";
//            if (!is_null($aQuestion->parent_id)){
//                // 1.ถ้าไม่ขึ้นกับแม่สัก option เลย
//                // 1.1 title ให้อยู่ล่างแม่ปกติ
//                // 1.2 text และ number ให้อยู่ล่างแม่ปกติ
//                // 1.3 checkbox ให้ไปอยู่ให้ทุก option ของแม่
//                // 1.4 radio ให้อยู่ล่างแม่ปกติ
//                // 2.ถ้าขึ้นกับแม่
//                // 2.1 ทั้ง checkbox และ radio ให้อยู่ล่าง option ของแม่ทั้งหมด

            if (!is_null($aQuestion[0]->parent_id)) {
                $typeArr = [Question::TYPE_TITLE, Question::TYPE_TEXT, Question::TYPE_NUMBER];
                $inArray = in_array($grouped[$aQuestion[0]->parent_id][0]->input_type, $typeArr);
                if ($inArray) {
                    if (!isset($grouped[$aQuestion[0]->parent_id]->{"children"})) {
                        $grouped[$aQuestion[0]->parent_id]->{"children"} = [];
                    }
                    $aQuestion->{"class"} = "";
                    $grouped[$aQuestion[0]->parent_id]->{"children"}[$aQuestion[0]->id] = $aQuestion;
                }

                $type2Arr = [Question::TYPE_CHECKBOX, Question::TYPE_RADIO];
                $inArray2 = in_array($grouped[$aQuestion[0]->parent_id][0]->input_type, $type2Arr);
                if ($inArray2) {
                    $aQuestion->{"class"} = ' has-parent';

                    if (is_null($aQuestion->dependent_parent_option_id)) {
                        if ($grouped[$aQuestion[0]->parent_id][0]->input_type === Question::TYPE_RADIO) {
                            if (!isset($grouped[$aQuestion[0]->parent_id]->{"children"})) {
                                $grouped[$aQuestion[0]->parent_id]->{"children"} = [];
                            }
                            $grouped[$aQuestion[0]->parent_id]->{"children"}[$aQuestion[0]->id] = $aQuestion;
                        } else if ($grouped[$aQuestion[0]->parent_id][0]->input_type === Question::TYPE_CHECKBOX) {
                            foreach ($grouped[$aQuestion[0]->parent_id] as $each_parent_option) {
                                if (!isset($each_parent_option->{"children"})) {
                                    $each_parent_option->{"children"} = [];
                                }

                                $each_parent_option->{"children"}[$aQuestion[0]->id] = $aQuestion;
                            }
                        }
                    }

                    if (!is_null($aQuestion->dependent_parent_option_id)) {
                        foreach ($grouped[$aQuestion[0]->parent_id] as $each_parent_option) {
                            $dependentArr = explode(",", $aQuestion->dependent_parent_option_id);
                            if (in_array($each_parent_option->option_id, $dependentArr)) {
                                if (!isset($each_parent_option->{"children"})) {
                                    $each_parent_option->{"children"} = [];
                                }

                                // ให้หาว่ากรณีที่มีคำตอบอยู่แล้ว parent_option_selected_id เท่ากับค่าของแม่จริงๆ
                                $each_parent_option->{"children"}[$aQuestion[0]->id] = $aQuestion;
                            }
                        }
                    }
                }

                $forgetList[] = $aQuestion[0]->id;
            }
        }
        //for get in list
        foreach ($forgetList as $aId) {
            $grouped->forget((string)$aId);
        }

        return $grouped;
    }
}
