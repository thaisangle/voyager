<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Color extends Model
{
    //
    protected $table = 'colors';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['path_image'];

    public $image = 'upload/color/';

    public function product()
    {
        return $this->belongsToMany(Product::class, 'color_product', 'color_id', 'product_id');
    }

    public function getPathImageAttribute()
    {
        $serve = env('APP_STORAGE', 'https://storage.googleapis.com/decent-core-265909.appspot.com/');
        $name = $this->attributes['name'];
        if ($this->status == 1) {
            if (Storage::disk('public')->exists($this->image . $name)) {
                return Storage::disk('public')->url($this->image . $name);
            }
        }
        return null;
    }

    public function child()
    {
        return $this->hasMany('App\Color', 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Color', 'parent_id');
    }

    public function color_product()
    {
        return $this->hasMany('App\ColorProduct', 'color_id');
    }
}
