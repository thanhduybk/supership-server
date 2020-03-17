<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public function districts() {
        return $this->morphMany('App\District', 'province');
    }
}
