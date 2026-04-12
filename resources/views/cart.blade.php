<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">



<div class="d-flex flex-grow-1">

    <aside>
        <nav>
            <a class="side-link" href="{{ route('home') }}">Home</a>
            <a class="side-link" href="{{ route('artworks') }}">Artworks</a>
        </nav>
    </aside>

    <main class="p-4 flex-grow-1" style="min-width:0;overflow-y:auto">
        <h1>Cart</h1>

        @if (session('success'))
            <div class="alert alert-success py-2 mb-3" style="font-size:13px">{{ session('success') }}</div>
        @endif

        @if (empty($cart))
            <div class="text-center py-5 text-muted">
                <p style="font-size:18px">Your cart is empty.</p>
                <a href="{{ route('artworks') }}" class="btn btn-dark mt-2">Browse artworks</a>
            </div>
        @else

            @foreach ($cart as $id => $item)
                <div class="row gx-3 align-items-center py-3 border-top">

                    <div class="col-auto col-md-1">
                        <img class="cart-img" src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}"/>
                    </div>

                    <div class="col col-md-5">
                        <h2>{{ $item['title'] }}</h2>
                        <small class="text-muted">{{ $item['artist'] }}</small>
                    </div>

                    <!-- Quantity update -->
                    <div class="col-auto col-md-3">
                        <form method="POST" action="{{ route('cart.update', $id) }}">
                            @csrf
                            <div class="qty-control">
                                <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}"
                                        class="qty-btn">−</button>
                                <input class="qty-num" type="number" name="quantity"
                                       value="{{ $item['quantity'] }}" min="1"
                                       onchange="this.form.submit()"/>
                                <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                                        class="qty-btn">+</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-auto col-md-2">
                        <h2>{{ number_format($item['price'] * $item['quantity'], 0) }}€</h2>
                        @if ($item['quantity'] > 1)
                            <small class="text-muted">{{ number_format($item['price'], 0) }}€ each</small>
                        @endif
                    </div>

                    <!-- Remove -->
                    <div class="col-auto col-md-1">
                        <form method="POST" action="{{ route('cart.remove', $id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="sm-icon-btn" title="Remove">
                                <img src="{{ asset('icons/x.svg') }}" alt=""/>
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach

            <div class="row gx-3 py-3 border-top">
                <div class="col-md-6">
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">Clear cart</button>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-1 text-muted small">
                        {{ collect($cart)->sum('quantity') }} item(s)
                    </p>
                    <span style="font-size:22px;font-weight:500;font-family:var(--font-body)">
              {{ number_format($total, 0) }}€
            </span>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a class="order-btn" style="max-width:300px" href="{{ route('cart.shipping') }}">
                    Continue to shipping
                </a>
            </div>

            @guest
                <p class="text-end text-muted small mt-3">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-dark fw-semibold text-decoration-none">Log in</a>
                </p>
            @endguest

        @endif
    </main>
</div>



</body>
</html>
