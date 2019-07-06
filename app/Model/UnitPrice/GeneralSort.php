<?php

namespace App\Model\UnitPrice;

use Illuminate\Database\Eloquent\Model;

class GeneralSort extends Model
{
    protected $fillable = [
        'sgn_id',
        'system_id',
        'sub_system_id',
        'sort',
    ];
    protected $table = 'general_sort';
    public $timestamps = false;
}
