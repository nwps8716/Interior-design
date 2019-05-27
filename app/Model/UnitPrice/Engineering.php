<?php

namespace App\Model\UnitPrice;

use Illuminate\Database\Eloquent\Model;

class Engineering extends Model
{
    protected $fillable = [
        'project_id',
        'project_name',
        'sort',
    ];

    protected $table = 'engineering';
    protected $primaryKey = 'project_id';
    public $timestamps = false;
}
