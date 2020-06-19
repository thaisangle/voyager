<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    //
    protected $table = 'sizes';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
    protected $hidden = ['created_at','updated_at'];

    protected $appends = [];

    public function child(){
        return $this->hasMany('App\Size','parent_id');
    }

    public function parent(){
       return $this->belongsTo('App\Size','parent_id');
    }
    
    public function product(){
       return $this->hasMany('App\Product','size_id');
    }

    // public $col_show = [
    //     'id' => [
    //         'name' => 'ID',
    //         'sort' => true,
    //         'search' => 'text'
    //     ],
    //     'name' => [
    //         'name' => 'Name',
    //         'sort' => true,
    //         'search' => 'text'
    //     ],
    //     'birth_day' => [
    //         'name' => 'Birth day',
    //         'sort' => true,
    //         // 'search' => 'text'
    //     ],
    //     'email' => [
    //         'name' => 'Email',
    //         'sort' => true,
    //         'search' => 'text'
    //     ],
    //     'type_social' => [
    //         'name' => 'Type social',
    //         'sort' => true,
    //         'search' => 'select',
    //         'value' => [ 'normal'=>'normal','gmail'=>'gmail','facebook'=>'facebook']
    //     ],
    //     'created_at' => [
    //         'name' => 'Created at',
    //         'sort' => true,
    //     ]  
    // ];
}
