<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    const ANSWERS_TABLE_LAST_UPDATE = 'answers_table_last_update';
    const DASHBOARD_LAST_UPDATE = 'dashboard_last_update';

    protected $casts = [
        'value'=>'array'
    ];
}
