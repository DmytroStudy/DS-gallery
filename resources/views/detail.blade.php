<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: {{ $artwork->title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">

@include('header')

<div class="d-flex flex-grow-1">

    <aside>
        <nav>
            <a class="side-link" href="{{ route('home') }}">Home</a>
            <a class="side-link active" href="{{ route('artworks') }}">Artworks</a>
        </nav>
    </aside>

    <main class="p-4 overflow-y-auto flex-grow-1">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible py-2 mb-3" style="font-size:13px">
                {{ session('success') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <h1>{{ $artwork->title }}</h1>

        <div class="row g-4 align-items-start mb-4">

            <!-- Image -->
            <div class="col-12 col-md-8">
                <div class="border rounded-1 overflow-hidden">
                    <div class="img-card" style="height:500px">
                        <img class="art-image" src="{{ asset($artwork->image) }}"
                             alt="{{ $artwork->title }}" style="object-fit:contain"/>
                    </div>
                </div>
            </div>

            <!-- Meta and add-to-cart -->
            <div class="col-12 col-md-4 d-flex flex-column gap-4 pt-2">
                <div>
                    <div class="muted-label">ARTIST</div>
                    <div class="detail-value">{{ $artwork->artist->name }}</div>
                </div>
                <div>
                    <div class="muted-label">DATE</div>
                    <div class="detail-value">{{ $artwork->year }}</div>
                </div>
                <div>
                    <div class="muted-label">GENRE</div>
                    <div class="detail-value">{{ $artwork->genre }}</div>
                </div>
                <div>
                    <div class="muted-label">PRICE</div>
                    <div class="detail-value" style="font-size:22px">
                        {{ number_format($artwork->price, 0) }}€
                    </div>
                </div>

                <!-- Add to cart form -->
                <form method="POST" action="{{ route('cart.add', $artwork) }}"
                      class="border-top pt-3 d-flex flex-column gap-2">
                    @csrf
                    <div class="muted-label">QUANTITY</div>
                    <div class="qty-control" style="width:120px">
                        <button type="button" class="qty-btn"
                                onclick="let i=document.getElementById('qty');i.value=Math.max(1,+i.value-1)">−</button>
                        <input id="qty" class="qty-num" type="number" name="quantity"
                               min="1" value="1"/>
                        <button type="button" class="qty-btn"
                                onclick="let i=document.getElementById('qty');i.value=+i.value+1">+</button>
                    </div>

                    <div class="d-flex gap-2" style="width:180px">
                        <button type="submit" class="sm-icon-btn flex-grow-1" title="Add to cart"
                                style="width:auto;padding:0 12px">
                            <img src="{{ asset('icons/cart.svg') }}" alt=""/>
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('saved.toggle', $artwork) }}">
                    @csrf
                    @php $isSaved = in_array($artwork->artwork_id, session('saved', [])); @endphp
                    <button type="submit" class="sm-icon-btn {{ $isSaved ? 'in-saved' : '' }}"
                            title="{{ $isSaved ? 'Remove from saved' : 'Save' }}">
                        <img src="{{ asset('icons/bookmark.svg') }}" alt=""/>
                    </button>
                </form>

                <a class="mid-btn mt-1" style="border-width:2px;width:100px;height:28px"
                   href="{{ route('artworks') }}">Go back</a>
            </div>
        </div>

        @if ($artwork->description)
            <p style="font-size:18px;color:var(--muted);padding-top:24px">
                {{ $artwork->description }}
            </p>
        @endif

    </main>
</div>

@include('footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
