<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Option;
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

        foreach ($input as $key=>$value){
            if(strpos($key,"q_")!==false && strpos($key,"other")===false){
                //q_2__3 => question id 3 when parent is 2 and dont care what choice parent is selected

                //q_10_21_11 => question id 11 when parent is 10 and selected 21

                $removed_q_key = explode("_", str_replace("q_","",$key));
                if(count($removed_q_key)===1){
                    $question_id = (int)$removed_q_key[0];
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
