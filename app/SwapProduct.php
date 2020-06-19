<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SwapProduct extends Model
{
    //
    protected $table = 'swap_product';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
    protected $hidden = ['created_at','updated_at'];

    protected $appends = [];
    
}
