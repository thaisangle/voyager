<?php

namespace App\Http\Controllers\API;

use App\Brand;
use App\Traits\Helper;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Product;
use Intervention\Image\Facades\Image;
use League\Flysystem\Plugin\ListWith;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $data = Brand::select('*');
        // pagitation
        if ($request->page_size !=null && $request->current_page != null) {
            $limit = (int)$request->page_size == -1 ?$data->count():((int)$request->page_size?$request->page_size : 10);
            $page = (int)$request->current_page ? $request->current_page : 1;
            $data = $data->paginate($limit, ['*'], 'page', $page);
            return Helper::sendResponsePaginating(true, 'success', $data, 200);
        } 
        $data = $data->get();
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
        //
        $Brand = Brand::find($id);
        if(!$Brand)
            return Helper::sendResponse(false, 'Not found',404,'Brand with id ='.$id.' not found');
        return Helper::sendSuccess($Brand);

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
    public function cronjob(){
        // $dir = 'public/upload/product';

        // $files = [];
        // $storage = Storage::disk('')->addPlugin(new ListWith());
        // $storageItems = $storage->listWith(['mimetype'], $dir);
        // foreach($storageItems as $key => $value){
        //     if($value['type'] == 'dir'){
        //         $storageImg = Storage::disk('')->addPlugin(new ListWith());
        //         $storageImage = $storageImg->listWith(['mimetype'], $value['path']);
        //         foreach($storageImage as $image){
        //             if($image['type'] != 'dir'){
        //                 if(Storage::exists($image['path']) && !Storage::exists($image['dirname'].'/thumbnail/'.$image['basename'])){
        //                     Storage::copy($image['path'], $image['dirname'].'/thumbnail/'.$image['basename']);
                            
        //                 }
        //                 $thumbnailpath = public_path(Storage::url($image['dirname'].'/thumbnail/'.$image['basename']));
        //                 // dd($thumbnailpath);
        //                 $img = Image::make($thumbnailpath)->resize(300, 300, function($constraint) {
        //                     $constraint->aspectRatio();
        //                 });
        //                 $img->save($thumbnailpath);
        //             }
                    
                       
                    
        //         } 
        //     }   
        // }
        $product = Product::get();
        foreach ($product as $key => $value) {
            # code...
            if($value->old_user_id == $value->user_id){
                Product::where('id',$value->id)->update(['old_user_id'=>null]);
            }
        }
        return "x";
    }
}
