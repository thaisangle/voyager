<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Helper;
use Illuminate\Support\Facades\DB;
use App\Color;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $list_Color = Color::where('parent_id',null);
        $custorm_list = [];
        // pagitation
        if ($request->page_size !=null && $request->current_page != null) {
            $limit = (int)$request->page_size == -1 ?$list_Color->count():((int)$request->page_size?$request->page_size : 10);
            $page = (int)$request->current_page ? $request->current_page : 1;
            $list_Color = $list_Color->paginate($limit, ['*'], 'page', $page);
        }else{
            $list_Color = $list_Color->get();
        } 
        $custorm_list = $list_Color->toArray();
        foreach ($list_Color as $key => $value) {
            if ($request->page_size !=null && $request->current_page != null) {
                $custorm_list['data'][$key] = $value->toArray(); 
                $custorm_list['data'][$key]['child'] = $value->child->toArray();
            }else{
                $custorm_list[$key] = $value->toArray(); 
                $custorm_list[$key]['child'] = $value->child->toArray();
            }
        }

        if ($request->page_size !=null && $request->current_page != null) {
            return Helper::sendResponsePaginating(true, 'success', $custorm_list, 200);
        }else{
            return Helper::sendSuccess($custorm_list);
        }
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
        $Color = Color::find($id);
        if(!$Color)
            return Helper::sendResponse(false, 'Not found',404,'Color with id ='.$id.' not found');
        $Color_custorm = $Color->toArray();
        $Color_custorm['child'] = $Color->child->toArray();
        return Helper::sendSuccess($Color_custorm);

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
