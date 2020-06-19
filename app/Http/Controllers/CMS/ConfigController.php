<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Size;
use App\Color;
use App\Brand;
class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $list_Size = Size::where('parent_id',null)->get();
        $custorm_list = [];
        foreach ($list_Size as $key => $value) {
            $custorm_list[$key] = $value->toArray(); 
            $custorm_list[$key]['child'] = $value->child->toArray();
        }
        $list_Size = $custorm_list;

        $list_Category = Category::get();

        $list_Color = Color::where('parent_id',null)->get();
        $custorm_list = [];
        foreach ($list_Color as $key => $value) {
            $custorm_list[$key] = $value->toArray(); 
            $custorm_list[$key]['child'] = $value->child->toArray();
        }
        $list_Color = $custorm_list;
        $list_Brand = Brand::get();
        return view('cms.pages.config.index', [
            'sizes'=> $list_Size,
            'categories'=> $list_Category,
            'colors' => $list_Color,
            'brands' => $list_Brand
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
}
