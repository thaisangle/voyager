<?php

namespace App\Http\Controllers\CMS;

use App\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ColorController extends Controller
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

    public function addColorChild(Request $request, $id)
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
        $color = new Color();
        $color->name = $request->name;
        $color->status = $request->status;
        $color->save();
        return redirect()->back()->with('success', "Add success!");
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
        $color = new Color();
        if ($request->name) {
            $color->name = $request->name;
        } else {
            $request_file = $request->image;

            $disk = Storage::disk('public');

            $dir = $color->image;
            $fileName  = rand(1, 1000) . time() . '.' . $request_file->getClientOriginalExtension();
            $imagepathSelect = $dir . "/" . $fileName;
            $disk->put($imagepathSelect, file_get_contents($request_file));
            $color->name = $fileName;
            $color->status = 1;
        }
        $color->parent_id = $id;
        $color->save();

        return redirect()->back()->with('success', "Add color success!");
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

        $color = Color::findOrFail($id);
        foreach ($color->child as $key => $value) {
            if ($value->product->count()) {
                $value->product()->delete();
                // return redirect()->back()->with('danger', "The color (" . $value->name . ") was used !");
            }
            Color::find($value->id)->delete();
        }
        if ($color->product->count()) {
            $color->product()->delete();
            // return redirect()->back()->with('danger', "The color (" . $color->name . ") was used !");
        }
        $color->delete();

        return redirect()->back()->with('success', "Delete color success!");
    }
}
