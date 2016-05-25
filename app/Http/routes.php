<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    $str = "select 
        t1.id,
        t1.parent_id,
        t1.sibling_order,
        t1.section,
        t1.input_type,
        t1.name,
        t1.subtext,
        t1.required,
        t1.dependent_question_id,
        t1.dependent_parent_option_id,
        t3.id as option_id,
        t3.name as option_name
        from questions t1
        LEFT JOIN option_questions t2
        on t1.id=t2.question_id
        LEFT JOIN options t3
        on t2.option_id=t3.id
        ORDER BY t1.parent_id,t1.sibling_order ";
    $result = DB::select($str);
    $t = collect($result);
    $grouped = $t->groupBy('id');

    $template = '{"schema": {"type": "object","properties": {}},'.
    '"options":{"fields":{}}}';

    $returnJson = json_decode($template);

    $grouped->each(function($item,$key)use(&$returnJson){
        $newAttr = "question_".$key;
        $firstItem = $item->first();

        $newProperty = new stdClass();
        $newProperty->type = "text";
        $newProperty->enum = [];

        $newField = new stdClass();
        $newField->label = $firstItem->name .' '. (!empty($firstItem->subtext)?'('.$firstItem->subtext.')':'');
        $newField->type = "radio";
//        $newField->vertical = false;
        $newField->sort = false;
        $newField->fieldClass = "text-left";
        // only show this question when parent is selected
        if(!is_null($firstItem->parent_id)){
            $newField->fieldClass .= " hidden";
        }
//        $newField->optionLabels = [];
        $newField->optionLabels = new stdClass();

        foreach ($item as $row){
            $newProperty->enum[] = "option_" . $row->option_id;

//            $newField->optionLabels[] = $row->option_name;
            $newField->optionLabels->{"option_".$row->option_id} = $row->option_name;
        }

        $returnJson->schema->properties->{$newAttr} = $newProperty;
        $returnJson->options->fields->{$newAttr} = $newField;
    });



//    dd($returnJson);
    $json = json_encode($returnJson);

//    dd($json);

    return view('welcome', compact('result','json'));
});

Route::get('html-loop/{id}', function($id){
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
    $result = DB::select($str);

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
        if (!is_null($aQuestion->parent_id)){
            // TODO-nong ดูว่าถ้า parent มีค่า อาจจะไม่ hidden
            if($grouped[$aQuestion->parent_id][0]->input_type===\App\Question::TYPE_TITLE){
                $aQuestion->{"class"} = "";
            }
            else if($grouped[$aQuestion->parent_id][0]->input_type===\App\Question::TYPE_RADIO
            && is_null($aQuestion->dependent_parent_option_id)){
                $aQuestion->{"class"} = 'has-parent-no-dependent';
//                $aQuestion->{"class"} = 'hidden has-parent-no-dependent';
            }else{
//                $aQuestion->{"class"} = 'hidden has-parent';
                $aQuestion->{"class"} = 'has-parent';
//                $aQuestion->{"parent_input_type"} = $grouped[$aQuestion->parent_id][0]->input_type;
            }
        }

        if(!is_null($aQuestion[0]->parent_id)){
            $grouped[$aQuestion[0]->parent_id]->{"children"}[] = [$aQuestion[0]->id=>$aQuestion];
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
});

Route::post('test-post','AnswerController@testPost');

Route::get('import-excel','QuestionController@importExcelQuestion');

Route::get('html-loop-2/{id}', 'QuestionController@htmlLoop');