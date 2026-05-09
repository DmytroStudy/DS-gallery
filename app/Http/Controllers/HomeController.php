<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $newArrivals = Product::latest()->take(3)->get();

        return view('home', compact('newArrivals'));
    }
}
