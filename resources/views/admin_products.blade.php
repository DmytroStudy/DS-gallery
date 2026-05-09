<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: Admin — products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">

@include('header')

<div class="d-flex flex-grow-1">

    @include('sidebar')

    <div class="d-flex flex-grow-1">

        <!-- Sort panel -->
        <form method="GET" action="{{ route('products') }}" id="filterForm">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}"/>
            @endif
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}"/>
                <input type="hidden" name="category" value="{{ $category }}"/>
            @endif

            <div class="filter-panel">
                <input type="hidden" name="category" value="{{ $category }}"/>

                <p class="muted-label">SORT BY</p>
                <select name="sort" class="form-select mb-2" style="font-size:13px" onchange="this.form.submit()">
                    <option value="title_asc"  {{ request('sort','title_asc')=='title_asc' ? 'selected':'' }}>Title A–Z</option>
                    <option value="title_desc" {{ request('sort')=='title_desc' ? 'selected':'' }}>Title Z–A</option>
                    <option value="price_asc"  {{ request('sort')=='price_asc' ? 'selected':'' }}>Price ↑</option>
                    <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected':'' }}>Price ↓</option>
                    <option value="year_asc"   {{ request('sort')=='year_asc' ? 'selected':'' }}>Year ↑</option>
                    <option value="year_desc"  {{ request('sort')=='year_desc' ? 'selected':'' }}>Year ↓</option>
                </select>

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

                @if($category === 'product')
                    {{-- products: Year, Genre, Artist --}}
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

                    <p class="muted-label">ARTIST</p>
                    <div class="filter-check mb-3">
                        @foreach ($artists as $artistId => $artistName)
                            <label>
                                <input type="checkbox" name="artist[]" value="{{ $artistId }}"
                                    {{ in_array($artistId, (array) request('artist', [])) ? 'checked' : '' }}/>
                                {{ $artistName }}
                            </label>
                        @endforeach
                    </div>

                @else
                    {{-- Tools: Type only --}}
                    <p class="muted-label">TYPE</p>
                    <div class="filter-check mb-3">
                        @foreach ($genres as $type)
                            <label>
                                <input type="checkbox" name="type[]" value="{{ $type }}"
                                    {{ in_array($type, (array) request('type', [])) ? 'checked' : '' }}/>
                                {{ $type }}
                            </label>
                        @endforeach
                    </div>
                @endif

                <button type="submit" class="btn btn-dark btn-sm w-100 mb-1">Apply</button>
                <a href="{{ route('products', ['category' => $category]) }}"
                   class="btn btn-outline-secondary btn-sm w-100">Reset</a>
            </div>
        </form>

        <main class="p-4 flex-grow-1" style="overflow-y:auto">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">products</h1>
            </div>

            <div class="d-flex gap-2 mb-4">
                <a href="{{ route('products', array_merge(request()->except(['category','page']), ['category' => 'product'])) }}"
                   class="mid-btn {{ $category === 'product' ? 'active' : '' }}">
                    products
                </a>
                <a href="{{ route('products', array_merge(request()->except(['category','page']), ['category' => 'tool'])) }}"
                   class="mid-btn {{ $category === 'tool' ? 'active' : '' }}">
                    Tools
                </a>
            </div>

            @if(request('search'))
                <p class="text-muted small mb-3">
                    Search: <strong>{{ request('search') }}</strong>
                    <a href="{{ route('products', request()->except('search')) }}" class="ms-2 text-dark">✕ clear</a>
                </p>
            @endif

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
                <!-- Add product card -->
                <div class="col">
                    <a class="add-card" href="{{ route('admin.add') }}">
                        <img src="{{ asset('icons/add.svg') }}" style="width:30px;height:30px"/>
                        <span>Add item</span>
                    </a>
                </div>

                @foreach ($products as $product)
                    <div class="col">
                        <figure class="card p-0 h-100">
                            <a class="img-card" style="height:260px" href="{{ route('admin.detail', $product) }}">
                                <img class="art-image" src="{{ asset($product->image) }}" alt="{{ $product->title }}"/>

                                <!-- Delete button -->
                                <form method="POST" action="{{ route('admin.destroy', $product) }}"
                                      onsubmit="return confirm('Delete &quot;{{ $product->title }}&quot;?')"
                                style="position:absolute;top:10px;right:10px;z-index:10">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="sm-icon-btn delete" title="Delete">
                                    <img src="{{ asset('icons/x.svg') }}" alt="Delete"/>
                                </button>
                                </form>
                            </a>
                            <div class="tile-info">
                                <figcaption class="name">
                                    {{ $product->title }}
                                    <div class="text-muted small">{{ $product->artist?->name }}</div>
                                </figcaption>
                                <div class="price">{{ number_format($product->price, 0) }}€</div>
                            </div>
                        </figure>
                    </div>
                @endforeach
            </div>

            @if ($products->hasPages())
                <nav class="paginator d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </nav>
            @endif

        </main>
    </div>
</div>

@include('footer')

</body>
</html>
