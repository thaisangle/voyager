<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    //
    protected $table = 'categories';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
    protected $hidden = ['icon_default', 'icon_select', 'icon_none', 'icon_bg_white', 'created_at', 'updated_at'];

    protected $appends = ['path_icon_default', 'path_icon_select', 'path_icon_none', 'path_icon_bg_white'];

    public $image = 'upload/category/';

    public function getPathIconDefaultAttribute()
    {
        $serve = env('APP_STORAGE', 'https://storage.googleapis.com/decent-core-265909.appspot.com/');
        $image = $this->attributes['icon_default'];
        $id = $this->attributes['id'];
        if ($image) {
            if (Storage::disk('public')->exists($this->image . $id . '/' . $image)) {
                return Storage::disk('public')->url($this->image . $id . '/' . $image);
            }
        }
        return Storage::url($this->image . 'default.png');
    }

    public function getPathIconSelectAttribute()
    {
        $serve = env('APP_STORAGE', 'https://storage.googleapis.com/decent-core-265909.appspot.com/');
        $image = $this->attributes['icon_select'];
        $id = $this->attributes['id'];
        if ($image) {
            if (Storage::disk('public')->exists($this->image . $id . '/' . $image)) {
                return Storage::disk('public')->url($this->image . $id . '/' . $image);
            }
        }
        return Storage::disk('public')->url($this->image . 'default.png');
    }

    public function getPathIconNoneAttribute()
    {
        $serve = env('APP_STORAGE', 'https://storage.googleapis.com/decent-core-265909.appspot.com/');
        $image = $this->attributes['icon_none'];
        $id = $this->attributes['id'];
        if ($image) {
            if (Storage::disk('public')->exists($this->image . $id . '/' . $image)) {
                return Storage::disk('public')->url($this->image . $id . '/' . $image);
            }
        }
        return Storage::disk('public')->url($this->image . 'default.png');
    }

    public function getPathIconBgWhiteAttribute()
    {
        $serve = env('APP_STORAGE', 'https://storage.googleapis.com/decent-core-265909.appspot.com/');
        $image = $this->attributes['icon_bg_white'];
        $id = $this->attributes['id'];
        if ($image) {
            if (Storage::disk('public')->exists($this->image . $id . '/' . $image)) {
                return Storage::disk('public')->url($this->image . $id . '/' . $image);
            }
        }
        return Storage::disk('public')->url($this->image . 'default.png');
    }

    public function product()
    {
        return $this->hasMany('App\Product', 'category_id');
    }
}
