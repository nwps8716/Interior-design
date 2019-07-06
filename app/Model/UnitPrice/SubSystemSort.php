<?php

namespace App\Model\UnitPrice;

use Illuminate\Database\Eloquent\Model;

class SubSystemSort extends Model
{
    protected $fillable = [
        'sgn_id',
        'system_id',
        'general_name',
        'sort',
    ];
    protected $table = 'sub_system_sort';
    protected $primaryKey = 'sgn_id';
    public $timestamps = false;
}
