<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'name_en','name_th','code','value','unit_of_measure','group_id',
        'category'
    ];
}
