<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Size;
use App\Color;
use App\Traits\Helper;
use App\Category;
use App\GalleryImageProduct;
use App\MatchProduct;
use App\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list_category()
    {
        $list_category = Category::all();
        return Helper::sendSuccess($list_category);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function config_detail()
    {
        $data = ['New', 'Short', 'Day', 'Used', 'Long', 'Night', 'Winter', 'Summer', 'Line', 'Cotton', 'Satin'];
        $add = [];
        foreach ($data as $key => $value) {
            $add[] = ['value' => $value];
        }
        return $data;
    }
    public function list_detail()
    {
        //
        $detail = $this->config_detail();
        return Helper::sendSuccess($detail);
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

    public function cron_config()
    {

        // Storage::deleteDirectory();
        // $directory = "public/upload/product";
        // $directories = Storage::directories($directory);
        // $count = 0;
        // foreach ($directories as $key => $value) {
        //     $tmp = explode("public/upload/product/", $value);
        //     $tmp = $tmp[1];
        //     if (!Product::find($tmp)) {
        //         Storage::deleteDirectory($value);
        //         $count++;
        //     }
        // }
        // GalleryImageProduct::where('id', '>', 0)->delete();
        return Helper::sendResponse(true, 'xxx', 200, 'Sell success.');
    }

    // https://github.com/Superbalist/laravel-google-cloud-storage
    public function test_upload(Request $request)
    {

        $disk = Storage::disk('public');
        $file = $request->file('image_main');
        $uuid =   (string) Str::uuid();

        $fileContents = $file;
        $fileName = $uuid . "." . $file->getClientOriginalExtension();

        $filePath = 'uploads/product/1000/' . $fileName;
        $disk->put($filePath, file_get_contents($fileContents));
        return $filePath;
        $url = $disk->url($filePath);
        return Helper::sendResponse(true, $url, 200, "OK");
    }
}
