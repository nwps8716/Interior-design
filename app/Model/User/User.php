<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'password',
        'level',
    ];

    protected $table = 'users';
    protected $primaryKey = 'user_id';
   
    public function user()
    {
        return $this->belongsTo('App\Model\User\User');
    }
}
