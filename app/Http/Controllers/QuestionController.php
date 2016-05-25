<?php

namespace App\Http\Controllers;

use App\Option;
use App\Question;
use Illuminate\Http\Request;

use App\Http\Requests;

class QuestionController extends Controller
{
    public function importExcelQuestion()
    {
        $filename = "survey_question.xlsx";
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
        $currentRowInExcelCount = 2;
        $step = 1;
        $duplicateCode = [];

        // sheet 2 option
        \DB::transaction(function () use ($worksheetData,$chunkFilter,$objReader,$chunkSize,$path) {
            $totalRows = $worksheetData[2]['totalRows'];
            for ($startRow = 2; $startRow <= $totalRows; $startRow += $chunkSize) {
                $chunkFilter->setRows($startRow,$chunkSize);

                $objPHPExcel = $objReader->load($path);

                $sheetData = $objPHPExcel
                    ->getSheet(2)
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
//        dd('options finish');

        // sheet 1 question
        \DB::transaction(function ()use($worksheetData,$chunkFilter,$objReader,$chunkSize,$path) {
            $totalRows = $worksheetData[1]['totalRows'];
            for ($startRow = 2; $startRow <= $totalRows; $startRow += $chunkSize) {
                $chunkFilter->setRows($startRow,$chunkSize);

                $objPHPExcel = $objReader->load($path);

                $sheetData = $objPHPExcel
                    ->getSheet(1)
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
        });
//
//        dd('finish questions insert');
        // sheet 3 question_option
        \DB::transaction(function () use ($worksheetData,$chunkFilter,$objReader,$chunkSize,$path) {
            $totalRows = $worksheetData[3]['totalRows'];
            for ($startRow = 2; $startRow <= $totalRows; $startRow += $chunkSize) {
                $chunkFilter->setRows($startRow,$chunkSize);

                $objPHPExcel = $objReader->load($path);

                $sheetData = $objPHPExcel
                    ->getSheet(3)
                    ->toArray(null,true,true,true);

                for($i=$startRow; $i <= ($startRow+$chunkSize-1); $i++) {
                    if ($i > $totalRows) {
                        break;
                    }
                    
                    \DB::table('option_questions')
                        ->insert([
                            'question_id'=>(int)$sheetData[$i]["A"],
                            'option_id'=>(int)$sheetData[$i]["B"],
                            'order'=>(int)$sheetData[$i]["C"],
                        ]);
                }
            }
        });

        dd("complete");

    }

    public function htmlLoop($id)
    {
        switch ($id){
            case 1:
                $section = "ทั่วไป";
                break;
            case 2:
                $section = "ก.1";
                break;
            case 3:
                $section = "ก.2";
                break;
            case 4:
                $section = "ก.3";
                break;
            case 5:
                $section = "ข.1";
                break;
            case 6:
                $section = "ข.2";
                break;
            default:
                $section = "ทั่วไป";
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
        t3.id as option_id,
        t3.name as option_name,
        t2.order as option_order,
        t2.id as option_question_id,
        t4.id as selected,
        t4.answer_numeric,
        t4.answer_text,
        t4.other_text
        from questions t1
        LEFT JOIN option_questions t2
        on t1.id=t2.question_id
        LEFT JOIN options t3
        on t2.option_id=t3.id
        LEFT JOIN answers t4
        on t2.id=t4.option_question_id and t4.main_id=1
        WHERE t1.section='{$section}'
        ORDER BY t1.id,t1.parent_id,t1.sibling_order,t2.id ";
        $result = \DB::select($str);

//    dd($result);
        $t = collect($result);
        $grouped = $t->groupBy('id');

        $forgetList =[];
        foreach ($grouped as $aQuestion){
            $aQuestion->{"input_type"} = $aQuestion[0]->input_type;
            $aQuestion->{"id"} = $aQuestion[0]->id;
            $aQuestion->{"parent_id"} = $aQuestion[0]->parent_id;
            $aQuestion->{"name"} = $aQuestion[0]->text;
//        $aQuestion->{"subtext"} = $aQuestion[0]->subtext;
            $aQuestion->{"subtext"} = null;
            $aQuestion->{"dependent_parent_option_id"} = $aQuestion[0]->dependent_parent_option_id;

            $aQuestion->{"class"} = "";
//            if (!is_null($aQuestion->parent_id)){
//                // 1.ถ้าไม่ขึ้นกับแม่สัก option เลย
//
//                // 1.1 title ให้อยู่ล่างแม่ปกติ
//
//                // 1.2 text และ number ให้อยู่ล่างแม่ปกติ
//
//                // 1.3 checkbox ให้ไปอยู่ให้ทุก option ของแม่
//
//                // 1.4 radio ให้อยู่ล่างแม่ปกติ
//
//                // 2.ถ้าขึ้นกับแม่
//
//                // 2.1 ทั้ง checkbox และ radio ให้อยู่ล่าง option ของแม่ทั้งหมด
//
//                // TODO-nong ดูว่าถ้า parent มีค่า อาจจะไม่ hidden
//                if($grouped[$aQuestion->parent_id][0]->input_type===Question::TYPE_RADIO
//                    && is_null($aQuestion->dependent_parent_option_id)){
//                    $aQuestion->{"class"} = 'has-parent-no-dependent';
////                $aQuestion->{"class"} = 'hidden has-parent-no-dependent';
//                }else{
////                $aQuestion->{"class"} = 'hidden has-parent';
//                    $aQuestion->{"class"} = 'has-parent';
////                $aQuestion->{"parent_input_type"} = $grouped[$aQuestion->parent_id][0]->input_type;
//                }
//            }

            if(!is_null($aQuestion[0]->parent_id)){
                $typeArr = [Question::TYPE_TITLE, Question::TYPE_TEXT, Question::TYPE_NUMBER];
                $inArray = in_array($grouped[$aQuestion[0]->parent_id]->input_type, $typeArr);
                if($inArray){
                    if(!isset($grouped[$aQuestion[0]->parent_id]->{"children"})){
                        $grouped[$aQuestion[0]->parent_id]->{"children"} = [];
                    }
                    $aQuestion->{"class"} = "";
                    $grouped[$aQuestion[0]->parent_id]->{"children"}[$aQuestion[0]->id] = $aQuestion;
                }

                $type2Arr = [Question::TYPE_CHECKBOX, Question::TYPE_RADIO];
                $inArray2 = in_array($grouped[$aQuestion[0]->parent_id]->input_type, $type2Arr);
                if($inArray2){
                    $aQuestion->{"class"} = ' has-parent';
                    if (is_null($aQuestion->dependent_parent_option_id)){
                        if ($grouped[$aQuestion[0]->parent_id]->input_type===Question::TYPE_RADIO){
                            if(!isset($grouped[$aQuestion[0]->parent_id]->{"children"})){
                                $grouped[$aQuestion[0]->parent_id]->{"children"} = [];
                            }
                            $grouped[$aQuestion[0]->parent_id]->{"children"}[$aQuestion[0]->id] = $aQuestion;
                        }
                        else if ($grouped[$aQuestion[0]->parent_id]->input_type===Question::TYPE_CHECKBOX){
                            foreach ($grouped[$aQuestion[0]->parent_id] as $each_parent_option){
                                if (!isset($each_parent_option->{"children"})){
                                    $each_parent_option->{"children"} = [];
                                }
                                $each_parent_option->{"children"}[$aQuestion[0]->id] = $aQuestion;
                            }
                        }
                    }

                    if (!is_null($aQuestion->dependent_parent_option_id)){
                        foreach ($grouped[$aQuestion[0]->parent_id] as $each_parent_option){
                            $dependentArr = explode(",", $aQuestion->dependent_parent_option_id);
                            if (in_array($each_parent_option->option_id, $dependentArr)){
                                if (!isset($each_parent_option->{"children"})){
                                    $each_parent_option->{"children"} = [];
                                }
                                $each_parent_option->{"children"}[$aQuestion[0]->id] = $aQuestion;
                            }
                        }
                    }
                }

//                $grouped[$aQuestion[0]->parent_id]->{"children"}[] = [$aQuestion[0]->id=>$aQuestion];

                $forgetList[] = $aQuestion[0]->id;
            }
        }
        //for get in list
        foreach ($forgetList as $aId){
            $grouped->forget((string)$aId);
        }

//    dd($grouped);

//    dd($grouped);
        return view('welcome2', compact('grouped'));
    }
}
