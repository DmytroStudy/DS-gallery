<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: {{ $product->title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">

@include('header')

<div class="d-flex flex-grow-1">

    @include('sidebar')

    <main class="p-4 overflow-y-auto flex-grow-1">

        <h1>{{ $product->title }}</h1>

        <div class="row g-4 align-items-start mb-4">

            <!-- Image -->
            <div class="col-12 col-md-8">
                <div class="border rounded-1 overflow-hidden">

                    @if($product->images->count() > 0)
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">

                            {{-- Navigation --}}
                            @if($product->images->count() > 1)
                                <div class="carousel-indicators">
                                    @foreach($product->images as $i => $img_path)
                                        <button type="button" data-bs-target="#productCarousel"
                                                data-bs-slide-to="{{ $i }}"
                                                class="{{ $i === 0 ? 'active' : '' }}">
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Slides --}}
                            <div class="carousel-inner">
                                @foreach($product->images as $i => $image)
                                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                        <div class="img-card" style="height:500px">
                                            <img class="art-image"
                                                 src="{{ asset($image->img_path) }}"
                                                 alt="{{ $product->title }}"
                                                 style="object-fit:contain"/>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Arrows --}}
                            @if($product->images->count() > 1)
                                <button class="carousel-control-prev" type="button"
                                        data-bs-target="#productCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                        data-bs-target="#productCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif

                        </div>
                    @else
                        {{-- If no image --}}
                        <div class="img-card" style="height:500px">
                            <img class="art-image" src="{{ asset($product->image ?? 'icons/img.svg') }}"
                                 alt="{{ $product->title }}" style="object-fit:contain"/>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Meta and add-to-cart -->
            <div class="col-12 col-md-4 d-flex flex-column gap-4 pt-2">
                @if($product->artist)
                    <div>
                        <div class="muted-label">ARTIST</div>
                        <div class="detail-value">{{ $product->artist->name }}</div>
                    </div>
                @endif

                @if($product->year)
                    <div>
                        <div class="muted-label">DATE</div>
                        <div class="detail-value">{{ $product->year }}</div>
                    </div>
                @endif

                @if($product->genre)
                    <div>
                        <div class="muted-label">{{ $product->category === 'tool' ? 'TYPE' : 'GENRE' }}</div>
                        <div class="detail-value">{{ $product->genre }}</div>
                    </div>
                @endif

                <div>
                    <div class="muted-label">PRICE</div>
                    <div class="detail-value" style="font-size:22px">
                        {{ number_format($product->price, 0) }}€
                    </div>
                </div>

                <div class="border-top pt-3 d-flex flex-column align-items-start gap-2">

                    {{-- Qty --}}
                    <div class="qty-control" style="width:104px">
                        <button type="button" class="qty-btn" onclick="let i=document.getElementById('detail-qty');i.value=Math.max(1,+i.value-1)">−</button>
                        <input id="detail-qty" class="qty-num" type="number" name="quantity" value="1" min="1"
                               style="width:44px;border:none;text-align:center;font-size:14px;background:none;outline:none;-moz-appearance:textfield"/>
                        <button type="button" class="qty-btn" onclick="let i=document.getElementById('detail-qty');i.value=+i.value+1">+</button>
                    </div>

                    {{-- Cart + Saved --}}
                    @php $isSaved = in_array($product->product_id, session('saved', [])); @endphp
                    <div class="d-flex gap-2" style="width:104px">

                        <form method="POST" action="{{ route('cart.add', $product) }}" style="flex:1" id="cart-form">
                            @csrf
                            <input type="hidden" name="quantity" id="cart-qty-hidden" value="1"/>
                            <button type="submit" class="sm-icon-btn" style="width:100%"
                                    onclick="document.getElementById('cart-qty-hidden').value=document.getElementById('detail-qty').value">
                                <img src="{{ asset('icons/cart.svg') }}" alt="Add to cart"/>
                            </button>
                        </form>

                        <form method="POST" action="{{ route('saved.toggle', $product) }}" style="flex:1">
                            @csrf
                            <button type="submit" class="sm-icon-btn {{ $isSaved ? 'in-saved' : '' }}" style="width:100%">
                                <img src="{{ asset('icons/bookmark.svg') }}" alt="Save"/>
                            </button>
                        </form>

                    </div>

                    {{-- Go back --}}
                    <div class="d-flex">
                        <a class="mid-btn" style="border-width:2px; width: 104px; height: 28px;"
                           href="{{ route('products') }}">Go back</a>
                    </div>

                </div>
            </div>
        </div>

        @if ($product->description)
            <p style="font-size:18px;color:var(--muted);padding-top:24px">
                {{ $product->description }}
            </p>
        @endif

    </main>
</div>

@include('footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
