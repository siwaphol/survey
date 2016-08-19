<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Main extends Model
{
    protected $fillable = ['main_id', 'recorder_id'];

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

        if ($groupId===1){
            return $result->filter(function ($value, $key){
                return (int)$value->inborder===1 && ((int)$value->chiangmai===1 || (int)$value->utaradit===1);
            })->lists('main_id')->toArray();
        }elseif ($groupId===2){
            return $result->filter(function ($value, $key){
                return (int)$value->inborder===1 && ((int)$value->nan===1 || (int)$value->pitsanurok===1 || (int)$value->petchabul===1);
            })->lists('main_id')->toArray();
        }elseif ($groupId===3){
            return $result->filter(function ($value, $key){
                return (int)$value->outborder===1 && ((int)$value->chiangmai===1 || (int)$value->utaradit===1);
            })->lists('main_id')->toArray();
        }elseif ($groupId===4){
            return $result->filter(function ($value, $key){
                return (int)$value->outborder===1 && ((int)$value->nan===1 || (int)$value->pitsanurok===1 || (int)$value->petchabul===1);
            })->lists('main_id')->toArray();
        }

        return null;
    }
}
