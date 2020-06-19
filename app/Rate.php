<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    //
    protected $table = 'rates';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
    protected $hidden = ['created_at','updated_at'];
    
    protected $fillable = ['product_id','user_id','report','star','type','created_at','updated_at'];

    protected $appends = [];

    public function product(){
       return $this->belongsTo('App\Product','product_id');
    }
    // $type = ['buy', 'sell', 'swap', 'app'];
    
    public function user(){
        return $this->belongsTo('App\User','user_id');
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
