<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary11Controller extends Controller
{
    public function downloadSum11_1()
    {
        Summary11Controller::report11_1();
        return response()->download(storage_path('excel/sum11_1.xlsx'), '11.1 แหล่งพลังงานที่หาเองได้.xlsx');
    }
    public function downloadSum11_2()
    {
        Summary11Controller::report11_2();
        return response()->download(storage_path('excel/sum11_2.xlsx'), '11.2 แหล่งพลังงานที่ซื้อ.xlsx');
    }
    public static function report11_1()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary11.xlsx';
        $inputSheet = '11.1';
        $outputFile = 'sum11_1.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ra800_o81_ti801_ch802_o266_ra803'=>[221,222,223,224],
            'no_ra800_o81_ti801_ch802_o267_ra803'=>[221,222,223,224],
            'no_ra800_o81_ti801_ch802_o268_ra803'=>[221,222,223,224],
            'no_ra800_o81_ti801_ch802_o269_ra803'=>[221,222,223,224],
            'no_ra800_o81_ti801_ch802_o1_ra803'=>[221,222,223,224]
        ];
        $startColumn = [
            Main::NORTHERN=>'C',
            Main::NORTHERN_INNER=> 'M',
            Main::NORTHERN_OUTER=>'W'];
        $startRow = 10;
        $objPHPExcel = Summary11Controller::sum($table1, $startColumn,$startRow,$objPHPExcel,$mainObj);

        $table7 = [
            'no_ra800_o81_ti801_ch802_o266_nu806',
            'no_ra800_o81_ti801_ch802_o267_nu806',
            'no_ra800_o81_ti801_ch802_o268_nu806',
            'no_ra800_o81_ti801_ch802_o269_nu806',
            'no_ra800_o81_ti801_ch802_o1_nu806'
        ];
        $objPHPExcel = Summary::average($table7,'BK',11,$objPHPExcel,$mainObj);

        $table8 = [
            'no_ra800_o81_ti801_ch802_o266_nu805',
            'no_ra800_o81_ti801_ch802_o267_nu805',
            'no_ra800_o81_ti801_ch802_o268_nu805',
            'no_ra800_o81_ti801_ch802_o269_nu805',
            'no_ra800_o81_ti801_ch802_o1_nu805'
        ];
        $objPHPExcel = Summary11Controller::average($table8, 'BY', 11,$objPHPExcel,$mainObj, $table7);

        $startColumn = 'CM';
        $isRadio = true;
        $table9_1 = [
            ['no_ra800_o81_ti801_ch802_o266_ra804'=>225],
            ['no_ra800_o81_ti801_ch802_o266_ra804'=>226]
        ];
        $startRow = 12;
        $objPHPExcel = Summary::sum($table9_1,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);
        $table9_2 = [
            ['no_ra800_o81_ti801_ch802_o267_ra804'=>225],
            ['no_ra800_o81_ti801_ch802_o267_ra804'=>226]
        ];
        $startRow = 15;
        $objPHPExcel = Summary::sum($table9_2,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);
        $table9_3 = [
            ['no_ra800_o81_ti801_ch802_o268_ra804'=>225],
            ['no_ra800_o81_ti801_ch802_o268_ra804'=>226]
        ];
        $startRow = 18;
        $objPHPExcel = Summary::sum($table9_3,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);
        $table9_4 = [
            ['no_ra800_o81_ti801_ch802_o269_ra804'=>225],
            ['no_ra800_o81_ti801_ch802_o269_ra804'=>226]
        ];
        $startRow = 21;
        $objPHPExcel = Summary::sum($table9_4,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);
        $table9_5 = [
            ['no_ra800_o81_ti801_ch802_o1_ra804'=>225],
            ['no_ra800_o81_ti801_ch802_o1_ra804'=>226]
        ];
        $startRow = 24;
        $objPHPExcel = Summary::sum($table9_5,$startColumn,$startRow,$objPHPExcel,$mainObj,$isRadio);

        $table10 = [
            'no_ra800_o81_ti801_ch802_o266_nu807',
            'no_ra800_o81_ti801_ch802_o267_nu807',
            'no_ra800_o81_ti801_ch802_o268_nu807',
            'no_ra800_o81_ti801_ch802_o269_nu807',
            'no_ra800_o81_ti801_ch802_o1_nu807'
        ];
        $startColumn = "DA";
        $startRow = 11;
        $objPHPExcel = Summary::average($table10,$startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return true;
    }

    public static function report11_2()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary11.xlsx';
        $inputSheet = '11.2';
        $outputFile = 'sum11_2.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ra808_o81_ti829_ch830_o266_ra831'=>[326,327,328],
            'no_ra808_o81_ti829_ch830_o267_ra831'=>[326,327,328],
            'no_ra808_o81_ti829_ch830_o268_ra831'=>[326,327,328],
            'no_ra808_o81_ti829_ch830_o269_ra831'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o228_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o229_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o230_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o231_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o232_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o233_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o234_ra811'=>[326,327,328],
            'no_ra808_o81_ti809_ch810_o235_ra811'=>[326,327,328],
            'no_ra808_o81_ti821_ch822_o236_ra823'=>[326,327,328],
            'no_ra808_o81_ti829_ch830_o1_ra831'=>[326,327,328]
        ];

        $startColumn = [
            Main::NORTHERN=>'C',
            Main::NORTHERN_INNER=> 'K',
            Main::NORTHERN_OUTER=>'S'];
        $startRow = 10;
        $objPHPExcel = Summary11Controller::sum($table1, $startColumn,$startRow,$objPHPExcel,$mainObj);

        // ครั้งต่อเดือน
        $table11_9_1 = [
            'no_ra808_o81_ti829_ch830_o266_nu834',
            'no_ra808_o81_ti829_ch830_o267_nu834',
            'no_ra808_o81_ti829_ch830_o268_nu834',
            'no_ra808_o81_ti829_ch830_o269_nu834',
            ['no_ra808_o81_ti809_ch810_o228_ch3000_o281_nu3002', 'no_ra808_o81_ti809_ch810_o228_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o228_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o229_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o229_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o229_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o230_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o230_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o230_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o231_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o231_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o231_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o232_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o232_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o232_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o233_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o233_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o233_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o234_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o234_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o234_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o235_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o235_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o235_ch3000_o283_nu3002'],
            'no_ra808_o81_ti829_ch830_o1_nu834'
        ];
        $startColumn = "AY";
        $startRow = 11;
        $objPHPExcel = Summary::average($table11_9_1,$startColumn,$startRow,$objPHPExcel,$mainObj,false,[],true);
        // ถังต่อปี
        $table11_9_2 =[
            ['no_ra808_o81_ti821_ch822_o236_ch825_o208_nu826','no_ra808_o81_ti821_ch822_o236_ch825_o209_nu826','no_ra808_o81_ti821_ch822_o236_ch825_o210_nu826']
        ];
        $startRow = 23;
        $objPHPExcel = Summary::average($table11_9_2,$startColumn,$startRow,$objPHPExcel,$mainObj);
        $table11_9_3 = [
            'no_ra808_o81_ti829_ch830_o1_nu834'
        ];
        $startRow = 24;
        $objPHPExcel = Summary::average($table11_9_3,$startColumn,$startRow,$objPHPExcel,$mainObj, false,[], true);

        // ตารางที่ 11.10
        // แบบคูณตรงๆ
        $table11_10_1 = [
            'no_ra808_o81_ti829_ch830_o266_nu833', 'no_ra808_o81_ti829_ch830_o267_nu833', 'no_ra808_o81_ti829_ch830_o268_nu833', 'no_ra808_o81_ti829_ch830_o269_nu833'
        ];
        $multiplier11_10_1 = ['no_ra808_o81_ti829_ch830_o266_nu834',            'no_ra808_o81_ti829_ch830_o267_nu834',            'no_ra808_o81_ti829_ch830_o268_nu834', 'no_ra808_o81_ti829_ch830_o269_nu834',
        ];
        $startColumn = 'BN';
        $startRow = 11;
        $objPHPExcel = Summary11Controller::average($table11_10_1,$startColumn,$startRow,$objPHPExcel,$mainObj,$multiplier11_10_1,true);
        // บาทต่อครั้ง
        $table11_10_2 = [
            ['no_ra808_o81_ti809_ch810_o228_ch3000_o281_nu3001', 'no_ra808_o81_ti809_ch810_o228_ch3000_o282_nu3001', 'no_ra808_o81_ti809_ch810_o228_ch3000_o283_nu3001'],
            ['no_ra808_o81_ti809_ch810_o229_ch3000_o281_nu3001', 'no_ra808_o81_ti809_ch810_o229_ch3000_o282_nu3001', 'no_ra808_o81_ti809_ch810_o229_ch3000_o283_nu3001'],
            ['no_ra808_o81_ti809_ch810_o230_ch3000_o281_nu3001', 'no_ra808_o81_ti809_ch810_o230_ch3000_o282_nu3001', 'no_ra808_o81_ti809_ch810_o230_ch3000_o283_nu3001'],
            ['no_ra808_o81_ti809_ch810_o231_ch3000_o281_nu3001', 'no_ra808_o81_ti809_ch810_o231_ch3000_o282_nu3001', 'no_ra808_o81_ti809_ch810_o231_ch3000_o283_nu3001'],
            ['no_ra808_o81_ti809_ch810_o232_ch3000_o281_nu3001', 'no_ra808_o81_ti809_ch810_o232_ch3000_o282_nu3001', 'no_ra808_o81_ti809_ch810_o232_ch3000_o283_nu3001'],
            ['no_ra808_o81_ti809_ch810_o233_ch3000_o281_nu3001', 'no_ra808_o81_ti809_ch810_o233_ch3000_o282_nu3001', 'no_ra808_o81_ti809_ch810_o233_ch3000_o283_nu3001'],
            ['no_ra808_o81_ti809_ch810_o234_ch3000_o281_nu3001', 'no_ra808_o81_ti809_ch810_o234_ch3000_o282_nu3001', 'no_ra808_o81_ti809_ch810_o234_ch3000_o283_nu3001'],
            ['no_ra808_o81_ti809_ch810_o235_ch3000_o281_nu3001', 'no_ra808_o81_ti809_ch810_o235_ch3000_o282_nu3001', 'no_ra808_o81_ti809_ch810_o235_ch3000_o283_nu3001']
        ];
        //ครั้งต่อเดือน
        $multiplier11_10_2 = [
            ['no_ra808_o81_ti809_ch810_o228_ch3000_o281_nu3002', 'no_ra808_o81_ti809_ch810_o228_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o228_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o229_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o229_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o229_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o230_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o230_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o230_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o231_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o231_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o231_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o232_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o232_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o232_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o233_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o233_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o233_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o234_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o234_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o234_ch3000_o283_nu3002'],
            ['no_ra808_o81_ti809_ch810_o235_ch3000_o281_nu3002','no_ra808_o81_ti809_ch810_o235_ch3000_o282_nu3002','no_ra808_o81_ti809_ch810_o235_ch3000_o283_nu3002'],
        ];
        // บาทต่อลิตร
        $divide11_10_2 = [
            'no_ra808_o81_ti809_ch810_o228_nu820',
            'no_ra808_o81_ti809_ch810_o229_nu820',
            'no_ra808_o81_ti809_ch810_o230_nu820',
            'no_ra808_o81_ti809_ch810_o231_nu820',
            'no_ra808_o81_ti809_ch810_o232_nu820',
            'no_ra808_o81_ti809_ch810_o233_nu820',
            'no_ra808_o81_ti809_ch810_o234_nu820',
            'no_ra808_o81_ti809_ch810_o235_nu820'
        ];
        $table11_10_2_final = [];
        $level1 = 0;
        foreach ($table11_10_2 as $row){
            $finalSql = " IF(SUM(IF(unique_key='$divide11_10_2[$level1]', answer_numeric,0))<=0,0, ( 0 ";
            $level2 = 0;
            foreach ($row as $row2){
                $sql = " SUM(IF(unique_key='param1',answer_numeric,0)) * SUM(IF(unique_key='param2',answer_numeric,0)) * 12 ";
                $temp = str_replace("param1", $row2, $sql);
                $temp = str_replace("param2", $multiplier11_10_2[$level1][$level2], $temp);
                $finalSql .= " + " . $temp;

                $level2++;
            }
            $finalSql .= " ) / SUM(IF(unique_key='$divide11_10_2[$level1]', answer_numeric,0))) ";
            $table11_10_2_final[] = $finalSql;
            $level1++;
        }
        $startColumn = 'BN';
        $startRow = 15;
        $objPHPExcel = Summary11Controller::average($table11_10_2_final, $startColumn,$startRow, $objPHPExcel, $mainObj,null,false,true);
        // ก๊าซปิโตรเลียมเหลว สำหรับหุงต้ม
        $size = [4,15,48];
        $amount = ['no_ra808_o81_ti821_ch822_o236_ch825_o208_nu826', 'no_ra808_o81_ti821_ch822_o236_ch825_o209_nu826', 'no_ra808_o81_ti821_ch822_o236_ch825_o210_nu826'];
        $level1 = 0;
        $sum = 0;
        $finalSql = " ( 0 ";
        foreach ($amount as $item){
            $sql = " SUM(IF(unique_key='$amount[$level1]',answer_numeric,0)) * $size[$level1] ";
            $finalSql .= " + " . $sql;
            $sum+=$size[$level1];

            $level1++;
        }
        $finalSql .= " )/ $sum ";
        $table11_10_3 = [$finalSql];
        $startColumn = 'BN';
        $startRow = 23;
        $objPHPExcel = Summary11Controller::average($table11_10_3, $startColumn,$startRow, $objPHPExcel, $mainObj,null,false,true);

        $table11_10_4 = ['no_ra808_o81_ti829_ch830_o1_nu833'];
        $multiplier11_10_4 = ['no_ra808_o81_ti829_ch830_o1_nu834'];
        $startRow = 24;
        $objPHPExcel = Summary11Controller::average($table11_10_4,$startColumn,$startRow,$objPHPExcel,$mainObj,$multiplier11_10_4,true);

        //ตารางที่ 11.11 จำนวนและร้อยละของครัวเรือนที่ใช้วิธีการไปซื้อพลังงานจำแนกตามเขตปกครอง
        $table11_11 = [
            ['no_ra808_o81_ti829_ch830_o266_ra832'=>225],
            ['no_ra808_o81_ti829_ch830_o266_ra832'=>226],
            [],
            ['no_ra808_o81_ti829_ch830_o267_ra832'=>225],
            ['no_ra808_o81_ti829_ch830_o267_ra832'=>226],
            [],
            ['no_ra808_o81_ti829_ch830_o268_ra832'=>225],
            ['no_ra808_o81_ti829_ch830_o268_ra832'=>226],
            [],
            ['no_ra808_o81_ti829_ch830_o286_ra832'=>225],
            ['no_ra808_o81_ti829_ch830_o286_ra832'=>226],
            [],
            ['no_ra808_o81_ti809_ch810_o228_ra812'=>225],
            ['no_ra808_o81_ti809_ch810_o228_ra812'=>226],
            [],
            ['no_ra808_o81_ti809_ch810_o229_ra812'=>225],
            ['no_ra808_o81_ti809_ch810_o229_ra812'=>226],
            [],
            ['no_ra808_o81_ti809_ch810_o230_ra812'=>225],
            ['no_ra808_o81_ti809_ch810_o230_ra812'=>226],
            [],
            ['no_ra808_o81_ti809_ch810_o231_ra812'=>225],
            ['no_ra808_o81_ti809_ch810_o231_ra812'=>226],
            [],
            ['no_ra808_o81_ti809_ch810_o232_ra812'=>225],
            ['no_ra808_o81_ti809_ch810_o232_ra812'=>226],
            [],
            ['no_ra808_o81_ti809_ch810_o233_ra812'=>225],
            ['no_ra808_o81_ti809_ch810_o233_ra812'=>226],
            [],
            ['no_ra808_o81_ti809_ch810_o234_ra812'=>225],
            ['no_ra808_o81_ti809_ch810_o234_ra812'=>226],
            [],
            ['no_ra808_o81_ti809_ch810_o235_ra812'=>225],
            ['no_ra808_o81_ti809_ch810_o235_ra812'=>226],
            [],
            ['no_ra808_o81_ti821_ch822_o236_ra824'=>226],
            ['no_ra808_o81_ti821_ch822_o236_ra824'=>225],
            [],
            ['no_ra808_o81_ti829_ch830_o1_ra832'=>225],
            ['no_ra808_o81_ti829_ch830_o1_ra832'=>226],
        ];
        $startColumn = 'CB';
        $startRow = 12;
        $objPHPExcel = Summary::sum($table11_11, $startColumn, $startRow, $objPHPExcel, $mainObj, true);

        $table11_12 = [
            'no_ra808_o81_ti829_ch830_o266_nu835',
            'no_ra808_o81_ti829_ch830_o267_nu835',
            'no_ra808_o81_ti829_ch830_o268_nu835',
            'no_ra808_o81_ti829_ch830_o269_nu835',
            'no_ra808_o81_ti809_ch810_o228_nu819',
            'no_ra808_o81_ti809_ch810_o229_nu819',
            'no_ra808_o81_ti809_ch810_o230_nu819',
            'no_ra808_o81_ti809_ch810_o231_nu819',
            'no_ra808_o81_ti809_ch810_o232_nu819',
            'no_ra808_o81_ti809_ch810_o233_nu819',
            'no_ra808_o81_ti809_ch810_o234_nu819',
            'no_ra808_o81_ti809_ch810_o235_nu819',
            [],
            'no_ra808_o81_ti829_ch830_o1_nu835'
        ];
        $startColumn = 'CP';
        $startRow = 11;
        $objPHPExcel = Summary::average($table11_12, $startColumn,$startRow, $objPHPExcel, $mainObj);
        $table11_12_2 = [];
        $gasLength = ['no_ra808_o81_ti821_ch822_o236_ch825_o208_nu827', 'no_ra808_o81_ti821_ch822_o236_ch825_o209_nu827', 'no_ra808_o81_ti821_ch822_o236_ch825_o210_nu827'];
        $gasAmount = ['no_ra808_o81_ti821_ch822_o236_ch825_o208_nu826', 'no_ra808_o81_ti821_ch822_o236_ch825_o209_nu826', 'no_ra808_o81_ti821_ch822_o236_ch825_o210_nu826'];
        $gasSql = " 0 ";
        for($i=0; $i< count($gasLength); $i++){
            $sql = " IF(SUM(IF(unique_key='param2', answer_numeric,0))<=0,0,SUM(IF(unique_key='param1', answer_numeric,0))/SUM(IF(unique_key='param2', answer_numeric,0))) ";
            $temp = str_replace("param1", $gasLength[$i], $sql);
            $temp = str_replace("param2", $gasAmount[$i], $temp);

            $gasSql .= " + " . $temp;
        }
        $table11_12_2[] = $gasSql;
        $startRow = 23;
        $objPHPExcel = Summary11Controller::average($table11_12_2, $startColumn, $startRow, $objPHPExcel, $mainObj, null,false,true);

        $table11_13 = [
            'no_ra808_o81_ti829_ch830_o266_nu836',
            'no_ra808_o81_ti829_ch830_o267_nu836',
            'no_ra808_o81_ti829_ch830_o268_nu836',
            'no_ra808_o81_ti829_ch830_o269_nu836',
            'no_ra808_o81_ti809_ch810_o228_nu820',
            'no_ra808_o81_ti809_ch810_o229_nu820',
            'no_ra808_o81_ti809_ch810_o230_nu820',
            'no_ra808_o81_ti809_ch810_o231_nu820',
            'no_ra808_o81_ti809_ch810_o232_nu820',
            'no_ra808_o81_ti809_ch810_o233_nu820',
            'no_ra808_o81_ti809_ch810_o234_nu820',
            'no_ra808_o81_ti809_ch810_o235_nu820',
            [],
            'no_ra808_o81_ti829_ch830_o1_nu836'
        ];
        $startColumn = 'DE';
        $startRow = 11;
        $objPHPExcel = Summary::average($table11_13, $startColumn,$startRow, $objPHPExcel, $mainObj);
        $gasPrice = ['no_ra808_o81_ti821_ch822_o236_ch825_o208_nu828', 'no_ra808_o81_ti821_ch822_o236_ch825_o209_nu828', 'no_ra808_o81_ti821_ch822_o236_ch825_o210_nu828'];
        $gasSql = " 0 ";
        $table11_13_2 = [];
        for($i=0; $i< count($gasLength); $i++){
            $sql = " IF(SUM(IF(unique_key='param2', answer_numeric,0))<=0,0,SUM(IF(unique_key='param1', answer_numeric,0))/SUM(IF(unique_key='param2', answer_numeric,0))) ";
            $temp = str_replace("param1", $gasPrice[$i], $sql);
            $temp = str_replace("param2", $gasAmount[$i], $temp);

            $gasSql .= " + " . $temp;
        }
        $table11_13_2[] = $gasSql;
        $startRow = 23;
        $objPHPExcel = Summary11Controller::average($table11_13_2, $startColumn, $startRow, $objPHPExcel, $mainObj, null,false,true);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return true;
    }

    public static function sum($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj)
    {
        $w = [];
        $w[1] = Main::$weight[Main::INNER_GROUP_1];
        $w[2] = Main::$weight[Main::INNER_GROUP_2];
        $w[3] = Main::$weight[Main::OUTER_GROUP_1];
        $w[4] = Main::$weight[Main::OUTER_GROUP_2];

        $s = [];
        $s[1] = Main::$sample[Main::INNER_GROUP_1];
        $s[2] = Main::$sample[Main::INNER_GROUP_2];
        $s[3] = Main::$sample[Main::OUTER_GROUP_1];
        $s[4] = Main::$sample[Main::OUTER_GROUP_2];

        $parameterExcel = \PHPExcel_IOFactory::load(storage_path('excel/parameters.xlsx'));
        $parameterExcel->setActiveSheetIndex(2);
        $paramSheet = $parameterExcel->getActiveSheet();
        $S = [];
        $S[1] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_INNER])->getValue();
        $S[2] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_INNER])->getValue();
        $S[3] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_OUTER])->getValue();
        $S[4] = (float)$paramSheet->getCell(Parameter::$populationColumn[Main::NORTHERN_OUTER])->getValue();

//        $rows = [];
//        $rowNumber = $startRow;
//        foreach ($uniqueKeyArr as $uniqueKey){
//            $rows[$startCol.$rowNumber] = $uniqueKey;
//            $rowNumber++;
//        }
//        $answerObj = Answer::whereIn('unique_key', $uniqueKeyArr)->get();

//        $whereIn = [];
        $answers = [];
        $percents = [];
        if (!isset($answers[Main::NORTHERN_INNER])){
            $answers[Main::NORTHERN_INNER] = array();
            $percents[Main::NORTHERN_INNER] = array();
        }
        if (!isset($answers[Main::NORTHERN_OUTER])){
            $answers[Main::NORTHERN_OUTER] = array();
            $percents[Main::NORTHERN_OUTER] = array();
        }
        if (!isset($answers[Main::NORTHERN])){
            $answers[Main::NORTHERN] = array();
            $percents[Main::NORTHERN] = array();
        }
        foreach ($uniqueKeyArr as $unique_key=>$options){
//            $whereIn[] = $value;
            if (empty($options)){
                $startRow++;
                continue;
            }
            $p = [];
            $count = [];

            for ($i=1; $i<=4; $i++){
                $mainList = $mainObj->filterMain($i);
                $whereCondition = "";
                $selectCountSql = "";

                $idx = 0;
                $count[$i] = array();
                $p[$i] = array();
                $allCountAttr = [];
                foreach ($options as $option_id){
                    if ($idx===0){
                        $whereCondition .= " AND ( ";
                        $selectCountSql .= " SUM(IF(unique_key='$unique_key' AND option_id=$option_id,1,0)) AS count{$option_id} ";
                        $allCountAttr[] = "count".$option_id;
                    }
                    else{
                        $whereCondition .= " OR ";
                        $selectCountSql .= " ,SUM(IF(unique_key='$unique_key' AND option_id=$option_id,1,0)) AS count{$option_id} ";
                        $allCountAttr[] = "count".$option_id;
                    }
                    $whereCondition .= " (unique_key='$unique_key' AND option_id=$option_id) ";

                    $idx++;
                }
                $whereCondition .= " )";

                $whereInMainId = implode(",", $mainList);
                $sql = "SELECT {$selectCountSql} FROM (SELECT main_id,unique_key,option_id FROM answers WHERE main_id IN ($whereInMainId) " . $whereCondition . " GROUP BY main_id,unique_key,option_id) t1";

                $result = \DB::select($sql)[0];

                foreach ($allCountAttr as $attr){
                    $count[$i][$attr] = $result->{$attr};
                    $percents[$i][$attr] = $w[$i] * ((float)$count[$i][$attr] / $s[$i]);
//                    $p[$i][$attr] = $w[$i] * ((float)$count[$i][$attr] / $s[$i]);
                }
//                $count[$i] = \DB::select($sql)[0]->count;
//                $p[$i] = $w[$i] * ((float)$count[$i]/ $s[$i]) * $S[$i];
            }

            $tempCol = $startCol;
            foreach ($allCountAttr as $attr){
                $percents[Main::NORTHERN_INNER][$attr] = $percents[Main::INNER_GROUP_1][$attr]+$percents[Main::INNER_GROUP_2][$attr];
                $percents[Main::NORTHERN_OUTER][$attr] = $percents[Main::OUTER_GROUP_1][$attr]+$percents[Main::OUTER_GROUP_2][$attr];

                $answers[Main::NORTHERN_INNER][$attr] = $percents[Main::NORTHERN_INNER][$attr]*Main::$population[Main::NORTHERN_INNER];
                $answers[Main::NORTHERN_OUTER][$attr] = $percents[Main::NORTHERN_OUTER][$attr]*Main::$population[Main::NORTHERN_OUTER];

                $percents[Main::NORTHERN][$attr] = $percents[Main::NORTHERN_INNER][$attr]*Main::$weight[Main::NORTHERN_INNER] + $percents[Main::NORTHERN_OUTER][$attr]*Main::$weight[Main::NORTHERN_OUTER];
                $answers[Main::NORTHERN][$attr] = $percents[Main::NORTHERN][$attr]*Main::$population[Main::NORTHERN];

                $l_loop = [Main::NORTHERN,Main::NORTHERN_OUTER, Main::NORTHERN_INNER];
                foreach ($l_loop as $l_key){
                    $objPHPExcel->getActiveSheet()->setCellValue($tempCol[$l_key].$startRow, $answers[$l_key][$attr]);
                    $objPHPExcel->getActiveSheet()->getStyle($tempCol[$l_key].$startRow)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
                    $tempCol[$l_key]++;
                    $objPHPExcel->getActiveSheet()->setCellValue($tempCol[$l_key].$startRow, $percents[$l_key][$attr]*100);
                    $objPHPExcel->getActiveSheet()->getStyle($tempCol[$l_key].$startRow)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
                    $tempCol[$l_key]++;
                }
            }
            $startRow++;
//            $answers[Main::NORTHERN_INNER] = (int)($p[Main::INNER_GROUP_1] + $p[Main::INNER_GROUP_2]);
        }

        return $objPHPExcel;
    }

    public static function average($uniqueKeyArr, $startCol, $startRow, $objPHPExcel, $mainObj, $multiplier,$year = false, $customSql=false)
    {
        $rows = [];
        $rowNumber = $startRow;
        foreach ($uniqueKeyArr as $uniqueKey) {
            $rows[$startCol . $rowNumber] = $uniqueKey;
            $rowNumber++;
        }

        $allUniqueArr = [];
        foreach ($uniqueKeyArr as $item) {
            if (!is_array($item))
                $allUniqueArr[] = $item;
            else {
                foreach ($item as $subItem)
                    $allUniqueArr[] = $subItem;
            }
        }

        $whereIn = [];
        $answers = [];
        $count = [];
        $A = [];

        $level1Counter = 0;
        foreach ($rows as $key => $value) {
            $whereIn[] = $value;
            $p = [];
            $avg = [];

            foreach (Main::$provinceWeight as $p_key => $p_weight) {
                $mainList = $mainObj->filterMain($p_key);

                $avg[$p_key] = 0;
                $whereMainId = implode(",", $mainList);

                if ($customSql){
                    $avgSql = "SELECT AVG(sum1) as average, COUNT(*) as countAll FROM
                    (SELECT $value AS sum1 FROM answers
                    WHERE main_id IN ($whereMainId) "
                        . " GROUP BY main_id) T1";
                }else{
                    if (is_array($value)) {
                        $whereUniqueKey = implode("','", $value);
                        $tempUniqueKey = $whereUniqueKey;
                        $whereUniqueKey = " AND unique_key IN ('" .$whereUniqueKey."','$multiplier[$level1Counter]') ";
                        $sumSQL = " SUM(IF(unique_key IN ('$tempUniqueKey'),answer_numeric,0)) * 
                    SUM(IF(unique_key='$multiplier[$level1Counter]',answer_numeric,0)) ";
                    }else{
                        $whereUniqueKey = " AND (unique_key='$value' OR unique_key='$multiplier[$level1Counter]') ";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) * 
                    SUM(IF(unique_key='$multiplier[$level1Counter]',answer_numeric,0)) ";
                    }

                    $avgSql = "SELECT SUM(sum1)/".Main::$provinceSample[$p_key]." as average, COUNT(*) as countAll FROM
                    (SELECT $sumSQL AS sum1 FROM answers
                    WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id) T1";
                }

                $avgResult = \DB::select($avgSql);
                $avg[$p_key] = $avgResult[0]->average;
                $count[$p_key] = $avgResult[0]->countAll;
            }

            foreach (Main::$borderWeight as $b_key => $b_weight) {
                $mainList = $mainObj->filterMain($b_key);

                $avg[$b_key] = 0;
                $whereMainId = implode(",", $mainList);
                //old2
                if ($customSql){
                    $avgSql = "SELECT SUM(sum1)/".Main::$sample[$b_key]." as average, COUNT(*) as countAll FROM
                    (SELECT $value AS sum1 FROM answers
                    WHERE main_id IN ($whereMainId) "
                        . " GROUP BY main_id) T1";
                }else{
                    if (is_array($value)) {
                        $whereUniqueKey = implode("','", $value);
                        $tempUniqueKey = $whereUniqueKey;
                        $whereUniqueKey = " AND unique_key IN ('" .$whereUniqueKey."','$multiplier[$level1Counter]') ";
                        $sumSQL = " SUM(IF(unique_key IN ('$tempUniqueKey'),answer_numeric,0)) * 
                    SUM(IF(unique_key='$multiplier[$level1Counter]',answer_numeric,0)) ";
                    }else{
                        $whereUniqueKey = " AND (unique_key='$value' OR unique_key='$multiplier[$level1Counter]') ";
                        $sumSQL = " SUM(IF(unique_key='$value', answer_numeric,0)) * 
                    SUM(IF(unique_key='$multiplier[$level1Counter]',answer_numeric,0)) ";
                    }

                    $avgSql = "SELECT SUM(sum1)/".Main::$sample[$b_key]." as average, COUNT(*) as countAll FROM
                    (SELECT $sumSQL AS sum1 FROM answers
                    WHERE main_id IN ($whereMainId) " . $whereUniqueKey
                        . " GROUP BY main_id) T1";
                }

                $avgResult = \DB::select($avgSql);
                $avg[$b_key] = $avgResult[0]->average;
                $count[$b_key] = $avgResult[0]->countAll;

                $p[$b_key] = $avg[$b_key] * $b_weight;
            }

            $col = $startCol;
            $col++;
            $key2 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key3 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key4 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key5 = preg_replace('/[A-Z]+/', $col, $key);
            $col++;
            $key6 = preg_replace('/[A-Z]+/', $col, $key);

            $answers[$key] = $p[Main::INNER_GROUP_1] + $p[Main::INNER_GROUP_2];
            if ($count[Main::INNER_GROUP_1] - 1 === 0)
                $A[Main::INNER_GROUP_1] = 0;
            else
                $A[Main::INNER_GROUP_1] = (1.0 / ($count[Main::INNER_GROUP_1] - 1))
                    * (
                        pow(($avg[Main::CHIANGMAI_INNER] - $avg[Main::INNER_GROUP_1]), 2)
                        + pow(($avg[Main::UTARADIT_INNER] - $avg[Main::INNER_GROUP_1]), 2)
                    );

            if ($count[Main::INNER_GROUP_2] - 1 === 0)
                $A[Main::INNER_GROUP_2] = 0;
            else
                $A[Main::INNER_GROUP_2] = (1.0 / ($count[Main::INNER_GROUP_2] - 1))
                    * (
                        pow(($avg[Main::NAN_INNER] - $avg[Main::INNER_GROUP_2]), 2)
                        + pow(($avg[Main::PITSANULOK_INNER] - $avg[Main::INNER_GROUP_2]), 2)
                        + pow(($avg[Main::PETCHABUL_INNER] - $avg[Main::INNER_GROUP_2]), 2)
                    );
            if (($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]) === 0)
                $part1 = 0;
            else
                $part1 = $count[Main::INNER_GROUP_1] / ($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]);
            $part2 = ($count[Main::INNER_GROUP_1] === 0) ? 0 : ($A[Main::INNER_GROUP_1] / $count[Main::INNER_GROUP_1]);
            $part3 = ($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]) === 0 ?
                0 : ($count[Main::INNER_GROUP_2] / ($count[Main::INNER_GROUP_1] + $count[Main::INNER_GROUP_2]));
            $part4 = $count[Main::INNER_GROUP_2] === 0 ? 0 : ($A[Main::INNER_GROUP_2] / $count[Main::INNER_GROUP_2]);

            $answers[$key2] = sqrt(
                (Main::$weight[Main::INNER_GROUP_1] * (1.0 - $part1) * $part2) +
                (Main::$weight[Main::INNER_GROUP_2] * (1.0 - $part3) * $part4)
            );
            $answers[$key3] = $p[Main::OUTER_GROUP_1] + $p[Main::OUTER_GROUP_2];
            if ($count[Main::OUTER_GROUP_1] - 1 === 0)
                $A[Main::OUTER_GROUP_1] = 0;
            else
                $A[Main::OUTER_GROUP_1] = (1.0 / ($count[Main::OUTER_GROUP_1] - 1))
                    * (
                        pow(($avg[Main::CHIANGMAI_OUTER] - $avg[Main::OUTER_GROUP_1]), 2)
                        + pow(($avg[Main::UTARADIT_OUTER] - $avg[Main::OUTER_GROUP_1]), 2)
                    );
            if ($count[Main::OUTER_GROUP_2] - 1 === 0)
                $A[Main::OUTER_GROUP_2] = 0;
            else
                $A[Main::OUTER_GROUP_2] = (1.0 / ($count[Main::OUTER_GROUP_2] - 1))
                    * (
                        pow(($avg[Main::NAN_OUTER] - $avg[Main::OUTER_GROUP_2]), 2)
                        + pow(($avg[Main::PITSANULOK_OUTER] - $avg[Main::OUTER_GROUP_2]), 2)
                        + pow(($avg[Main::PETCHABUL_OUTER] - $avg[Main::OUTER_GROUP_2]), 2)
                    );
            if (($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]) === 0)
                $part1 = 0;
            else
                $part1 = $count[Main::OUTER_GROUP_1] / ($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]);
            $part2 = ($count[Main::OUTER_GROUP_1] === 0) ? 0 : ($A[Main::OUTER_GROUP_1] / $count[Main::OUTER_GROUP_1]);
            $part3 = ($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]) === 0 ?
                0 : ($count[Main::OUTER_GROUP_2] / ($count[Main::OUTER_GROUP_1] + $count[Main::OUTER_GROUP_2]));
            $part4 = $count[Main::OUTER_GROUP_2] === 0 ? 0 : ($A[Main::OUTER_GROUP_2] / $count[Main::OUTER_GROUP_2]);

            $answers[$key4] = sqrt(
                (Main::$weight[Main::OUTER_GROUP_1] * (1.0 - $part1) * $part2) +
                (Main::$weight[Main::OUTER_GROUP_2] * (1.0 - $part3) * $part4)
            );

            $objPHPExcel->getActiveSheet()->setCellValue($key, $answers[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue($key2, $answers[$key2]);
            $objPHPExcel->getActiveSheet()->setCellValue($key3, $answers[$key3]);
            $objPHPExcel->getActiveSheet()->setCellValue($key4, $answers[$key4]);
            $objPHPExcel->getActiveSheet()->setCellValue($key5, (($answers[$key] + $answers[$key3]) / 2.0));
            $objPHPExcel->getActiveSheet()->setCellValue($key6, (($answers[$key2] + $answers[$key4]) / 2.0));

            $objPHPExcel->getActiveSheet()->getStyle($key)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key2)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key3)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key4)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key5)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);
            $objPHPExcel->getActiveSheet()->getStyle($key6)->getNumberFormat()->setFormatCode(Main::NUMBER_FORMAT);

            $level1Counter++;
        }

        return $objPHPExcel;
    }


}
