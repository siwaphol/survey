<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Custom\ChunkReadFilter;
use App\Menu;
use App\Question;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UploadRawController extends Controller
{
    public function index()
    {
        if (in_array(auth()->user()->email, array('test@email.com','boy.kittikun@gmail.com','aiw_w@hotmail.com','pimphram.setaphram@gmail.com'))){
            $menus = Menu::whereNull('parent_id')
                ->orderBy('order')
                ->get();
            return view('upload.index', compact('menus'));
        }else
            return abort(404);
    }

    protected function checkAnswerType($uniqueKey)
    {
        $explodedUK = explode("_",$uniqueKey);
        if (substr($explodedUK[count($explodedUK)-1],0,2)==="nu"){
            return Question::TYPE_NUMBER;
        }elseif (substr($explodedUK[count($explodedUK)-1],0,2)==="ra"){
            return Question::TYPE_RADIO;
        }elseif (substr($explodedUK[count($explodedUK)-1],0,1)==="o"){
            return Question::TYPE_CHECKBOX;
        }elseif (substr($explodedUK[count($explodedUK)-1],0,2)==="te"){
            return Question::TYPE_TEXT;s
        }

        return null;
    }

    public function uploadRaw(Request $request)
    {
        $path = storage_path('excel/view_mp_suggest.xlsx');

        $objReader = \PHPExcel_IOFactory::createReader("Excel2007");
        $objReader->setReadDataOnly(true);
        $worksheetData = $objReader->listWorksheetInfo($path);
        $totalRows = $worksheetData[0]['totalRows'];
        $totalColumns = $worksheetData[0]['totalColumns'];
        $chunkSize = 10000;
        $chunkFilter = new ChunkReadFilter();
        $objReader->setReadFilter($chunkFilter);



        for ($startRow = 2; $startRow <= $totalRows; $startRow += $chunkSize) {
            $chunkFilter->setRows($startRow,$chunkSize);

            $objPHPExcel = $objReader->load($path);

            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

            for($i=$startRow; $i <= ($startRow+$chunkSize-1); $i++) {
                if ($i > $totalRows) {
                    break;
                }

                //TODO-nong put loop for each unique_key here

                //TODO-nong this variable will be used to store unique_key
                $uniqueKey = "";
                //TOTO-nong this variable will store current column
                $curColumn = "B";
                if(is_numeric(trim($sheetData[$i]["A"]))){
                    $mainId = trim($sheetData[$i]["A"]);
                    $oldAnswer = Answer::where('main_id', $mainId)
                        ->where('unique_key', $uniqueKey)
                        ->first();

                    //TODO-nong อาจจะเกิดปัญหาว่าบางที uniqueKey ที่ใส่เข้ามาผิดแล้วจะทำให้ค่าที่ insert เข้าไปผิดไปด้วย
                    // 1. ตรวจว่าเคยมี uniqueKey นี้ในตาราง answer มั้ย?
                    if (is_null($oldAnswer)){
                        $oldAnswer = new Answer();
                        $oldAnswer->main_id = $mainId;
                        $oldAnswer->unique_key = $uniqueKey;
                    }

                    $newValue = $sheetData[$i][$curColumn];

                    // เปลี่ยนค่าตรงนี้
                    // ถ้า unique_key บอกว่าเป็น text หรือ number input ให้ใส่ค่าเข้าไปทันที
                    if ($this->checkAnswerType($uniqueKey)===Question::TYPE_TEXT){
                        $oldAnswer->answer_text = $newValue;
                    }elseif ($this->checkAnswerType($uniqueKey)===Question::TYPE_NUMBER){
                        $oldAnswer->answer_numeric = (float)$newValue;
                    }elseif ($this->checkAnswerType($uniqueKey)===Question::TYPE_RADIO){
                        //TODO-nong get list of all option that come with this radio
                        // and find if text in newValue match any option name
                        // then insert that option_id
                    }elseif ($this->checkAnswerType($uniqueKey)===Question::TYPE_CHECKBOX){

                    }

                    $oldAnswer->save();

                }
            }
        }
    }
}
