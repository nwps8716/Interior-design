<?php

namespace App\Model\UnitPrice;

use Illuminate\Database\Eloquent\Model;

class SubEngineering extends Model
{
    protected $fillable = [
        'sub_project_id',
        'sub_project_name',
        'unti_price',
        'unti',
        'project_id',
        'sort',
    ];
    protected $table = 'sub_engineering';
    protected $primaryKey = 'sub_project_id';
    public $timestamps = false;
}
