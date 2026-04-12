<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: Payment</title>
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

    <main class="d-flex justify-content-center p-4 flex-grow-1">
        <div style="width:100%;max-width:500px">
            <h1>Payment details</h1>

            @php $total = collect(session('cart', []))->sum(fn($i) => $i['price'] * $i['quantity']); @endphp

            <div class="pay-tabs">
                <div class="pay-tab active">Credit card</div>
                <div class="pay-tab">PayPal</div>
                <div class="pay-tab">Bank transfer</div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Cardholder name</label>
                <input class="form-control" type="text"
                       value="{{ Auth::user()?->name }}" placeholder="Willem Dafoe"/>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Card number</label>
                <input class="form-control" type="text" placeholder="1234 1234 1234 1234" maxlength="19"/>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label small fw-semibold">Expiry date</label>
                    <input class="form-control" type="text" placeholder="MM/YY" maxlength="5"/>
                </div>
                <div class="col-6">
                    <label class="form-label small fw-semibold">CVV</label>
                    <input class="form-control" type="password" placeholder="•••" maxlength="3"/>
                </div>
            </div>

            <a class="btn btn-dark w-100 py-2" href="{{ route('home') }}" style="font-size:15px">
                Confirm payment: {{ number_format($total, 0) }}€
            </a>
            <div class="mt-3">
                <a href="{{ route('cart.shipping') }}" style="font-size:13px;color:var(--muted);text-decoration:none">Back to shipping</a>
            </div>
        </div>
    </main>
</div>



</body>
</html>
