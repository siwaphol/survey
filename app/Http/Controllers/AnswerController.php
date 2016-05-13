<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Option;
use Illuminate\Http\Request;

use App\Http\Requests;

class AnswerController extends Controller
{
    public function testPost(Request $request)
    {
        $input = $request->input();

        // อาจจะต้องการ section ตรงนี้

        foreach ($input as $key=>$value){
            if(strpos($key,"q_")!==false && strpos($key,"other")===false){
                $question_id = (int)str_replace("q_","",$key);
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
}
