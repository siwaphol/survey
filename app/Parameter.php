<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\ParameterBag;

class Parameter extends Model
{
    const ELECTRIC_POWER = 'electric_power';
    const TIME_UNIT = 'time_unit';
    const POPULATION = 'population';
    const OIL_PRICE = 'oil_price';
    const KTOE_UNIT = 'ktoe_unit';

    public static $populationColumn = [
        Main::NORTHERN=>'D10',
        Main::NORTHERN_INNER=>'D11',
        Main::NORTHERN_OUTER=>'D12',
        Main::CHIANGMAI_INNER=>'D13',
        Main::CHIANGMAI_OUTER=>'D14',
        Main::NAN_INNER=>'D15',
        Main::NAN_OUTER=>'D16',
        Main::UTARADIT_INNER=>'D17',
        Main::UTARADIT_OUTER=>'D18',
        Main::PITSANULOK_INNER=>'D19',
        Main::PITSANULOK_OUTER=>'D20',
        Main::PETCHABUL_INNER=>'D21',
        Main::PETCHABUL_OUTER=>'D22',
    ];

    const NATURAL_GAS = 1;
    CONST LIQUID_PETROLEUM = 2;
    CONST BENZINE_95 = 3;
    CONST GASOHAL_91 = 4;
    CONST GASOHAL_95 = 5;
    CONST GASOHAL_E_20 = 6;
    CONST GASOHAL_E_85 = 7;
    CONST DIESEL = 8;
    CONST ELECTRIC = 9;
    CONST WOOD = 10;
    CONST CHARCOAL = 11;
    CONST GRAB = 12;
    CONST AGRICULTURAL_REMAIN = 13;

    const WEEK_PER_YEAR = 52.14;

    const ELECTRIC_KTOE = 0.08521;
    const GAS_KTOE = 0.024;

    const INPUT_FILE_SUMMARY_9 = 'summary9.xlsx';

    public static $ktoe = [
        Parameter::NATURAL_GAS=>0.02457,
        Parameter::LIQUID_PETROLEUM=>1.16690,
        Parameter::BENZINE_95=>0.74507,
        Parameter::GASOHAL_91=>0.74507,
        Parameter::GASOHAL_95=>0.74507,
        Parameter::GASOHAL_E_20=>0.74507,
        Parameter::GASOHAL_E_85=>0.74507,
        Parameter::DIESEL=>0.86198,
        Parameter::ELECTRIC=>0.08521,
        Parameter::WOOD=>0.37848,
        Parameter::CHARCOAL=>0.68364,
        Parameter::GRAB=>0.34083,
        Parameter::AGRICULTURAL_REMAIN=>0.30021,
    ];

    public static function importParameterFromExcel(){
        $objPHPExcel = \PHPExcel_IOFactory::load(storage_path('excel/parameters.xlsx'));
        \DB::transaction(function()use($objPHPExcel){
            $objWorksheet = $objPHPExcel->getSheet(4);
            $startRow= 9;
            $maxRow = 21;

            $category = null;
            $level1Text = "";
            $level2Text = "";
            $groupId = \DB::table('setting_groups')->where('name_en', Parameter::KTOE_UNIT)->first()->id;
            for ($i=$startRow; $i<=$maxRow; $i++){
                if (!empty($objWorksheet->getCell("A".$i)->getValue())){
                    $category = $objWorksheet->getCell("A".$i)->getValue();
                    $level1Text = "";
                    $level2Text = "";
                    $level3Text = "";
                }

                if (!empty($objWorksheet->getCell("B".$i)->getValue())){
                    $level1Text = $objWorksheet->getCell("B".$i)->getValue();
                }

//                if (!empty($objWorksheet->getCell("D".$i)->getValue())){
//                    $level2Text = $objWorksheet->getCell("D".$i)->getValue();
//                }
//                $level3Text = "";
//                if (!empty($objWorksheet->getCell("E".$i)->getValue()) || $objWorksheet->getCell("E".$i)->getValue()!=='-'){
//                    $level3Text = $objWorksheet->getCell("E".$i)->getValue();
//                }

//                $name_th = $level1Text . "/" . $level2Text . "/" . $level3Text;
                $name_th = $level1Text;
                $value = (float)$objWorksheet->getCell("D".$i)->getValue();
                $unit_of_measure = $objWorksheet->getCell("E".$i)->getValue();

                $setting = new Setting();
                $setting->name_en = " ";
                $setting->name_th = $name_th;
                $setting->value = $value;
                $setting->unit_of_measure = $unit_of_measure;
                $setting->category = $category;
                $setting->code = $objWorksheet->getCell("C".$i)->getValue();
                $setting->group_id = $groupId;
                $setting->save();
            }
        });

        dd('success');
    }

}
