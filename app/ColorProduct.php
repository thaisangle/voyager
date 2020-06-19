<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColorProduct extends Model
{
    //
    protected $table = 'color_product';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
    protected $hidden = ['created_at','updated_at'];

    protected $appends = [];


    // public function getPathImageAttribute(){
    //     $id = $this->attributes['product_id'];
    //     $serve = env('APP_URL','http://dreesu.stdiohue.com');
    //     $image = $this->attributes['path'];
    //     if($image)
	   //      return $serve.$this->image.$id.'/'.$image;
	   //  return $serve.$this->image.'default.png';
    // }

    // public function ImageMain(){
    //     $product_id = $this->product->id;
    //     $ImageMain = App\GalleryImageProduct::where('product_id',$product_id)->where('status',1)->first();
    //     return $ImageMain;
    // }

    // public function product(){
    //    return $this->belongsTo('App\Product','product_id');
    // }

}
