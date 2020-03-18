<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    protected $fillable = [
        'product',
        'receiver',
        'address',
        'repository_id',
        'ward_id',
        'sender_id',
        'money_taking'
    ];

    public function sender() {
        return $this->$this->belongsTo('App\User');
    }

    public function repository() {
        return $this->belongsTo('App\Repository');
    }

    public function ward() {
        return $this->belongsTo('App\Ward');
    }
}
