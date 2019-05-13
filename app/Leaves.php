<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leaves extends Model
{
    protected $fillable = [
        'user_id',
        'type', 
        'description',
        'date'
    ];

    public function User()  
    {
        return $this->hasMany(User::class);
    }

}
