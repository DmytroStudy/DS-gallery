@php
    $cartCount  = collect(session('cart',  []))->sum('quantity');
    $savedCount = count(session('saved', []));
@endphp
<nav class="navbar px-4 py-2 border-bottom sticky-top bg-white d-flex justify-content-between">

    <a href="{{ route('home') }}">
        <img src="{{ asset('images/home/logo.png') }}" alt="DSgallery" style="max-height:40px"/>
    </a>

    <div class="d-flex align-items-center gap-2">

        <form method="GET" action="{{ route('artworks') }}" class="d-flex align-items-center">
            <div class="search-wrap">
                <input type="text" name="search" placeholder="Search…"
                       value="{{ request()->routeIs('artworks') ? request('search') : '' }}"/>
                <img class="icon-search" src="{{ asset('icons/search.svg') }}" alt=""/>
            </div>
        </form>

        <a class="mid-icon-btn {{ request()->routeIs('cart') ? 'active' : '' }} position-relative"
           href="{{ route('cart') }}" title="Cart">
            <img src="{{ asset('icons/cart.svg') }}" alt="Cart"/>
            @if ($cartCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark"
                      style="font-size:9px">{{ $cartCount }}</span>
            @endif
        </a>

        <a class="mid-icon-btn {{ request()->routeIs('saved') ? 'active' : '' }} position-relative"
           href="{{ route('saved') }}" title="Saved">
            <img src="{{ asset('icons/bookmark.svg') }}" alt="Saved"/>
            @if ($savedCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark"
                      style="font-size:9px">{{ $savedCount }}</span>
            @endif
        </a>

        @auth
            <span class="mid-btn" style="pointer-events:none;opacity:.7;font-size:12px">
        {{ Str::before(Auth::user()->name, ' ') }}
      </span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="mid-btn">Log out</button>
            </form>
        @else
            <a class="mid-btn {{ request()->routeIs('login')    ? 'active' : '' }}"
               href="{{ route('login') }}">Log in</a>
            <a class="mid-btn {{ request()->routeIs('register') ? 'active' : '' }}"
               href="{{ route('register') }}">Register</a>
        @endauth

    </div>
</nav>
