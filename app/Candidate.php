<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    public function voting()
    {
        return $this->belongsTo('App\Voting');
    }
}
