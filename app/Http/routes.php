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
    Route::get('test-get-report', 'SummaryController@testReport');
    Route::get('test-get-report2', 'SummaryController@test2');
});

Route::group(['prefix'=>'api'], function (){
    Route::get('menus','MenuController@index');
});

Route::get('import-excel','QuestionController@importExcelQuestion');
Route::auth();

Route::get('test-sum', 'SummaryController@sum');
Route::get('test-average', 'SummaryController@average');
Route::get('test-usage', 'SummaryController@usage');
Route::get('test-avg2', 'SummaryController@average2');
Route::get('test-page', function(){
    app('App\Http\Controllers\SummaryController')->test2();
});
