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
//        \DB::transaction(function () use ($worksheetData,$chunkFilter,$objReader,$chunkSize,$path) {
//            $totalRows = $worksheetData[2]['totalRows'];
//            for ($startRow = 2; $startRow <= $totalRows; $startRow += $chunkSize) {
//                $chunkFilter->setRows($startRow,$chunkSize);
//
//                $objPHPExcel = $objReader->load($path);
//
//                $sheetData = $objPHPExcel
//                    ->getSheet(2)
//                    ->toArray(null,true,true,true);
//
//                for($i=$startRow; $i <= ($startRow+$chunkSize-1); $i++) {
//                    if ($i > $totalRows) {
//                        break;
//                    }
//                    $option = Option::findOrNew((int)$sheetData[$i]["A"]);
//                    $option->id = (int)$sheetData[$i]["A"];
//                    $option->name = is_null($sheetData[$i]["B"])?'':$sheetData[$i]["B"];
//                    $option->save();
//                }
//            }
//        });
//        dd('options finish');

        // sheet 1 question
//        \DB::transaction(function ()use($worksheetData,$chunkFilter,$objReader,$chunkSize,$path) {
//            $totalRows = $worksheetData[1]['totalRows'];
//            for ($startRow = 2; $startRow <= $totalRows; $startRow += $chunkSize) {
//                $chunkFilter->setRows($startRow,$chunkSize);
//
//                $objPHPExcel = $objReader->load($path);
//
//                $sheetData = $objPHPExcel
//                    ->getSheet(1)
//                    ->toArray(null,true,true,true);
//
//                for($i=$startRow; $i <= ($startRow+$chunkSize-1); $i++) {
//                    if ($i > $totalRows) {
//                        break;
//                    }
//                    $question = Question::findOrNew((int)$sheetData[$i]["A"]);
//                    $question->id = (int)$sheetData[$i]["A"];
//                    $question->parent_id = $sheetData[$i]["B"]==='NULL'?null:(int)$sheetData[$i]["B"];
//                    $question->sibling_order = (int)$sheetData[$i]["C"];
//                    $question->dependent_parent_option_id = $sheetData[$i]["D"]==='NULL'?null:$sheetData[$i]["D"];
//                    $question->section = $sheetData[$i]["E"];
//                    $question->sub_section = $sheetData[$i]["F"];
//                    $question->input_type = $sheetData[$i]["G"];
//                    $question->text = $sheetData[$i]["H"];
//                    $question->unit_of_measure = $sheetData[$i]["I"]==='NULL'?null:$sheetData[$i]["I"];
//                    $question->save();
//                }
//            }
//        });
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
}
