<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Custom\ChunkReadFilter;
use App\Menu;
use App\OptionQuestion;
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
            return Question::TYPE_TEXT;
        }

        return null;
    }

    public function uploadRaw(Request $request)
    {
        $path = storage_path('excel/test_upload.xlsx');

        $objReader = \PHPExcel_IOFactory::createReader("Excel2007");
        $objReader->setReadDataOnly(true);
        $worksheetData = $objReader->listWorksheetInfo($path);
        $totalRows = $worksheetData[0]['totalRows'];
        $totalColumns = $worksheetData[0]['totalColumns'];
        $chunkSize = 10000;
        $chunkFilter = new ChunkReadFilter();
        $objReader->setReadFilter($chunkFilter);

        $errors = array();

        for ($startRow = 3; $startRow <= $totalRows; $startRow += $chunkSize) {
            $chunkFilter->setRows($startRow,$chunkSize);
            $objPHPExcel = $objReader->load($path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

            // loop ไปทีละคอลัมป์ เพราะดูเหมือนว่าตอนนี้มีการ query หลายครั้ง ที่ไม่จำเป็น ถ้าวน row ก่อนวน column
            // แถวแรกต้องเป็น unique_key

            $curColumn = "B";
            // column loop each unique_key
            while (isset($sheetData[1][$curColumn])&&!empty(trim($sheetData[1][$curColumn]))){

                $uniqueKey = trim($sheetData[1][$curColumn]);
                // อาจจะเกิดปัญหาว่าบางที uniqueKey ที่ใส่เข้ามาผิดแล้วจะทำให้ค่าที่ insert เข้าไปผิดไปด้วย
                // อาจจะแก้ปัญหาโดยการหาว่ามี unique_key นี้ในระบบหรือไม่
                // หาในตาราง answers ว่ามี unique_key นี้มั้ย ถ้าไม่มีก็ ข้ามคอลัมน์ปัจจุบันไปทำคอลัมน์ถัดไป
                $uniqueKeyAnswer = Answer::where('unique_key', $uniqueKey)->first();
                if (is_null($uniqueKeyAnswer)){
                    $errors[] = 'Not found unique key ' . $uniqueKey .' in system, please contact administrator';
                    $curColumn++;
                    continue;
                }

                if ($this->checkAnswerType($uniqueKey)===Question::TYPE_RADIO){
                    // ** this one is tricky เพราะ 1 radio สามารถมีได้หลาย option_id
                    // get list of all option that come with this radio
                    // and find if text in newValue match any option name
                    // then insert that option_id
                    $explodedUK = explode("_",$uniqueKey);
                    $radioId = filter_var($explodedUK[count($explodedUK)-1],FILTER_SANITIZE_NUMBER_INT );
                    $curRadioOptions = OptionQuestion::where('question_id', $radioId)
                        ->leftJoin('options','option_questions.option_id','=','options.id')
                        ->select(\DB::raw('options.id as option_id, options.name as option_name'))
                        ->get();
                }

                // row loop
                for($i=$startRow; $i <= ($startRow+$chunkSize-1); $i++) {
                    if ($i > $totalRows) {
                        break;
                    }

                    if(is_numeric(trim($sheetData[$i]["A"]))){

                        $newValue = $sheetData[$i][$curColumn];
                        $mainId = trim($sheetData[$i]["A"]);

                        // ถ้าค่าที่ใส่มาเป็นช่องเปล่าข้ามไปแถวต่อไป
                        if (empty($newValue)){
                            if ($this->checkAnswerType($uniqueKey)===Question::TYPE_CHECKBOX) {
                                //TODO-nong this is slow เพราะถ้ามี checkbox หลายๆอันมันจะ query เท่ากับจำนวนชุดสอบถามเลย
                                $oldAnswer = Answer::where('main_id', $mainId)
                                    ->where('unique_key', $uniqueKey)
                                    ->first();
                                if ($oldAnswer)
                                    $oldAnswer->delete();
                            }
                            continue;
                        }

                        $oldAnswer = Answer::where('main_id', $mainId)
                            ->where('unique_key', $uniqueKey)
                            ->first();

                        if (is_null($oldAnswer)){
                            $oldAnswer = new Answer();
                            $oldAnswer->main_id = $mainId;
                            $oldAnswer->unique_key = $uniqueKey;
                            $oldAnswer->section_id = $uniqueKeyAnswer->section_id;
                            $oldAnswer->sub_section_id = $uniqueKeyAnswer->sub_section_id;
                            $oldAnswer->option_question_id = $uniqueKeyAnswer->option_question_id;
                            $oldAnswer->question_id = $uniqueKeyAnswer->question_id;
                        }

                        //TODO-nong ตอนนี้คิดว่าควรจะเลือก ตัวแม่ กรณีที่ เป็นความสัมพันธ์ แบบมีตัวแม่หรือไม่ ซึ่งต้องไปดูการคำนวณ ถ้าไม่จำเป็นต้องใช้ตัวแม่ก็น่าจะทำได้
                        // เปลี่ยนค่าตรงนี้
                        // ถ้า unique_key บอกว่าเป็น text หรือ number input ให้ใส่ค่าเข้าไปทันที
                        if ($this->checkAnswerType($uniqueKey)===Question::TYPE_TEXT){
                            $oldAnswer->answer_text = $newValue;
                        }elseif ($this->checkAnswerType($uniqueKey)===Question::TYPE_NUMBER){
                            $oldAnswer->answer_numeric = (float)$newValue;
                        }elseif ($this->checkAnswerType($uniqueKey)===Question::TYPE_RADIO){
                            // หาว่าค่าที่รับเข้ามาอยู่ใน option ไหน
                            foreach ($curRadioOptions as $item){
                                if (!empty(trim($newValue)) && trim($item->option_name)===trim($newValue)){
                                    $oldAnswer->option_id = $item->option_id;
                                    break;
                                }
                            }
                        }elseif ($this->checkAnswerType($uniqueKey)===Question::TYPE_CHECKBOX){
                            if (!empty(trim($newValue)) && (int)trim($newValue)===1){
                                $oldAnswer->option_id = $uniqueKeyAnswer->option_id;
                            }
                        }

                        $oldAnswer->save();
                    }
                }
                $curColumn++;
            }
        }

        return json_encode(array('success'=>true, 'errors'=>$errors));
    }
}
