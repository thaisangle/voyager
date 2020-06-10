<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeControllers extends Controller
{
    public function index()
    {
        $user = Product::all();
        dd($user);
        return view('sang',compact('user'));
    }
   
}
