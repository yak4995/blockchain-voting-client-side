<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    public function employees()
    {
        return $this->hasMany('App\Employee');
    }

    public function votings()
    {
        return $this->hasMany('App\Voting');
    }
}
