<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Option;
use App\OptionQuestion;
use App\Question;
use Illuminate\Http\Request;

use App\Http\Requests;

class AnswerController extends Controller
{
    public function testPost(Request $request)
    {
        $input = $request->input();

        dd($request->input());
        // อาจจะต้องการ section ตรงนี้
        $questions = Question::where('section',$request->input('section'))->get();
        $oldAnswers = Answer::where('main_id',$request->input('main_id'))->get();
        $optionQuestions = OptionQuestion::get();

        // คำตอบของแต่ละคำถาม
        // 1. q_a_b_c คำถามที่ c ให้ดูว่า คำถาม a ถูกเลือก option b หรือไม่
        // 2. q_a__c คำถามที่ c ให้ดูว่า คำถาม a ถูกเลือกสัก option หรือไม่
        // 3. q___c ดูว่าค่าที่่สงมาไม่เป็นค่าว่าง

        //ดดยแต่ละคำถาม ถ้าเป้นประเภท option หรือประเภท text อาจจะง่ายหน่อยเพราะแทนที่ค่าเดิมได้
        // แต่กรณีที่ต้องขึ้นกับแม่ ให้ตรวจดูประเภทแม่
        // ของ เดิมมี a=1 b=1, a=1 b=2 แล้วของใหม่เป้น a=1 b=2 , a=1 b=3 อาจจะต้องลบ a=1 b1 ออกจากตาราง answers

        foreach ($input as $key=>$value){
            if(strpos($key,"q_")!==false && strpos($key,"other")===false){
                //q_2__3 => question id 3 when parent is 2 and dont care what choice parent is selected

                //q_10_21_11 => question id 11 when parent is 10 and selected 21

                $removed_q_key = explode("_", str_replace("q_","",$key));

                if(empty($removed_q_key[0])&&empty($removed_q_key[1])){
                    $input_type = $questions->where('id', $removed_q_key[2])->first()->input_type;
                    if ($input_type!==Question::TYPE_CHECKBOX){
                        $answerForThisQuestion = $oldAnswers->where('question_id',$removed_q_key[2])->first();
                        if(!is_null($answerForThisQuestion)){
                            $answerForThisQuestion = new Answer();
                            $answerForThisQuestion->main_id = $input['main_id'];
                            $answerForThisQuestion->question_id = $removed_q_key[2];
                        }

                        if ($input_type ===Question::TYPE_NUMBER){
                            $answerForThisQuestion->answer_numeric = (float)$value;
                        }
                        else if($input_type ===Question::TYPE_TEXT){
                            $answerForThisQuestion->answer_text = $value;
                        }
                        else if ($input_type ===Question::TYPE_RADIO){
                            $option_question_id = \DB::table('option_questions')
                                ->where('question',$removed_q_key[2])
                                ->where('option',$value)
                                ->get('id');
                            $answerForThisQuestion->option_question_id = $option_question_id[0]->id;
                        }
                    }
                    // ============ CHECKBOX TYPE
                    if ($input_type===Question::TYPE_CHECKBOX){
                        $answersForThisQuestion = $oldAnswers->where('question_id',$removed_q_key[2])->get();

                        $option_question_id_list = OptionQuestion::where('question_id',$removed_q_key)
                            ->whereIn('option_id',$value)
                            ->lists('id');

                        // ลบของเดิมที่ไม่มีใน answer ใหม่
                        $notFoundInNewAnswers = $oldAnswers->where('question_id',$removed_q_key[2])
                            ->whereNotIn('option_question_id',$option_question_id_list)
                            ->get();
                        //หาว่ามีลูกที่ขึ้นกับ option_id ปัจจุบันของแม่ให้เอาออก
                        foreach ($notFoundInNewAnswers as $deleteAnswer){
                            $current_option_id = $optionQuestions->where('id',$deleteAnswer->option_quesiton_id)
                                ->first()->option_id;
                            $childrenAnswers = $oldAnswers
                                ->where('parent_id', (int)$removed_q_key[2])
                                ->where('parent_option_selected_id',(int)$current_option_id)->get();
                            if ($childrenAnswers->count > 0){
                                foreach ($childrenAnswers as $childAnswer){
                                    $childAnswer->delete();
                                }
                            }
                            $deleteAnswer->delete();
                        }
                        // ใส่คำตอบใหม่ที่ได้
                        foreach ($value as $anOption){
                            
                            $option_question_id = $optionQuestions->where('question',$removed_q_key[2])
                                ->where('option',$anOption)
                                ->first()->id;

                            //ลบอันที่ไม่มีในคำตอบรอบใหม่ออก
                            //TODO-nong เกิดปัญหาว่าต้องลบลูกออกด้วยไหมเพราะ ถ้าลูกขึ้นกับ option นี้ของแม่

                        }
                    }


                    //TODO-nong ถ้าเป็น checkbox กับ radio
                    //TODO-nong ถ้าเป็น checkbox อาจจะต้องวนลูป
                    $option_question_id = \DB::table('option_questions')
                        ->where('question',$removed_q_key[2])
                        ->where('option',$value)
                        ->get('id');

                    $answerForThisQuestion->option_question_id = $option_question_id;

                }else{
                    $parent_question_id = (int)$removed_q_key[0];
                    $parent_option_id = empty($removed_q_key[1])?null:(int)$removed_q_key[1];
                    $question_id = (int)$removed_q_key[2];
                }
//                $question_id = (int)str_replace("q_","",$key);
                $option_id = (int)$value[0];
                $result = \DB::select("select id from option_questions 
                  where question_id=? and option_id=? ",array($question_id,$option_id));
                $OQid = (int)$result[0]->id;
                $otherText = null;
                if((int)$value[0]===Option::OTHER_OPTION){
                    $otherText = $input[$key."_other"];
                }
                // This one for single radio question
                $answer = Answer::where([
                    'main_id'=>1,
                    'question_id'=>$question_id
                ])->first();
                if(is_null($answer)){
                    $answer = new Answer();
                }
                $answer->fill([
                    'main_id'=>1,
                    'option_question_id'=>$OQid,
                    'question_id'=>$question_id,
                    'other_text'=>$otherText
                ]);
                $answer->save();
            }
        }

        dd($request->input());
    }

    protected function parentQuestionIsSelected($parent_id, $collection, $option_id=null)
    {
        foreach ($collection as $key=>$value){
            $splitArr = explode("_", str_replace("q_","", $key));
            if((int)$splitArr[2]===(int)$parent_id){
                if (!is_null($option_id)){
                    if (is_array($value))
                    {
                        foreach ($value as $parent_option){
                            if((int)$parent_option===(int)$option_id){
                                return true;
                            }
                        }
                        continue;
                    }
                    if ((int)$value===(int)$option_id){
                        return true;
                    }
                    continue;
                }
                return true;
            }
        }

        return false;
    }
}
