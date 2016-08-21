<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Main extends Model
{
    protected $fillable = ['main_id', 'recorder_id'];
    const INNER_GROUP_1 = 1;
    const INNER_GROUP_2 = 2;
    const OUTER_GROUP_1 = 3;
    const OUTER_GROUP_2 = 4;
    const CHIANGMAI_INNER = 5;
    const CHIANGMAI_OUTER = 6;
    const UTARADIT_INNER = 7;
    const UTARADIT_OUTER = 8;
    const NAN_INNER = 9;
    const NAN_OUTER = 10;
    const PITSANULOK_INNER = 11;
    const PITSANULOK_OUTER = 12;
    const PETCHABUL_INNER = 13;
    const PETCHABUL_OUTER = 14;

    const NORTHERN = 15;
    const NORTHERN_INNER = 16;
    const NORTHERN_OUTER = 17;

    const NUMBER_FORMAT = '#,##0.00';

    public static $weight = [
        Main::INNER_GROUP_1=>0.66,
        Main::INNER_GROUP_2=>0.34,
        Main::OUTER_GROUP_1=>0.50,
        Main::OUTER_GROUP_2=>0.50,
        Main::CHIANGMAI_INNER=>0.853,
        Main::CHIANGMAI_OUTER=>0.799,
        Main::UTARADIT_INNER=>0.147,
        Main::UTARADIT_OUTER=>0.201,
        Main::NAN_INNER=>0.150,
        Main::NAN_OUTER=>0.27,
        Main::PITSANULOK_INNER=>0.436,
        Main::PITSANULOK_OUTER=>0.36,
        Main::PETCHABUL_INNER=>0.413,
        Main::PETCHABUL_OUTER=>0.38
    ];

    public static $sample = [
      Main::INNER_GROUP_1=>526.00,
        Main::INNER_GROUP_2=>274.00,
        Main::OUTER_GROUP_1=>850.00,
        Main::OUTER_GROUP_2=>850.00
    ];

    public static $population = [
        Main::NORTHERN=>4467077,
        Main::NORTHERN_INNER=>1432284,
        Main::NORTHERN_OUTER=>3034793
    ];

    public static $provinceWeight = [
        Main::CHIANGMAI_INNER=>0.853,
        Main::CHIANGMAI_OUTER=>0.799,
        Main::UTARADIT_INNER=>0.147,
        Main::UTARADIT_OUTER=>0.201,
        Main::NAN_INNER=>0.150,
        Main::NAN_OUTER=>0.27,
        Main::PITSANULOK_INNER=>0.436,
        Main::PITSANULOK_OUTER=>0.36,
        Main::PETCHABUL_INNER=>0.413,
        Main::PETCHABUL_OUTER=>0.38
    ];

    public static $borderWeight = [
        Main::INNER_GROUP_1=>0.66,
        Main::INNER_GROUP_2=>0.34,
        Main::OUTER_GROUP_1=>0.50,
        Main::OUTER_GROUP_2=>0.50,
    ];



    public static function getMainList($groupId)
    {
        $sql = "SELECT
                  main_id,
                sum(if(unique_key='no_ra14_o6_ra2002' and option_id=310,1,0)) as chiangmai
                ,sum(if(unique_key='no_ra14_o6_ra2002' and option_id=311,1,0)) as nan
                ,sum(if(unique_key='no_ra14_o6_ra2002' and option_id=312,1,0)) as utaradit
                ,sum(if(unique_key='no_ra14_o6_ra2002' and option_id=313,1,0)) as pitsanurok
                ,sum(if(unique_key='no_ra14_o6_ra2002' and option_id=314,1,0)) as petchabul
            
                ,sum(if(unique_key='no_ra11' and option_id=4,1,0)) as inborder
                ,sum(if(unique_key='no_ra11' and option_id=5,1,0)) as outborder
                from answers
                WHERE unique_key in ('no_ra11','no_ra14_o6_ra2002','no_ra14_o7_ra2003', 'no_ra14')
                GROUP BY main_id";
        $result = \DB::select($sql);

        $result = collect($result);

        if ($groupId===Main::INNER_GROUP_1){
            return $result->filter(function ($value, $key){
                return (int)$value->inborder===1 && ((int)$value->chiangmai===1 || (int)$value->utaradit===1);
            })->lists('main_id')->toArray();
        }elseif ($groupId===Main::INNER_GROUP_2){
            return $result->filter(function ($value, $key){
                return (int)$value->inborder===1 && ((int)$value->nan===1 || (int)$value->pitsanurok===1 || (int)$value->petchabul===1);
            })->lists('main_id')->toArray();
        }elseif ($groupId===Main::OUTER_GROUP_1){
            return $result->filter(function ($value, $key){
                return (int)$value->outborder===1 && ((int)$value->chiangmai===1 || (int)$value->utaradit===1);
            })->lists('main_id')->toArray();
        }elseif ($groupId===Main::OUTER_GROUP_2){
            return $result->filter(function ($value, $key){
                return (int)$value->outborder===1 && ((int)$value->nan===1 || (int)$value->pitsanurok===1 || (int)$value->petchabul===1);
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::CHIANGMAI_INNER){
            return $result->filter(function ($value, $key){
                return ((int)$value->chiangmai===1) && (int)$value->inborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::CHIANGMAI_OUTER){
            return $result->filter(function ($value, $key){
                return ((int)$value->chiangmai===1) && (int)$value->outborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::UTARADIT_INNER){
            return $result->filter(function ($value, $key){
                return ((int)$value->utaradit===1)&& (int)$value->inborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::UTARADIT_OUTER){
            return $result->filter(function ($value, $key){
                return ((int)$value->utaradit===1) && (int)$value->outborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::NAN_INNER){
            return $result->filter(function ($value, $key){
                return ((int)$value->nan===1)&& (int)$value->inborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::NAN_OUTER){
            return $result->filter(function ($value, $key){
                return ((int)$value->nan===1) && (int)$value->outborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::PITSANULOK_INNER){
            return $result->filter(function ($value, $key){
                return ((int)$value->pitsanurok===1)&& (int)$value->inborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::PITSANULOK_OUTER){
            return $result->filter(function ($value, $key){
                return ((int)$value->pitsanurok===1) && (int)$value->outborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::PETCHABUL_INNER){
            return $result->filter(function ($value, $key){
                return ((int)$value->pitsanurok===1)&& (int)$value->inborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::PETCHABUL_OUTER){
            return $result->filter(function ($value, $key){
                return ((int)$value->pitsanurok===1) && (int)$value->outborder===1;
            })->lists('main_id')->toArray();
        }

        return null;
    }
}
