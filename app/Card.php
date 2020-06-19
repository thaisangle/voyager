<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    //
    protected $table = 'cards';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
    protected $hidden = ['created_at','updated_at'];

    protected $appends = [];

    public function parent(){
       return $this->belongsTo('App\User','user_id');
    }


}
