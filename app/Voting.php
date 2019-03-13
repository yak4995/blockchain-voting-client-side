<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{

    public function faculty()
    {
        return $this->belongsTo('App\Faculty');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function candidates()
    {
        return $this->hasMany('App\Candidate');
    }

    public function admittedVoters()
    {
        return $this->hasMany('App\AdmittedVoter');
    }
}
