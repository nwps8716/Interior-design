<?php

namespace App\Model\Appraisal;

use Illuminate\Database\Eloquent\Model;

class TotalBudget extends Model
{
    protected $fillable = [
        'id',
        'user_name',
        'total_budget',
        'engineering_budget',
        'system_budget',
        'system_discount',
    ];

    protected $table = 'total_budget';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
