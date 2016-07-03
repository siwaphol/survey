<?php
Route::get('/',function(){
    return redirect('html-loop-2/1');
});
Route::post('test-post','AnswerController@testPost');
Route::post('test-post-2','AnswerController@saveAnswersWithEasiestWay');
Route::get('import-excel','QuestionController@importExcelQuestion');
Route::get('html-loop-2/{id}', 'QuestionController@htmlLoop');
Route::get('html-loop-2/{id}/{sub}', 'QuestionController@htmlLoop');