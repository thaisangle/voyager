<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryImageProduct extends Model
{
    //
    protected $table = 'gallery_image_product';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
    protected $hidden = ['created_at', 'updated_at', 'product_id', 'path'];

    protected $appends = ['path_image', 'path_image_thumbnail'];

    public $image = 'upload/product/';

    public function getPathImageAttribute()
    {
        $id = $this->attributes['product_id'];
        $serve = env('APP_STORAGE', 'https://storage.googleapis.com/decent-core-265909.appspot.com/');
        $image = $this->attributes['path'];
        if ($image) {
            if (Storage::disk('public')->exists($this->image . $id . '/' . $image)) {
                return Storage::disk('public')->url($this->image . $id . '/' . $image);
            }
        }
        return Storage::disk('public')->url($this->image . 'default.png');
    }
    public function getPathImageThumbnailAttribute()
    {
        $id = $this->attributes['product_id'];
        $serve = env('APP_STORAGE', 'https://storage.googleapis.com/decent-core-265909.appspot.com/');
        $image = $this->attributes['path'];
        if ($image) {
            if (Storage::disk('public')->exists($this->image . $id . '/thumbnail/' . $image)) {
                return Storage::disk('public')->url($this->image . $id . '/thumbnail/' . $image);
            }
        }
        return $this->path_image;
    }

    public function ImageMain()
    {
        $product_id = $this->product->id;
        $ImageMain = App\GalleryImageProduct::where('product_id', $product_id)->where('status', 1)->first();
        return $ImageMain;
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
}
