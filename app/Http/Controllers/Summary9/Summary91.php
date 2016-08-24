<?php

namespace App\Http\Controllers\Summary9;

use App\Main;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Summary91 extends Controller
{
    public static function report914()
    {
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

        $table3Eletric = [
            ['no_ch1028_o356_ch323_o149_ch324_o156_nu325', 'no_ch1028_o356_ch323_o149_ch324_o156_nu326','no_ch1028_o356_ch323_o149_ch324_o156_nu327',0.04],
            ['no_ch1028_o356_ch323_o149_ch324_o157_nu325', 'no_ch1028_o356_ch323_o149_ch324_o157_nu326','no_ch1028_o356_ch323_o149_ch324_o157_nu327',0.05],
            ['no_ch1028_o356_ch323_o149_ch324_o158_nu325', 'no_ch1028_o356_ch323_o149_ch324_o158_nu326','no_ch1028_o356_ch323_o149_ch324_o158_nu327',0.078],
            ['no_ch1028_o356_ch323_o150_ch330_o156_nu331', 'no_ch1028_o356_ch323_o150_ch330_o156_nu332','no_ch1028_o356_ch323_o150_ch330_o156_nu332',0.04],
            ['no_ch1028_o356_ch323_o150_ch330_o157_nu331', 'no_ch1028_o356_ch323_o150_ch330_o157_nu332','no_ch1028_o356_ch323_o150_ch330_o157_nu332',0.05],
            ['no_ch1028_o356_ch323_o150_ch330_o158_nu331', 'no_ch1028_o356_ch323_o150_ch330_o158_nu332','no_ch1028_o356_ch323_o150_ch330_o158_nu332',0.078],
            ['no_ch1028_o356_ch323_o151_ch336_o156_nu337', 'no_ch1028_o356_ch323_o151_ch336_o156_nu338','no_ch1028_o356_ch323_o151_ch336_o156_nu339',0.04],
            ['no_ch1028_o356_ch323_o151_ch336_o157_nu337', 'no_ch1028_o356_ch323_o151_ch336_o157_nu338','no_ch1028_o356_ch323_o151_ch336_o157_nu339',0.05],
            ['no_ch1028_o356_ch323_o151_ch336_o158_nu337', 'no_ch1028_o356_ch323_o151_ch336_o158_nu338','no_ch1028_o356_ch323_o151_ch336_o158_nu339',0.078],
            ['no_ch1028_o356_ch323_o152_ch342_o157_nu343', 'no_ch1028_o356_ch323_o152_ch342_o157_nu344','no_ch1028_o356_ch323_o152_ch342_o157_nu345',0.05],
            ['no_ch1028_o356_ch323_o152_ch342_o159_nu343', 'no_ch1028_o356_ch323_o152_ch342_o159_nu344','no_ch1028_o356_ch323_o152_ch342_o159_nu345',0.095],
            ['no_ch1028_o356_ch323_o153_ch348_o158_nu349', 'no_ch1028_o356_ch323_o153_ch348_o158_nu350','no_ch1028_o356_ch323_o153_ch348_o158_nu350',0.125],
            ['no_ch1028_o356_ch323_o153_ch348_o160_nu349', 'no_ch1028_o356_ch323_o153_ch348_o160_nu350','no_ch1028_o356_ch323_o153_ch348_o160_nu351',0.2],
            ['no_ch1028_o356_ch323_o153_ch348_o161_nu349', 'no_ch1028_o356_ch323_o153_ch348_o161_nu350','no_ch1028_o356_ch323_o153_ch348_o161_nu351',0.225],
            ['no_ch1028_o356_ch323_o154_nu353', 'no_ch1028_o356_ch323_o154_nu354','no_ch1028_o356_ch323_o154_nu355',0.045],
            ['no_ch1028_o356_ch323_o155_nu353', 'no_ch1028_o356_ch323_o155_nu354','no_ch1028_o356_ch323_o155_nu355',0.1],
            ['no_ch1028_o357_ch358_o151_ch359_o163_nu360', 'no_ch1028_o357_ch358_o151_ch359_o163_nu361','no_ch1028_o357_ch358_o151_ch359_o163_nu362',0.03],
            ['no_ch1028_o357_ch358_o151_ch359_o164_nu360', 'no_ch1028_o357_ch358_o151_ch359_o164_nu361','no_ch1028_o357_ch358_o151_ch359_o164_nu362',0.035],
            ['no_ch1028_o357_ch358_o151_ch359_o165_nu360', 'no_ch1028_o357_ch358_o151_ch359_o165_nu361','no_ch1028_o357_ch358_o151_ch359_o165_nu362',0.042],
            ['no_ch1028_o358_nu367', 'no_ch1028_o358_nu368','no_ch1028_o358_nu368',0.05],
            ['no_ch1028_o359_nu373', 'no_ch1028_o359_nu374','no_ch1028_o359_nu375',3.5],
            ['no_ch1028_o360_ch379_o167_nu380', 'no_ch1028_o360_ch379_o167_nu381','no_ch1028_o360_ch379_o167_nu382',1.6],
            ['no_ch1028_o360_ch379_o168_nu380', 'no_ch1028_o360_ch379_o168_nu381','no_ch1028_o360_ch379_o168_nu382',0.025],
            ['no_ch1028_o360_ch379_o169_nu380', 'no_ch1028_o360_ch379_o169_nu381','no_ch1028_o360_ch379_o169_nu382',0.8],
            ['no_ch1028_o361_ch385_o170_nu386', 'no_ch1028_o361_ch385_o170_nu387','no_ch1028_o361_ch385_o170_nu388',1],
            ['no_ch1028_o361_ch385_o171_nu386', 'no_ch1028_o361_ch385_o171_nu387','no_ch1028_o361_ch385_o171_nu388',1.2],
            ['no_ch1028_o361_ch385_o172_nu386', 'no_ch1028_o361_ch385_o172_nu387','no_ch1028_o361_ch385_o172_nu388',2.4],
            ['no_ch1028_o361_ch385_o173_nu386', 'no_ch1028_o361_ch385_o173_nu387','no_ch1028_o361_ch385_o173_nu388',1.6],
            ['no_ch1028_o361_ch385_o174_nu386', 'no_ch1028_o361_ch385_o174_nu387','no_ch1028_o361_ch385_o174_nu388',1.5],
            ['no_ch1028_o362_ch392_o175_ch393_o177_nu394', 'no_ch1028_o362_ch392_o175_ch393_o177_nu395','no_ch1028_o362_ch392_o175_ch393_o177_nu396',0.06],
            ['no_ch1028_o362_ch392_o175_ch393_o178_nu394', 'no_ch1028_o362_ch392_o175_ch393_o178_nu395','no_ch1028_o362_ch392_o175_ch393_o178_nu396',0.075],
            ['no_ch1028_o362_ch392_o176_ch1001_o179_nu1002', 'no_ch1028_o362_ch392_o176_ch1001_o179_nu1003','no_ch1028_o362_ch392_o176_ch1001_o179_nu1004',0.09],
            ['no_ch1028_o362_ch392_o176_ch1001_o180_nu1002', 'no_ch1028_o362_ch392_o176_ch1001_o180_nu1003','no_ch1028_o362_ch392_o176_ch1001_o180_nu1004',0.25],
            ['no_ch1028_o363_ch400_o181_ch401_o183_nu402', 'no_ch1028_o363_ch400_o181_ch401_o183_nu403','no_ch1028_o363_ch400_o181_ch401_o183_nu404',0.6],
            ['no_ch1028_o363_ch400_o181_ch401_o184_nu402', 'no_ch1028_o363_ch400_o181_ch401_o184_nu403','no_ch1028_o363_ch400_o181_ch401_o184_nu404',0.95],
            ['no_ch1028_o363_ch400_o181_ch401_o185_nu402', 'no_ch1028_o363_ch400_o181_ch401_o185_nu403','no_ch1028_o363_ch400_o181_ch401_o185_nu404',1.3],
            ['no_ch1028_o363_ch400_o181_ch401_o186_nu402', 'no_ch1028_o363_ch400_o181_ch401_o186_nu403','no_ch1028_o363_ch400_o181_ch401_o186_nu404',1.6],
            ['no_ch1028_o363_ch400_o181_ch401_o187_nu402', 'no_ch1028_o363_ch400_o181_ch401_o187_nu403','no_ch1028_o363_ch400_o181_ch401_o187_nu404',2],
            ['no_ch1028_o363_ch400_o181_ch401_o188_nu402', 'no_ch1028_o363_ch400_o181_ch401_o188_nu403','no_ch1028_o363_ch400_o181_ch401_o188_nu404',2.3],
            ['no_ch1028_o363_ch400_o182_ch410_o185_nu411', 'no_ch1028_o363_ch400_o182_ch410_o185_nu412','no_ch1028_o363_ch400_o182_ch410_o185_nu413',1.55],
            ['no_ch1028_o363_ch400_o182_ch410_o186_nu411', 'no_ch1028_o363_ch400_o182_ch410_o186_nu412','no_ch1028_o363_ch400_o182_ch410_o186_nu413',1.75],
            ['no_ch1028_o363_ch400_o182_ch410_o187_nu411', 'no_ch1028_o363_ch400_o182_ch410_o187_nu412','no_ch1028_o363_ch400_o182_ch410_o187_nu413',2.15],
            ['no_ch1028_o363_ch400_o182_ch410_o188_nu411', 'no_ch1028_o363_ch400_o182_ch410_o188_nu412','no_ch1028_o363_ch400_o182_ch410_o188_nu413',2.3],
            ['no_ch1028_o363_ch400_o182_ch410_o189_nu411', 'no_ch1028_o363_ch400_o182_ch410_o189_nu412','no_ch1028_o363_ch400_o182_ch410_o189_nu413',3],
            ['no_ch1028_o363_ch400_o182_ch410_o190_nu411', 'no_ch1028_o363_ch400_o182_ch410_o190_nu412','no_ch1028_o363_ch400_o182_ch410_o190_nu413',3.5],
            ['no_ch1028_o363_ch400_o182_ch410_o191_nu411', 'no_ch1028_o363_ch400_o182_ch410_o191_nu412','no_ch1028_o363_ch400_o182_ch410_o191_nu413',5.3],
            ['no_ch1028_o364_ch420_o195_ch421_o198_nu422', 'no_ch1028_o364_ch420_o195_ch421_o198_nu423','no_ch1028_o364_ch420_o195_ch421_o198_nu424',2],
            ['no_ch1028_o364_ch420_o195_ch421_o199_nu422', 'no_ch1028_o364_ch420_o195_ch421_o199_nu423','no_ch1028_o364_ch420_o195_ch421_o199_nu424',2.2],
            ['no_ch1028_o364_ch420_o195_ch421_o200_nu422', 'no_ch1028_o364_ch420_o195_ch421_o200_nu423','no_ch1028_o364_ch420_o195_ch421_o200_nu424',2.2],
            ['no_ch1028_o364_ch420_o195_ch421_o201_nu422', 'no_ch1028_o364_ch420_o195_ch421_o201_nu423','no_ch1028_o364_ch420_o195_ch421_o201_nu424',2.3],
            ['no_ch1028_o364_ch420_o196_ch421_o198_nu422', 'no_ch1028_o364_ch420_o196_ch421_o198_nu423','no_ch1028_o364_ch420_o196_ch421_o198_nu424',0.35],
            ['no_ch1028_o364_ch420_o196_ch421_o199_nu422', 'no_ch1028_o364_ch420_o196_ch421_o199_nu423','no_ch1028_o364_ch420_o196_ch421_o199_nu424',0.4],
            ['no_ch1028_o364_ch420_o196_ch421_o200_nu422', 'no_ch1028_o364_ch420_o196_ch421_o200_nu423','no_ch1028_o364_ch420_o196_ch421_o200_nu424',0.5],
            ['no_ch1028_o364_ch420_o196_ch421_o201_nu422', 'no_ch1028_o364_ch420_o196_ch421_o201_nu423','no_ch1028_o364_ch420_o196_ch421_o201_nu424',0.55],
            ['no_ch1028_o364_ch420_o197_ch421_o198_nu422', 'no_ch1028_o364_ch420_o197_ch421_o198_nu423','no_ch1028_o364_ch420_o197_ch421_o198_nu424',0.3],
            ['no_ch1028_o364_ch420_o197_ch421_o199_nu422', 'no_ch1028_o364_ch420_o197_ch421_o199_nu423','no_ch1028_o364_ch420_o197_ch421_o199_nu424',0.35],
            ['no_ch1028_o364_ch420_o197_ch421_o200_nu422', 'no_ch1028_o364_ch420_o197_ch421_o200_nu423','no_ch1028_o364_ch420_o197_ch421_o200_nu424',0.4],
            ['no_ch1028_o364_ch420_o197_ch421_o201_nu422', 'no_ch1028_o364_ch420_o197_ch421_o201_nu423','no_ch1028_o364_ch420_o197_ch421_o201_nu424',0.45],
            ['no_ch1028_o365_nu429', 'no_ch1028_o365_nu430','no_ch1028_o365_nu431',1.1],
            ['no_ch1028_o366_nu435', 'no_ch1028_o366_nu436','no_ch1028_o366_nu437',0.05],
            ['no_ch1028_o367_nu441', 'no_ch1028_o367_nu442','no_ch1028_o367_nu443',0.01],
            ['no_ch1028_o368_nu447', 'no_ch1028_o368_nu448','no_ch1028_o368_nu449',1.4],
            ['no_ch1028_o369_nu453', 'no_ch1028_o369_nu454','no_ch1028_o369_nu455',0.2],
        ];
        $ktoe = Parameter::ELECTRIC_KTOE;
        $week = Parameter::WEEK_PER_YEAR;
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) * sum(if(unique_key='param2', answer_numeric,0)) * sum(if(unique_key='param3', answer_numeric,0))) * {$week}  * (param4) as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2,
            'param4'=>3
        ];
        $objPHPExcel = Summary::usageElectric($table3Eletric, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL,$params,$ktoe);
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

        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $sumAmountSQL = " (sum(IF(unique_key='param1',answer_numeric,0)) * (sum(IF(unique_key='param2',answer_numeric,0)) 
        (sum(IF(unique_key='param3',answer_numeric,0)) * 12 as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2
        ];
        $objPHPExcel = Summary::usageElectric($table3, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL,$params,0,false,3);
        $objPHPExcel = Summary::average($table4, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

    public static function report916()
    {
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

        $startColumn = ['E','U','AL','BB'];
        $startRow = 13;

        $objPHPExcel = Summary::sum($table1, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $objPHPExcel = Summary::average($table2, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $week = Parameter::WEEK_PER_YEAR;
        $elecKTOE = Parameter::ELECTRIC_KTOE;
        $sumAmountSQL_1 = " (sum(IF(unique_key='param1',answer_numeric,0)) * (sum(IF(unique_key='param2',answer_numeric,0)) 
        (sum(IF(unique_key='param3',answer_numeric,0)) * {$week} * (param4) as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2,
            'param4'=>3
        ];
        $objPHPExcel = Summary::usageElectric($table3_1, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL_1,$params,$elecKTOE);
        $sumAmountSQL_2 = " (sum(IF(unique_key='param1',answer_numeric,0)) * (sum(IF(unique_key='param2',answer_numeric,0)) 
        (sum(IF(unique_key='param3',answer_numeric,0)) * 12 as sumAmount ";
        $params = [
            'param1'=>0,
            'param2'=>1,
            'param3'=>2
        ];
        $objPHPExcel = Summary::usageElectric($table3_2, $startColumn, $startRow,$objPHPExcel, $mainObj,$sumAmountSQL_2,$params,0,false,3);

        $objPHPExcel = Summary::average($table4, $startColumn, $startRow, $objPHPExcel, $mainObj);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(storage_path(iconv('UTF-8', 'windows-874', 'excel/'.$outputFile)));
    }

    public function report917()
    {
        
    }

    public function report918()
    {
        
    }
}
