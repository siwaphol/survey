<?php

Route::group(['middleware' => 'auth'], function () {
    Route::get('/',function(){
        return redirect('main');
    });

    Route::get('main', 'MainController@index');
    Route::post('main', 'MainController@postHandle');

    Route::get('html-loop-2/{id}', 'QuestionController@htmlLoop');
    Route::get('html-loop-2/{id}/{sub}', 'QuestionController@htmlLoop');
    Route::post('test-post','AnswerController@testPost');
    Route::post('test-post-2','AnswerController@saveAnswersWithEasiestWay');
});

Route::get('angular-material', function(){
    return view('angular_material_main');
});
Route::get('import-excel','QuestionController@importExcelQuestion');
Route::auth();
