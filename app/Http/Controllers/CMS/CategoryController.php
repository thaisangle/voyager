<?php

namespace App\Http\Controllers\CMS;

use App\Category;
use App\Traits\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $Category = new Category();
        $Category->name  = $request->name;
        $Category->save();

        $request_file_select = $request->img_select;
        $request_file_none = $request->img_none;
        $request_file_default = $request->img_default;
        $request_file_white = $request->img_white;

        $fileNameSelect  = rand(1, 1000) . "-select-" . time() . '.' . $request_file_select->getClientOriginalExtension();
        $fileNameNone    = rand(1, 1000) . "-none-" . time() . '.' . $request_file_none->getClientOriginalExtension();
        $fileNameDefault = rand(1, 1000) . "-default-" . time() . '.' . $request_file_default->getClientOriginalExtension();
        $fileNameWhite = rand(1, 1000) . "-white-" . time() . '.' . $request_file_white->getClientOriginalExtension();

        $disk = Storage::disk('public');

        $dir = $Category->image . $Category->id;
        $imagepathSelect = $dir . "/" . $fileNameSelect;
        $disk->put($imagepathSelect, file_get_contents($request_file_select));

        $imagepathNone = $dir . "/" . $fileNameNone;
        $disk->put($imagepathNone, file_get_contents($request_file_none));

        $imagepathDefault = $dir . "/" . $fileNameDefault;
        $disk->put($imagepathDefault, file_get_contents($request_file_default));

        $imagepathWhite = $dir . "/" . $fileNameWhite;
        $disk->put($imagepathWhite, file_get_contents($request_file_white));


        $Category->icon_select = $fileNameSelect;
        $Category->icon_none = $fileNameNone;
        $Category->icon_default = $fileNameDefault;
        $Category->icon_bg_white = $fileNameWhite;
        $Category->save();
        return redirect()->back()->with('success', "Add category sucess!");
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
        try {
            $Category = Category::findOrFail($id);

            if ($Category->product->count()) {
                $Category->product()->delete();
                // return redirect()->back()->with('danger', "The Category (" . $Category->id . ") was used !");
            }
            $Category->delete();
        } catch (Exception $e) {
            DB::rollBack();
            return Helper::sendResponse(false, 'Delete folder', 400, 'Delete folder false.');
        }

        return redirect()->back()->with('success', "Delete category success!");
    }
}
