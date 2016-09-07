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
        Main::NORTHERN_INNER=>0.336,
        Main::NORTHERN_OUTER=>0.664,
        Main::INNER_GROUP_1=>0.673,
        Main::INNER_GROUP_2=>0.327,
        Main::OUTER_GROUP_1=>0.488,
        Main::OUTER_GROUP_2=>0.512,
        Main::CHIANGMAI_INNER=>0.862,
        Main::CHIANGMAI_OUTER=>0.782,
        Main::UTARADIT_INNER=>0.138,
        Main::UTARADIT_OUTER=>0.218,
        Main::NAN_INNER=>0.150,
        Main::NAN_OUTER=>0.206,
        Main::PITSANULOK_INNER=>0.438,
        Main::PITSANULOK_OUTER=>0.386,
        Main::PETCHABUL_INNER=>0.412,
        Main::PETCHABUL_OUTER=>0.408
    ];

    public static $sample = [
      Main::INNER_GROUP_1=>565.00,
        Main::INNER_GROUP_2=>274.00,
        Main::OUTER_GROUP_1=>811.00,
        Main::OUTER_GROUP_2=>850.00
    ];

    public static $provinceSample =
    [
        Main::CHIANGMAI_INNER=>487,
        Main::CHIANGMAI_OUTER=>634,
        Main::NAN_INNER=>41,
        Main::NAN_OUTER=>175,
        Main::UTARADIT_INNER=>78,
        Main::UTARADIT_OUTER=>177,
        Main::PITSANULOK_INNER=>120,
        Main::PITSANULOK_OUTER=>328,
        Main::PETCHABUL_INNER=>113,
        Main::PETCHABUL_OUTER=>347
    ];

    public static $population = [
        Main::NORTHERN=>4467077,
        Main::NORTHERN_INNER=>1413774,
        Main::NORTHERN_OUTER=>3053303
    ];

    public static $provinceWeightText = [
        Main::CHIANGMAI_INNER=>'เชียงใหม่ในเขต',
        Main::CHIANGMAI_OUTER=>'เชียงใหม่นอกเขต',
        Main::UTARADIT_INNER=>'อุตรดิตในเขต',
        Main::UTARADIT_OUTER=>'อุตรดิตนอกเขต',
        Main::NAN_INNER=>'น่านในเขต',
        Main::NAN_OUTER=>'น่านนอกเขต',
        Main::PITSANULOK_INNER=>'พิษณุโลกในเขต',
        Main::PITSANULOK_OUTER=>'พิษณุโลกนอกเขต',
        Main::PETCHABUL_INNER=>'เพชรบูรณ์ในเขต',
        Main::PETCHABUL_OUTER=>'เพชรบูรณ์นอกเขต'
    ];

    public static $provinceWeight = [
        Main::CHIANGMAI_INNER=>0.862,
        Main::CHIANGMAI_OUTER=>0.782,
        Main::UTARADIT_INNER=>0.138,
        Main::UTARADIT_OUTER=>0.218,
        Main::NAN_INNER=>0.150,
        Main::NAN_OUTER=>0.206,
        Main::PITSANULOK_INNER=>0.438,
        Main::PITSANULOK_OUTER=>0.386,
        Main::PETCHABUL_INNER=>0.412,
        Main::PETCHABUL_OUTER=>0.408
    ];

    public static $borderWeight = [
        Main::INNER_GROUP_1=>0.673,
        Main::INNER_GROUP_2=>0.327,
        Main::OUTER_GROUP_1=>0.488,
        Main::OUTER_GROUP_2=>0.512,
    ];
    public static $borderWeightText = [
        Main::INNER_GROUP_1=>'ในเขตกลุ่ม 1',
        Main::INNER_GROUP_2=>'ในเขตกลุ่ม 2',
        Main::OUTER_GROUP_1=>'นอกเขตกลุ่ม 1',
        Main::OUTER_GROUP_2=>'นอกเขตกลุ่ม 2',
    ];

    protected $mainList;

    public function initList()
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
        //echo 'Init complete' . "\n";
        $this->mainList = collect($result);
    }

    public function filterMain($groupId)
    {
        if ($groupId===Main::INNER_GROUP_1){
            return $this->mainList->filter(function ($value, $key){
                return (int)$value->inborder===1 && ((int)$value->chiangmai===1 || (int)$value->utaradit===1);
            })->lists('main_id')->toArray();
        }elseif ($groupId===Main::INNER_GROUP_2){
            return $this->mainList->filter(function ($value, $key){
                return (int)$value->inborder===1 && ((int)$value->nan===1 || (int)$value->pitsanurok===1 || (int)$value->petchabul===1);
            })->lists('main_id')->toArray();
        }elseif ($groupId===Main::OUTER_GROUP_1){
            return $this->mainList->filter(function ($value, $key){
                return (int)$value->outborder===1 && ((int)$value->chiangmai===1 || (int)$value->utaradit===1);
            })->lists('main_id')->toArray();
        }elseif ($groupId===Main::OUTER_GROUP_2){
            return $this->mainList->filter(function ($value, $key){
                return (int)$value->outborder===1 && ((int)$value->nan===1 || (int)$value->pitsanurok===1 || (int)$value->petchabul===1);
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::CHIANGMAI_INNER){
            return $this->mainList->filter(function ($value, $key){
                return ((int)$value->chiangmai===1) && (int)$value->inborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::CHIANGMAI_OUTER){
            return $this->mainList->filter(function ($value, $key){
                return ((int)$value->chiangmai===1) && (int)$value->outborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::UTARADIT_INNER){
            return $this->mainList->filter(function ($value, $key){
                return ((int)$value->utaradit===1)&& (int)$value->inborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::UTARADIT_OUTER){
            return $this->mainList->filter(function ($value, $key){
                return ((int)$value->utaradit===1) && (int)$value->outborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::NAN_INNER){
            return $this->mainList->filter(function ($value, $key){
                return ((int)$value->nan===1)&& (int)$value->inborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::NAN_OUTER){
            return $this->mainList->filter(function ($value, $key){
                return ((int)$value->nan===1) && (int)$value->outborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::PITSANULOK_INNER){
            return $this->mainList->filter(function ($value, $key){
                return ((int)$value->pitsanurok===1)&& (int)$value->inborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::PITSANULOK_OUTER){
            return $this->mainList->filter(function ($value, $key){
                return ((int)$value->pitsanurok===1) && (int)$value->outborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::PETCHABUL_INNER){
            return $this->mainList->filter(function ($value, $key){
                return ((int)$value->pitsanurok===1)&& (int)$value->inborder===1;
            })->lists('main_id')->toArray();
        }
        elseif ($groupId===Main::PETCHABUL_OUTER){
            return $this->mainList->filter(function ($value, $key){
                return ((int)$value->pitsanurok===1) && (int)$value->outborder===1;
            })->lists('main_id')->toArray();
        }

        return null;
    }

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

//        echo 'getMainListQuery complete' . "\n";

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
