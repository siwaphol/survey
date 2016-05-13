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

Route::get('html-loop', function(){
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
        t3.name as option_name,
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
        ORDER BY t1.id,t1.parent_id,t1.sibling_order,t2.id ";
    $result = DB::select($str);

//    dd($result);
    $t = collect($result);
    $grouped = $t->groupBy('id');

//    dd($grouped);

//    dd($grouped);
    return view('welcome2', compact('grouped'));
});

Route::post('test-post','AnswerController@testPost');