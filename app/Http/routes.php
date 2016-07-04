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


//Route::get('login', function(){
//    return view('login');
//});
//Route::get('import-excel','QuestionController@importExcelQuestion');
Route::auth();
