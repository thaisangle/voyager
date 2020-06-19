<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargeStripe extends Model
{
    //
    protected $table = 'charges';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
    protected $hidden = ['created_at','updated_at'];
    protected $fillable = [
        'user_id', 'charge_stripe_id', 'price','description','status','reason','created_at','updated_at'
    ];
    protected $appends = [];

    public function user(){
       return $this->hasMany('App\User','user_id');
    }
    //status = 1 buy product success
    //status = 0 refunr
}
