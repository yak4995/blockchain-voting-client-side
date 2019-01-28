<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faculties';

    public function departments()
    {
        return $this->hasMany('App\Department');
    }

    public function votings()
    {
        return $this->hasMany('App\Voting');
    }
}
