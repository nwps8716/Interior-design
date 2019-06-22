<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;

class WhiteIP extends Model
{
    protected $fillable = [
        'id',
        'ip',
        'remark'
    ];

    protected $table = 'white_ip';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
