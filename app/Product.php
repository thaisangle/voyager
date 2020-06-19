<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Color;
use App\User;
use App\Size;
use App\ColorProduct;
use App\MatchProduct;
class Product extends Model
{
    //
    protected $table = 'products';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];
    protected $hidden = ['created_at','updated_at'];

    protected $appends = ['path_main_image','image_list','color_list','info_user'];

    public $fillable = [
        'category_id','user_id','color_id','size_id','pur_price','brand','title','descriptions'
        ,'lat','lng','address','sell_now_status','swap_status','active_status','created_at','updated_at'                
                        ];
                        // 
    public $image = 'upload/product/';
    
    public function getDistanceAttribute(){
        
        return round($this->attributes['distance']);
    }

    public function getColorListAttribute(){
        $list_image = [];
        $id = $this->attributes['id'];
        $serve = env('APP_STORAGE','https://storage.googleapis.com/decent-core-265909.appspot.com/');
        $id_color_of_product = ColorProduct::where('product_id',$id)->get();
        $id_color_array  = [];

        foreach ($id_color_of_product as $key => $value) {
            $id_color_array[] = $value->color_id;
        }
        $List_Color = Color::whereIn('id',$id_color_array)->get();
        return $List_Color;
    }

    public function getImageListAttribute(){
        $list_image = [];
        $id = $this->attributes['id'];
        $serve = env('APP_STORAGE','https://storage.googleapis.com/decent-core-265909.appspot.com/');
        $listImage = $this->galleryImage()->orderBy('status','desc')->get();

        return $listImage;
    }

    public function getPathMainImageAttribute(){
        $list_image = [];
        $id = $this->attributes['id'];
        $serve = env('APP_STORAGE','https://storage.googleapis.com/decent-core-265909.appspot.com/');
        $image_main = $this->galleryImage()->where('status',1)->first();
        if($image_main)
            return $image_main['path_image_thumbnail'];
        return $serve.$this->image.'default.png';
    }
    public function getMatchAttribute(){
        $MatchProduct = MatchProduct::where('product_id',$this->id)->get();

        $Product = [];

        foreach ($MatchProduct as $key => $value) {
            $find_product = Product::find($value->match_product_id);
            $Product[] = ['id' => $value->match_product_id,
                          'status' => $value->status,
                          'status_confirm_swap' => $value->status_cofirm_swap ,
                          'title' => $find_product->title,
                          'name_of_user' => $find_product->user->name,
                          'user_id' => $find_product->user_id,
                          'sell_now_status' => $find_product->sell_now_status,
                          'swap_status' => $find_product->swap_status,
                          'path_main_image' => $find_product->path_main_image];
        }
        return $Product;
    }

    public function getCheckSeenAttribute(){
        $check = MatchProduct::where('product_id',$this->id)->where('status',1)->exists()?1:0;

        return $check;
    }

    public function getUserBuyAttribute(){
        $MatchProduct = UserBuyProduct::with('user')->where('product_id',$this->id)->get();
        $list_user_id = [];
        foreach ($MatchProduct as $key => $value) {
            $list_user_id[] = $value->user_id;
        }
        // $user = User::whereIn('id',$list_user_id)->get();
        $data = [];
        // id_buy_request;
        foreach ($MatchProduct as $key => $value) {
            $data[] = ['id'=>$value->user->id, 'name'=>$value->user->name,'avatar'=>$value->user->avatar,'id_buy_request' => $value->id];
        }
        return $data;
    }
    public function getInfoUserAttribute(){
        $user = User::select('*')->find($this->user_id);
        $user = ['id'=>$user->id,'name'=>$user->name,'star'=>round($user->star,2).'','phone'=>$user->phone,'email'=>$user->email,'avatar'=>$user->avatar];
        return $user;
    }
    public function galleryImage(){
        return $this->hasMany('App\GalleryImageProduct','product_id');
    }

    public function user(){
       return $this->belongsTo('App\User','user_id');
    }
    public function category(){
        return $this->belongsTo('App\Category','category_id');
     }
     public function size(){
        return $this->belongsTo('App\Size','size_id');
     }
     public function brand(){
        return $this->belongsTo('App\Brand','brand_id');
     }

}
