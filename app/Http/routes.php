<?php

Route::group(['middleware' => 'auth'], function () {
    Route::get('/',function(){
        return redirect('dashboard');
    });

    Route::get('dashboard', 'HomeController@dashboard');

    Route::get('main', 'MainController@index');
    Route::post('main', 'MainController@postHandle');

    Route::get('setting', 'SettingController@index');
    Route::get('setting/create', 'SettingController@create');
    Route::get('setting/{id}', 'SettingController@edit');
    Route::put('setting/{id}', 'SettingController@update');
    Route::post('setting','SettingController@store');

    Route::post('filter-main', 'MainController@filter');
    Route::get('export', 'FilterExportController@index');
    Route::get('test-export', 'FilterExportController@export');
    Route::get('test-export-2', 'FilterExportController@exportWithSheetLoop');

    Route::get('filter', 'FilterSelectionController@index');
    Route::get('test-export-3/{section_id}', 'FilterSelectionController@testExport');
    Route::get('test-export-3/{section_id}/{sub_section_id}', 'FilterSelectionController@testExport');

    Route::get('upload', 'UploadRawController@index');
    Route::get('test-upload', 'UploadRawController@uploadRaw');

    Route::get('html-loop-2/{id}', 'QuestionController@htmlLoop');
    Route::get('html-loop-2/{id}/{sub}', 'QuestionController@htmlLoop');
    Route::post('test-post','AnswerController@testPost');
    Route::post('test-post-2','AnswerController@saveAnswersWithEasiestWay');
    Route::post('test-post-3','AnswerController@saveAnswersWithEasiestWay2');

    Route::get('report', 'SummaryController@index');
    Route::get('download/{menu_id}', 'SummaryController@download');
    Route::get('get-report81', 'SummaryController@downloadSum81');
    Route::get('get-report82', 'SummaryController@downloadSum82');
    Route::get('get-report83', 'SummaryController@downloadSum83');
    Route::get('get-report84', 'SummaryController@downloadSum84');
    Route::get('get-report85', 'SummaryController@downloadSum85');
    Route::get('get-report911', 'SummaryController@downloadSum911');

    Route::get('get-report912/{order}', 'SummaryController@downloadSum912');
    // หมวดความสะดวกสบาย
    Route::get('get-report913', 'SummaryController@downloadSum913');
    Route::get('get-report914', 'SummaryController@downloadSum914');
    Route::get('get-report915', 'SummaryController@downloadSum915');
    Route::get('get-report916', 'SummaryController@downloadSum916');
    Route::get('get-report917', 'SummaryController@downloadSum917');
    Route::get('get-report918', 'SummaryController@downloadSum918');

    Route::get('download911', function (){
        return response()->download(storage_path('excel/sum911.xlsx'), 'ตารางสรุปหมวดแสงสว่าง.xlsx');
    });

    Route::get('get-report11-1', 'Summary11Controller@downloadSum11_1');
    Route::get('get-report11-2', 'Summary11Controller@downloadSum11_2');

    Route::get('get-report121', 'SummaryController@downloadSum121');
    Route::get('get-report122', 'SummaryController@downloadSum122');
    Route::get('get-report123', 'SummaryController@downloadSum123');
    Route::get('get-report124', 'SummaryController@downloadSum124');

    Route::get('get-report131', 'SummaryController@downloadSum131');
    Route::get('get-report132', 'SummaryController@downloadSum132');
    Route::get('get-report133', 'SummaryController@downloadSum133');

    Route::get('echo-report', function(){
        \App\EchoSummary::report911Test();
    });

    Route::group(['prefix'=>'report'], function(){
        Route::get('tool_electric/{tool_number}', 'Summary9ByToolElectric@reportTool');
        Route::get('tool_renew/{tool_number}', 'Summary9ByToolRenewEnergy@reportTool');
        Route::get('tool_fuel/{tool_number}', 'Summary9ByToolFuel@reportTool');
    });
});

Route::get('phpinfo', function(){
   return phpinfo();
});

Route::group(['prefix'=>'api'], function (){
    Route::get('menus','MenuController@index');
});

//Route::get('import-excel','QuestionController@importExcelQuestion');
Route::get('import-parameter', 'SettingController@importExcelSettingParameter');
Route::auth();
Route::get('test-sum',function(){
   \App\Http\Controllers\Summary9\Summary91::report917();
});

Route::get('test-sum2',function(){
    app('App\Http\Controllers\SummaryController')->report912();
});