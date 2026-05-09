<aside>
    <nav>
        <a class="side-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>

        @if(auth()->check() && auth()->user()->is_admin)
            <a class="side-link {{ Route::is('admin.products') ? 'active' : '' }}" href="{{ route('admin.products') }}">Products</a>
        @else
            <a class="side-link {{ Route::is('products') ? 'active' : '' }}" href="{{ route('products') }}">Products</a>
        @endif
    </nav>
</aside>
