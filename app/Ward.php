<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $table = 'wards';

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public function district() {
        return $this->belongsTo('App\District');
    }

    public function repositories() {
        return $this->hasMany('App\Repository');
    }
}
