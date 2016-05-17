<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_TITLE = 'title';
    const TYPE_NUMBER = 'number';
    const TYPE_TEXT = 'text';
}
