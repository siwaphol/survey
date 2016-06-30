<?php

Route::post('test-post','AnswerController@testPost');
Route::get('import-excel','QuestionController@importExcelQuestion');
Route::get('html-loop-2/{id}', 'QuestionController@htmlLoop');