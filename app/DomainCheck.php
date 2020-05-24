<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DomainCheck extends Model
{
    protected $fillable = [];

    public function domain()
    {
        return $this->belongsTo('App\Domain');
    }
}
