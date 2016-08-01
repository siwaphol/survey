<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Main;
use Illuminate\Http\Request;

use App\Http\Requests;

class MainController extends Controller
{
    public function index()
    {
        $mainList = Main::orderBy('mains.updated_at','desc')
            ->leftJoin('users', 'mains.recorder_id','=','users.id')
            ->select('mains.*','users.name')
            ->get();

        $dupMainId = [];
        foreach ($mainList as $key => $model) {
            if (in_array($model->main_id, $dupMainId)){
                unset($mainList[$key]);
                continue;
            }
            $dupMainId[] = $model->main_id;
        }

        return view('main_id_input', compact('mainList'));
    }

    public function postHandle(Request $request)
    {
        $user_id = \Auth::user()->id;
        $main = Main::where([
            'main_id'=>$request->input('main_id'),
            'recorder_id'=>$user_id
        ])->first();
        if (is_null($main)){
            $main = Main::create([
                'main_id'=>$request->input('main_id'),
                'recorder_id'=>$user_id
            ]);

            $mainId = $request->input('main_id');
            $newMain = Main::where('main_id', $mainId)
                ->count();
            if((int)$newMain===1){ // newly created main
                // predefined answer ตั้งแต่ ค.1 ให้เลือก ไม่มี ทั้งหมด
                Answer::create(['main_id'=>$mainId, 'section'=>'ค.1 หมวดแหล่งเชื้อเพลิงที่หาเองได้', 'sub_section'=>'NULL', 'option_question_id'=>'486', 'question_id'=>'800', 'unique_key'=>'no_ra800', 'option_id'=>82, 'section_id'=> 25, 'sub_section_id'=>null]);
                Answer::create(['main_id'=>$mainId, 'section'=>'ค.2 หมวดแหล่งเชื้อเพลิงที่ซื้อ', 'sub_section'=>'NULL', 'option_question_id'=>'499', 'question_id'=>'808', 'unique_key'=>'no_ra808', 'option_id'=>82, 'section_id'=> 26, 'sub_section_id'=>null]);
                Answer::create(['main_id'=>$mainId, 'section'=>'ง.1 การเผาถ่าน', 'sub_section'=>'NULL', 'option_question_id'=>'536', 'question_id'=>'700', 'unique_key'=>'no_ra700', 'option_id'=>82, 'section_id'=> 27, 'sub_section_id'=>null]);
                Answer::create(['main_id'=>$mainId, 'section'=>'ง.2 การผลิตก๊าซชีวภาพ', 'sub_section'=>'NULL', 'option_question_id'=>'551', 'question_id'=>'716', 'unique_key'=>'no_ra716', 'option_id'=>244, 'section_id'=> 28, 'sub_section_id'=>null]);
                Answer::create(['main_id'=>$mainId, 'section'=>'ง.3 เครื่องปั่นไฟ', 'sub_section'=>'NULL', 'option_question_id'=>'561', 'question_id'=>'729', 'unique_key'=>'no_ra729', 'option_id'=>250, 'section_id'=> 29, 'sub_section_id'=>null]);
                Answer::create(['main_id'=>$mainId, 'section'=>'ง.4 การผลิตไฟฟ้าด้วยเซลล์แสงอาทิตย์', 'sub_section'=>'NULL', 'option_question_id'=>'572', 'question_id'=>'735', 'unique_key'=>'no_ra735', 'option_id'=>82, 'section_id'=> 30, 'sub_section_id'=>null]);
                Answer::create(['main_id'=>$mainId, 'section'=>'จ.1 แนวโน้มการเปลี่ยนการใช้พลังงานในการประกอบอาหาร', 'sub_section'=>'NULL', 'option_question_id'=>'584', 'question_id'=>'900', 'unique_key'=>'no_ra900', 'option_id'=>263, 'section_id'=> 31, 'sub_section_id'=>null]);
                Answer::create(['main_id'=>$mainId, 'section'=>'จ.2 แนวโน้มการเปลี่ยนการใช้พลังงานในการเดินทางและคมนาคม', 'sub_section'=>'NULL', 'option_question_id'=>'611', 'question_id'=>'904', 'unique_key'=>'no_ra904', 'option_id'=>263, 'section_id'=> 32, 'sub_section_id'=>null]);
                Answer::create(['main_id'=>$mainId, 'section'=>'จ.3 แนวโน้มการเปลี่ยนการใช้ยานพาหนะในการเดินทาง', 'sub_section'=>'NULL', 'option_question_id'=>'640', 'question_id'=>'908', 'unique_key'=>'no_ra908', 'option_id'=>263, 'section_id'=> 33, 'sub_section_id'=>null]);
            }
        }

        \Session::put('main_id', $main->main_id);
        \Session::put('recorder_id', $user_id);

        return redirect('html-loop-2/1');
    }
}
