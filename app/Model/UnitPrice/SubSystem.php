<?php

namespace App\Model\UnitPrice;

use Illuminate\Database\Eloquent\Model;

class SubSystem extends Model
{
    protected $fillable = [
        'sub_system_id',
        'sub_system_name',
        'format',
        'unit_price',
        'unit',
        'system_id',
    ];
    protected $table = 'sub_system';
    protected $primaryKey = 'sub_system_id';
    public $timestamps = false;
}
