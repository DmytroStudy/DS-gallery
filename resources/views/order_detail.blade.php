<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: Order Confirmed</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">

@include('header')

<div class="d-flex flex-grow-1">
    <aside>
        <nav>
            <a class="side-link" href="{{ route('home') }}">Home</a>
            <a class="side-link" href="{{ route('artworks') }}">Artworks</a>
        </nav>
    </aside>

    <main class="p-4 flex-grow-1" style="overflow-y:auto">

        {{-- Success banner --}}
        @if (session('success'))
            <div class="alert alert-success py-3 mb-4" style="font-size:14px">
                {{ session('success') }}
            </div>
        @endif

        {{-- Confirmation header --}}
        <div class="text-center py-4 mb-4 border rounded-1" style="background:#f9f9f9">
            <div style="font-size:48px;line-height:1">✓</div>
            <h1 class="mt-2 mb-1" style="font-size:26px">Order confirmed!</h1>
            <p class="text-muted mb-0" style="font-size:14px">
                Thank you for your purchase. Order #{{ $order->order_id }}
            </p>
        </div>

        <div class="row g-4">

            {{-- Order items --}}
            <div class="col-12 col-md-7">
                <h2 style="font-size:16px;font-weight:600;margin-bottom:12px">Items ordered</h2>

                @foreach ($order->items as $item)
                    <div class="d-flex align-items-center gap-3 py-3 border-top">
                        <div class="col-auto">
                            <img src="{{ asset($item->artwork->image ?? 'icons/img.svg') }}"
                                 alt="{{ $item->title }}"
                                 style="width:64px;height:64px;object-fit:cover;border-radius:4px"/>
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-size:14px;font-weight:500">{{ $item->title }}</div>
                            <div class="text-muted" style="font-size:12px">{{ $item->artist }}</div>
                            @if ($item->quantity > 1)
                                <div class="text-muted" style="font-size:12px">
                                    {{ $item->quantity }} × {{ number_format($item->price, 0) }}€
                                </div>
                            @endif
                        </div>
                        <div style="font-size:15px;font-weight:500;white-space:nowrap">
                            {{ number_format($item->price * $item->quantity, 0) }}€
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-between py-3 border-top border-bottom">
                    <span class="text-muted" style="font-size:13px">
                        {{ $order->items->sum('quantity') }} item(s)
                    </span>
                    <span style="font-size:18px;font-weight:500">
                        {{ number_format($order->total, 0) }}€
                    </span>
                </div>
            </div>

            {{-- Shipping summary --}}
            <div class="col-12 col-md-5">
                <h2 style="font-size:16px;font-weight:600;margin-bottom:12px">Shipping to</h2>
                <div class="border rounded-1 p-3" style="font-size:13px;line-height:2">
                    <div><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></div>
                    <div>{{ $order->address }}{{ $order->address2 ? ', ' . $order->address2 : '' }}</div>
                    <div>{{ $order->city }}, {{ $order->postal_code }}</div>
                    <div>{{ $order->country }}</div>
                    <div class="text-muted mt-1">{{ $order->email }}</div>
                    @if ($order->phone)
                        <div class="text-muted">{{ $order->phone }}</div>
                    @endif
                </div>

                <div class="mt-3 border rounded-1 p-3" style="font-size:13px">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Payment</span>
                        <span class="text-capitalize">{{ $order->payment_method ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-success" style="font-size:11px">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- CTA --}}
        <div class="d-flex gap-3 mt-5 pt-4 border-top">
            <a href="{{ route('artworks') }}" class="btn btn-dark px-4">Continue shopping</a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4">Go to home</a>
        </div>

    </main>
</div>

@include('footer')

</body>
</html>
