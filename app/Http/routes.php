<?php

Route::group(['middleware' => 'auth'], function () {
    Route::get('/',function(){
        return redirect('dashboard');
    });

    Route::get('dashboard', 'HomeController@dashboard');

    Route::get('main', 'MainController@index');
    Route::post('main', 'MainController@postHandle');

    Route::post('filter-main', 'MainController@filter');

    Route::get('html-loop-2/{id}', 'QuestionController@htmlLoop');
    Route::get('html-loop-2/{id}/{sub}', 'QuestionController@htmlLoop');
    Route::post('test-post','AnswerController@testPost');
    Route::post('test-post-2','AnswerController@saveAnswersWithEasiestWay');
    Route::post('test-post-3','AnswerController@saveAnswersWithEasiestWay2');

    Route::get('report', 'SummaryController@index');
    Route::get('get-report911', 'SummaryController@report911');
    Route::get('get-report912', 'SummaryController@report912');
    // หมวดความสะดวกสบาย
    Route::get('get-report913', 'SummaryController@report913');
    Route::get('get-report914', 'SummaryController@report914');
    Route::get('get-report915', 'SummaryController@report915');
    Route::get('get-report916', 'SummaryController@report916');

    Route::get('download911', function (){
        return response()->download(storage_path('excel/sum911.xlsx'), 'ตารางสรุปหมวดแสงสว่าง.xlsx');
    });
});

Route::group(['prefix'=>'api'], function (){
    Route::get('menus','MenuController@index');
});

//Route::get('import-excel','QuestionController@importExcelQuestion');
Route::auth();
