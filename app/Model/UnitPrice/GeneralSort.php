<?php

namespace App\Model\UnitPrice;

use Illuminate\Database\Eloquent\Model;

class GeneralSort extends Model
{
    protected $fillable = [
        'sub_system_id',
        'sort',
    ];
    protected $table = 'general_sort';
    public $timestamps = false;
}
