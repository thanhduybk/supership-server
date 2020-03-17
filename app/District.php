<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function province() {
        return $this->belongsTo('App\Province');
    }

    public function wards() {
        return $this->hasMany('App\Ward');
    }
}
