<?php

Route::post('test-post','AnswerController@testPost');
Route::post('test-post-2','AnswerController@saveAnswersWithEasiestWay');
Route::get('import-excel','QuestionController@importExcelQuestion');
Route::get('html-loop-2/{id}', 'QuestionController@htmlLoop');