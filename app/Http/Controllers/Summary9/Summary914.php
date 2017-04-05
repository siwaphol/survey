<?php

namespace App\Http\Controllers\Summary9;

use App\Main;
use App\Parameter;
use App\Setting;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary914 extends Controller
{
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

            'no_ch1029_o370'
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
            'no_ch1029_o370_ch461_o209_nu462',
        ];
        $startColumn = 'U';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 135;
        $endLastDigit = 242;
        // โทรทัศน์ เพราะมีที่มีเบอร์ 5 กับไม่มีเบอร์ 5
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        $table3Eletric = [
            //พัดลม
            ['no_ch1028_o356_ch323_o149_ch324_o156_nu325', 'no_ch1028_o356_ch323_o149_ch324_o156_nu326','no_ch1028_o356_ch323_o149_ch324_o156_nu327',$factors[135],$electricPower[135],'no_ch1028_o356_ch323_o149_ch324_o156_ra329'],
            ['no_ch1028_o356_ch323_o149_ch324_o157_nu325', 'no_ch1028_o356_ch323_o149_ch324_o157_nu326','no_ch1028_o356_ch323_o149_ch324_o157_nu327',$factors[137], $electricPower[137],'no_ch1028_o356_ch323_o149_ch324_o157_ra329'],
            ['no_ch1028_o356_ch323_o149_ch324_o158_nu325', 'no_ch1028_o356_ch323_o149_ch324_o158_nu326','no_ch1028_o356_ch323_o149_ch324_o158_nu327',$factors[139], $electricPower[139],'no_ch1028_o356_ch323_o149_ch324_o158_ra329'],
            ['no_ch1028_o356_ch323_o150_ch330_o156_nu331', 'no_ch1028_o356_ch323_o150_ch330_o156_nu332','no_ch1028_o356_ch323_o150_ch330_o156_nu332',$factors[141], $electricPower[141],'no_ch1028_o356_ch323_o150_ch330_o156_ra335'],
            ['no_ch1028_o356_ch323_o150_ch330_o157_nu331', 'no_ch1028_o356_ch323_o150_ch330_o157_nu332','no_ch1028_o356_ch323_o150_ch330_o157_nu332',$factors[143], $electricPower[143],'no_ch1028_o356_ch323_o150_ch330_o157_ra335'],
            ['no_ch1028_o356_ch323_o150_ch330_o158_nu331', 'no_ch1028_o356_ch323_o150_ch330_o158_nu332','no_ch1028_o356_ch323_o150_ch330_o158_nu332',$factors[145], $electricPower[145],'no_ch1028_o356_ch323_o150_ch330_o158_ra335'],
            ['no_ch1028_o356_ch323_o151_ch336_o156_nu337', 'no_ch1028_o356_ch323_o151_ch336_o156_nu338','no_ch1028_o356_ch323_o151_ch336_o156_nu339',$factors[147], $electricPower[147],'no_ch1028_o356_ch323_o151_ch336_o156_ra341'],
            ['no_ch1028_o356_ch323_o151_ch336_o157_nu337', 'no_ch1028_o356_ch323_o151_ch336_o157_nu338','no_ch1028_o356_ch323_o151_ch336_o157_nu339',$factors[149], $electricPower[149],'no_ch1028_o356_ch323_o151_ch336_o157_ra341'],
            ['no_ch1028_o356_ch323_o151_ch336_o158_nu337', 'no_ch1028_o356_ch323_o151_ch336_o158_nu338','no_ch1028_o356_ch323_o151_ch336_o158_nu339',$factors[151], $electricPower[151],'no_ch1028_o356_ch323_o151_ch336_o158_ra341'],
            ['no_ch1028_o356_ch323_o152_ch342_o157_nu343', 'no_ch1028_o356_ch323_o152_ch342_o157_nu344','no_ch1028_o356_ch323_o152_ch342_o157_nu345',$factors[153], $electricPower[153],'no_ch1028_o356_ch323_o152_ch342_o157_ra347'],
            ['no_ch1028_o356_ch323_o152_ch342_o159_nu343', 'no_ch1028_o356_ch323_o152_ch342_o159_nu344','no_ch1028_o356_ch323_o152_ch342_o159_nu345',$factors[155], $electricPower[155],''],
            ['no_ch1028_o356_ch323_o153_ch348_o158_nu349', 'no_ch1028_o356_ch323_o153_ch348_o158_nu350','no_ch1028_o356_ch323_o153_ch348_o158_nu350',$factors[156], $electricPower[156],''],
            ['no_ch1028_o356_ch323_o153_ch348_o160_nu349', 'no_ch1028_o356_ch323_o153_ch348_o160_nu350','no_ch1028_o356_ch323_o153_ch348_o160_nu351',$factors[157], $electricPower[157],''],
            ['no_ch1028_o356_ch323_o153_ch348_o161_nu349', 'no_ch1028_o356_ch323_o153_ch348_o161_nu350','no_ch1028_o356_ch323_o153_ch348_o161_nu351',$factors[158], $electricPower[158],''],
            ['no_ch1028_o356_ch323_o154_nu353', 'no_ch1028_o356_ch323_o154_nu354','no_ch1028_o356_ch323_o154_nu355',$factors[159], $electricPower[159],''],
            ['no_ch1028_o356_ch323_o155_nu353', 'no_ch1028_o356_ch323_o155_nu354','no_ch1028_o356_ch323_o155_nu355',$factors[160], $electricPower[160],''],
            // พัดลมดูดอากาศ
            ['no_ch1028_o357_ch358_o151_ch359_o163_nu360', 'no_ch1028_o357_ch358_o151_ch359_o163_nu361','no_ch1028_o357_ch358_o151_ch359_o163_nu362',$factors[161], $electricPower[161],'no_ch1028_o357_ch358_o151_ch359_o163_ra364'],
            ['no_ch1028_o357_ch358_o151_ch359_o164_nu360', 'no_ch1028_o357_ch358_o151_ch359_o164_nu361','no_ch1028_o357_ch358_o151_ch359_o164_nu362',$factors[163], $electricPower[163],'no_ch1028_o357_ch358_o151_ch359_o164_ra364'],
            ['no_ch1028_o357_ch358_o151_ch359_o165_nu360', 'no_ch1028_o357_ch358_o151_ch359_o165_nu361','no_ch1028_o357_ch358_o151_ch359_o165_nu362',$factors[165], $electricPower[165],'no_ch1028_o357_ch358_o151_ch359_o165_ra364'],
            // เครื่องฟอกอากาศ
            ['no_ch1028_o358_nu367', 'no_ch1028_o358_nu368','no_ch1028_o358_nu368', $factors[167], $electricPower[167],''],
            // เครื่องทำน้ำอุ่น
            ['no_ch1028_o359_nu373', 'no_ch1028_o359_nu374','no_ch1028_o359_nu375', $factors[168], $electricPower[168],'no_ch1028_o359_ra377'],
            // เครื่องดูดฝุ่น
            ['no_ch1028_o360_ch379_o167_nu380', 'no_ch1028_o360_ch379_o167_nu381','no_ch1028_o360_ch379_o167_nu382',$factors[170], $electricPower[170],''],
            ['no_ch1028_o360_ch379_o168_nu380', 'no_ch1028_o360_ch379_o168_nu381','no_ch1028_o360_ch379_o168_nu382',$factors[171], $electricPower[171],''],
            ['no_ch1028_o360_ch379_o169_nu380', 'no_ch1028_o360_ch379_o169_nu381','no_ch1028_o360_ch379_o169_nu382',$factors[172], $electricPower[172],''],
            // เตารีดไฟฟ้า
            ['no_ch1028_o361_ch385_o170_nu386', 'no_ch1028_o361_ch385_o170_nu387','no_ch1028_o361_ch385_o170_nu388',$factors[173], $electricPower[173],'no_ch1028_o361_ch385_o170_ra390'],
            ['no_ch1028_o361_ch385_o171_nu386', 'no_ch1028_o361_ch385_o171_nu387','no_ch1028_o361_ch385_o171_nu388',$factors[175], $electricPower[175],'no_ch1028_o361_ch385_o171_ra390'],
            ['no_ch1028_o361_ch385_o172_nu386', 'no_ch1028_o361_ch385_o172_nu387','no_ch1028_o361_ch385_o172_nu388',$factors[177], $electricPower[177],''],
            ['no_ch1028_o361_ch385_o173_nu386', 'no_ch1028_o361_ch385_o173_nu387','no_ch1028_o361_ch385_o173_nu388',$factors[178], $electricPower[178],''],
            ['no_ch1028_o361_ch385_o174_nu386', 'no_ch1028_o361_ch385_o174_nu387','no_ch1028_o361_ch385_o174_nu388',$factors[179], $electricPower[179],''],
            // ตู้เย็น
            ['no_ch1028_o362_ch392_o175_ch393_o177_nu394', 'no_ch1028_o362_ch392_o175_ch393_o177_nu395','no_ch1028_o362_ch392_o175_ch393_o177_nu396',$factors[180], $electricPower[180],'no_ch1028_o362_ch392_o175_ch393_o177_ra398'],
            ['no_ch1028_o362_ch392_o175_ch393_o178_nu394', 'no_ch1028_o362_ch392_o175_ch393_o178_nu395','no_ch1028_o362_ch392_o175_ch393_o178_nu396',$factors[182], $electricPower[182],'no_ch1028_o362_ch392_o175_ch393_o178_ra398'],
            ['no_ch1028_o362_ch392_o176_ch1001_o179_nu1002', 'no_ch1028_o362_ch392_o176_ch1001_o179_nu1003','no_ch1028_o362_ch392_o176_ch1001_o179_nu1004',$factors[184], $electricPower[184],'no_ch1028_o362_ch392_o176_ch1001_o179_ra1006'],
            ['no_ch1028_o362_ch392_o176_ch1001_o180_nu1002', 'no_ch1028_o362_ch392_o176_ch1001_o180_nu1003','no_ch1028_o362_ch392_o176_ch1001_o180_nu1004',$factors[186], $electricPower[186],'no_ch1028_o362_ch392_o176_ch1001_o180_ra1006'],
            // เครื่องปรับอากาศ หรือแอร์
            ['no_ch1028_o363_ch400_o181_ch401_o183_nu402', 'no_ch1028_o363_ch400_o181_ch401_o183_nu403','no_ch1028_o363_ch400_o181_ch401_o183_nu404',$factors[188], $electricPower[188],'no_ch1028_o363_ch400_o181_ch401_o183_ra406'],
            ['no_ch1028_o363_ch400_o181_ch401_o184_nu402', 'no_ch1028_o363_ch400_o181_ch401_o184_nu403','no_ch1028_o363_ch400_o181_ch401_o184_nu404',$factors[190], $electricPower[190],'no_ch1028_o363_ch400_o181_ch401_o184_ra406'],
            ['no_ch1028_o363_ch400_o181_ch401_o185_nu402', 'no_ch1028_o363_ch400_o181_ch401_o185_nu403','no_ch1028_o363_ch400_o181_ch401_o185_nu404',$factors[192], $electricPower[192],'no_ch1028_o363_ch400_o181_ch401_o185_ra406'],
            ['no_ch1028_o363_ch400_o181_ch401_o186_nu402', 'no_ch1028_o363_ch400_o181_ch401_o186_nu403','no_ch1028_o363_ch400_o181_ch401_o186_nu404',$factors[194], $electricPower[194],'no_ch1028_o363_ch400_o181_ch401_o186_ra406'],
            ['no_ch1028_o363_ch400_o181_ch401_o187_nu402', 'no_ch1028_o363_ch400_o181_ch401_o187_nu403','no_ch1028_o363_ch400_o181_ch401_o187_nu404',$factors[196], $electricPower[196],'no_ch1028_o363_ch400_o181_ch401_o187_ra406'],
            ['no_ch1028_o363_ch400_o181_ch401_o188_nu402', 'no_ch1028_o363_ch400_o181_ch401_o188_nu403','no_ch1028_o363_ch400_o181_ch401_o188_nu404',$factors[198], $electricPower[198],'no_ch1028_o363_ch400_o181_ch401_o188_ra406'],
            ['no_ch1028_o363_ch400_o182_ch410_o185_nu411', 'no_ch1028_o363_ch400_o182_ch410_o185_nu412','no_ch1028_o363_ch400_o182_ch410_o185_nu413',$factors[200], $electricPower[200],'no_ch1028_o363_ch400_o182_ch410_o185_ra415'],
            ['no_ch1028_o363_ch400_o182_ch410_o186_nu411', 'no_ch1028_o363_ch400_o182_ch410_o186_nu412','no_ch1028_o363_ch400_o182_ch410_o186_nu413',$factors[202], $electricPower[202],'no_ch1028_o363_ch400_o182_ch410_o186_ra415'],
            ['no_ch1028_o363_ch400_o182_ch410_o187_nu411', 'no_ch1028_o363_ch400_o182_ch410_o187_nu412','no_ch1028_o363_ch400_o182_ch410_o187_nu413',$factors[204], $electricPower[204],'no_ch1028_o363_ch400_o182_ch410_o187_ra415'],
            ['no_ch1028_o363_ch400_o182_ch410_o188_nu411', 'no_ch1028_o363_ch400_o182_ch410_o188_nu412','no_ch1028_o363_ch400_o182_ch410_o188_nu413',$factors[206], $electricPower[206],'no_ch1028_o363_ch400_o182_ch410_o188_ra415'],
            ['no_ch1028_o363_ch400_o182_ch410_o189_nu411', 'no_ch1028_o363_ch400_o182_ch410_o189_nu412','no_ch1028_o363_ch400_o182_ch410_o189_nu413',$factors[208], $electricPower[208],'no_ch1028_o363_ch400_o182_ch410_o189_ra415'],
            ['no_ch1028_o363_ch400_o182_ch410_o190_nu411', 'no_ch1028_o363_ch400_o182_ch410_o190_nu412','no_ch1028_o363_ch400_o182_ch410_o190_nu413',$factors[210], $electricPower[210],'no_ch1028_o363_ch400_o182_ch410_o190_ra415'],
            ['no_ch1028_o363_ch400_o182_ch410_o191_nu411', 'no_ch1028_o363_ch400_o182_ch410_o191_nu412','no_ch1028_o363_ch400_o182_ch410_o191_nu413',$factors[212], $electricPower[212],'no_ch1028_o363_ch400_o182_ch410_o191_ra415'],
            // เครื่องซักผ้าและอบผ้า
            ['no_ch1028_o364_ch420_o195_ch421_o198_nu422', 'no_ch1028_o364_ch420_o195_ch421_o198_nu423','no_ch1028_o364_ch420_o195_ch421_o198_nu424',$factors[214], $electricPower[214],'no_ch1028_o364_ch420_o195_ch421_o198_ra426'],
            ['no_ch1028_o364_ch420_o195_ch421_o199_nu422', 'no_ch1028_o364_ch420_o195_ch421_o199_nu423','no_ch1028_o364_ch420_o195_ch421_o199_nu424',$factors[216], $electricPower[216],'no_ch1028_o364_ch420_o195_ch421_o199_ra426'],
            ['no_ch1028_o364_ch420_o195_ch421_o200_nu422', 'no_ch1028_o364_ch420_o195_ch421_o200_nu423','no_ch1028_o364_ch420_o195_ch421_o200_nu424',$factors[218], $electricPower[218],'no_ch1028_o364_ch420_o195_ch421_o200_ra426'],
            ['no_ch1028_o364_ch420_o195_ch421_o201_nu422', 'no_ch1028_o364_ch420_o195_ch421_o201_nu423','no_ch1028_o364_ch420_o195_ch421_o201_nu424',$factors[220], $electricPower[220],'no_ch1028_o364_ch420_o195_ch421_o201_ra426'],
            ['no_ch1028_o364_ch420_o196_ch421_o198_nu422', 'no_ch1028_o364_ch420_o196_ch421_o198_nu423','no_ch1028_o364_ch420_o196_ch421_o198_nu424',$factors[222], $electricPower[222],'no_ch1028_o364_ch420_o196_ch421_o198_ra426'],
            ['no_ch1028_o364_ch420_o196_ch421_o199_nu422', 'no_ch1028_o364_ch420_o196_ch421_o199_nu423','no_ch1028_o364_ch420_o196_ch421_o199_nu424',$factors[224], $electricPower[224],'no_ch1028_o364_ch420_o196_ch421_o199_ra426'],
            ['no_ch1028_o364_ch420_o196_ch421_o200_nu422', 'no_ch1028_o364_ch420_o196_ch421_o200_nu423','no_ch1028_o364_ch420_o196_ch421_o200_nu424',$factors[226], $electricPower[226],'no_ch1028_o364_ch420_o196_ch421_o200_ra426'],
            ['no_ch1028_o364_ch420_o196_ch421_o201_nu422', 'no_ch1028_o364_ch420_o196_ch421_o201_nu423','no_ch1028_o364_ch420_o196_ch421_o201_nu424',$factors[228], $electricPower[228],'no_ch1028_o364_ch420_o196_ch421_o201_ra426'],
            ['no_ch1028_o364_ch420_o197_ch421_o198_nu422', 'no_ch1028_o364_ch420_o197_ch421_o198_nu423','no_ch1028_o364_ch420_o197_ch421_o198_nu424',$factors[230], $electricPower[230],'no_ch1028_o364_ch420_o197_ch421_o198_ra426'],
            ['no_ch1028_o364_ch420_o197_ch421_o199_nu422', 'no_ch1028_o364_ch420_o197_ch421_o199_nu423','no_ch1028_o364_ch420_o197_ch421_o199_nu424',$factors[232], $electricPower[232],'no_ch1028_o364_ch420_o197_ch421_o199_ra426'],
            ['no_ch1028_o364_ch420_o197_ch421_o200_nu422', 'no_ch1028_o364_ch420_o197_ch421_o200_nu423','no_ch1028_o364_ch420_o197_ch421_o200_nu424',$factors[234], $electricPower[234],'no_ch1028_o364_ch420_o197_ch421_o200_ra426'],
            ['no_ch1028_o364_ch420_o197_ch421_o201_nu422', 'no_ch1028_o364_ch420_o197_ch421_o201_nu423','no_ch1028_o364_ch420_o197_ch421_o201_nu424',$factors[236], $electricPower[236],'no_ch1028_o364_ch420_o197_ch421_o201_ra426'],

            [],
            [],

            ['no_ch1028_o367_nu441', 'no_ch1028_o367_nu442','no_ch1028_o367_nu443',$factors[240], $electricPower[240],''],
            ['no_ch1028_o368_nu447', 'no_ch1028_o368_nu448','no_ch1028_o368_nu449',$factors[241], $electricPower[241],''],
            ['no_ch1028_o369_nu453', 'no_ch1028_o369_nu454','no_ch1028_o369_nu455',$factors[242], $electricPower[242],'no_ch1028_o369_ra457'],
        ];
        $startColumn = "AL";
        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (ชม/ครั้ง) * อัตราการใช้ (ครั้ง/สัปดาห์) * 52 ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * {$week}
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=81,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";
        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/ครั้ง)
            'param3'=>2, //อัตราการใช้ (ครั้ง/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5  //ฉลากประหยัดไฟ
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // ไดร์เป่าผม และเครื่องหนีบผม
        $tabl3MinuteEachTime = [
            ['no_ch1028_o365_nu429', 'no_ch1028_o365_nu430','no_ch1028_o365_nu431',$factors[238], $electricPower[238],''],
            ['no_ch1028_o366_nu435', 'no_ch1028_o366_nu436','no_ch1028_o366_nu437',$factors[239], $electricPower[239],'']
        ];
        $startRow = 71;
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/วัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) 
        * sum(if(unique_key='param2', answer_numeric,0)) 
        * sum(if(unique_key='param3', answer_numeric,0)))
        * ({$week}/60.0)
        * param4
        * param5 
        * (if(sum(if(unique_key='param6' and option_id=81,1,0)) + if('param6'='',1,0) >0,1,0)) 
        as sumAmount ";
        $params = [
            'param1'=>0, //จำนวน
            'param2'=>1, //อัตราการใช้ (นาที/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5  //ฉลากประหยัดไฟ
        ];
        $objPHPExcel = Summary::usageElectric($tabl3MinuteEachTime, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // เครื่องทำน้ำอุ่นแก๊ส
        $volumes = (float)$settings->where('code', 'volume_fuel_4')->first()->value;
        $fuelFactors = (float)$settings->where('code','tool_factor_fuel_4')->first()->value
            * (float)$settings->where('code','season_factor_fuel_4')->first()->value
            * (float)$settings->where('code','usage_factor_fuel_4')->first()->value;
        $table3Petro = [
            ['no_ch1029_o370_ch461_o209_nu463',$volumes,$fuelFactors]
        ];
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2
        ];
        $ktoe = Setting::where('code','E2')->first()->value;
        $startRow = 76;
        $sumAmountSQL = " sum(IF(unique_key='param1',answer_numeric,0)) 
        * param2 * param3
        as sumAmount ";
        $objPHPExcel = Summary::usageElectric($table3Petro, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

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

            'no_ch1028_o369_nu456'
        ];
        $startColumn = 'BB';
        $startRow = 13;
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);
        // สำหรับเครื่องทำน้ำอุ่น
        $startRow = 76;
        $table4_2 = ['no_ch1029_o370_ch461_o209_nu464'];
        $table4_2_amount = ['no_ch1029_o370'];
        $objPHPExcel = Summary::averageLifetime($table4_2, $table4_2_amount, $startColumn, $startRow, $objPHPExcel, $mainObj
            ,false,[],false,null,1);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return true;
    }
}
