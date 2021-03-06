<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class punches extends Model
{
    protected $fillable = [
        'user_id',
        'punch_in', 
        'punch_out'
    ];

    public function User()  
    {
        return $this->hasMany(User::class);
    }
     
    public function getTimingAttribute()
    {
       if ($this->punch_out) {
           return $this->punch_out->diffInSeconds($this->punch_in);
       }
    }  
    
}
