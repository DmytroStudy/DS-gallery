<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: Artworks</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">


<div class="d-flex flex-grow-1">

    <!-- Sidebar -->
    <aside>
        <nav>
            <a class="side-link" href="{{ route('home') }}">Home</a>
            <a class="side-link active" href="{{ route('artworks') }}">Artworks</a>
        </nav>
    </aside>

    <div class="d-flex flex-grow-1">

        <!-- Filter panel -->
        <form method="GET" action="{{ route('artworks') }}" id="filterForm">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}"/>
            @endif
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}"/>
            @endif

            <div class="filter-panel">

                <!-- Sort -->
                <p class="muted-label">SORT BY</p>
                <select name="sort" class="form-select mb-2" style="font-size:13px"
                        onchange="this.form.submit()">
                    <option value="title_asc"  {{ request('sort','title_asc')=='title_asc'  ? 'selected':'' }}>Title A–Z</option>
                    <option value="title_desc" {{ request('sort')=='title_desc' ? 'selected':'' }}>Title Z–A</option>
                    <option value="price_asc"  {{ request('sort')=='price_asc'  ? 'selected':'' }}>Price ↑</option>
                    <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected':'' }}>Price ↓</option>
                    <option value="year_asc"   {{ request('sort')=='year_asc'   ? 'selected':'' }}>Year ↑</option>
                    <option value="year_desc"  {{ request('sort')=='year_desc'  ? 'selected':'' }}>Year ↓</option>
                </select>

                <!-- Price range -->
                <p class="muted-label">PRICE (€)</p>
                <div class="d-flex gap-1 mb-2">
                    <input class="form-control form-control-sm" type="number" name="price_min"
                           placeholder="{{ $minPrice }}" value="{{ request('price_min') }}"
                           min="{{ $minPrice }}" max="{{ $maxPrice }}" style="width:80px"/>
                    <span class="align-self-center">–</span>
                    <input class="form-control form-control-sm" type="number" name="price_max"
                           placeholder="{{ $maxPrice }}" value="{{ request('price_max') }}"
                           min="{{ $minPrice }}" max="{{ $maxPrice }}" style="width:80px"/>
                </div>

                <!-- Year range -->
                <p class="muted-label">YEAR</p>
                <div class="d-flex gap-1 mb-2">
                    <input class="form-control form-control-sm" type="number" name="year_min"
                           placeholder="{{ $minYear }}" value="{{ request('year_min') }}"
                           min="{{ $minYear }}" max="{{ $maxYear }}" style="width:80px"/>
                    <span class="align-self-center">–</span>
                    <input class="form-control form-control-sm" type="number" name="year_max"
                           placeholder="{{ $maxYear }}" value="{{ request('year_max') }}"
                           min="{{ $minYear }}" max="{{ $maxYear }}" style="width:80px"/>
                </div>

                <!-- Genre -->
                <p class="muted-label">GENRE</p>
                <div class="filter-check mb-2">
                    @foreach ($genres as $genre)
                        <label>
                            <input type="checkbox" name="genre[]" value="{{ $genre }}"
                                {{ in_array($genre, (array) request('genre', [])) ? 'checked' : '' }}/>
                            {{ $genre }}
                        </label>
                    @endforeach
                </div>

                <!-- Artist -->
                <p class="muted-label">ARTIST</p>
                <div class="filter-check mb-3">
                    @foreach ($artists as $artistName)
                        <label>
                            <input type="checkbox" name="artist[]" value="{{ $artistName }}"
                                {{ in_array($artistName, (array) request('artist', [])) ? 'checked' : '' }}/>
                            {{ $artistName }}
                        </label>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-dark btn-sm w-100 mb-1">Apply</button>
                <a href="{{ route('artworks') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
            </div>
        </form>

        <!-- Product grid -->
        <main class="p-4 flex-grow-1" style="overflow-y:auto">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Artworks</h1>
                <span class="text-muted small">{{ $artworks->total() }} result{{ $artworks->total() !== 1 ? 's' : '' }}</span>
            </div>

            @if(request('search'))
                <p class="text-muted small mb-3">
                    Search: <strong>{{ request('search') }}</strong>
                    <a href="{{ route('artworks', request()->except('search')) }}" class="ms-2 text-dark">✕ clear</a>
                </p>
            @endif

            @if ($artworks->isEmpty())
                <div class="text-center py-5 text-muted">
                    <p style="font-size:18px">No artworks found matching your criteria.</p>
                    <a href="{{ route('artworks') }}" class="btn btn-outline-dark mt-2">Browse all</a>
                </div>
            @else
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
                    @foreach ($artworks as $artwork)
                        <div class="col">
                            <figure class="card p-0 h-100">
                                <a class="img-card" style="height:300px" href="{{ route('detail', $artwork) }}">
                                    <img class="art-image" src="{{ asset($artwork->image) }}" alt="{{ $artwork->title }}"/>
                                    <div class="tile-btns">
                                        <form method="POST" action="{{ route('cart.add', $artwork) }}">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1"/>
                                            <button type="submit" class="sm-icon-btn" title="Add to cart">
                                                <img src="{{ asset('icons/cart.svg') }}" alt=""/>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('saved.toggle', $artwork) }}">
                                            @csrf
                                            @php $isSaved = in_array($artwork->id, session('saved', [])); @endphp
                                            <button type="submit" class="sm-icon-btn {{ $isSaved ? 'in-saved' : '' }}" title="{{ $isSaved ? 'Remove from saved' : 'Save' }}">
                                                <img src="{{ asset('icons/bookmark.svg') }}" alt=""/>
                                            </button>
                                        </form>
                                    </div>
                                </a>
                                <div class="tile-info">
                                    <figcaption class="name">{{ $artwork->title }}</figcaption>
                                    <div class="price">{{ number_format($artwork->price, 0) }}€</div>
                                </div>
                            </figure>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($artworks->hasPages())
                    <nav class="d-flex justify-content-center mt-4">
                        {{ $artworks->links('pagination::bootstrap-5') }}
                    </nav>
                @endif
            @endif

        </main>
    </div>
</div>


</body>
</html>
