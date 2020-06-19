<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $Size = new Size();
        $tmp = trim($request->name, " ");
        $tmp = explode(" ", $tmp);
        $Size->name = $tmp[0];
        $Size->ted_size = $tmp[1];
        $Size->save();
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
        $Size = new Size();
        $Size->name = $request->name;
        $Size->parent_id = $id;
        $Size->save();

        return redirect()->back()->with('success', "Add Size success!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //\
        $Size = Size::findOrFail($id);
        foreach ($Size->child as $key => $value) {
            if ($value->product->count()) {
                return redirect()->back()->with('danger', "The Size (" . $value->name . ") was used !");
            }
            Size::find($value->id)->delete();
        }
        if ($Size->product->count()) {
            return redirect()->back()->with('danger', "The Size (" . $value->name . ") was used !");
        }
        $Size->delete();

        return redirect()->back()->with('success', "Delete Size success!");
    }
}
