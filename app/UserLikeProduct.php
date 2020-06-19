<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLikeProduct extends Model
{
    //
    protected $table = 'user_like_product';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
    protected $hidden = [];

    protected $appends = [];

    public $fillable = [];

    private $name_status = [
    	0	=> 'skip',
    	1   => 'like',
    	2   => 'like_and_skip'
    ];

}
