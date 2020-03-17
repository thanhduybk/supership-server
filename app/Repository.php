<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $table = 'repositories';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'phone',
        'contact',
        'address',
        'ward_id',
        'owner_id',
        'main_repo'
    ];

    public function owner() {
        return $this->belongsTo('App\User');
    }

    public function ward() {
        return $this->belongsTo('App\Ward');
    }
}
