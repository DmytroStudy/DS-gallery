<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'product');
        $query = Product::with('artist')->where('category', $category);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('artist', function ($a) use ($search) {
                        $a->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->price_max);
        }

        if ($category === 'tool') {
            if ($request->filled('type')) {
                $query->whereIn('genre', (array) $request->type);
            }
        } else {
            if ($request->filled('genre')) {
                $query->whereIn('genre', (array) $request->genre);
            }
            if ($request->filled('artist')) {
                $query->whereIn('artist_id', (array) $request->artist);
            }
            if ($request->filled('year_min')) {
                $query->where('year', '>=', (int) $request->year_min);
            }
            if ($request->filled('year_max')) {
                $query->where('year', '<=', (int) $request->year_max);
            }
        }

        $sort = $request->get('sort', 'title_asc');

        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'year_asc' => $query->orderBy('year', 'asc'),
            'year_desc' => $query->orderBy('year', 'desc'),
            'title_desc' => $query->orderBy('title', 'desc'),
            default => $query->orderBy('title', 'asc'),
        };

        $products = $query->paginate(8)->withQueryString();

        $genres = Product::where('category', $category)
            ->whereNotNull('genre')
            ->select('genre')
            ->distinct()
            ->orderBy('genre')
            ->pluck('genre');

        $artists = Artist::whereHas('products', function($q) use ($category) {
            $q->where('category', $category);
        })->orderBy('name')->pluck('name', 'artist_id');

        $minPrice = (int) Product::where('category', $category)->min('price');
        $maxPrice = (int) Product::where('category', $category)->max('price');
        $minYear  = (int) Product::where('category', $category)->whereNotNull('year')->min('year');
        $maxYear  = (int) Product::where('category', $category)->whereNotNull('year')->max('year');

        return view('admin_products', compact('products', 'genres', 'artists', 'minPrice',
            'maxPrice', 'minYear', 'maxYear', 'category'));
    }

    public function create()
    {
        $genres = Product::distinct()->orderBy('genre')->pluck('genre');

        return view('admin_add', compact('genres'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'artist'      => 'required|string|max:255',
            'year'        => 'required|integer|min:1000|max:2100',
            'genre'       => 'required|string|max:100',
            'category'    => 'required|in:product,tool',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $path          = $request->file('image')->store('images/art/uploads', 'public');
            $data['image'] = 'storage/' . $path;
        } else {
            $data['image'] = 'images/art/van_gogh/Bridges_across_the_Seine_at_Asnieres.jpg';
        }

        Product::create($data);

        return redirect()->route('admin.products')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $genres = Product::distinct()->orderBy('genre')->pluck('genre');

        return view('admin_detail', compact('product', 'genres'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'artist'      => 'required|string|max:255',
            'year'        => 'required|integer|min:1000|max:2100',
            'genre'       => 'required|string|max:100',
            'category'    => 'required|in:product,tool',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $path          = $request->file('image')->store('images/art/uploads', 'public');
            $data['image'] = 'storage/' . $path;
        } else {
            unset($data['image']);
        }

        $product->update($data);

        return redirect()->route('admin.products')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products')
            ->with('success', 'Product deleted.');
    }
}
