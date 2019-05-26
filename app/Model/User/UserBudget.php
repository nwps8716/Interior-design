<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;

class UserBudget extends Model
{
    protected $fillable = [
        'id',
        'user_name',
        'budget_id',
        'sub_project_id',
        'sub_project_number',
        'remark',
    ];

    protected $table = 'user_budget';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
