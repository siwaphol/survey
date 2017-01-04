<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    const NORTHERN_INNER_GROUP_1_WEIGHT_CODE = 'wh_inner_province_1';
    const NORTHERN_INNER_GROUP_2_WEIGHT_CODE = 'wh_inner_province_2';
    const NORTHERN_OUTER_GROUP_1_WEIGHT_CODE = 'wh_outer_province_1';
    const NORTHERN_OUTER_GROUP_2_WEIGHT_CODE = 'wh_outer_province_2';

    const NORTHERN_INNER_GROUP_1_SAMPLE_CODE = 'real_sample_inner_province_1';
    const NORTHERN_INNER_GROUP_2_SAMPLE_CODE = 'real_sample_inner_province_2';
    const NORTHERN_OUTER_GROUP_1_SAMPLE_CODE = 'real_sample_outer_province_1';
    const NORTHERN_OUTER_GROUP_2_SAMPLE_CODE = 'real_sample_outer_province_2';

    CONST NORTHERN_POPULATION_CODE = 'C1';
    CONST NORTHERN_INNER_POPULATION_CODE = 'C2';
    CONST NORTHERN_OUTER_POPULATION_CODE = 'C3';



    protected $fillable = [
        'name_en','name_th','code','value','unit_of_measure','group_id',
        'category'
    ];


}
