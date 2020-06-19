<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Helper;
use Illuminate\Support\Facades\DB;
use App\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $data = Category::select('*');

        // pagitation
        if ($request->page_size != null && $request->current_page != null) {
            $limit = (int) $request->page_size == -1 ? $data->count() : ((int) $request->page_size ? $request->page_size : 10);
            $page = (int) $request->current_page ? $request->current_page : 1;
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
        $Category = Category::find($id);
        if (!$Category)
            return Helper::sendResponse(false, 'Not found', 404, 'Category with id =' . $id . ' not found');
        return Helper::sendSuccess($Category);
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
