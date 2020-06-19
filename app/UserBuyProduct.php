<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBuyProduct extends Model
{
    //
    protected $table = 'user_buy_product';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
    protected $hidden = ['created_at','updated_at'];

    protected $appends = [];
    // status = 0 then pending
    // status = 1 then accept
    public function user(){
        return $this->belongsTo('App\User','user_id');
     }
}
