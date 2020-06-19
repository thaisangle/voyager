<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Helper;
use Illuminate\Support\Facades\DB;
use App\Size;

class SizeController extends Controller
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
        return Helper::sendSuccess($custorm_list);
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
        $Size = Size::find($id);
        if(!$Size)
            return Helper::sendResponse(false, 'Not found',404,'Size with id ='.$id.' not found');
        $size_custorm = $Size->toArray();
        $size_custorm['child'] = $Size->child->toArray();
        $size_custorm['name_parent'] = ($Size->parent)?$Size->parent->name:null;
        
        return Helper::sendSuccess($size_custorm);

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
