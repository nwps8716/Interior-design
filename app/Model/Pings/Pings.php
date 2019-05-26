<?php

namespace App\Model\Pings;

use Illuminate\Database\Eloquent\Model;

class Pings extends Model
{
    protected $fillable = [
        'id',
        'name',
        'percent',
    ];

    protected $table = 'pings';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
