<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\User;
use App\UserLikeProduct;
use App\ColorProduct;
use App\Color;
use App\MatchProduct;
use App\SwapProduct;
use App\UserBuyProduct;
use App\GalleryImageProduct;
use Illuminate\Support\Facades\Schema;
use App\Traits\Helper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendNotifyMessage;

class MatchProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm_swap_product(Request $request)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'match_product_id' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        MatchProduct::where('product_id', $request->product_id)
            ->where('match_product_id', $request->match_product_id)
            ->update(['status_cofirm_swap' => 1]);
        $check = MatchProduct::where('match_product_id', $request->product_id)
            ->where('product_id', $request->match_product_id)
            ->where('status_cofirm_swap', 1)->exists();
        $product1 = Product::find($request->product_id);
        $product2 = Product::find($request->match_product_id);
        if ($check) {
            $product1->old_user_id = $product1->user_id;
            $product2->old_user_id = $product2->user_id;
            $user_id2 = $product2->user->id;
            $user_id1 = $product1->user->id;
            $product1->user_id = $user_id2;
            $product2->user_id = $user_id1;
            $product1->swap_status = 1;
            $product2->swap_status = 1;
            $product1->active_status = 0;
            $product2->active_status = 0;
            if ($product1->old_user_id == $product1->user_id) {
                $product1->old_user_id = null;
            }
            if ($product2->old_user_id == $product2->user_id) {
                $product2->old_user_id = null;
            }
            $product1->save();
            $product2->save();

            $id_small = (int) $request->product_id > (int) $request->match_product_id
                ? $request->match_product_id : $request->product_id;
            $id_big =   (int) $request->product_id < (int) $request->match_product_id
                ? $request->match_product_id : $request->product_id;
            $check_swap_product = SwapProduct::where('product_id_small', $id_small)->where('product_id_big', $id_big)->exists();
            if (!$check_swap_product) {
                $data = ['product_id_small' => $id_small, 'product_id_big' => $id_big];
                $SwapProduct = new SwapProduct();
                $SwapProduct = Helper::saveData($SwapProduct, $data);
                $SwapProduct->save();
            }
            MatchProduct::whereIn('product_id', [$id_small, $id_big])->delete();
            MatchProduct::whereIn('match_product_id', [$id_small, $id_big])->delete();
            UserLikeProduct::whereIn('product_id', [$id_small, $id_big])->delete();
            UserLikeProduct::whereIn('of_product_id', [$id_small, $id_big])->delete();
            // $product_update = Product::whereIn('id',[$request->product_id,$request->match_product_id])->update(['swap_status'=>1]);
            $more = [
                'data' => [
                    'product1' => $product1->id,
                    'product2' => $product2->id,
                    'content' => 'swapped product'
                ]
            ];
            $list_user = [
                $user_id2,
                $user_id1
            ];
            $content = $product1->title . " swapped with " . $product2->title;
            $this->notifi($more, $content, $list_user);
            return Helper::sendSuccess([$id_small, $id_big]);
            // return Helper::sendResponse(true, 'Success',200,'Swap success.');
        }
        $content = $user->name . " want to swap " . $product1->title . " with your dress - " . $product2->title;
        $more = [
            'content' => 'confirm swap product',
            'user_id' => $user->id,
            'product1' => $product1->id,
            'product2' => $product2->id
        ];
        $this->notifi($more, $content, [$product2->user_id]);
        return Helper::sendResponse(true, 'Success', 200, 'Cofirm success, please wait for them to confirm.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function Like(Request $request, $id)
    // {
    //     //
    //     $user = $request->user();
    //     if($user === null) return Helper::sendResponse(false, 'Not found',404,'User not found');

    //     $check_product = Product::find($id);
    //     if(!$check_product) return Helper::sendResponse(false, 'Not found',404,'Product not found');

    //     $check = UserLikeProduct::where('product_id',$id)->where('user_id',$user->id);

    //     $is_match = ['match'=>false,'product_match'=>[]];

    //     if(!($check->exists())){
    //         $data = ['user_id'=>$user->id,'product_id'=>$id,'of_user_id'=>$check_product->user->id,'status'=>1];
    //         $UserLikeProduct = new UserLikeProduct();
    //         $UserLikeProduct = Helper::saveData($UserLikeProduct,$data);
    //         $UserLikeProduct->save();

    //     }else{
    //         $check->update(['status' => 1]);
    //     }

    //     $check_match = UserLikeProduct::where('user_id',$check_product->user->id)
    //                                     ->where('of_user_id',$user->id)
    //                                     ->whereIn('status',[1,2])->get();
    //     $data_match = [];
    //     foreach ($check_match as $key => $value) {
    //         $data_match[] = ['product_id' => $id, 'match_product_id' => $value->product_id];    
    //         $data_match[] = ['product_id' => $value->product_id, 'match_product_id' => $id];    
    //     }
    //     if(count($data_match)){
    //         $check_match_product = MatchProduct::where('product_id',$data_match[0]['product_id'])->where('match_product_id',$data_match[0]['match_product_id'])->exists();
    //         if(!$check_match_product)
    //             MatchProduct::insert($data_match);
    //         $is_match['match'] = true;
    //         $list_product_match = $check_match->map->product_id->all();
    //         $product_match = Product::select(['id','title','user_id'])->whereIn('id',$list_product_match)->get();
    //         $is_match['product_match'] = $product_match;
    //         $filter = [];
    //         $filter['included_segments'] = [];
    //         $filter['filters'] = [['key'=>'userId','value'=>$user->id,'relation'=>'=','operator'=>'AND']];
    //         $content = "Someone match you.";
    //         SendNotifyMessage::dispatch($filter,$content);
    //         $filter['filters'] = [['key'=>'userId','value'=>$check_product->user->id,'relation'=>'=','operator'=>'AND']];
    //         SendNotifyMessage::dispatch($filter,$content);
    //     }

    //     return Helper::sendSuccess($is_match);
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function seen(Request $request)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'match_product_id' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        MatchProduct::where('product_id', $request->product_id)
            ->where('match_product_id', $request->match_product_id)
            ->update(['status' => 0]);
        return Helper::sendResponse(true, 'Success', 200, 'Seen success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dislike(Request $request, $id)
    {

        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $validator = Validator::make($request->all(), [
            'list_my_product_id' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $list_my_product_id = json_decode($request->list_my_product_id);

        if (!$list_my_product_id)
            return Helper::sendResponse(false, 'Validator error', 400, 'Json list my product fail.');

        $my_product_id = $user->product->map->id->all();

        $check_match = MatchProduct::whereIn('product_id', $my_product_id)->where('match_product_id', $id)->get();
        $check_match_product_id = $check_match->map->product_id->all();

        $res_product_id = [];
        foreach ($list_my_product_id as $value) {
            if (!in_array($value, $check_match_product_id)) {
                $res_product_id[] = $value;
            }
        }
        UserLikeProduct::whereIn('product_id', $res_product_id)->where('of_product_id', $id)->delete();

        return Helper::sendResponse(true, 'Success', 200, 'Dislike success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request, $id)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');

        $validator = Validator::make($request->all(), [
            'list_my_product_id' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $list_my_product_id = json_decode($request->list_my_product_id);

        if (!$list_my_product_id)
            return Helper::sendResponse(false, 'Validator error', 400, 'Json list my product fail.');

        $check_product = Product::find($id);
        if (!$check_product) return Helper::sendResponse(false, 'Not found', 404, 'Product not found');
        $My_product_match = MatchProduct::whereIn('product_id', $list_my_product_id)->where('match_product_id', $id)->get();
        $My_product_match_id = $My_product_match->map->product_id->all();

        $My_product_like = UserLikeProduct::where('user_id', $user->id)->where('of_product_id', $id)->get();
        $My_product_like_id = $My_product_like->map->product_id->all();
        $res_product_match = [];
        foreach ($list_my_product_id as $key => $value) {
            if (!in_array($value, $My_product_like_id)) {
                $like = new UserLikeProduct;
                $like->user_id = $user->id;
                $like->product_id = $value;
                $like->of_product_id = $id;
                $like->of_user_id = $check_product->user_id;
                $like->status = 1;
                $like->save();

                $check_match = UserLikeProduct::where('product_id', $id)
                    ->where('of_product_id', $value)
                    ->whereIn('status', [1, 2])
                    ->exists();
                if ($check_match) {
                    $res_product_match[] = $value;

                    $data_match[] = ['product_id' => $id, 'match_product_id' => $value];
                    $data_match[] = ['product_id' => $value, 'match_product_id' => $id];
                    MatchProduct::insert($data_match);
                    $content = "Someone match you.";
                    $more = [
                        'data' => ['user_id' => $user->id, 'product_id' => $id, 'content' => 'user like product']
                    ];
                    $this->notifi($more, $content, [$check_product->user->id, $user->id]);
                }
            }
        }
        $res_product = Product::whereIn('id', $res_product_match)->get();
        return Helper::sendSuccess($res_product);
    }

    public function unmatch(Request $request)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');

        $validator = Validator::make($request->all(), [
            'my_product_id' => 'required',
            'product_id' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }

        $check_product = Product::find($request->product_id);
        if (!$check_product) return Helper::sendResponse(false, 'Not found', 404, 'Product not found');

        $check_my_product = Product::find($request->product_id);
        if (!$check_my_product) return Helper::sendResponse(false, 'Not found', 404, 'My product not found');


        UserLikeProduct::where('product_id', $request->my_product_id)->where('of_product_id', $request->product_id)->delete();
        MatchProduct::where('product_id', $request->my_product_id)->where('match_product_id', $request->product_id)->delete();
        MatchProduct::where('product_id', $request->product_id)->where('match_product_id', $request->my_product_id)->delete();
        return Helper::sendResponse(true, 'Success', 200, 'Unmatch success');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check_seen_match(Request $request)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');

        $my_product = $user->product;
        $list_product_id = [];
        foreach ($my_product as $key => $value) {
            $list_product_id[] = $value->id;
        }
        $check = MatchProduct::whereIn('product_id', $list_product_id)
            ->where('status', 1)->exists();
        return Helper::sendSuccess(['match' => $check]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function Skip(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'list_product_id' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        // $user = User::find(1);
        $list_product_id = json_decode($request->list_product_id);
        if ($list_product_id == null) {
            return Helper::sendResponse(false, 'Validator error', 400, 'List product id fail');
        }

        $check_product = Product::whereIn('id', $list_product_id)->get();
        foreach ($check_product as $value) {
            $check = UserLikeProduct::where('of_product_id', $value)->where('user_id', $user->id)->exists();
            if ($check) {
                $update = UserLikeProduct::where('of_product_id', $value->id)->where('user_id', $user->id)->update(['status' => 2]);
            } else {
                $data = ['user_id' => $user->id, 'of_product_id' => $value->id, 'of_user_id' => $value->user_id, 'status' => 0];
                $UserLikeProduct = new UserLikeProduct();
                $UserLikeProduct = Helper::saveData($UserLikeProduct, $data);
                $UserLikeProduct->save();
            }
        }
        return Helper::sendResponse(true, 'Success', 200, 'Success success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
