<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Helper;
use Illuminate\Support\Facades\DB;
use App\Rate;
use App\User;
use App\Product;
use Illuminate\Support\Facades\Validator;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = $request->user();
        if($user === null) return Helper::sendResponse(false, 'Not found',404,'User not found');
        $my_product = $user->product()->select('products.id')->get();
        $list_id_my_product = [];
        foreach ($my_product as $key => $value) {
            $list_id_my_product[] = $value->id;
        }
    
        $rate_type_swap = Rate::join('users','users.id','=','rates.user_id');
        if($request->type) 
            $rate_type_swap= $rate_type_swap->where('type',$request->type);
        $rate_type_swap = $rate_type_swap->whereIn('product_id',$list_id_my_product)
                                ->select(['rates.*','users.id','users.name','users.avatar'])->get();

        return Helper::sendSuccess($rate_type_swap);
    }

    public function rate_of_product(Request $request)
    {
        //
        $user = $request->user();
        if($user === null) return Helper::sendResponse(false, 'Not found',404,'User not found');
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        
    
        $rate_type_swap = Rate::join('users','users.id','=','rates.user_id')
                                ->where('product_id',$request->product_id)
                                ->where('type','<>','sell')
                                ->select(['rates.*','users.id','users.name','users.avatar'])->get();

        return Helper::sendSuccess($rate_type_swap);
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
    public function store(Request $request)
    {
        //
        $user = $request->user();
        if($user === null) return Helper::sendResponse(false, 'Not found',404,'User not found');
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'star' => 'required',
            'type' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        if($request->type == 'sell' && !$request->user_buy_id)
            return Helper::sendResponse(false, 'Validator error', 400, 'user_buy_id required');
        $data = $request->all();
        $data['user_id'] = $user->id;
        if($request->type != 'sell') unset($data['user_buy_id']);
        $rate = new Rate();
        $rate = Helper::saveData($rate,$data);
        $rate->save();
        $user_of_product = Product::find($request->product_id)->user;
        $Product = $user_of_product->product->toArray();
        $list_product_id = [];
        foreach($Product as $key => $value){
            $list_product_id[] = $value['id'];
        }
        $Rate1 = Rate::whereIn('product_id',$list_product_id)->where('type','<>','sell')->get()->toArray();
        $Rate2 = Rate::where('user_buy_id',$user_of_product->id)->where('type','sell')->get()->toArray();
        $RateMerge = array_merge($Rate1,$Rate2);
        $total = 0;
        foreach($RateMerge as $value){
            $total += (int)$value['star'];
        }
        if($RateMerge !== null && count($RateMerge)){
            $User = User::find($user_of_product->id);
            $User->star = $total/(count($RateMerge));
            $User->save();
        }
        return Helper::sendResponse(true, 'success',200,'Rate success.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rate_app(Request $request)
    {
        $user = $request->user();
        if($user === null) return Helper::sendResponse(false, 'Not found',404,'User not found');
        $validator = Validator::make($request->all(), [
            'star' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $data = $request->all();
        $data['user_id'] = $user->id;
        $rate = new Rate();
        $rate = Helper::saveData($rate,$data);
        $rate->save();
        return Helper::sendResponse(true, 'success',200,'Rate success.');

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
    public function update(Request $request, $id)
    {
        //
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
