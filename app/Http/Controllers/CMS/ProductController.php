<?php

namespace App\Http\Controllers\CMS;

use App\Brand;
use App\Category;
use App\GalleryImageProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Size;
use App\User;
use Braintree\Collection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::get();
        // dump($product);
        $list_id = array();
    
        
        foreach($product as $key =>$value){
            $list_id[$key]["id"] = $value->id;  
           $list_id[$key]["descriptions"] = $value->descriptions;
           $list_id[$key]["brand"] = $value->brand['name'];
           $list_id[$key]["pur_price"] = $value->pur_price;
           $list_id[$key]["title"] = $value->tilte;
           $list_id[$key]["size"] = $value->size['name'];
           $list_id[$key]["user"]= $value->user['name'];
           $list_id[$key]["category"] = $value->category['name'];
           $list_id[$key]["address"] = $value->descriptions;
           $list_id[$key]["create"] = $value->created_at;
           $image = $value->galleryImage->where('product_id',$value->id)->first();
           
           if($image){
             $list_id[$key]["image"]= $image->path;
            }else { $list_id[$key]["image"]= null;
            }  
        }
         $pani = Product::where('active_status',1)->paginate(10);
        
        // dd($list_id);
        return view('cms.pages.product.index',[
            'product'=>$list_id,
            'paginate'=>$pani
            ]);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        // dd($product);
           $list_id = array();
           $list_id["id"] = $product->id;  
           $list_id["descriptions"] = $product->descriptions;
           $list_id["brand"] = $product->brand['name'];
           $list_id["price"] = $product->pur_price;
           $list_id["title"] = $product->tilte;
           $list_id["size"] = $product->size['name'];
           $list_id["user"]= $product->user['name'];
           $list_id["category"] = $product->category['name'];
           $list_id["address"] = $product->address;
           $list_id["create"] = $product->created_at;
           $image = $product->galleryImage->where('product_id',$product->id)->first();
           
           if($image){
             $list_id["image"]= $image->path;
            }else { $list_id["image"]= null;
            }  
            // dd($list_id);
        return view('cms.pages.product.detail', [
            'product'=>$list_id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $list_category = Category::get();
        $list_size= Size::get();
        $list_brand = Brand::get();

        $price = $product->pur_price;
        
        list($money ,$dola) = explode('£', $price);
        
        
        return view('cms.pages.product.edit', [
            'product'=>$product,
            'categories'=>$list_category,
            'sizes'=>$list_size,
            'brands'=> $list_brand,
            'price'=>$money
        ]);
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
