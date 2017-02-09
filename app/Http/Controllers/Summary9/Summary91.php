<?php

namespace App\Http\Controllers\Summary9;

use App\Main;
use App\Parameter;
use App\Setting;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary91 extends Controller
{

    public static function report911()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.1';
        $startRow = 13;
        $outputFile = 'sum911.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1023_o329_ch101_o68',
            'no_ch1023_o329_ch101_o69_ch102_o72',
            'no_ch1023_o329_ch101_o69_ch102_o73',
            'no_ch1023_o329_ch101_o69_ch102_o74',
            'no_ch1023_o329_ch101_o70',
            'no_ch1023_o329_ch101_o71',
        ];
        $startColumn = 'E';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1023_o329_ch101_o68_nu103',
            'no_ch1023_o329_ch101_o69_ch102_o72_nu107',
            'no_ch1023_o329_ch101_o69_ch102_o73_nu107',
            'no_ch1023_o329_ch101_o69_ch102_o74_nu107',
            'no_ch1023_o329_ch101_o70_nu103',
            'no_ch1023_o329_ch101_o71_nu103'
        ];

        $startColumn = 'U';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,9,10,11])->get();
        $factor1 = (float)$settings->where('code','tool_factor_1')->first()->value
            * (float)$settings->where('code','season_factor_1')->first()->value
            * (float)$settings->where('code','usage_factor_1')->first()->value;
        $factor2 = (float)$settings->where('code','tool_factor_2')->first()->value
            * (float)$settings->where('code','season_factor_2')->first()->value
            * (float)$settings->where('code','usage_factor_2')->first()->value;
        $factor3 = (float)$settings->where('code','tool_factor_3')->first()->value
            * (float)$settings->where('code','season_factor_3')->first()->value
            * (float)$settings->where('code','usage_factor_3')->first()->value;
        $factor4 = (float)$settings->where('code','tool_factor_4')->first()->value
            * (float)$settings->where('code','season_factor_4')->first()->value
            * (float)$settings->where('code','usage_factor_4')->first()->value;
        $factor5 = (float)$settings->where('code','tool_factor_5')->first()->value
            * (float)$settings->where('code','season_factor_5')->first()->value
            * (float)$settings->where('code','usage_factor_5')->first()->value;
        $factor6 = (float)$settings->where('code','tool_factor_6')->first()->value
            * (float)$settings->where('code','season_factor_6')->first()->value
            * (float)$settings->where('code','usage_factor_6')->first()->value;

        $electricPower = array();
        for ($i=0;$i<6;$i++)
        {
            $electricPower[$i] = (float)$settings->where('code','electric_power_'.($i+1))->first()->value;
        }

        $table3 = [
            ['no_ch1023_o329_ch101_o68_nu104','no_ch1023_o329_ch101_o68_nu105','no_ch1023_o329_ch101_o68_nu103',$factor1,$electricPower[0]],
            ['no_ch1023_o329_ch101_o69_ch102_o72_nu108','no_ch1023_o329_ch101_o69_ch102_o72_nu109','no_ch1023_o329_ch101_o69_ch102_o72_nu107',$factor2,$electricPower[1]],
            ['no_ch1023_o329_ch101_o69_ch102_o73_nu108','no_ch1023_o329_ch101_o69_ch102_o73_nu109','no_ch1023_o329_ch101_o69_ch102_o73_nu107',$factor3,$electricPower[2]],
            ['no_ch1023_o329_ch101_o69_ch102_o74_nu108','no_ch1023_o329_ch101_o69_ch102_o74_nu109','no_ch1023_o329_ch101_o69_ch102_o74_nu107',$factor4,$electricPower[3]],
            ['no_ch1023_o329_ch101_o70_nu104','no_ch1023_o329_ch101_o70_nu105','no_ch1023_o329_ch101_o70_nu103',$factor5,$electricPower[4]],
            ['no_ch1023_o329_ch101_o71_nu104','no_ch1023_o329_ch101_o71_nu105','no_ch1023_o329_ch101_o71_nu103',$factor6,$electricPower[5]]
        ];
        $startColumn = 'AL';
        $ktoe = Setting::where('code', 'E9')->first()->value;
        $week = Parameter::WEEK_PER_YEAR;

        // ระยะเวลาใช้งาน (ชั่วโมง / ปี) คำนวณจาก จำนวนหลอด*อัตราการใช้ (ชั่วโมง/วัน)*อัตราการใช้(วัน/สัปดาห์)*จำนวนอาทิตย์ต่อปี กะไว้ประมาณ 52
        $sumAmountSQL = " ( sum(IF(unique_key='param1',answer_numeric,0)) * sum(if(unique_key='param2', answer_numeric,0)) * sum(if(unique_key='param3',answer_numeric,0)) * {$week} ) 
        * (param4) * (param5) as sumAmount ";

        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2,
            'param4'=>3,
            'param5'=>4
        ];
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL,$params,$ktoe);

        $table4 = [
            'no_ch1023_o329_ch101_o68_nu106',
            'no_ch1023_o329_ch101_o69_ch102_o72_nu110',
            'no_ch1023_o329_ch101_o69_ch102_o73_nu110',
            'no_ch1023_o329_ch101_o69_ch102_o74_nu110',
            'no_ch1023_o329_ch101_o70_nu106',
            'no_ch1023_o329_ch101_o71_nu106',
        ];
        $startColumn = 'BB';
        $objPHPExcel = Summary::average($table4, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

    // รวมในบ้านกับนอกบ้านเข้าด้วยกัน
    public static function report911Special()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'sum911special.xlsx';
        $inputSheet = 0;
        $outputFile = '1,2.หลอดไฟ.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/raw_excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheet($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndex($inputSheet);

        // จำนวนครัวเรือน
        $table1 = [
            ['no_ch1023_o329_ch101_o68', 'no_ch1023_o330_ch112_o68'],
            ['no_ch1023_o329_ch101_o69_ch102_o72','no_ch1023_o330_ch112_o69_ch113_o72'],
            ['no_ch1023_o329_ch101_o69_ch102_o73','no_ch1023_o330_ch112_o69_ch113_o73'],
            ['no_ch1023_o329_ch101_o69_ch102_o74','no_ch1023_o330_ch112_o69_ch113_o74'],
            ['no_ch1023_o329_ch101_o70','no_ch1023_o330_ch112_o70'],
            ['no_ch1023_o329_ch101_o71','no_ch1023_o330_ch112_o71']
        ];
        $startRow = 5;
        $startColumn = 'D';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj, false, false,null, true);

        // ค่าเฉลี่ยจำนวนอุปกรณ์ กับ SE
        $table2 = [
            ['no_ch1023_o329_ch101_o68_nu103','no_ch1023_o330_ch112_o68_nu114'],
            ['no_ch1023_o329_ch101_o69_ch102_o72_nu107','no_ch1023_o330_ch112_o69_ch113_o72_nu118'],
            ['no_ch1023_o329_ch101_o69_ch102_o73_nu107','no_ch1023_o330_ch112_o69_ch113_o73_nu118'],
            ['no_ch1023_o329_ch101_o69_ch102_o74_nu107','no_ch1023_o330_ch112_o69_ch113_o74_nu118'],
            ['no_ch1023_o329_ch101_o70_nu103','no_ch1023_o330_ch112_o70_nu114'],
            ['no_ch1023_o329_ch101_o71_nu103','no_ch1023_o330_ch112_o71_nu114']
        ];
        $startColumn = 'D';
        $startRow = 21;
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        // ปริมาณการใช้งาน
        $table3UniqueKeys = [
            [
                'no_ch1023_o329_ch101_o68_nu104','no_ch1023_o329_ch101_o68_nu105','no_ch1023_o329_ch101_o68_nu103',0.06,
                'no_ch1023_o330_ch112_o68_nu115','no_ch1023_o330_ch112_o68_nu116','no_ch1023_o330_ch112_o68_nu114',0.060
            ],
            [
                'no_ch1023_o329_ch101_o69_ch102_o72_nu108','no_ch1023_o329_ch101_o69_ch102_o72_nu109','no_ch1023_o329_ch101_o69_ch102_o72_nu107',0.024,
                'no_ch1023_o330_ch112_o69_ch113_o72_nu119','no_ch1023_o330_ch112_o69_ch113_o72_nu120','no_ch1023_o330_ch112_o69_ch113_o72_nu118',0.024
            ],
            [
                'no_ch1023_o329_ch101_o69_ch102_o73_nu108','no_ch1023_o329_ch101_o69_ch102_o73_nu109','no_ch1023_o329_ch101_o69_ch102_o73_nu107',0.036,
                'no_ch1023_o330_ch112_o69_ch113_o73_nu119','no_ch1023_o330_ch112_o69_ch113_o73_nu120','no_ch1023_o330_ch112_o69_ch113_o73_nu118',0.036
            ],
            [
                'no_ch1023_o329_ch101_o69_ch102_o74_nu108','no_ch1023_o329_ch101_o69_ch102_o74_nu109','no_ch1023_o329_ch101_o69_ch102_o74_nu107',0.018,
                'no_ch1023_o330_ch112_o69_ch113_o74_nu119','no_ch1023_o330_ch112_o69_ch113_o74_nu120','no_ch1023_o330_ch112_o69_ch113_o74_nu118',0.018
            ],
            [
                'no_ch1023_o329_ch101_o70_nu104','no_ch1023_o329_ch101_o70_nu105','no_ch1023_o329_ch101_o70_nu103',0.018,
                'no_ch1023_o330_ch112_o70_nu115','no_ch1023_o330_ch112_o70_nu116','no_ch1023_o330_ch112_o70_nu114',0.018
            ],
            [
                'no_ch1023_o329_ch101_o71_nu104','no_ch1023_o329_ch101_o71_nu105','no_ch1023_o329_ch101_o71_nu103',0.010,
                'no_ch1023_o330_ch112_o71_nu115','no_ch1023_o330_ch112_o71_nu116','no_ch1023_o330_ch112_o71_nu114',0.010
            ]
        ];
        $startColumn = 'E';
        $startRow = 37;
        $ktoe = Parameter::$ktoe[Parameter::ELECTRIC];
        $week = Parameter::WEEK_PER_YEAR;
        $table3 = [];
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0))* sum(if(unique_key='param2', answer_numeric,0))* {$week})* (param4) * sum(if(unique_key='param3',1,0)) ";
        foreach ($table3UniqueKeys as $param){
            $finalSql = "";
            for ($i=0;$i<8;$i+=4){
                $finalSql .= $sumAmountSQL;
                $finalSql = str_replace("param1", $param[$i], $finalSql);
                $finalSql = str_replace("param2", $param[$i+1], $finalSql);
                $finalSql = str_replace("param3", $param[$i+2], $finalSql);
                $finalSql = str_replace("param4", $param[$i+3], $finalSql);

                $finalSql .= " + ";
            }
            $finalSql .= " 0 ";
            $table3[] = $finalSql;
        }
        $objPHPExcel = Summary::specialUsage($table3, $startColumn, $startRow, $objPHPExcel, $mainObj, $ktoe);

        $table4 = [
            ['no_ch1023_o329_ch101_o68_nu106','no_ch1023_o330_ch112_o68_nu117'],
            ['no_ch1023_o329_ch101_o69_ch102_o72_nu110','no_ch1023_o330_ch112_o69_ch113_o72_nu121'],
            ['no_ch1023_o329_ch101_o69_ch102_o73_nu110','no_ch1023_o330_ch112_o69_ch113_o73_nu121'],
            ['no_ch1023_o329_ch101_o69_ch102_o74_nu110','no_ch1023_o330_ch112_o69_ch113_o74_nu121'],
            ['no_ch1023_o329_ch101_o70_nu106','no_ch1023_o330_ch112_o70_nu117'],
            ['no_ch1023_o329_ch101_o71_nu106','no_ch1023_o330_ch112_o71_nu117'],
        ];
        $startColumn = 'D';
        $startRow = 53;
        $objPHPExcel = Summary::average($table4, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/raw_excel_output/'.$outputFile)));
    }

    public static function report914()
    {
        set_time_limit(3600);
        // หมวดความสะดวกสบาย
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.4';
        $startRow = 13;
        $outputFile = 'sum914.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1028_o356_ch323_o149_ch324_o156',
            'no_ch1028_o356_ch323_o149_ch324_o157',
            'no_ch1028_o356_ch323_o149_ch324_o158',
            'no_ch1028_o356_ch323_o150_ch330_o156',
            'no_ch1028_o356_ch323_o150_ch330_o157',
            'no_ch1028_o356_ch323_o150_ch330_o158',
            'no_ch1028_o356_ch323_o151_ch336_o156',
            'no_ch1028_o356_ch323_o151_ch336_o157',
            'no_ch1028_o356_ch323_o151_ch336_o158',
            'no_ch1028_o356_ch323_o152_ch342_o157',
            'no_ch1028_o356_ch323_o152_ch342_o159',
            'no_ch1028_o356_ch323_o153_ch348_o158',
            'no_ch1028_o356_ch323_o153_ch348_o160',
            'no_ch1028_o356_ch323_o153_ch348_o161',
            'no_ch1028_o356_ch323_o154',
            'no_ch1028_o356_ch323_o155',
            'no_ch1028_o357_ch358_o151_ch359_o163',
            'no_ch1028_o357_ch358_o151_ch359_o164',
            'no_ch1028_o357_ch358_o151_ch359_o165',
            'no_ch1028_o358',
            'no_ch1028_o359',
            'no_ch1028_o360_ch379_o167',
            'no_ch1028_o360_ch379_o168',
            'no_ch1028_o360_ch379_o169',
            'no_ch1028_o361_ch385_o170',
            'no_ch1028_o361_ch385_o171',
            'no_ch1028_o361_ch385_o172',
            'no_ch1028_o361_ch385_o173',
            'no_ch1028_o361_ch385_o174',
            'no_ch1028_o362_ch392_o175_ch393_o177',
            'no_ch1028_o362_ch392_o175_ch393_o178',
            'no_ch1028_o362_ch392_o176_ch1001_o179',
            'no_ch1028_o362_ch392_o176_ch1001_o180',
            'no_ch1028_o363_ch400_o181_ch401_o183',
            'no_ch1028_o363_ch400_o181_ch401_o184',
            'no_ch1028_o363_ch400_o181_ch401_o185',
            'no_ch1028_o363_ch400_o181_ch401_o186',
            'no_ch1028_o363_ch400_o181_ch401_o187',
            'no_ch1028_o363_ch400_o181_ch401_o188',
            'no_ch1028_o363_ch400_o182_ch410_o185',
            'no_ch1028_o363_ch400_o182_ch410_o186',
            'no_ch1028_o363_ch400_o182_ch410_o187',
            'no_ch1028_o363_ch400_o182_ch410_o188',
            'no_ch1028_o363_ch400_o182_ch410_o189',
            'no_ch1028_o363_ch400_o182_ch410_o190',
            'no_ch1028_o363_ch400_o182_ch410_o191',
            'no_ch1028_o364_ch420_o195_ch421_o198',
            'no_ch1028_o364_ch420_o195_ch421_o199',
            'no_ch1028_o364_ch420_o195_ch421_o200',
            'no_ch1028_o364_ch420_o195_ch421_o201',
            'no_ch1028_o364_ch420_o196_ch421_o198',
            'no_ch1028_o364_ch420_o196_ch421_o199',
            'no_ch1028_o364_ch420_o196_ch421_o200',
            'no_ch1028_o364_ch420_o196_ch421_o201',
            'no_ch1028_o364_ch420_o197_ch421_o198',
            'no_ch1028_o364_ch420_o197_ch421_o199',
            'no_ch1028_o364_ch420_o197_ch421_o200',
            'no_ch1028_o364_ch420_o197_ch421_o201',
            'no_ch1028_o365',
            'no_ch1028_o366',
            'no_ch1028_o367',
            'no_ch1028_o368',
            'no_ch1028_o369',
            'no_ch1028_o370'
        ];
        $startColumn = 'E';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1028_o356_ch323_o149_ch324_o156_nu325',
            'no_ch1028_o356_ch323_o149_ch324_o157_nu325',
            'no_ch1028_o356_ch323_o149_ch324_o158_nu325',
            'no_ch1028_o356_ch323_o150_ch330_o156_nu331',
            'no_ch1028_o356_ch323_o150_ch330_o157_nu331',
            'no_ch1028_o356_ch323_o150_ch330_o158_nu331',
            'no_ch1028_o356_ch323_o151_ch336_o156_nu337',
            'no_ch1028_o356_ch323_o151_ch336_o157_nu338',
            'no_ch1028_o356_ch323_o151_ch336_o158_nu339',
            'no_ch1028_o356_ch323_o152_ch342_o157_nu343',
            'no_ch1028_o356_ch323_o152_ch342_o159_nu343',
            'no_ch1028_o356_ch323_o153_ch348_o158_nu349',
            'no_ch1028_o356_ch323_o153_ch348_o160_nu349',
            'no_ch1028_o356_ch323_o153_ch348_o161_nu349',
            'no_ch1028_o356_ch323_o154_nu353',
            'no_ch1028_o356_ch323_o155_nu353',
            'no_ch1028_o357_ch358_o151_ch359_o163_nu360',
            'no_ch1028_o357_ch358_o151_ch359_o164_nu360',
            'no_ch1028_o357_ch358_o151_ch359_o165_nu360',
            'no_ch1028_o358_nu367',
            'no_ch1028_o359_nu373',
            'no_ch1028_o360_ch379_o167_nu380',
            'no_ch1028_o360_ch379_o168_nu380',
            'no_ch1028_o360_ch379_o169_nu380',
            'no_ch1028_o361_ch385_o170_nu386',
            'no_ch1028_o361_ch385_o171_nu386',
            'no_ch1028_o361_ch385_o172_nu386',
            'no_ch1028_o361_ch385_o173_nu386',
            'no_ch1028_o361_ch385_o174_nu386',
            'no_ch1028_o362_ch392_o175_ch393_o177_nu394',
            'no_ch1028_o362_ch392_o175_ch393_o178_nu394',
            'no_ch1028_o362_ch392_o176_ch1001_o179_nu1002',
            'no_ch1028_o362_ch392_o176_ch1001_o180_nu1002',
            'no_ch1028_o363_ch400_o181_ch401_o183_nu402',
            'no_ch1028_o363_ch400_o181_ch401_o184_nu402',
            'no_ch1028_o363_ch400_o181_ch401_o185_nu402',
            'no_ch1028_o363_ch400_o181_ch401_o186_nu402',
            'no_ch1028_o363_ch400_o181_ch401_o187_nu402',
            'no_ch1028_o363_ch400_o181_ch401_o188_nu402',
            'no_ch1028_o363_ch400_o182_ch410_o185_nu411',
            'no_ch1028_o363_ch400_o182_ch410_o186_nu411',
            'no_ch1028_o363_ch400_o182_ch410_o187_nu411',
            'no_ch1028_o363_ch400_o182_ch410_o188_nu411',
            'no_ch1028_o363_ch400_o182_ch410_o189_nu411',
            'no_ch1028_o363_ch400_o182_ch410_o190_nu411',
            'no_ch1028_o363_ch400_o182_ch410_o191_nu411',
            'no_ch1028_o364_ch420_o195_ch421_o198_nu422',
            'no_ch1028_o364_ch420_o195_ch421_o199_nu422',
            'no_ch1028_o364_ch420_o195_ch421_o200_nu422',
            'no_ch1028_o364_ch420_o195_ch421_o201_nu422',
            'no_ch1028_o364_ch420_o196_ch421_o198_nu422',
            'no_ch1028_o364_ch420_o196_ch421_o199_nu422',
            'no_ch1028_o364_ch420_o196_ch421_o200_nu422',
            'no_ch1028_o364_ch420_o196_ch421_o201_nu422',
            'no_ch1028_o364_ch420_o197_ch421_o198_nu422',
            'no_ch1028_o364_ch420_o197_ch421_o199_nu422',
            'no_ch1028_o364_ch420_o197_ch421_o200_nu422',
            'no_ch1028_o364_ch420_o197_ch421_o201_nu422',
            'no_ch1028_o365_nu429',
            'no_ch1028_o366_nu435',
            'no_ch1028_o367_nu441',
            'no_ch1028_o368_nu447',
            'no_ch1028_o369_nu453',
            ['no_ch1029_o370_ch461_o208_nu462', 'no_ch1029_o370_ch461_o209_nu462', 'no_ch1029_o370_ch461_o210_nu462']
        ];
        $startColumn = 'U';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        // พัดลมคูณ factor 0.7 ด้วย
        // แอร์ 0.75
        // ตู้เย็น 0.45
        // เครื่องทำน้ำอุ่น 0.6
        // เครื่องซักผ้า 0.55
        $table3Eletric = [
            //พัดลม
            ['no_ch1028_o356_ch323_o149_ch324_o156_nu325', 'no_ch1028_o356_ch323_o149_ch324_o156_nu326','no_ch1028_o356_ch323_o149_ch324_o156_nu327',(0.04*0.7)],
            ['no_ch1028_o356_ch323_o149_ch324_o157_nu325', 'no_ch1028_o356_ch323_o149_ch324_o157_nu326','no_ch1028_o356_ch323_o149_ch324_o157_nu327',(0.05*0.7)],
            ['no_ch1028_o356_ch323_o149_ch324_o158_nu325', 'no_ch1028_o356_ch323_o149_ch324_o158_nu326','no_ch1028_o356_ch323_o149_ch324_o158_nu327',(0.078*0.7)],
            ['no_ch1028_o356_ch323_o150_ch330_o156_nu331', 'no_ch1028_o356_ch323_o150_ch330_o156_nu332','no_ch1028_o356_ch323_o150_ch330_o156_nu332',(0.04*0.7)],
            ['no_ch1028_o356_ch323_o150_ch330_o157_nu331', 'no_ch1028_o356_ch323_o150_ch330_o157_nu332','no_ch1028_o356_ch323_o150_ch330_o157_nu332',(0.05*0.7)],
            ['no_ch1028_o356_ch323_o150_ch330_o158_nu331', 'no_ch1028_o356_ch323_o150_ch330_o158_nu332','no_ch1028_o356_ch323_o150_ch330_o158_nu332',(0.078*0.7)],
            ['no_ch1028_o356_ch323_o151_ch336_o156_nu337', 'no_ch1028_o356_ch323_o151_ch336_o156_nu338','no_ch1028_o356_ch323_o151_ch336_o156_nu339',(0.04*0.7)],
            ['no_ch1028_o356_ch323_o151_ch336_o157_nu337', 'no_ch1028_o356_ch323_o151_ch336_o157_nu338','no_ch1028_o356_ch323_o151_ch336_o157_nu339',(0.05*0.7)],
            ['no_ch1028_o356_ch323_o151_ch336_o158_nu337', 'no_ch1028_o356_ch323_o151_ch336_o158_nu338','no_ch1028_o356_ch323_o151_ch336_o158_nu339',(0.078*0.7)],
            ['no_ch1028_o356_ch323_o152_ch342_o157_nu343', 'no_ch1028_o356_ch323_o152_ch342_o157_nu344','no_ch1028_o356_ch323_o152_ch342_o157_nu345',(0.05*0.7)],
            ['no_ch1028_o356_ch323_o152_ch342_o159_nu343', 'no_ch1028_o356_ch323_o152_ch342_o159_nu344','no_ch1028_o356_ch323_o152_ch342_o159_nu345',(0.095*0.7)],
            ['no_ch1028_o356_ch323_o153_ch348_o158_nu349', 'no_ch1028_o356_ch323_o153_ch348_o158_nu350','no_ch1028_o356_ch323_o153_ch348_o158_nu350',(0.125*0.7)],
            ['no_ch1028_o356_ch323_o153_ch348_o160_nu349', 'no_ch1028_o356_ch323_o153_ch348_o160_nu350','no_ch1028_o356_ch323_o153_ch348_o160_nu351',(0.2*0.7)],
            ['no_ch1028_o356_ch323_o153_ch348_o161_nu349', 'no_ch1028_o356_ch323_o153_ch348_o161_nu350','no_ch1028_o356_ch323_o153_ch348_o161_nu351',(0.225*0.7)],
            ['no_ch1028_o356_ch323_o154_nu353', 'no_ch1028_o356_ch323_o154_nu354','no_ch1028_o356_ch323_o154_nu355',(0.045*0.7)],
            ['no_ch1028_o356_ch323_o155_nu353', 'no_ch1028_o356_ch323_o155_nu354','no_ch1028_o356_ch323_o155_nu355',(0.1*0.7)],
            // พัดลมดูดอากาศ
            // TODO-nong ถือเป็นพัดลมมั้ย?
            ['no_ch1028_o357_ch358_o151_ch359_o163_nu360', 'no_ch1028_o357_ch358_o151_ch359_o163_nu361','no_ch1028_o357_ch358_o151_ch359_o163_nu362',0.03],
            ['no_ch1028_o357_ch358_o151_ch359_o164_nu360', 'no_ch1028_o357_ch358_o151_ch359_o164_nu361','no_ch1028_o357_ch358_o151_ch359_o164_nu362',0.035],
            ['no_ch1028_o357_ch358_o151_ch359_o165_nu360', 'no_ch1028_o357_ch358_o151_ch359_o165_nu361','no_ch1028_o357_ch358_o151_ch359_o165_nu362',0.042],
            // เครื่องฟอกอากาศ
            ['no_ch1028_o358_nu367', 'no_ch1028_o358_nu368','no_ch1028_o358_nu368',0.05],
            // เครื่องทำน้ำอุ่น
            ['no_ch1028_o359_nu373', 'no_ch1028_o359_nu374','no_ch1028_o359_nu375', (3.5*0.6)],
            // เครื่องดูดฝุ่น
            ['no_ch1028_o360_ch379_o167_nu380', 'no_ch1028_o360_ch379_o167_nu381','no_ch1028_o360_ch379_o167_nu382',1.6],
            ['no_ch1028_o360_ch379_o168_nu380', 'no_ch1028_o360_ch379_o168_nu381','no_ch1028_o360_ch379_o168_nu382',0.025],
            ['no_ch1028_o360_ch379_o169_nu380', 'no_ch1028_o360_ch379_o169_nu381','no_ch1028_o360_ch379_o169_nu382',0.8],
            // เตารีดไฟฟ้า
            ['no_ch1028_o361_ch385_o170_nu386', 'no_ch1028_o361_ch385_o170_nu387','no_ch1028_o361_ch385_o170_nu388',1],
            ['no_ch1028_o361_ch385_o171_nu386', 'no_ch1028_o361_ch385_o171_nu387','no_ch1028_o361_ch385_o171_nu388',1.2],
            ['no_ch1028_o361_ch385_o172_nu386', 'no_ch1028_o361_ch385_o172_nu387','no_ch1028_o361_ch385_o172_nu388',2.4],
            ['no_ch1028_o361_ch385_o173_nu386', 'no_ch1028_o361_ch385_o173_nu387','no_ch1028_o361_ch385_o173_nu388',1.6],
            ['no_ch1028_o361_ch385_o174_nu386', 'no_ch1028_o361_ch385_o174_nu387','no_ch1028_o361_ch385_o174_nu388',1.5],
            // ตู้เย็น
            ['no_ch1028_o362_ch392_o175_ch393_o177_nu394', 'no_ch1028_o362_ch392_o175_ch393_o177_nu395','no_ch1028_o362_ch392_o175_ch393_o177_nu396',(0.06*0.45)],
            ['no_ch1028_o362_ch392_o175_ch393_o178_nu394', 'no_ch1028_o362_ch392_o175_ch393_o178_nu395','no_ch1028_o362_ch392_o175_ch393_o178_nu396',(0.075*0.45)],
            ['no_ch1028_o362_ch392_o176_ch1001_o179_nu1002', 'no_ch1028_o362_ch392_o176_ch1001_o179_nu1003','no_ch1028_o362_ch392_o176_ch1001_o179_nu1004',(0.09*0.45)],
            ['no_ch1028_o362_ch392_o176_ch1001_o180_nu1002', 'no_ch1028_o362_ch392_o176_ch1001_o180_nu1003','no_ch1028_o362_ch392_o176_ch1001_o180_nu1004',(0.25*0.45)],
            // เครื่องปรับอากาศ หรือแอร์
            ['no_ch1028_o363_ch400_o181_ch401_o183_nu402', 'no_ch1028_o363_ch400_o181_ch401_o183_nu403','no_ch1028_o363_ch400_o181_ch401_o183_nu404',(0.6*0.75)],
            ['no_ch1028_o363_ch400_o181_ch401_o184_nu402', 'no_ch1028_o363_ch400_o181_ch401_o184_nu403','no_ch1028_o363_ch400_o181_ch401_o184_nu404',(0.95*0.75)],
            ['no_ch1028_o363_ch400_o181_ch401_o185_nu402', 'no_ch1028_o363_ch400_o181_ch401_o185_nu403','no_ch1028_o363_ch400_o181_ch401_o185_nu404',(1.3*0.75)],
            ['no_ch1028_o363_ch400_o181_ch401_o186_nu402', 'no_ch1028_o363_ch400_o181_ch401_o186_nu403','no_ch1028_o363_ch400_o181_ch401_o186_nu404',(1.6*0.75)],
            ['no_ch1028_o363_ch400_o181_ch401_o187_nu402', 'no_ch1028_o363_ch400_o181_ch401_o187_nu403','no_ch1028_o363_ch400_o181_ch401_o187_nu404',(2*0.75)],
            ['no_ch1028_o363_ch400_o181_ch401_o188_nu402', 'no_ch1028_o363_ch400_o181_ch401_o188_nu403','no_ch1028_o363_ch400_o181_ch401_o188_nu404',(2.3*0.75)],
            ['no_ch1028_o363_ch400_o182_ch410_o185_nu411', 'no_ch1028_o363_ch400_o182_ch410_o185_nu412','no_ch1028_o363_ch400_o182_ch410_o185_nu413',(1.55*0.75)],
            ['no_ch1028_o363_ch400_o182_ch410_o186_nu411', 'no_ch1028_o363_ch400_o182_ch410_o186_nu412','no_ch1028_o363_ch400_o182_ch410_o186_nu413',(1.75*0.75)],
            ['no_ch1028_o363_ch400_o182_ch410_o187_nu411', 'no_ch1028_o363_ch400_o182_ch410_o187_nu412','no_ch1028_o363_ch400_o182_ch410_o187_nu413',(2.15*0.75)],
            ['no_ch1028_o363_ch400_o182_ch410_o188_nu411', 'no_ch1028_o363_ch400_o182_ch410_o188_nu412','no_ch1028_o363_ch400_o182_ch410_o188_nu413',(2.3*0.75)],
            ['no_ch1028_o363_ch400_o182_ch410_o189_nu411', 'no_ch1028_o363_ch400_o182_ch410_o189_nu412','no_ch1028_o363_ch400_o182_ch410_o189_nu413',(3*0.75)],
            ['no_ch1028_o363_ch400_o182_ch410_o190_nu411', 'no_ch1028_o363_ch400_o182_ch410_o190_nu412','no_ch1028_o363_ch400_o182_ch410_o190_nu413',(3.5*0.75)],
            ['no_ch1028_o363_ch400_o182_ch410_o191_nu411', 'no_ch1028_o363_ch400_o182_ch410_o191_nu412','no_ch1028_o363_ch400_o182_ch410_o191_nu413',(5.3*0.75)],
            // เครื่องซักผ้าและอบผ้า
            ['no_ch1028_o364_ch420_o195_ch421_o198_nu422', 'no_ch1028_o364_ch420_o195_ch421_o198_nu423','no_ch1028_o364_ch420_o195_ch421_o198_nu424',(2*0.55)],
            ['no_ch1028_o364_ch420_o195_ch421_o199_nu422', 'no_ch1028_o364_ch420_o195_ch421_o199_nu423','no_ch1028_o364_ch420_o195_ch421_o199_nu424',(2.2*0.55)],
            ['no_ch1028_o364_ch420_o195_ch421_o200_nu422', 'no_ch1028_o364_ch420_o195_ch421_o200_nu423','no_ch1028_o364_ch420_o195_ch421_o200_nu424',(2.2*0.55)],
            ['no_ch1028_o364_ch420_o195_ch421_o201_nu422', 'no_ch1028_o364_ch420_o195_ch421_o201_nu423','no_ch1028_o364_ch420_o195_ch421_o201_nu424',(2.3*0.55)],
            ['no_ch1028_o364_ch420_o196_ch421_o198_nu422', 'no_ch1028_o364_ch420_o196_ch421_o198_nu423','no_ch1028_o364_ch420_o196_ch421_o198_nu424',(0.35*0.55)],
            ['no_ch1028_o364_ch420_o196_ch421_o199_nu422', 'no_ch1028_o364_ch420_o196_ch421_o199_nu423','no_ch1028_o364_ch420_o196_ch421_o199_nu424',(0.4*0.55)],
            ['no_ch1028_o364_ch420_o196_ch421_o200_nu422', 'no_ch1028_o364_ch420_o196_ch421_o200_nu423','no_ch1028_o364_ch420_o196_ch421_o200_nu424',(0.5*0.55)],
            ['no_ch1028_o364_ch420_o196_ch421_o201_nu422', 'no_ch1028_o364_ch420_o196_ch421_o201_nu423','no_ch1028_o364_ch420_o196_ch421_o201_nu424',(0.55*0.55)],
            ['no_ch1028_o364_ch420_o197_ch421_o198_nu422', 'no_ch1028_o364_ch420_o197_ch421_o198_nu423','no_ch1028_o364_ch420_o197_ch421_o198_nu424',(0.3*0.55)],
            ['no_ch1028_o364_ch420_o197_ch421_o199_nu422', 'no_ch1028_o364_ch420_o197_ch421_o199_nu423','no_ch1028_o364_ch420_o197_ch421_o199_nu424',(0.35*0.55)],
            ['no_ch1028_o364_ch420_o197_ch421_o200_nu422', 'no_ch1028_o364_ch420_o197_ch421_o200_nu423','no_ch1028_o364_ch420_o197_ch421_o200_nu424',(0.4*0.55)],
            ['no_ch1028_o364_ch420_o197_ch421_o201_nu422', 'no_ch1028_o364_ch420_o197_ch421_o201_nu423','no_ch1028_o364_ch420_o197_ch421_o201_nu424',(0.45*0.55)],

            [],
            [],

            ['no_ch1028_o367_nu441', 'no_ch1028_o367_nu442','no_ch1028_o367_nu443',0.01],
            ['no_ch1028_o368_nu447', 'no_ch1028_o368_nu448','no_ch1028_o368_nu449',1.4],
            ['no_ch1028_o369_nu453', 'no_ch1028_o369_nu454','no_ch1028_o369_nu455',0.2],
        ];
        $startColumn = "AL";
        $ktoe = Parameter::$ktoe[Parameter::ELECTRIC];
        $week = Parameter::WEEK_PER_YEAR;
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) * sum(if(unique_key='param2', answer_numeric,0)) * sum(if(unique_key='param3', answer_numeric,0))) * {$week}  * (param4) as sumAmount ";
        //TODO-nong test only
//        $sumAmountSQL .= ", sum(IF(unique_key='param1',answer_numeric,0)) as amount, sum(if(unique_key='param2', answer_numeric,0)) as hourPerDay, sum(if(unique_key='param3', answer_numeric,0)) as dayPerWeek, main_id ";
        // end test only
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2,
            'param4'=>3
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL,$params,$ktoe);
//        dd();

        // ไดร์เป่าผม และเครื่องหนีบผม
        $tabl3MinuteEachTime = [
            ['no_ch1028_o365_nu429', 'no_ch1028_o365_nu430','no_ch1028_o365_nu431',1.1],
            ['no_ch1028_o366_nu435', 'no_ch1028_o366_nu436','no_ch1028_o366_nu437',0.05]
        ];
        $startRow = 71;
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) * sum(if(unique_key='param2', answer_numeric,0)) * sum(if(unique_key='param3', answer_numeric,0))) * {$week}/60  * (param4) as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2,
            'param4'=>3
        ];
        $objPHPExcel = Summary::usageElectric($tabl3MinuteEachTime, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL,$params,$ktoe);

        $table3Petro = [
            ['no_ch1029_o370_ch461_o208_nu462','no_ch1029_o370_ch461_o208_nu463',4
                ,'no_ch1029_o370_ch461_o209_nu462','no_ch1029_o370_ch461_o209_nu463',15
                ,'no_ch1029_o370_ch461_o210_nu462','no_ch1029_o370_ch461_o210_nu463',48]
        ];
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * param3) + " .
                        " (sum(IF(unique_key='param4',answer_numeric,0)) * sum(IF(unique_key='param5',answer_numeric,0)) * param6) + " .
                        " (sum(IF(unique_key='param7',answer_numeric,0)) * sum(IF(unique_key='param8',answer_numeric,0)) * param9) as sumAmount ";
        $params = [
          'param1'=>0,'param2'=>1, 'param3'=>2,
            'param4'=>3,'param5'=>4, 'param6'=>5,
            'param7'=>6, 'param8'=>7, 'param9'=>8
        ];
        $ktoe = Parameter::GAS_KTOE;
        $gasStartRow = 76;
        $objPHPExcel = Summary::usageElectric($table3Petro, $startColumn, $gasStartRow,$objPHPExcel, $mainObj,$sumAmountSQL,$params,$ktoe, true);

        $table4 = [
            'no_ch1028_o356_ch323_o149_ch324_o156_nu328',
            'no_ch1028_o356_ch323_o149_ch324_o157_nu328',
            'no_ch1028_o356_ch323_o149_ch324_o158_nu328',
            'no_ch1028_o356_ch323_o150_ch330_o156_nu334',
            'no_ch1028_o356_ch323_o150_ch330_o157_nu334',
            'no_ch1028_o356_ch323_o150_ch330_o158_nu334',
            'no_ch1028_o356_ch323_o151_ch336_o156_nu340',
            'no_ch1028_o356_ch323_o151_ch336_o157_nu340',
            'no_ch1028_o356_ch323_o151_ch336_o158_nu340',
            'no_ch1028_o356_ch323_o152_ch342_o157_nu346',
            'no_ch1028_o356_ch323_o152_ch342_o159_nu346',
            'no_ch1028_o356_ch323_o153_ch348_o158_nu352',
            'no_ch1028_o356_ch323_o153_ch348_o160_nu352',
            'no_ch1028_o356_ch323_o153_ch348_o161_nu352',
            'no_ch1028_o356_ch323_o154_nu356',
            'no_ch1028_o356_ch323_o155_nu356',
            'no_ch1028_o357_ch358_o151_ch359_o163_nu363',
            'no_ch1028_o357_ch358_o151_ch359_o164_nu363',
            'no_ch1028_o357_ch358_o151_ch359_o165_nu363',
            'no_ch1028_o358_nu370',
            'no_ch1028_o359_nu376',
            'no_ch1028_o360_ch379_o167_nu383',
            'no_ch1028_o360_ch379_o168_nu383',
            'no_ch1028_o360_ch379_o169_nu383',
            'no_ch1028_o361_ch385_o170_nu389',
            'no_ch1028_o361_ch385_o171_nu389',
            'no_ch1028_o361_ch385_o172_nu389',
            'no_ch1028_o361_ch385_o173_nu389',
            'no_ch1028_o361_ch385_o174_nu389',
            'no_ch1028_o362_ch392_o175_ch393_o177_nu397',
            'no_ch1028_o362_ch392_o175_ch393_o178_nu397',
            'no_ch1028_o362_ch392_o176_ch1001_o179_nu1005',
            'no_ch1028_o362_ch392_o176_ch1001_o180_nu1005',
            'no_ch1028_o363_ch400_o181_ch401_o183_nu405',
            'no_ch1028_o363_ch400_o181_ch401_o184_nu405',
            'no_ch1028_o363_ch400_o181_ch401_o185_nu405',
            'no_ch1028_o363_ch400_o181_ch401_o186_nu405',
            'no_ch1028_o363_ch400_o181_ch401_o187_nu405',
            'no_ch1028_o363_ch400_o181_ch401_o188_nu405',
            'no_ch1028_o363_ch400_o182_ch410_o185_nu414',
            'no_ch1028_o363_ch400_o182_ch410_o186_nu414',
            'no_ch1028_o363_ch400_o182_ch410_o187_nu414',
            'no_ch1028_o363_ch400_o182_ch410_o188_nu414',
            'no_ch1028_o363_ch400_o182_ch410_o189_nu414',
            'no_ch1028_o363_ch400_o182_ch410_o190_nu414',
            'no_ch1028_o363_ch400_o182_ch410_o191_nu414',
            'no_ch1028_o364_ch420_o195_ch421_o198_nu425',
            'no_ch1028_o364_ch420_o195_ch421_o199_nu425',
            'no_ch1028_o364_ch420_o195_ch421_o200_nu425',
            'no_ch1028_o364_ch420_o195_ch421_o201_nu425',
            'no_ch1028_o364_ch420_o196_ch421_o198_nu425',
            'no_ch1028_o364_ch420_o196_ch421_o199_nu425',
            'no_ch1028_o364_ch420_o196_ch421_o200_nu425',
            'no_ch1028_o364_ch420_o196_ch421_o201_nu425',
            'no_ch1028_o364_ch420_o197_ch421_o198_nu425',
            'no_ch1028_o364_ch420_o197_ch421_o199_nu426',
            'no_ch1028_o364_ch420_o197_ch421_o200_nu427',
            'no_ch1028_o364_ch420_o197_ch421_o201_nu428',
            'no_ch1028_o365_nu432',
            'no_ch1028_o366_nu438',
            'no_ch1028_o367_nu444',
            'no_ch1028_o368_nu450',
            'no_ch1028_o369_nu456',
            ['no_ch1029_o370_ch461_o208_nu464', 'no_ch1029_o370_ch461_o209_nu464', 'no_ch1029_o370_ch461_o210_nu464']
        ];
        $startColumn = 'BB';
        $objPHPExcel = Summary::average($table4, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return true;
    }

    public static function report915()
    {
        set_time_limit(3600);

        // หมวดเพื่อความอบอุ่น
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.5';
        $outputFile = 'sum915.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);
        $table1 = [
            'no_ch1030_o371_ch466_o100',
            'no_ch1030_o371_ch466_o101',
            'no_ch1030_o371_ch466_o102',
            'no_ch1030_o371_ch466_o103',
            'no_ch1030_o372_ch472_o100',
            'no_ch1030_o372_ch472_o101',
            'no_ch1030_o372_ch472_o102',
            'no_ch1030_o372_ch472_o103'
        ];

        $table2 = [
            'no_ch1030_o371_ch466_o100_nu467',
            'no_ch1030_o371_ch466_o101_nu467',
            'no_ch1030_o371_ch466_o102_nu467',
            'no_ch1030_o371_ch466_o103_nu467',
            'no_ch1030_o372_ch472_o100_nu473',
            'no_ch1030_o372_ch472_o101_nu473',
            'no_ch1030_o372_ch472_o102_nu473',
            'no_ch1030_o372_ch472_o103_nu473',
        ];

        $table3 = [
            ['no_ch1030_o371_ch466_o100_nu467','no_ch1030_o371_ch466_o100_nu468','no_ch1030_o371_ch466_o100_nu469',0.37848],
            ['no_ch1030_o371_ch466_o101_nu467','no_ch1030_o371_ch466_o101_nu468','no_ch1030_o371_ch466_o101_nu469',0.68364],
            ['no_ch1030_o371_ch466_o102_nu467','no_ch1030_o371_ch466_o102_nu468','no_ch1030_o371_ch466_o102_nu469',0.34083],
            ['no_ch1030_o371_ch466_o103_nu467','no_ch1030_o371_ch466_o103_nu468','no_ch1030_o371_ch466_o103_nu469',0.30021],
            ['no_ch1030_o372_ch472_o100_nu473','no_ch1030_o372_ch472_o100_nu474','no_ch1030_o372_ch472_o100_nu475',0.37848],
            ['no_ch1030_o372_ch472_o101_nu473','no_ch1030_o372_ch472_o101_nu474','no_ch1030_o372_ch472_o101_nu475',0.68364],
            ['no_ch1030_o372_ch472_o102_nu473','no_ch1030_o372_ch472_o102_nu474','no_ch1030_o372_ch472_o102_nu475',0.34083],
            ['no_ch1030_o372_ch472_o103_nu473','no_ch1030_o372_ch472_o103_nu474','no_ch1030_o372_ch472_o103_nu475',0.30021],
        ];

        $table4 = [
            'no_ch1030_o371_ch466_o100_nu470',
            'no_ch1030_o371_ch466_o101_nu470',
            'no_ch1030_o371_ch466_o102_nu470',
            'no_ch1030_o371_ch466_o103_nu470',
            'no_ch1030_o372_ch472_o100_nu476',
            'no_ch1030_o372_ch472_o101_nu476',
            'no_ch1030_o372_ch472_o102_nu476',
            'no_ch1030_o372_ch472_o103_nu476',
        ];

        $startColumn = ['E','U','AL','BB'];
        $startRow = 13;

        $objPHPExcel = Summary::sum($table1, $startColumn[0], $startRow, $objPHPExcel, $mainObj);
        $objPHPExcel = Summary::average($table2, $startColumn[1], $startRow, $objPHPExcel, $mainObj);
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12 as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2
        ];
        $objPHPExcel = Summary::usageElectric($table3, $startColumn[2], $startRow,$objPHPExcel, $mainObj,$sumAmountSQL,$params,0,false,3);
        $objPHPExcel = Summary::average($table4, $startColumn[3], $startRow, $objPHPExcel, $mainObj);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

    public static function report916()
    {
        set_time_limit(3600);

        // หมวดไล่และล่อแมลง
        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.6';
        $outputFile = 'sum916.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);
        $table1 = [
            'no_ch1031_o373',
            'no_ch1031_o374',
            'no_ch1032_o375_ch490_o100',
            'no_ch1032_o375_ch490_o101',
            'no_ch1032_o375_ch490_o102',
            'no_ch1032_o375_ch490_o103'
        ];

        $table2=[
            'no_ch1031_o373_nu479',
            'no_ch1031_o374_nu485',
            'no_ch1032_o375_ch490_o100_nu491',
            'no_ch1032_o375_ch490_o101_nu491',
            'no_ch1032_o375_ch490_o102_nu491',
            'no_ch1032_o375_ch490_o103_nu491'
        ];

        $table3_1 = [
            ['no_ch1031_o373_nu479','no_ch1031_o373_nu480','no_ch1031_o373_nu481',0.01],
            ['no_ch1031_o374_nu485','no_ch1031_o374_nu486','no_ch1031_o374_nu487',0.1]
        ];
        $table3_2 = [
            ['no_ch1032_o375_ch490_o100_nu491','no_ch1032_o375_ch490_o100_nu492','no_ch1032_o375_ch490_o100_nu493',0.37848],
            ['no_ch1032_o375_ch490_o101_nu491','no_ch1032_o375_ch490_o101_nu492','no_ch1032_o375_ch490_o101_nu493',0.68364],
            ['no_ch1032_o375_ch490_o102_nu491','no_ch1032_o375_ch490_o102_nu492','no_ch1032_o375_ch490_o102_nu493',0.34083],
            ['no_ch1032_o375_ch490_o103_nu491','no_ch1032_o375_ch490_o103_nu492','no_ch1032_o375_ch490_o103_nu493',0.30021]
        ];

        $table4 = [
            'no_ch1031_o373_nu482',
            'no_ch1031_o374_nu488',
            'no_ch1032_o375_ch490_o100_nu494',
            'no_ch1032_o375_ch490_o101_nu494',
            'no_ch1032_o375_ch490_o102_nu494',
            'no_ch1032_o375_ch490_o103_nu494',
        ];

        $startColumn = 'E';
        $startRow = 13;

        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $startColumn = 'U';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $week = Parameter::WEEK_PER_YEAR;
        $elecKTOE = Parameter::ELECTRIC_KTOE;
        $sumAmountSQL_1 = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * {$week} * (param4) as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2,
            'param4'=>3
        ];
        $startColumn = 'AL';
        $objPHPExcel = Summary::usageElectric($table3_1, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL_1,$params,$elecKTOE);
        $sumAmountSQL_2 = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12 as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2
        ];
        $startRow = 15;
        $objPHPExcel = Summary::usageElectric($table3_2, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL_2,$params,0,false,3);
        $startRow = 13;
        $startColumn = 'BB';
        $objPHPExcel = Summary::average($table4, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

    public static function report917()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.7';
        $outputFile = 'sum917.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            ['no_ch1033_o376_ch495_o300_ra498'=>213, 'no_ch1033_o376_ch495_o301_ra498'=>213, 'no_ch1033_o376_ch495_o302_ra498'=>213, 'no_ch1033_o376_ch495_o303_ra498'=>213, 'no_ch1033_o376_ch495_o304_ra498'=>213],
            ['no_ch1033_o376_ch495_o300_ra498'=>214, 'no_ch1033_o376_ch495_o301_ra498'=>214, 'no_ch1033_o376_ch495_o302_ra498'=>214, 'no_ch1033_o376_ch495_o303_ra498'=>214, 'no_ch1033_o376_ch495_o304_ra498'=>214],
            ['no_ch1033_o376_ch495_o300_ra498'=>215, 'no_ch1033_o376_ch495_o301_ra498'=>215, 'no_ch1033_o376_ch495_o302_ra498'=>215, 'no_ch1033_o376_ch495_o303_ra498'=>215, 'no_ch1033_o376_ch495_o304_ra498'=>215],
            ['no_ch1033_o376_ch495_o300_ra498'=>216, 'no_ch1033_o376_ch495_o301_ra498'=>216, 'no_ch1033_o376_ch495_o302_ra498'=>216, 'no_ch1033_o376_ch495_o303_ra498'=>216, 'no_ch1033_o376_ch495_o304_ra498'=>216],
            ['no_ch1033_o376_ch495_o300_ra498'=>217, 'no_ch1033_o376_ch495_o301_ra498'=>217, 'no_ch1033_o376_ch495_o302_ra498'=>217, 'no_ch1033_o376_ch495_o303_ra498'=>217, 'no_ch1033_o376_ch495_o304_ra498'=>213],
            ['no_ch1033_o377_ch504_o300_ra507'=>213, 'no_ch1033_o377_ch504_o301_ra507'=>213, 'no_ch1033_o377_ch504_o302_ra507'=>213, 'no_ch1033_o377_ch504_o303_ra507'=>213, 'no_ch1033_o377_ch504_o304_ra507'=>213],
            ['no_ch1033_o377_ch504_o300_ra507'=>214, 'no_ch1033_o377_ch504_o301_ra507'=>214, 'no_ch1033_o377_ch504_o302_ra507'=>214, 'no_ch1033_o377_ch504_o303_ra507'=>214, 'no_ch1033_o377_ch504_o304_ra507'=>214],
            ['no_ch1033_o377_ch504_o300_ra507'=>215, 'no_ch1033_o377_ch504_o301_ra507'=>215, 'no_ch1033_o377_ch504_o302_ra507'=>215, 'no_ch1033_o377_ch504_o303_ra507'=>215, 'no_ch1033_o377_ch504_o304_ra507'=>215],
            ['no_ch1033_o377_ch504_o300_ra507'=>216, 'no_ch1033_o377_ch504_o301_ra507'=>216, 'no_ch1033_o377_ch504_o302_ra507'=>216, 'no_ch1033_o377_ch504_o303_ra507'=>216, 'no_ch1033_o377_ch504_o304_ra507'=>216],
            ['no_ch1033_o377_ch504_o300_ra507'=>217, 'no_ch1033_o377_ch504_o301_ra507'=>217, 'no_ch1033_o377_ch504_o302_ra507'=>217, 'no_ch1033_o377_ch504_o303_ra507'=>217, 'no_ch1033_o377_ch504_o304_ra507'=>217],
            ['no_ch1033_o377_ch504_o300_ra507'=>218, 'no_ch1033_o377_ch504_o301_ra507'=>218, 'no_ch1033_o377_ch504_o302_ra507'=>218, 'no_ch1033_o377_ch504_o303_ra507'=>218, 'no_ch1033_o377_ch504_o304_ra507'=>218],
            ['no_ch1033_o377_ch504_o300_ra507'=>220, 'no_ch1033_o377_ch504_o301_ra507'=>220, 'no_ch1033_o377_ch504_o302_ra507'=>220, 'no_ch1033_o377_ch504_o303_ra507'=>220, 'no_ch1033_o377_ch504_o304_ra507'=>220],
            ['no_ch1033_o377_ch504_o300_ra507'=>219, 'no_ch1033_o377_ch504_o301_ra507'=>219, 'no_ch1033_o377_ch504_o302_ra507'=>219, 'no_ch1033_o377_ch504_o303_ra507'=>219, 'no_ch1033_o377_ch504_o304_ra507'=>219],
            ['no_ch1033_o378_ch513_o300_ra516'=>213, 'no_ch1033_o378_ch513_o301_ra516'=>213, 'no_ch1033_o378_ch513_o302_ra516'=>213, 'no_ch1033_o378_ch513_o303_ra516'=>213, 'no_ch1033_o378_ch513_o304_ra516'=>213],
            ['no_ch1033_o378_ch513_o300_ra516'=>214, 'no_ch1033_o378_ch513_o301_ra516'=>214, 'no_ch1033_o378_ch513_o302_ra516'=>214, 'no_ch1033_o378_ch513_o303_ra516'=>214, 'no_ch1033_o378_ch513_o304_ra516'=>214],
            ['no_ch1033_o378_ch513_o300_ra516'=>215, 'no_ch1033_o378_ch513_o301_ra516'=>215, 'no_ch1033_o378_ch513_o302_ra516'=>215, 'no_ch1033_o378_ch513_o303_ra516'=>215, 'no_ch1033_o378_ch513_o304_ra516'=>215],
            ['no_ch1033_o378_ch513_o300_ra516'=>216, 'no_ch1033_o378_ch513_o301_ra516'=>216, 'no_ch1033_o378_ch513_o302_ra516'=>216, 'no_ch1033_o378_ch513_o303_ra516'=>216, 'no_ch1033_o378_ch513_o304_ra516'=>216],
            ['no_ch1033_o378_ch513_o300_ra516'=>217, 'no_ch1033_o378_ch513_o301_ra516'=>217, 'no_ch1033_o378_ch513_o302_ra516'=>217, 'no_ch1033_o378_ch513_o303_ra516'=>217, 'no_ch1033_o378_ch513_o304_ra516'=>217],
            ['no_ch1033_o378_ch513_o300_ra516'=>218, 'no_ch1033_o378_ch513_o301_ra516'=>218, 'no_ch1033_o378_ch513_o302_ra516'=>218, 'no_ch1033_o378_ch513_o303_ra516'=>218, 'no_ch1033_o378_ch513_o304_ra516'=>218],
            ['no_ch1033_o378_ch513_o300_ra516'=>220, 'no_ch1033_o378_ch513_o301_ra516'=>220, 'no_ch1033_o378_ch513_o302_ra516'=>220, 'no_ch1033_o378_ch513_o303_ra516'=>220, 'no_ch1033_o378_ch513_o304_ra516'=>220],
            ['no_ch1033_o378_ch513_o300_ra516'=>219, 'no_ch1033_o378_ch513_o301_ra516'=>219, 'no_ch1033_o378_ch513_o302_ra516'=>219, 'no_ch1033_o378_ch513_o303_ra516'=>219, 'no_ch1033_o378_ch513_o304_ra516'=>219]
        ];

        $table2 = [
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o376_ch495_o300_nu499', 'no_ch1033_o376_ch495_o301_nu499', 'no_ch1033_o376_ch495_o302_nu499', 'no_ch1033_o376_ch495_o303_nu499', 'no_ch1033_o376_ch495_o304_nu499'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o377_ch504_o300_nu508', 'no_ch1033_o377_ch504_o301_nu508', 'no_ch1033_o377_ch504_o302_nu508', 'no_ch1033_o377_ch504_o303_nu508', 'no_ch1033_o377_ch504_o304_nu508'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517'],
            ['no_ch1033_o378_ch513_o300_nu517', 'no_ch1033_o378_ch513_o301_nu517', 'no_ch1033_o378_ch513_o302_nu517', 'no_ch1033_o378_ch513_o303_nu517', 'no_ch1033_o378_ch513_o304_nu517']
        ];

        // จำนวนเงินที่เติม
        $moneyFill = [
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o376_ch495_o300_nu500', 'no_ch1033_o376_ch495_o301_nu500', 'no_ch1033_o376_ch495_o302_nu500', 'no_ch1033_o376_ch495_o303_nu500', 'no_ch1033_o376_ch495_o304_nu500'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o377_ch504_o300_nu509', 'no_ch1033_o377_ch504_o301_nu509', 'no_ch1033_o377_ch504_o302_nu509', 'no_ch1033_o377_ch504_o303_nu509', 'no_ch1033_o377_ch504_o304_nu509'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518'],
            ['no_ch1033_o378_ch513_o300_nu518', 'no_ch1033_o378_ch513_o301_nu518', 'no_ch1033_o378_ch513_o302_nu518', 'no_ch1033_o378_ch513_o303_nu518', 'no_ch1033_o378_ch513_o304_nu518']
        ];

        // ความถี่ที่เติม
        $frequencyFill = [
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o376_ch495_o300_nu501', 'no_ch1033_o376_ch495_o301_nu501', 'no_ch1033_o376_ch495_o302_nu501', 'no_ch1033_o376_ch495_o303_nu501', 'no_ch1033_o376_ch495_o304_nu501'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o377_ch504_o300_nu510', 'no_ch1033_o377_ch504_o301_nu510', 'no_ch1033_o377_ch504_o302_nu510', 'no_ch1033_o377_ch504_o303_nu510', 'no_ch1033_o377_ch504_o304_nu510'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519'],
            ['no_ch1033_o378_ch513_o300_nu519', 'no_ch1033_o378_ch513_o301_nu519', 'no_ch1033_o378_ch513_o302_nu519', 'no_ch1033_o378_ch513_o303_nu519', 'no_ch1033_o378_ch513_o304_nu519']
        ];

        $priceFields = [
            'no_ra808_o81_ti809_ch810_o228_nu820',
            'no_ra808_o81_ti809_ch810_o229_nu820',
            'no_ra808_o81_ti809_ch810_o230_nu820',
            'no_ra808_o81_ti809_ch810_o231_nu820',
            'no_ra808_o81_ti809_ch810_o232_nu820',

            'no_ra808_o81_ti809_ch810_o228_nu820',
            'no_ra808_o81_ti809_ch810_o229_nu820',
            'no_ra808_o81_ti809_ch810_o230_nu820',
            'no_ra808_o81_ti809_ch810_o231_nu820',
            'no_ra808_o81_ti809_ch810_o232_nu820',
            'no_ra808_o81_ti809_ch810_o233_nu820',
            'no_ra808_o81_ti809_ch810_o234_nu820',
            'no_ra808_o81_ti809_ch810_o235_nu820',

            'no_ra808_o81_ti809_ch810_o228_nu820',
            'no_ra808_o81_ti809_ch810_o229_nu820',
            'no_ra808_o81_ti809_ch810_o230_nu820',
            'no_ra808_o81_ti809_ch810_o231_nu820',
            'no_ra808_o81_ti809_ch810_o232_nu820',
            'no_ra808_o81_ti809_ch810_o233_nu820',
            'no_ra808_o81_ti809_ch810_o234_nu820',
            'no_ra808_o81_ti809_ch810_o235_nu820'
        ];
        $table3 = [];
        $countTable1 = count($table1);
        $gasPrice = 20;
        $ktoe = 0.745;
        $radioCondition = " IF(SUM(IF(unique_key='param1' AND option_id=param2,1,0))>1,1,SUM(IF(unique_key='param1' AND option_id=param2,1,0))) ";
        $sql = " (param1 * (SUM(IF(unique_key='param2', answer_numeric,0))) * 
         (SUM(IF(unique_key='param3', answer_numeric,0))/ SUM(IF(unique_key='param4', answer_numeric,0))) *
         (SUM(IF(unique_key='param5', answer_numeric,0))) * 12) ";
        $whereSql = "";
        for($i=0;$i<$countTable1;$i++){
            $sumAmountSql = "";
            $j=0;
            foreach ($table1[$i] as $key=>$value){
                $tempRadioCon = $radioCondition;
                $tempRadioCon = str_replace('param1', $key, $tempRadioCon);
                $tempRadioCon = str_replace('param2', $value, $tempRadioCon);

                $tempSql = $sql;
                $tempSql = str_replace('param1',$tempRadioCon, $tempSql);
                $tempSql = str_replace('param2',$table2[$i][$j], $tempSql);
                $tempSql = str_replace('param3',$moneyFill[$i][$j], $tempSql);
                $tempSql = str_replace('param4',$priceFields[$i], $tempSql);
                $tempSql = str_replace('param5',$frequencyFill[$i][$j], $tempSql);
//                $tempSql = str_replace('param5',$table2[$i][$j], $tempSql);

                $sumAmountSql .= $tempSql . " + ";
                $j++;
            }
            $sumAmountSql .= $sumAmountSql . " 0 ";
            $table3[] = $sumAmountSql;
        }

        $table4 = [
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o376_ch495_o300_nu503', 'no_ch1033_o376_ch495_o301_nu503', 'no_ch1033_o376_ch495_o302_nu503', 'no_ch1033_o376_ch495_o303_nu503', 'no_ch1033_o376_ch495_o304_nu503'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o377_ch504_o300_nu512', 'no_ch1033_o377_ch504_o301_nu512', 'no_ch1033_o377_ch504_o302_nu512', 'no_ch1033_o377_ch504_o303_nu512', 'no_ch1033_o377_ch504_o304_nu512'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521'],
            ['no_ch1033_o378_ch513_o300_nu521', 'no_ch1033_o378_ch513_o301_nu521', 'no_ch1033_o378_ch513_o302_nu521', 'no_ch1033_o378_ch513_o303_nu521', 'no_ch1033_o378_ch513_o304_nu521']
        ];

        $isRadio = true;
        $startColumn = 'E';
        $objPHPExcel = Summary::sum($table1,$startColumn,13,$objPHPExcel,$mainObj,$isRadio);
        $startColumn = 'U';
        $objPHPExcel =Summary::average($table2, $startColumn, 13, $objPHPExcel, $mainObj, $isRadio, $table1);
        $startColumn = 'AL';
        $objPHPExcel = Summary::specialUsage($table3, $startColumn, 13, $objPHPExcel,$mainObj,$ktoe);
        $startColumn = 'BB';
        $objPHPExcel = Summary::average($table4, $startColumn, 13, $objPHPExcel, $mainObj, $isRadio, $table1);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

    public static function report918()
    {
        set_time_limit(3600);

        $table1 = [
            'no_ch1034_o379_ch523_o213',
            'no_ch1034_o379_ch523_o214',
            'no_ch1034_o379_ch523_o215',
            'no_ch1034_o379_ch523_o216',
            'no_ch1034_o379_ch523_o217',
            'no_ch1034_o380_ch529_o213',
            'no_ch1034_o380_ch529_o214',
            'no_ch1034_o380_ch529_o215',
            'no_ch1034_o380_ch529_o216',
            'no_ch1034_o380_ch529_o217',
            'no_ch1034_o381_ch535_o213',
            'no_ch1034_o381_ch535_o214',
            'no_ch1034_o381_ch535_o215',
            'no_ch1034_o381_ch535_o216',
            'no_ch1034_o381_ch535_o217',
            'no_ch1034_o382_ch541_o213',
            'no_ch1034_o382_ch541_o214',
            'no_ch1034_o382_ch541_o215',
            'no_ch1034_o382_ch541_o216',
            'no_ch1034_o382_ch541_o217',
            'no_ch1034_o382_ch541_o218',
            'no_ch1034_o383_ch547_o213',
            'no_ch1034_o383_ch547_o214',
            'no_ch1034_o383_ch547_o215',
            'no_ch1034_o383_ch547_o216',
            'no_ch1034_o383_ch547_o217',
            'no_ch1034_o383_ch547_o218',
            'no_ch1034_o384_ch553_o213',
            'no_ch1034_o384_ch553_o214',
            'no_ch1034_o384_ch553_o215',
            'no_ch1034_o384_ch553_o216',
            'no_ch1034_o384_ch553_o217',
            'no_ch1034_o384_ch553_o218',
        ];
        $table2 = [
            'no_ch1034_o379_ch523_o213_nu524',
            'no_ch1034_o379_ch523_o214_nu524',
            'no_ch1034_o379_ch523_o215_nu524',
            'no_ch1034_o379_ch523_o216_nu524',
            'no_ch1034_o379_ch523_o217_nu524',
            'no_ch1034_o380_ch529_o213_nu530',
            'no_ch1034_o380_ch529_o214_nu530',
            'no_ch1034_o380_ch529_o215_nu530',
            'no_ch1034_o380_ch529_o216_nu530',
            'no_ch1034_o380_ch529_o217_nu530',
            'no_ch1034_o381_ch535_o213_nu536',
            'no_ch1034_o381_ch535_o214_nu536',
            'no_ch1034_o381_ch535_o215_nu536',
            'no_ch1034_o381_ch535_o216_nu536',
            'no_ch1034_o381_ch535_o217_nu536',
            'no_ch1034_o382_ch541_o213_nu542',
            'no_ch1034_o382_ch541_o214_nu542',
            'no_ch1034_o382_ch541_o215_nu542',
            'no_ch1034_o382_ch541_o216_nu542',
            'no_ch1034_o382_ch541_o217_nu542',
            'no_ch1034_o382_ch541_o218_nu542',
            'no_ch1034_o383_ch547_o213_nu548',
            'no_ch1034_o383_ch547_o214_nu548',
            'no_ch1034_o383_ch547_o215_nu548',
            'no_ch1034_o383_ch547_o216_nu548',
            'no_ch1034_o383_ch547_o217_nu548',
            'no_ch1034_o383_ch547_o218_nu548',
            'no_ch1034_o384_ch553_o213_nu554',
            'no_ch1034_o384_ch553_o214_nu554',
            'no_ch1034_o384_ch553_o215_nu554',
            'no_ch1034_o384_ch553_o216_nu554',
            'no_ch1034_o384_ch553_o217_nu554',
            'no_ch1034_o384_ch553_o218_nu554',
        ];
        $table3 = [
            ['no_ch1034_o379_ch523_o213_nu524','no_ch1034_o379_ch523_o213_nu525','no_ch1034_o379_ch523_o213_nu526'],
            ['no_ch1034_o379_ch523_o214_nu524','no_ch1034_o379_ch523_o214_nu525','no_ch1034_o379_ch523_o214_nu526'],
            ['no_ch1034_o379_ch523_o215_nu524','no_ch1034_o379_ch523_o215_nu525','no_ch1034_o379_ch523_o215_nu526'],
            ['no_ch1034_o379_ch523_o216_nu524','no_ch1034_o379_ch523_o216_nu525','no_ch1034_o379_ch523_o216_nu526'],
            ['no_ch1034_o379_ch523_o217_nu524','no_ch1034_o379_ch523_o217_nu525','no_ch1034_o379_ch523_o217_nu526'],
            ['no_ch1034_o380_ch529_o213_nu530','no_ch1034_o380_ch529_o213_nu531','no_ch1034_o380_ch529_o213_nu532'],
            ['no_ch1034_o380_ch529_o214_nu530','no_ch1034_o380_ch529_o214_nu531','no_ch1034_o380_ch529_o214_nu532'],
            ['no_ch1034_o380_ch529_o215_nu530','no_ch1034_o380_ch529_o215_nu531','no_ch1034_o380_ch529_o215_nu532'],
            ['no_ch1034_o380_ch529_o216_nu530','no_ch1034_o380_ch529_o216_nu531','no_ch1034_o380_ch529_o216_nu532'],
            ['no_ch1034_o380_ch529_o217_nu530','no_ch1034_o380_ch529_o217_nu531','no_ch1034_o380_ch529_o217_nu532'],
            ['no_ch1034_o381_ch535_o213_nu536','no_ch1034_o381_ch535_o213_nu537','no_ch1034_o381_ch535_o213_nu538'],
            ['no_ch1034_o381_ch535_o214_nu536','no_ch1034_o381_ch535_o214_nu537','no_ch1034_o381_ch535_o214_nu538'],
            ['no_ch1034_o381_ch535_o215_nu536','no_ch1034_o381_ch535_o215_nu537','no_ch1034_o381_ch535_o215_nu538'],
            ['no_ch1034_o381_ch535_o216_nu536','no_ch1034_o381_ch535_o216_nu537','no_ch1034_o381_ch535_o216_nu538'],
            ['no_ch1034_o381_ch535_o217_nu536','no_ch1034_o381_ch535_o217_nu537','no_ch1034_o381_ch535_o217_nu538'],
            ['no_ch1034_o382_ch541_o213_nu542','no_ch1034_o382_ch541_o213_nu543','no_ch1034_o382_ch541_o213_nu544'],
            ['no_ch1034_o382_ch541_o214_nu542','no_ch1034_o382_ch541_o214_nu543','no_ch1034_o382_ch541_o214_nu544'],
            ['no_ch1034_o382_ch541_o215_nu542','no_ch1034_o382_ch541_o215_nu543','no_ch1034_o382_ch541_o215_nu544'],
            ['no_ch1034_o382_ch541_o216_nu542','no_ch1034_o382_ch541_o216_nu543','no_ch1034_o382_ch541_o216_nu544'],
            ['no_ch1034_o382_ch541_o217_nu542','no_ch1034_o382_ch541_o217_nu543','no_ch1034_o382_ch541_o217_nu544'],
            ['no_ch1034_o382_ch541_o218_nu542','no_ch1034_o382_ch541_o218_nu543','no_ch1034_o382_ch541_o218_nu544'],
            ['no_ch1034_o383_ch547_o213_nu548','no_ch1034_o383_ch547_o213_nu549','no_ch1034_o383_ch547_o213_nu550'],
            ['no_ch1034_o383_ch547_o214_nu548','no_ch1034_o383_ch547_o214_nu549','no_ch1034_o383_ch547_o214_nu550'],
            ['no_ch1034_o383_ch547_o215_nu548','no_ch1034_o383_ch547_o215_nu549','no_ch1034_o383_ch547_o215_nu550'],
            ['no_ch1034_o383_ch547_o216_nu548','no_ch1034_o383_ch547_o216_nu549','no_ch1034_o383_ch547_o216_nu550'],
            ['no_ch1034_o383_ch547_o217_nu548','no_ch1034_o383_ch547_o217_nu549','no_ch1034_o383_ch547_o217_nu550'],
            ['no_ch1034_o383_ch547_o218_nu548','no_ch1034_o383_ch547_o218_nu549','no_ch1034_o383_ch547_o218_nu550'],
            ['no_ch1034_o384_ch553_o213_nu554','no_ch1034_o384_ch553_o213_nu555','no_ch1034_o384_ch553_o213_nu556'],
            ['no_ch1034_o384_ch553_o214_nu554','no_ch1034_o384_ch553_o214_nu555','no_ch1034_o384_ch553_o214_nu556'],
            ['no_ch1034_o384_ch553_o215_nu554','no_ch1034_o384_ch553_o215_nu555','no_ch1034_o384_ch553_o215_nu556'],
            ['no_ch1034_o384_ch553_o216_nu554','no_ch1034_o384_ch553_o216_nu555','no_ch1034_o384_ch553_o216_nu556'],
            ['no_ch1034_o384_ch553_o217_nu554','no_ch1034_o384_ch553_o217_nu555','no_ch1034_o384_ch553_o217_nu556'],
            ['no_ch1034_o384_ch553_o218_nu554','no_ch1034_o384_ch553_o218_nu555','no_ch1034_o384_ch553_o218_nu556']
        ];
        $ktoe = 0.745;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) * sum(IF(unique_key='param2',answer_numeric,0)) * sum(IF(unique_key='param3',answer_numeric,0)) * 12 as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2
        ];

        $table4 = [
            'no_ch1034_o379_ch523_o213_nu527',
            'no_ch1034_o379_ch523_o214_nu527',
            'no_ch1034_o379_ch523_o215_nu527',
            'no_ch1034_o379_ch523_o216_nu527',
            'no_ch1034_o379_ch523_o217_nu527',
            'no_ch1034_o380_ch529_o213_nu533',
            'no_ch1034_o380_ch529_o214_nu533',
            'no_ch1034_o380_ch529_o215_nu533',
            'no_ch1034_o380_ch529_o216_nu533',
            'no_ch1034_o380_ch529_o217_nu533',
            'no_ch1034_o381_ch535_o213_nu539',
            'no_ch1034_o381_ch535_o214_nu539',
            'no_ch1034_o381_ch535_o215_nu539',
            'no_ch1034_o381_ch535_o216_nu539',
            'no_ch1034_o381_ch535_o217_nu539',
            'no_ch1034_o382_ch541_o213_nu545',
            'no_ch1034_o382_ch541_o214_nu545',
            'no_ch1034_o382_ch541_o215_nu545',
            'no_ch1034_o382_ch541_o216_nu545',
            'no_ch1034_o382_ch541_o217_nu545',
            'no_ch1034_o382_ch541_o218_nu545',
            'no_ch1034_o383_ch547_o213_nu551',
            'no_ch1034_o383_ch547_o214_nu551',
            'no_ch1034_o383_ch547_o215_nu551',
            'no_ch1034_o383_ch547_o216_nu551',
            'no_ch1034_o383_ch547_o217_nu551',
            'no_ch1034_o383_ch547_o218_nu551',
            'no_ch1034_o384_ch553_o213_nu557',
            'no_ch1034_o384_ch553_o214_nu557',
            'no_ch1034_o384_ch553_o215_nu557',
            'no_ch1034_o384_ch553_o216_nu557',
            'no_ch1034_o384_ch553_o217_nu557',
            'no_ch1034_o384_ch553_o218_nu557',
        ];

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.8';
        $outputFile = 'sum918.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $startColumn = 'E';
        $objPHPExcel = Summary::sum($table1,$startColumn,13,$objPHPExcel,$mainObj);
        $startColumn = 'U';
        $objPHPExcel = Summary::average($table2, $startColumn, 13, $objPHPExcel, $mainObj);
        $startColumn = 'AL';
//        Summary::usageElectric();
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, 13,$objPHPExcel, $mainObj,$sumAmountSQL,$params,$ktoe);
        $startColumn = 'BB';
        $objPHPExcel = Summary::average($table4, $startColumn, 13, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }
}
