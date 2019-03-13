<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'departments';

    public function faculty()
    {
        return $this->belongsTo('App\Faculty');
    }

    public function employees()
    {
        return $this->hasMany('App\Employee');
    }

    public function votings()
    {
        return $this->hasMany('App\Voting');
    }
}
