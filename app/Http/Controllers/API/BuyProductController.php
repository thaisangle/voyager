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

class BuyProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list_buy_product(Request $request)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $data = $user->buy_product;
        return Helper::sendSuccess($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registration_buy(Request $request)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $validator = Validator::make($request->all(), [
            'product_id' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $Product = Product::find($request->product_id);
        if ($Product->sell_now_status) {
            return Helper::sendResponse(false, 'Fail', 400, 'Sorry! This dress has been sold.');
        }
        if ($Product->sell_now_status) {
            return Helper::sendResponse(false, 'Fail', 400, 'Sorry! This dress has been swaped.');
        }
        $check = UserBuyProduct::where('user_id', $user->id)->where('product_id', $request->product_id);
        if ($check->exists()) {
            return Helper::sendResponse(false, 'Fail', 400, 'You have already registered this product.');
        }

        $of_user_id = $Product->user->id;
        $data = $request->all();
        $data['of_user_id'] = $of_user_id;
        $data['user_id'] = $user->id;
        $UserBuyProduct = new UserBuyProduct();
        $UserBuyProduct = Helper::saveData($UserBuyProduct, $data);
        $UserBuyProduct->save();
        $content = "Someone wants to buy your dress " . $Product->name . ".";
        $more = [
            'data' => ['content' => 'registration buy', 'product' => $Product->id]
        ];
        $this->notifi($more, $content, [$of_user_id]);
        return Helper::sendResponse(true, 'Success', 200, 'Send Success, please wait for them to Accept.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sell(Request $request)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'product_id' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $Product = Product::find($request->product_id);
        $of_user_id = $Product->user->id;
        $user_buy = User::find($request->user_id)->BuyProduct($Product);
        if ($user_buy) {
            UserBuyProduct::where('user_id', $request->user_id)->where('product_id', $request->product_id)->update(['status' => 1]);
            $product = Product::find($request->product_id);
            $product->old_user_id = $product->user_id;
            $product->user_id = $request->user_id;
            $product->sell_now_status = 1;
            $product->active_status = 0;
            $product->save();
            UserBuyProduct::where('product_id', $request->product_id)->delete();
            MatchProduct::where('product_id',  $product->id)->delete();
            MatchProduct::where('match_product_id',  $product->id)->delete();
            UserLikeProduct::whereIn('product_id', $product->id)->delete();
            // $filter = [];
            // $filter['included_segments'] = [];
            // $filter['filters'] = [['key'=>'userId','value'=>$of_user_id,'relation'=>'=','operator'=>"AND"]];
            // $content = "You bought product ".$Product->name." success.";

            // SendNotifyMessage::dispatch($filter,$content);
            return Helper::sendResponse(true, 'Success', 200, 'Sell success.');
        } else {
            // $filter = [];
            // $filter['included_segments'] = [];
            // $filter['filters'] = [['key'=>'userId','value'=>$of_user_id,'relation'=>'=','operator'=>"AND"]];
            // $content = "You amount was not enough to purchase the product".$Product->name;
            // SendNotifyMessage::dispatch($filter,$content);
            return Helper::sendResponse(false, 'Fail', 502, 'An error occurred during the transaction or their haven\'t card credit.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search_match_product_and_sell_product(Request $request)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $list_product = [];
        if ($request->text_search) {
            $list_product = Product::where('title', 'LIKE', '%' . $request->text_search . '%')->get();
        }
        $list_product_id = [];
        foreach ($list_product as $key => $value) {
            $list_product_id[] = $value->id;
        }
        $data['match'] = $user->match_product;
        if ($list_product_id !== null && count($list_product_id)) {
            $data['match'] = $data['match']->whereIn('id', $list_product_id);
        }
        $data['match'] = $data['match']->get();
        $data['buy'] = $user->sell_product;
        return Helper::sendSuccess($data);
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
        UserBuyProduct::where('id', $id)->delete($id);
        return Helper::sendResponse(true, 'Success', 200, 'Success');
    }
}
