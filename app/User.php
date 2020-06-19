<?php

namespace App;

use App\Card;
use App\Product;
use Stripe\Token;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Customer;
use App\ChargeStripe;
use App\Traits\Helper;
use App\Mail\VerifyMail;
use App\UserLikeProduct;
use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use App\Notifications\VerifyApiEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'birth_day', 'social_token', 'social_id', 'avatar', 'type_social', 'star'
    ];

    protected $appends = ['setting'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'type_role'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'timestamp',
        'birth_day' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function sendApiEmailVerificationNotification()
    {
        $data['verify_code'] = $this->verify_code;
        Mail::to($this->email)->send(new VerifyMail($data));
    }

    public function isAdmin()
    {
        return $this->type_role === 'admin';
    }
    public function getStarAttribute()
    {

        return round($this->attributes['star'], 2) . "";
    }
    public function getMatchProductAttribute()
    {
        $LikeProduct = [];

        $Product = $this->product;
        // dd($Product);
        $MatchProduct = [];
        $list_id_match_product = [];
        foreach ($Product as $key => $value) {
            $list_match_product = MatchProduct::where('product_id', $value->id)->get();
            foreach ($list_match_product as $key => $value) {
                $list_id_match_product[] = $value->match_product_id;
            }
        }
        $MatchProduct = Product::whereIn('id', $list_id_match_product);
        return $MatchProduct;
    }

    public function getSellProductAttribute()
    {

        $UserBuyProduct = UserBuyProduct::where('of_user_id', $this->id)->get();
        $data = [];
        foreach ($UserBuyProduct as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'user' => User::find($value->user_id)->toArray(),
                'product' => Product::find($value->product_id)->toArray(),
                'status' => $value->status
            ];
        }
        return $data;
    }

    public function getBuyProductAttribute()
    {

        $UserBuyProduct = UserBuyProduct::where('user_id', $this->id)->get();
        $data = [];
        foreach ($UserBuyProduct as $key => $value) {
            $Product = Product::find($value->product_id);
            if ($Product) {
                $data[] = [
                    'id' => $value->id,
                    'product' => $Product->toArray(),
                    'status' => $value->status
                ];
            }
        }
        return $data;
    }

    public function getLikeProductAttribute()
    {
        $LikeProduct = [];
        $Product = UserLikeProduct::where('user_id', $this->id)->where('status', 1)->get();
        foreach ($Product as $key => $value) {
            $LikeProduct[] = Product::find($value->product_id);
        }
        return $LikeProduct;
    }

    public function getNopeProductAttribute()
    {
        $NopeProduct = [];
        $Product = UserLikeProduct::where('user_id', $this->id)->where('status', 0)->get();
        foreach ($Product as $key => $value) {
            $NopeProduct[] = Product::find($value->product_id);
        }
        return $NopeProduct;
    }

    public function getNotSeeProductAttribute()
    {
        $NotSeeProduct = [];
        $SeeProduct = [];
        $Product = UserLikeProduct::select('of_product_id')
            ->where('user_id', $this->id)
            ->Where(function ($query) {
                $query->where('status', 0)
                    ->orWhere('status', 2);
            })->get();
        $my_product = $this->product;
        foreach ($Product as $key => $value) {
            $SeeProduct[] = $value->of_product_id;
        }
        foreach ($my_product as $key => $value) {
            $SeeProduct[] = $value->id;
        }
        $NotSeeProduct = Product::whereNotIn('id', $SeeProduct);
        return $NotSeeProduct;
    }

    public function getProductFilterAttribute()
    {
        $request = $this->setting;
        $Product = $this->not_see_product->where('active_status', 1);

        if ($request->category) {
            $category = $request->category;
            if ($category !== null && count($category)) {
                $category_id = [];
                foreach ($category as $key => $value) {
                    $category_id[] = $value->id;
                }
                $Product = $Product->whereIn('category_id', $category_id);
            }
        }

        if ($request->color) {
            $color = $request->color;
            if ($color !== null && count($color)) {
                $color_id = [];
                foreach ($color as $key => $value) {
                    $color_id[] = $value->id;
                }
                $Color = Color::whereIn('id', $color_id)
                    ->with('color_product')
                    ->get()->toArray();
                $arr_product = [];
                foreach ($Color as $key => $value) {
                    // $row = [];
                    foreach ($value['color_product'] as $key => $value2) {
                        // $row[] = $value2['product_id'];
                        $arr_product[] = $value2['product_id'];
                    }
                    // $arr_product[] =$row;
                }

                // $arr_product_id = $arr_product[0];
                // foreach ($arr_product as $key => $value) {
                //     $arr_product_id = array_intersect($arr_product_id, $value);
                // }
                // $Product = $Product->whereIn('id',$arr_product_id);
                $Product = $Product->whereIn('id', $arr_product);
            }
        }

        if ($request->brand) {
            $brand = $request->brand;
            if ($brand !== null && count($brand)) {
                $Product_get_id = Product::when($brand, function ($query, $brand) {
                    foreach ($brand as $key => $value) {
                        if ($key)
                            $query = $query->orWhere('brand', 'LIKE', "%" . $value->value . "%");
                        else
                            $query = $query->where('brand', 'LIKE', "%" . $value->value . "%");
                    }
                    return $query;
                })->get();
                $arr_product_id = [];
                foreach ($Product_get_id as $key => $value) {
                    $arr_product_id[] = $value->id;
                }
                $Product = $Product->whereIn('id', $arr_product_id);
            }
        }
        // check size same
        $size = Size::find($request->size_id);
        $size_feild = Size::where('ted_size', $size->ted_size)->get();
        $size_feild = $size_feild->map->id->all();
        // end check size
        $Product = $Product->whereIn('size_id', $size_feild);


        $Product = $Product->select('*');
        if ($request->lat != null && $request->lng != null && $request->distance != null) {
            // query distane
            $haversine = "(6371 * acos(cos(radians(" . $request->lat . ")) 
            * cos(radians(`lat`)) 
            * cos(radians(`lng`) 
            - radians(" . $request->lng . ")) 
            + sin(radians(" . $request->lat . ")) 
            * sin(radians(`lat`))))";
            $Product = $Product->selectRaw("{$haversine} AS distance")
                ->whereRaw("{$haversine} < ?", [$request->distance]);
        }

        return $Product;
    }

    public function getSettingAttribute()
    {
        $setting = json_decode($this->setting_json);
        $setting->category = json_decode($setting->category);

        foreach ($setting->category as $key => $value) {
            $setting->category[$key]->id = (int) $value->id;
        }
        $setting->color = json_decode($setting->color);
        foreach ($setting->color as $key => $value) {
            $setting->color[$key]->id = (int) $value->id;
        }
        $setting->brand = json_decode($setting->brand);
        foreach ($setting->brand as $key => $value) {
            $setting->brand[$key]->name = $value->value;
        }
        $setting->distance = json_decode($setting->distance);
        $setting->lat = json_decode($setting->lat);
        $setting->lng = json_decode($setting->lng);
        $setting->swap_today = json_decode($setting->swap_today);
        $setting->size_id = json_decode($setting->size_id);
        return $setting;
    }

    public function BuyProduct($Product)
    {
        // Stripe::setApiKey(env('STRIPE_SECRET'));
        // $customer_id = $this->stripe_id;
        // if($customer_id){
        //     $description = 'Buy Product with id= '.$Product->id;
        //     $price = (float)$Product->pur_price;
        //     $charge = Charge::create([
        //         'amount' => $price*100,
        //         'currency' => 'usd',
        //         'customer' => $customer_id, // Previously stored, then retrieved
        //         'description' => $description
        //     ]);
        //     $data = ['user_id'=>$this->id,'charge_stripe_id'=>$charge->id,'price'=>$price,'description'=>$description,'status'=>1];
        //     $Charge = new ChargeStripe();
        //     $Charge = Helper::saveData($Charge,$data);
        //     $Charge->save();
        //     return true;
        // }

        return true;
    }

    public function product()
    {
        return $this->hasMany('App\Product', 'user_id');
    }

    public function check_premium()
    {
        return $this->vip == 'premium';
    }


    public $col_show = [
        'id' => [
            'name' => 'ID',
            'sort' => true,
            'search' => 'text'
        ],
        'name' => [
            'name' => 'Name',
            'sort' => true,
            'search' => 'text'
        ],
        'birth_day' => [
            'name' => 'Birth day',
            'sort' => true,
            // 'search' => 'text'
        ],
        'email' => [
            'name' => 'Email',
            'sort' => true,
            'search' => 'text'
        ],
        'type_social' => [
            'name' => 'Type social',
            'sort' => true,
            'search' => 'select',
            'value' => ['normal' => 'normal', 'gmail' => 'gmail', 'facebook' => 'facebook']
        ],
        'created_at' => [
            'name' => 'Created at',
            'sort' => true,
        ]
    ];

    //is_trial = 0 then chua dung
    // is_trial = 1 then dang dung
    // is_trial = 2 then dung roi
}
