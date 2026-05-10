<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Artist;
use Illuminate\Http\Request;
use App\Models\ProductImage;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'artwork');
        $query = Product::with(['artist', 'images'])->where('category', $category);

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
        $genres = Product::whereNotNull('genre')->select('genre', 'category')->distinct()->orderBy('category', 'asc')->orderBy('genre', 'asc')->get();
        return view('admin_add', compact('genres'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'nullable|required|string|max:255',
            'year' => 'nullable|required|integer|max:2100',
            'genre' => 'nullable|required|string|max:100',
            'category' => 'required|in:artwork,tool',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:4096',
        ]);

        $artist = Artist::firstOrCreate(['name' => $data['artist']]);
        $product = Product::create([
            'title' => $data['title'],
            'artist_id' => $artist->artist_id,
            'year' => $data['year'],
            'genre' => $data['genre'],
            'category' => $data['category'],
            'price'=> $data['price'],
            'description' => $data['description'] ?? null,
        ]);


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('images/art/uploads', 'public');
                $product->images()->create(['img_path' => 'storage/' . $path, 'order' => $index]);
            }
        }

        return redirect()->route('admin.products');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'nullable|required|string|max:255',
            'year' => 'nullable|required|integer|max:2100',
            'genre' => 'nullable|required|string|max:100',
            'category' => 'required|in:artwork,tool',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|max:4096',
            'remove_images' => 'nullable|array',
        ]);

        $artist = $data['artist'] ? Artist::firstOrCreate(['name' => $data['artist']]) : null;

        $product->update([
            'title' => $data['title'],
            'artist_id' => $artist?->artist_id,
            'year' => $data['year'],
            'genre' => $data['genre'],
            'category' => $data['category'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
        ]);

        // Delete selected images
        if ($request->filled('remove_images')) {
            ProductImage::whereIn('image_id', $request->remove_images)->delete();
        }

        // Upload new images
        if ($request->hasFile('new_images')) {
            $lastOrder = $product->images()->max('order') ?? -1;
            foreach ($request->file('new_images') as $index => $file) {
                $path = $file->store('images/art/uploads', 'public');
                $product->images()->create(['img_path' => 'storage/' . $path, 'order'    => $lastOrder + $index + 1]);
            }
        }

        return redirect()->route('admin.products')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->images()->delete();
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product and its images deleted.');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $genres = Product::whereNotNull('genre')->select('genre', 'category')->distinct()->orderBy('category', 'asc')->orderBy('genre', 'asc')->get();

        return view('admin_detail', compact('product', 'genres'));
    }
}
