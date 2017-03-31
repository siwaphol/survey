<?php

namespace App\Http\Controllers\Summary9;

use App\Main;
use App\Parameter;
use App\Setting;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary913 extends Controller
{
    //หมวดความสะดวกสบาย
    public static function report913()
    {
        set_time_limit(3600);

        $mainObj = new Main();
        $mainObj->initList();

        $inputFile = 'summary9.xlsx';
        $inputSheet = '9.1.3';
        $startRow = 13;
        $outputFile = 'sum913.xlsx';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcelMain = \PHPExcel_IOFactory::load(storage_path('excel/'. $inputFile));
        $objPHPExcel->addExternalSheet($objPHPExcelMain->getSheetByName($inputSheet));
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->setActiveSheetIndexByName($inputSheet);

        $table1 = [
            'no_ch1027_o347_ch240_o104_ch241_o108',
            'no_ch1027_o347_ch240_o104_ch241_o109',
            'no_ch1027_o347_ch240_o104_ch241_o110',
            'no_ch1027_o347_ch240_o104_ch241_o111',
            'no_ch1027_o347_ch240_o104_ch241_o112',
            'no_ch1027_o347_ch240_o104_ch241_o113',
            'no_ch1027_o347_ch240_o104_ch241_o114',
            'no_ch1027_o347_ch240_o105_ch248_o115',
            'no_ch1027_o347_ch240_o105_ch248_o116',
            'no_ch1027_o347_ch240_o105_ch248_o117',
            'no_ch1027_o347_ch240_o105_ch248_o118',
            'no_ch1027_o347_ch240_o105_ch248_o119',
            'no_ch1027_o347_ch240_o106_ch254_o108',
            'no_ch1027_o347_ch240_o106_ch254_o109',
            'no_ch1027_o347_ch240_o106_ch254_o110',
            'no_ch1027_o347_ch240_o106_ch254_o111',
            'no_ch1027_o347_ch240_o106_ch254_o112',
            'no_ch1027_o347_ch240_o106_ch254_o113',
            'no_ch1027_o347_ch240_o106_ch254_o114',
            'no_ch1027_o347_ch240_o106_ch254_o115',
            'no_ch1027_o347_ch240_o106_ch254_o116',
            'no_ch1027_o347_ch240_o106_ch254_o117',
            'no_ch1027_o347_ch240_o106_ch254_o118',
            'no_ch1027_o347_ch240_o106_ch254_o119',
            'no_ch1027_o347_ch240_o107_ch260_o108',
            'no_ch1027_o347_ch240_o107_ch260_o109',
            'no_ch1027_o347_ch240_o107_ch260_o110',
            'no_ch1027_o347_ch240_o107_ch260_o111',
            'no_ch1027_o347_ch240_o107_ch260_o112',
            'no_ch1027_o347_ch240_o107_ch260_o113',
            'no_ch1027_o347_ch240_o107_ch260_o114',
            'no_ch1027_o347_ch240_o107_ch260_o115',
            'no_ch1027_o347_ch240_o107_ch260_o116',
            'no_ch1027_o347_ch240_o107_ch260_o117',
            'no_ch1027_o347_ch240_o107_ch260_o118',
            'no_ch1027_o347_ch240_o107_ch260_o119',
            'no_ch1027_o348_ch267_o122',
            'no_ch1027_o348_ch267_o123',
            'no_ch1027_o348_ch267_o124',
            'no_ch1027_o349',
            'no_ch1027_o350',
            'no_ch1027_o351',
            'no_ch1027_o352_ch291_o128_ch293_o134',
            'no_ch1027_o352_ch291_o128_ch293_o135',
            'no_ch1027_o352_ch291_o128_ch293_o136',
            'no_ch1027_o352_ch291_o128_ch293_o137',
            'no_ch1027_o352_ch291_o128_ch293_o138',
            'no_ch1027_o352_ch291_o129_ch298_o132',
            'no_ch1027_o352_ch291_o129_ch298_o133',
            'no_ch1027_o352_ch291_o129_ch298_o134',
            'no_ch1027_o353_ch304_o139',
            'no_ch1027_o353_ch304_o140',
            'no_ch1027_o353_ch304_o141',
            'no_ch1027_o354_ch310_o142',
            'no_ch1027_o354_ch310_o143',
            'no_ch1027_o354_ch310_o144',
            'no_ch1027_o355_ch317_o146',
            'no_ch1027_o355_ch317_o147',
            'no_ch1027_o355_ch317_o148',
        ];
        $startColumn = 'E';
        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $table2 = [
            'no_ch1027_o347_ch240_o104_ch241_o108_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o109_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o110_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o111_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o112_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o113_nu242',
            'no_ch1027_o347_ch240_o104_ch241_o114_nu242',
            'no_ch1027_o347_ch240_o105_ch248_o115_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o116_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o117_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o118_nu249',
            'no_ch1027_o347_ch240_o105_ch248_o119_nu249',
            'no_ch1027_o347_ch240_o106_ch254_o108_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o109_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o110_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o111_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o112_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o113_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o114_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o115_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o116_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o117_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o118_nu255',
            'no_ch1027_o347_ch240_o106_ch254_o119_nu255',
            'no_ch1027_o347_ch240_o107_ch260_o108_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o109_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o110_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o111_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o112_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o113_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o114_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o115_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o116_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o117_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o118_nu261',
            'no_ch1027_o347_ch240_o107_ch260_o119_nu261',
            'no_ch1027_o348_ch267_o122_nu268',
            'no_ch1027_o348_ch267_o123_nu268',
            'no_ch1027_o348_ch267_o124_nu268',
            'no_ch1027_o349_nu274',
            'no_ch1027_o350_nu280',
            'no_ch1027_o351_nu286',
            'no_ch1027_o352_ch291_o128_ch293_o134_nu294',
            'no_ch1027_o352_ch291_o128_ch293_o135_nu294',
            'no_ch1027_o352_ch291_o128_ch293_o136_nu294',
            'no_ch1027_o352_ch291_o128_ch293_o137_nu294',
            'no_ch1027_o352_ch291_o128_ch293_o138_nu294',
            'no_ch1027_o352_ch291_o129_ch298_o132_nu299',
            'no_ch1027_o352_ch291_o129_ch298_o133_nu299',
            'no_ch1027_o352_ch291_o129_ch298_o134_nu299',
            'no_ch1027_o353_ch304_o139_nu305',
            'no_ch1027_o353_ch304_o140_nu305',
            'no_ch1027_o353_ch304_o141_nu305',
            'no_ch1027_o354_ch310_o142_nu311',
            'no_ch1027_o354_ch310_o143_nu311',
            'no_ch1027_o354_ch310_o144_nu311',
            'no_ch1027_o355_ch317_o146_nu318',
            'no_ch1027_o355_ch317_o147_nu318',
            'no_ch1027_o355_ch317_o148_nu318',
        ];
        $startColumn = 'U';
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $settings = Setting::whereIn('group_id',[1,5,9,10,11,12,13])
            ->get();
        $factors = array();
        $electricPower = array();
        $startLastDigit = 40;
        $endLastDigit = 110;
        // โทรทัศน์ เพราะมีที่มีเบอร์ 5 กับไม่มีเบอร์ 5
        for ($i=$startLastDigit;$i<=$endLastDigit;$i+=2){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }
        // อุปกรณ์อื่นไม่มีเบอร์ 5 radio
        $startLastDigit = 112;
        $endLastDigit = 134;
        for ($i=$startLastDigit;$i<=$endLastDigit;$i++){
            $electricPower[$i] = (float)$settings->where('code', 'electric_power_' . $i)->first()->value;

            $factors[$i] = (float)$settings->where('code','tool_factor_'. $i)->first()->value
                * (float)$settings->where('code','season_factor_'. $i)->first()->value
                * (float)$settings->where('code','usage_factor_'. $i)->first()->value;
        }

        //table 3
        $table3 = [
            ['no_ch1027_o347_ch240_o104_ch241_o108_nu242','no_ch1027_o347_ch240_o104_ch241_o108_nu243','no_ch1027_o347_ch240_o104_ch241_o108_nu244',$factors[40],$electricPower[40],'no_ch1027_o347_ch240_o104_ch241_o108_ra246'],
            ['no_ch1027_o347_ch240_o104_ch241_o109_nu242','no_ch1027_o347_ch240_o104_ch241_o109_nu243','no_ch1027_o347_ch240_o104_ch241_o109_nu244',$factors[42],$electricPower[42],'no_ch1027_o347_ch240_o104_ch241_o109_ra246'],
            ['no_ch1027_o347_ch240_o104_ch241_o110_nu242','no_ch1027_o347_ch240_o104_ch241_o110_nu243','no_ch1027_o347_ch240_o104_ch241_o110_nu244',$factors[44],$electricPower[44],'no_ch1027_o347_ch240_o104_ch241_o110_ra246'],
            ['no_ch1027_o347_ch240_o104_ch241_o111_nu242','no_ch1027_o347_ch240_o104_ch241_o111_nu243','no_ch1027_o347_ch240_o104_ch241_o111_nu244',$factors[46],$electricPower[46],'no_ch1027_o347_ch240_o104_ch241_o111_ra246'],
            ['no_ch1027_o347_ch240_o104_ch241_o112_nu242','no_ch1027_o347_ch240_o104_ch241_o112_nu243','no_ch1027_o347_ch240_o104_ch241_o112_nu244',$factors[48],$electricPower[48],'no_ch1027_o347_ch240_o104_ch241_o112_ra246'],
            ['no_ch1027_o347_ch240_o104_ch241_o113_nu242','no_ch1027_o347_ch240_o104_ch241_o113_nu243','no_ch1027_o347_ch240_o104_ch241_o113_nu244',$factors[50],$electricPower[50],'no_ch1027_o347_ch240_o104_ch241_o113_ra246'],
            ['no_ch1027_o347_ch240_o104_ch241_o114_nu242','no_ch1027_o347_ch240_o104_ch241_o114_nu243','no_ch1027_o347_ch240_o104_ch241_o114_nu244',$factors[52],$electricPower[52],'no_ch1027_o347_ch240_o104_ch241_o114_ra246'],

            ['no_ch1027_o347_ch240_o105_ch248_o115_nu249','no_ch1027_o347_ch240_o105_ch248_o115_nu250','no_ch1027_o347_ch240_o105_ch248_o115_nu251',$factors[54],$electricPower[54],'no_ch1027_o347_ch240_o105_ch248_o115_nu253'],
            ['no_ch1027_o347_ch240_o105_ch248_o116_nu249','no_ch1027_o347_ch240_o105_ch248_o116_nu250','no_ch1027_o347_ch240_o105_ch248_o116_nu251',$factors[56],$electricPower[56],'no_ch1027_o347_ch240_o105_ch248_o116_nu253'],
            ['no_ch1027_o347_ch240_o105_ch248_o117_nu249','no_ch1027_o347_ch240_o105_ch248_o117_nu250','no_ch1027_o347_ch240_o105_ch248_o117_nu251',$factors[58],$electricPower[58],'no_ch1027_o347_ch240_o105_ch248_o117_nu253'],
            ['no_ch1027_o347_ch240_o105_ch248_o118_nu249','no_ch1027_o347_ch240_o105_ch248_o118_nu250','no_ch1027_o347_ch240_o105_ch248_o118_nu251',$factors[60],$electricPower[60],'no_ch1027_o347_ch240_o105_ch248_o118_nu253'],
            ['no_ch1027_o347_ch240_o105_ch248_o119_nu249','no_ch1027_o347_ch240_o105_ch248_o119_nu250','no_ch1027_o347_ch240_o105_ch248_o119_nu251',$factors[62],$electricPower[62],'no_ch1027_o347_ch240_o105_ch248_o119_nu253'],

            ['no_ch1027_o347_ch240_o106_ch254_o108_nu255','no_ch1027_o347_ch240_o106_ch254_o108_nu256','no_ch1027_o347_ch240_o106_ch254_o108_nu257',$factors[64],$electricPower[64],'no_ch1027_o347_ch240_o106_ch254_o108_ra259'],
            ['no_ch1027_o347_ch240_o106_ch254_o109_nu255','no_ch1027_o347_ch240_o106_ch254_o109_nu256','no_ch1027_o347_ch240_o106_ch254_o109_nu257',$factors[66],$electricPower[66],'no_ch1027_o347_ch240_o106_ch254_o109_ra259'],
            ['no_ch1027_o347_ch240_o106_ch254_o110_nu255','no_ch1027_o347_ch240_o106_ch254_o110_nu256','no_ch1027_o347_ch240_o106_ch254_o110_nu257',$factors[68],$electricPower[68],'no_ch1027_o347_ch240_o106_ch254_o110_ra259'],
            ['no_ch1027_o347_ch240_o106_ch254_o111_nu255','no_ch1027_o347_ch240_o106_ch254_o111_nu256','no_ch1027_o347_ch240_o106_ch254_o111_nu257',$factors[70],$electricPower[70],'no_ch1027_o347_ch240_o106_ch254_o111_ra259'],
            ['no_ch1027_o347_ch240_o106_ch254_o112_nu255','no_ch1027_o347_ch240_o106_ch254_o112_nu256','no_ch1027_o347_ch240_o106_ch254_o112_nu257',$factors[72],$electricPower[72],'no_ch1027_o347_ch240_o106_ch254_o112_ra259'],
            ['no_ch1027_o347_ch240_o106_ch254_o113_nu255','no_ch1027_o347_ch240_o106_ch254_o113_nu256','no_ch1027_o347_ch240_o106_ch254_o113_nu257',$factors[74],$electricPower[74],'no_ch1027_o347_ch240_o106_ch254_o113_ra259'],
            ['no_ch1027_o347_ch240_o106_ch254_o114_nu255','no_ch1027_o347_ch240_o106_ch254_o114_nu256','no_ch1027_o347_ch240_o106_ch254_o114_nu257',$factors[76],$electricPower[76],'no_ch1027_o347_ch240_o106_ch254_o114_ra259'],
            ['no_ch1027_o347_ch240_o106_ch254_o115_nu255','no_ch1027_o347_ch240_o106_ch254_o115_nu256','no_ch1027_o347_ch240_o106_ch254_o115_nu257',$factors[78],$electricPower[78],'no_ch1027_o347_ch240_o106_ch254_o115_ra259'],
            ['no_ch1027_o347_ch240_o106_ch254_o116_nu255','no_ch1027_o347_ch240_o106_ch254_o116_nu256','no_ch1027_o347_ch240_o106_ch254_o116_nu257',$factors[80],$electricPower[80],'no_ch1027_o347_ch240_o106_ch254_o116_ra259'],
            ['no_ch1027_o347_ch240_o106_ch254_o117_nu255','no_ch1027_o347_ch240_o106_ch254_o117_nu256','no_ch1027_o347_ch240_o106_ch254_o117_nu257',$factors[82],$electricPower[82],'no_ch1027_o347_ch240_o106_ch254_o117_ra259'],
            ['no_ch1027_o347_ch240_o106_ch254_o118_nu255','no_ch1027_o347_ch240_o106_ch254_o118_nu256','no_ch1027_o347_ch240_o106_ch254_o118_nu257',$factors[84],$electricPower[84],'no_ch1027_o347_ch240_o106_ch254_o118_ra259'],
            ['no_ch1027_o347_ch240_o106_ch254_o119_nu255','no_ch1027_o347_ch240_o106_ch254_o119_nu256','no_ch1027_o347_ch240_o106_ch254_o119_nu257',$factors[86],$electricPower[86],'no_ch1027_o347_ch240_o106_ch254_o119_ra259'],

            ['no_ch1027_o347_ch240_o107_ch260_o108_nu261','no_ch1027_o347_ch240_o107_ch260_o108_nu262','no_ch1027_o347_ch240_o107_ch260_o108_nu261',$factors[88],$electricPower[88],'no_ch1027_o347_ch240_o107_ch260_o108_ra265'],
            ['no_ch1027_o347_ch240_o107_ch260_o109_nu261','no_ch1027_o347_ch240_o107_ch260_o109_nu262','no_ch1027_o347_ch240_o107_ch260_o109_nu261',$factors[90],$electricPower[90],'no_ch1027_o347_ch240_o107_ch260_o109_ra265'],
            ['no_ch1027_o347_ch240_o107_ch260_o110_nu261','no_ch1027_o347_ch240_o107_ch260_o110_nu262','no_ch1027_o347_ch240_o107_ch260_o110_nu261',$factors[92],$electricPower[92],'no_ch1027_o347_ch240_o107_ch260_o110_ra265'],
            ['no_ch1027_o347_ch240_o107_ch260_o111_nu261','no_ch1027_o347_ch240_o107_ch260_o111_nu262','no_ch1027_o347_ch240_o107_ch260_o111_nu263',$factors[94],$electricPower[94],'no_ch1027_o347_ch240_o107_ch260_o111_ra265'],
            ['no_ch1027_o347_ch240_o107_ch260_o112_nu261','no_ch1027_o347_ch240_o107_ch260_o112_nu262','no_ch1027_o347_ch240_o107_ch260_o112_nu263',$factors[96],$electricPower[96],'no_ch1027_o347_ch240_o107_ch260_o112_ra265'],
            ['no_ch1027_o347_ch240_o107_ch260_o113_nu261','no_ch1027_o347_ch240_o107_ch260_o113_nu262','no_ch1027_o347_ch240_o107_ch260_o113_nu263',$factors[98],$electricPower[98],'no_ch1027_o347_ch240_o107_ch260_o113_ra265'],
            ['no_ch1027_o347_ch240_o107_ch260_o114_nu261','no_ch1027_o347_ch240_o107_ch260_o114_nu262','no_ch1027_o347_ch240_o107_ch260_o114_nu263',$factors[100],$electricPower[100],'no_ch1027_o347_ch240_o107_ch260_o114_ra265'],
            ['no_ch1027_o347_ch240_o107_ch260_o115_nu261','no_ch1027_o347_ch240_o107_ch260_o115_nu262','no_ch1027_o347_ch240_o107_ch260_o115_nu263',$factors[102],$electricPower[102],'no_ch1027_o347_ch240_o107_ch260_o115_ra265'],
            ['no_ch1027_o347_ch240_o107_ch260_o116_nu261','no_ch1027_o347_ch240_o107_ch260_o116_nu262','no_ch1027_o347_ch240_o107_ch260_o116_nu263',$factors[104],$electricPower[104],'no_ch1027_o347_ch240_o107_ch260_o116_ra265'],
            ['no_ch1027_o347_ch240_o107_ch260_o117_nu261','no_ch1027_o347_ch240_o107_ch260_o117_nu262','no_ch1027_o347_ch240_o107_ch260_o117_nu263',$factors[106],$electricPower[106],'no_ch1027_o347_ch240_o107_ch260_o117_ra265'],
            ['no_ch1027_o347_ch240_o107_ch260_o118_nu261','no_ch1027_o347_ch240_o107_ch260_o118_nu262','no_ch1027_o347_ch240_o107_ch260_o118_nu263',$factors[108],$electricPower[108],'no_ch1027_o347_ch240_o107_ch260_o118_ra265'],
            ['no_ch1027_o347_ch240_o107_ch260_o119_nu261','no_ch1027_o347_ch240_o107_ch260_o119_nu262','no_ch1027_o347_ch240_o107_ch260_o119_nu263',$factors[110],$electricPower[110],'no_ch1027_o347_ch240_o107_ch260_o119_ra265'],
            ['no_ch1027_o348_ch267_o122_nu268','no_ch1027_o348_ch267_o122_nu269','no_ch1027_o348_ch267_o122_nu270',$factors[112],$electricPower[112],''],
            ['no_ch1027_o348_ch267_o123_nu268','no_ch1027_o348_ch267_o123_nu269','no_ch1027_o348_ch267_o123_nu270',$factors[113],$electricPower[113],''],
            ['no_ch1027_o348_ch267_o124_nu268','no_ch1027_o348_ch267_o124_nu269','no_ch1027_o348_ch267_o124_nu270',$factors[114],$electricPower[114],''],
            ['no_ch1027_o349_nu274','no_ch1027_o349_nu275','no_ch1027_o349_nu276',$factors[115],$electricPower[115],''],
            ['no_ch1027_o350_nu280','no_ch1027_o350_nu281','no_ch1027_o350_nu282',$factors[116],$electricPower[116],''],
            ['no_ch1027_o351_nu286','no_ch1027_o351_nu287','no_ch1027_o351_nu288',$factors[117],$electricPower[117],''],
            ['no_ch1027_o352_ch291_o128_ch293_o134_nu294','no_ch1027_o352_ch291_o128_ch293_o134_nu295','no_ch1027_o352_ch291_o128_ch293_o134_nu296',$factors[118],$electricPower[118],''],
            ['no_ch1027_o352_ch291_o128_ch293_o135_nu294','no_ch1027_o352_ch291_o128_ch293_o135_nu295','no_ch1027_o352_ch291_o128_ch293_o135_nu296',$factors[119],$electricPower[119],''],
            ['no_ch1027_o352_ch291_o128_ch293_o136_nu294','no_ch1027_o352_ch291_o128_ch293_o136_nu295','no_ch1027_o352_ch291_o128_ch293_o136_nu296',$factors[120],$electricPower[120],''],
            ['no_ch1027_o352_ch291_o128_ch293_o137_nu294','no_ch1027_o352_ch291_o128_ch293_o137_nu295','no_ch1027_o352_ch291_o128_ch293_o137_nu296',$factors[121],$electricPower[121],''],
            ['no_ch1027_o352_ch291_o128_ch293_o138_nu294','no_ch1027_o352_ch291_o128_ch293_o138_nu295','no_ch1027_o352_ch291_o128_ch293_o138_nu296',$factors[122],$electricPower[122],''],
            ['no_ch1027_o352_ch291_o129_ch298_o132_nu299','no_ch1027_o352_ch291_o129_ch298_o132_nu300','no_ch1027_o352_ch291_o129_ch298_o132_nu301',$factors[123],$electricPower[123],''],
            ['no_ch1027_o352_ch291_o129_ch298_o133_nu299','no_ch1027_o352_ch291_o129_ch298_o133_nu300','no_ch1027_o352_ch291_o129_ch298_o133_nu301',$factors[124],$electricPower[124],''],
            ['no_ch1027_o352_ch291_o129_ch298_o134_nu299','no_ch1027_o352_ch291_o129_ch298_o134_nu300','no_ch1027_o352_ch291_o129_ch298_o134_nu301',$factors[125],$electricPower[125],''],
            ['no_ch1027_o353_ch304_o139_nu305','no_ch1027_o353_ch304_o139_nu306','no_ch1027_o353_ch304_o139_nu307',$factors[126],$electricPower[126],''],
            ['no_ch1027_o353_ch304_o140_nu305','no_ch1027_o353_ch304_o140_nu306','no_ch1027_o353_ch304_o140_nu307',$factors[127],$electricPower[127],''],
            ['no_ch1027_o353_ch304_o141_nu305','no_ch1027_o353_ch304_o141_nu306','no_ch1027_o353_ch304_o141_nu307',$factors[128],$electricPower[128],''],
            ['no_ch1027_o354_ch310_o142_nu311','no_ch1027_o354_ch310_o142_nu312','no_ch1027_o354_ch310_o142_nu313',$factors[129],$electricPower[129],''],
            ['no_ch1027_o354_ch310_o143_nu311','no_ch1027_o354_ch310_o143_nu312','no_ch1027_o354_ch310_o143_nu313',$factors[130],$electricPower[130],''],
            ['no_ch1027_o354_ch310_o144_nu311','no_ch1027_o354_ch310_o144_nu312','no_ch1027_o354_ch310_o144_nu313',$factors[131],$electricPower[131],''],
            ['no_ch1027_o355_ch317_o146_nu318','no_ch1027_o355_ch317_o146_nu319','no_ch1027_o355_ch317_o146_nu320',$factors[132],$electricPower[132],''],
            ['no_ch1027_o355_ch317_o147_nu318','no_ch1027_o355_ch317_o147_nu319','no_ch1027_o355_ch317_o147_nu320',$factors[133],$electricPower[133],''],
            ['no_ch1027_o355_ch317_o148_nu318','no_ch1027_o355_ch317_o148_nu319','no_ch1027_o355_ch317_o148_nu320',$factors[134],$electricPower[134],''],
        ];

        $week = Parameter::WEEK_PER_YEAR;
        $ktoe = Setting::where('code', 'E9')->first()->value;

        // ที่มีฉลากประหยัดไปเบอร์ 5
        // [ จำนวนหม้อ * อัตราการใช้ (นาที/ครั้ง) * อัตราการใช้ (ครั้งต่อวัน) * อัตราการใช้ (วัน/สัปดาห์) * (52/60) ] * factor * electric power
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
            'param2'=>1, //อัตราการใช้ (ชั่วโมง/วัน)
            'param3'=>2, //อัตราการใช้ (วัน/สัปดาห์)
            'param4'=>3, //factor
            'param5'=>4, //electric power
            'param6'=>5  //ฉลากประหยัดไฟ
        ];
        $startColumn = 'AL';
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow, $objPHPExcel,$mainObj,$sumAmountSQL,$params,$ktoe);

        // Table 4
        $table4 = [
            'no_ch1027_o347_ch240_o104_ch241_o108_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o109_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o110_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o111_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o112_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o113_nu245',
            'no_ch1027_o347_ch240_o104_ch241_o114_nu245',
            'no_ch1027_o347_ch240_o105_ch248_o115_nu252',
            'no_ch1027_o347_ch240_o105_ch248_o116_nu252',
            'no_ch1027_o347_ch240_o105_ch248_o117_nu252',
            'no_ch1027_o347_ch240_o105_ch248_o118_nu252',
            'no_ch1027_o347_ch240_o105_ch248_o119_nu252',
            'no_ch1027_o347_ch240_o106_ch254_o108_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o109_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o110_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o111_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o112_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o113_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o114_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o115_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o116_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o117_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o118_nu258',
            'no_ch1027_o347_ch240_o106_ch254_o119_nu258',
            'no_ch1027_o347_ch240_o107_ch260_o108_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o109_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o110_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o111_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o112_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o113_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o114_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o115_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o116_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o117_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o118_nu264',
            'no_ch1027_o347_ch240_o107_ch260_o119_nu264',
            'no_ch1027_o348_ch267_o122_nu271',
            'no_ch1027_o348_ch267_o123_nu271',
            'no_ch1027_o348_ch267_o124_nu271',
            'no_ch1027_o349_nu277',
            'no_ch1027_o350_nu283',
            'no_ch1027_o351_nu289',
            'no_ch1027_o352_ch291_o128_ch293_o134_nu297',
            'no_ch1027_o352_ch291_o128_ch293_o135_nu297',
            'no_ch1027_o352_ch291_o128_ch293_o136_nu297',
            'no_ch1027_o352_ch291_o128_ch293_o137_nu297',
            'no_ch1027_o352_ch291_o128_ch293_o138_nu297',
            'no_ch1027_o352_ch291_o129_ch298_o132_nu302',
            'no_ch1027_o352_ch291_o129_ch298_o133_nu302',
            'no_ch1027_o352_ch291_o129_ch298_o134_nu302',
            'no_ch1027_o353_ch304_o139_nu308',
            'no_ch1027_o353_ch304_o140_nu308',
            'no_ch1027_o353_ch304_o141_nu308',
            'no_ch1027_o354_ch310_o142_nu314',
            'no_ch1027_o354_ch310_o143_nu314',
            'no_ch1027_o354_ch310_o144_nu314',
            'no_ch1027_o355_ch317_o146_nu321',
            'no_ch1027_o355_ch317_o147_nu321',
            'no_ch1027_o355_ch317_o148_nu321',
        ];

        $startColumn = 'BB';
        $objPHPExcel = Summary::averageLifetime($table4,$table2,$startColumn ,$startRow, $objPHPExcel, $mainObj);
//        $objPHPExcel = Summary::average($table4, $startColumn, $startRow, $objPHPExcel, $mainObj);

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));

        return response()->download(storage_path('excel/'.$outputFile), 'ตารางสรุปหมวดข่าวสารบันเทิง.xlsx');
    }

}
