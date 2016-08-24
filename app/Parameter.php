<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
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

    const WEEK_PER_YEAR = 52.14;

}
