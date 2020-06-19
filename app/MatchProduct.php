<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatchProduct extends Model
{
    //
    protected $table = 'match_product';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
    protected $hidden = ['created_at','updated_at'];

    protected $appends = [];

    protected $fillable = [
        'id','product_id','match_product_id','status','status_cofirm_swap','created_at','updated_at'
    ];
    // status = 1 then not seen match
    // status = 0 then seen

    // status_confirm_swap = 0 then not confirm
    // status_confirm_swap = 1 then confirm
}
