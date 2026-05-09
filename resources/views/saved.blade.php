<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: Saved</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">

@include('header')
<div class="d-flex flex-grow-1">

    @include('sidebar')

    <main class="p-4 overflow-y-auto flex-grow-1">
        <h1>Saved</h1>

        @if ($saved->isEmpty())
            <div class="text-center py-5 text-muted">
                <p style="font-size:18px">No saved products yet.</p>
                <a href="{{ route('products') }}" class="btn btn-dark mt-2">Browse products</a>
            </div>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
                @foreach ($saved as $product)
                    <div class="col">
                        <figure class="card p-0 h-100">
                            <a class="img-card tile-img" href="{{ route('detail', $product) }}">
                                <img class="art-image" src="{{ asset($product->image) }}" alt="{{ $product->title }}"/>
                                <div class="tile-btns">
                                    {{-- Add to cart --}}
                                    <form method="POST" action="{{ route('cart.add', $product) }}">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1"/>
                                        <button type="submit" class="sm-icon-btn" title="Add to cart">
                                            <img src="{{ asset('icons/cart.svg') }}" alt=""/>
                                        </button>
                                    </form>
                                    {{-- Remove from saved --}}
                                    <form method="POST" action="{{ route('saved.toggle', $product) }}">
                                        @csrf
                                        <button type="submit" class="sm-icon-btn in-saved" title="Remove from saved">
                                            <img src="{{ asset('icons/bookmark.svg') }}" alt=""/>
                                        </button>
                                    </form>
                                </div>
                            </a>
                            <div class="tile-info">
                                <figcaption class="name">{{ $product->title }}</figcaption>
                                <div class="price">{{ number_format($product->price, 0) }}€</div>
                            </div>
                        </figure>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</div>

@include('footer')
</body>
</html>
