<?php

namespace App\Model\UnitPrice;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $fillable = [
        'system_id',
        'system_name',
        'sort',
    ];

    protected $table = 'system';
    protected $primaryKey = 'system_id';
    public $timestamps = false;
}
