<?php

namespace App\Http\Controllers;

use App\Models\Product;

class SavedController extends Controller
{
    public function index()
    {
        $ids   = session('saved', []);
        $saved = $ids ? Product::whereIn('product_id', $ids)->get() : collect();

        return view('saved', compact('saved'));
    }

    public function toggle(Product $product)
    {
        $saved = session('saved', []);
        $key   = $product->product_id;

        if (in_array($key, $saved)) {
            $saved = array_values(array_filter($saved, fn ($id) => $id !== $key));
            $msg   = "\"{$product->title}\" removed from saved.";
        } else {
            $saved[] = $key;
            $msg     = "\"{$product->title}\" saved.";
        }

        session(['saved' => $saved]);

        return back()->with('success', $msg);
    }
}
